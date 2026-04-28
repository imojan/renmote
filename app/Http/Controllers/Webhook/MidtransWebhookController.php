<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\BookingNotificationService;
use App\Services\MidtransService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly MidtransService $midtransService,
        private readonly BookingNotificationService $bookingNotificationService
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'order_id' => ['required', 'string'],
            'status_code' => ['required', 'string'],
            'gross_amount' => ['required'],
            'signature_key' => ['required', 'string'],
            'transaction_status' => ['nullable', 'string'],
            'fraud_status' => ['nullable', 'string'],
            'payment_type' => ['nullable', 'string'],
            'transaction_id' => ['nullable', 'string'],
        ]);

        if (!$this->midtransService->isValidNotificationSignature($payload)) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid Midtrans signature.',
            ], 403);
        }

        $payment = Payment::query()
            ->where('invoice_number', (string) $payload['order_id'])
            ->with('booking')
            ->first();

        if (!$payment || !$payment->booking) {
            return response()->json([
                'ok' => true,
                'message' => 'Invoice not found, ignored.',
            ]);
        }

        $previousStatus = $payment->status;
        $previousGatewayStatus = $payment->gateway_status;

        $payment = $this->paymentService->syncMidtransTransaction($payment, $payload);

        if ($payment->status === 'paid' && $previousStatus !== 'paid') {
            $this->bookingNotificationService->notifyPaymentSuccess($payment->booking, $payment);
        }

        if ($payment->gateway_status === 'failed' && $previousGatewayStatus !== 'failed') {
            $this->bookingNotificationService->notifyPaymentFailed($payment->booking, $payment);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Midtrans notification processed.',
            'status' => $payment->status,
            'gateway_status' => $payment->gateway_status,
        ]);
    }
}
