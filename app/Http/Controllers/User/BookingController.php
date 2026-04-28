<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Booking;
use App\Models\District;
use App\Models\User;
use App\Models\UserDocument;
use App\Models\Vehicle;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use App\Services\BookingNotificationService;
use App\Services\MidtransService;
use App\Services\PaymentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected PaymentService $paymentService;
    protected MidtransService $midtransService;
    protected AvailabilityService $availabilityService;
    protected BookingNotificationService $bookingNotificationService;

    public function __construct(
        BookingService $bookingService,
        PaymentService $paymentService,
        MidtransService $midtransService,
        AvailabilityService $availabilityService,
        BookingNotificationService $bookingNotificationService
    )
    {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
        $this->midtransService = $midtransService;
        $this->availabilityService = $availabilityService;
        $this->bookingNotificationService = $bookingNotificationService;
    }

    /**
     * Daftar semua booking user
     */
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with('vehicle.vendor', 'payment')
            ->latest()
            ->get();

        return view('front.bookings.index', compact('bookings'));
    }

    /**
     * Form untuk membuat booking baru
     */
    public function create(Vehicle $vehicle)
    {
        return view('front.bookings.create', compact('vehicle'));
    }

    /**
     * Step 1: konfirmasi penyewaan sebelum membuat pesanan.
     */
    public function confirmation(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        if (!$this->availabilityService->checkAvailability($vehicle->id, $validated['start_date'], $validated['end_date'])) {
            return redirect()
                ->route('user.bookings.create', $vehicle)
                ->withInput($validated)
                ->with('error', 'Tanggal yang dipilih sudah dipakai booking lain. Silakan pilih rentang tanggal lain.');
        }

        $user = $request->user()->load(['addresses.district', 'userDocuments']);
        $districts = District::query()->orderBy('name')->get();
        $summary = $this->buildSummary($vehicle, $validated['start_date'], $validated['end_date']);
        $documentsByType = $user->userDocuments->keyBy('type');

        return view('front.bookings.confirmation', [
            'vehicle' => $vehicle,
            'startDate' => $validated['start_date'],
            'endDate' => $validated['end_date'],
            'summary' => $summary,
            'user' => $user,
            'districts' => $districts,
            'documentsByType' => $documentsByType,
        ]);
    }

    /**
     * Cek ketersediaan kendaraan secara real-time untuk ditampilkan di form booking.
     */
    public function checkAvailability(Request $request, Vehicle $vehicle): JsonResponse
    {
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate && !$endDate) {
            return response()->json([
                'available' => true,
                'message' => 'Pilih tanggal sewa untuk cek ketersediaan.',
                'overlaps' => [],
            ]);
        }

        if ($startDate && !$endDate) {
            $endDate = Carbon::parse($startDate)->addDay()->toDateString();
        }

        if (!$startDate && $endDate) {
            $startDate = Carbon::parse($endDate)->subDay()->toDateString();
        }

        $isAvailable = $this->availabilityService->checkAvailability(
            $vehicle->id,
            $startDate,
            $endDate
        );

        $overlappingRanges = [];

        if (!$isAvailable) {
            $overlappingRanges = Booking::query()
                ->where('vehicle_id', $vehicle->id)
                ->where('status', '!=', 'cancelled')
                ->where('start_date', '<', $endDate)
                ->where('end_date', '>', $startDate)
                ->orderBy('start_date')
                ->get(['start_date', 'end_date'])
                ->map(function (Booking $booking) {
                    return [
                        'start_date' => $booking->start_date,
                        'end_date' => $booking->end_date,
                    ];
                })
                ->toArray();
        }

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable
                ? 'Tanggal tersedia untuk dibooking.'
                : 'Tanggal yang dipilih sudah digunakan booking lain.',
            'overlaps' => $overlappingRanges,
        ]);
    }

    /**
     * Simpan booking baru
     */
    public function store(Request $request, Vehicle $vehicle)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'fulfillment_method' => 'required|in:pickup,delivery',
                'payment_method' => 'required|in:qris',
                'address_id' => 'nullable|integer',
                'use_new_address' => 'nullable|boolean',
                'document_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
                'document_sim' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            ]);

            if (!$this->availabilityService->checkAvailability($vehicle->id, $validated['start_date'], $validated['end_date'])) {
                return redirect()
                    ->route('user.bookings.create', $vehicle)
                    ->withInput($request->except('document_ktp', 'document_sim'))
                    ->with('error', 'Tanggal yang dipilih sudah dipakai booking lain. Silakan pilih rentang tanggal lain.');
            }

            $user = $request->user()->load(['addresses.district', 'userDocuments']);

            $selectedAddress = null;
            $deliverySnapshot = null;

            if ($validated['fulfillment_method'] === 'delivery') {
                $selectedAddress = $this->resolveDeliveryAddress($request, $user);
                $deliverySnapshot = $this->buildAddressSnapshot($selectedAddress);
            }

            $documentsByType = $user->userDocuments->keyBy('type');
            $ktpFile = $request->file('document_ktp');
            $simFile = $request->file('document_sim');

            if (!$documentsByType->has('ktp') && !$ktpFile) {
                throw ValidationException::withMessages([
                    'document_ktp' => 'Dokumen KTP/KTM wajib dilampirkan sebelum membuat pesanan.',
                ]);
            }

            if ($ktpFile instanceof UploadedFile) {
                $this->upsertUserDocument($user, 'ktp', $ktpFile);
            }

            if ($simFile instanceof UploadedFile) {
                $this->upsertUserDocument($user, 'sim', $simFile);
            }

            // Buat booking
            $booking = $this->bookingService->createBooking(
                $request->user(),
                $vehicle,
                $validated['start_date'],
                $validated['end_date'],
                [
                    'fulfillment_method' => $validated['fulfillment_method'],
                    'address_id' => $selectedAddress?->id,
                    'delivery_address_snapshot' => $deliverySnapshot,
                ]
            );

            // Buat DP payment (30%)
            $this->paymentService->createDpPayment($booking, [
                'payment_method' => 'qris',
            ]);

            return redirect()->route('user.bookings.payment', $booking)
                ->with('success', 'Pesanan berhasil dibuat. Lanjutkan pembayaran DP pada langkah berikutnya.');

        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
        * Step 2: halaman pembayaran Midtrans.
     */
    public function payment(Booking $booking)
    {
        $this->authorizeOwnedBooking($booking);
        $booking->load('vehicle.vendor', 'payment', 'address.district');

        if (!$booking->payment) {
            return redirect()
                ->route('user.bookings.show', $booking->id)
                ->with('error', 'Data pembayaran belum tersedia untuk pesanan ini.');
        }

        if ($booking->payment->status === 'paid' && !$booking->payment->proof_path) {
            return redirect()
                ->route('user.bookings.payment.proof', $booking)
                ->with('success', 'Pembayaran terverifikasi. Lanjutkan upload bukti pembayaran.');
        }

        $payment = $booking->payment;

        if ($payment->status !== 'paid' && !$payment->expires_at) {
            $payment->update([
                'expires_at' => now()->addMinutes(30),
            ]);
            $payment->refresh();
        }

        if ($payment->status !== 'paid' && empty($payment->invoice_number)) {
            $payment = $this->paymentService->regenerateInvoice($payment);
        }

        if ($payment->expires_at && $payment->expires_at->isPast() && $payment->status !== 'paid') {
            $payment->update([
                'gateway_status' => 'failed',
                'gateway_last_synced_at' => now(),
            ]);
            $payment->refresh();
        }

        $midtransError = null;

        if (!$this->midtransService->isConfigured()) {
            $midtransError = 'Konfigurasi Midtrans belum lengkap. Silakan hubungi admin.';
        } elseif ($payment->status !== 'paid' && $payment->gateway_status !== 'failed' && !$payment->snap_token) {
            try {
                $payment->update([
                    'snap_token' => $this->midtransService->createSnapTokenForBooking($booking),
                ]);
                $payment->refresh();
            } catch (\Throwable $exception) {
                Log::warning('Midtrans token creation failed', [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->id,
                    'invoice_number' => $payment->invoice_number,
                    'exception' => $exception->getMessage(),
                ]);

                $midtransError = $this->humanizeMidtransError($exception);
            }
        }

        return view('front.bookings.payment', [
            'booking' => $booking,
            'summary' => $this->buildSummary($booking->vehicle, $booking->start_date, $booking->end_date),
            'snapToken' => $payment->snap_token,
            'midtransClientKey' => (string) config('services.midtrans.client_key'),
            'midtransSnapScriptUrl' => $this->midtransService->getSnapScriptUrl(),
            'midtransLanguage' => $this->midtransService->normalizeLanguage(app()->getLocale()),
            'midtransStatusEndpoint' => route('user.bookings.payment.midtrans.finish', $booking),
            'canRetryPayment' => $payment->status !== 'paid' && (
                $payment->gateway_status === 'failed'
                || ($payment->expires_at && $payment->expires_at->isPast())
                || $midtransError !== null
            ),
            'midtransError' => $midtransError,
        ]);
    }

    /**
     * Aksi tombol "Saya Sudah Bayar" dari step 2.
     */
    public function confirmPayment(Booking $booking)
    {
        $this->authorizeOwnedBooking($booking);
        $booking->load('payment');

        if (!$booking->payment) {
            return redirect()->route('user.bookings.show', $booking->id)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $payment = $booking->payment;
        $previousStatus = $payment->status;
        $previousGatewayStatus = $payment->gateway_status;

        if ($payment->status === 'paid') {
            return redirect()
                ->route('user.bookings.payment.proof', $booking)
                ->with('success', 'Pembayaran terverifikasi. Lanjutkan upload bukti pembayaran.');
        }

        if (!$this->midtransService->isConfigured()) {
            return back()->with('error', 'Konfigurasi Midtrans belum lengkap.');
        }

        try {
            $statusPayload = $this->midtransService->getTransactionStatus((string) $payment->invoice_number);
            $payment = $this->paymentService->syncMidtransTransaction($payment, $statusPayload);
            $this->dispatchPaymentNotificationByState($booking, $payment, $previousStatus, $previousGatewayStatus);
        } catch (\Throwable $exception) {
            Log::warning('Midtrans status check failed', [
                'booking_id' => $booking->id,
                'payment_id' => $payment->id,
                'invoice_number' => $payment->invoice_number,
                'exception' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Status pembayaran belum bisa dipastikan saat ini. Coba beberapa saat lagi atau ajukan ulang pembayaran.');
        }

        if ($payment->status === 'paid') {
            return redirect()
                ->route('user.bookings.payment.proof', $booking)
                ->with('success', 'Pembayaran terverifikasi. Lanjutkan upload bukti pembayaran.');
        }

        if ($payment->gateway_status === 'failed') {
            return back()->with('error', 'Pembayaran gagal atau kadaluarsa. Silakan ajukan ulang pembayaran.');
        }

        if ($booking->payment->expires_at && $booking->payment->expires_at->isPast()) {
            return back()->with('error', 'Waktu pembayaran sudah habis. Silakan ajukan ulang pembayaran.');
        }

        return back()->with('info', 'Pembayaran masih pending. Silakan selesaikan pembayaran di widget Midtrans.');
    }

    /**
     * Sinkronisasi hasil transaksi Midtrans dari event frontend embed.
     */
    public function syncMidtransResult(Request $request, Booking $booking): JsonResponse
    {
        $this->authorizeOwnedBooking($booking);
        $booking->load('payment');

        if (!$booking->payment) {
            return response()->json([
                'ok' => false,
                'message' => 'Data pembayaran tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'order_id' => 'required|string',
            'transaction_status' => 'nullable|string',
            'fraud_status' => 'nullable|string',
            'payment_type' => 'nullable|string',
            'transaction_id' => 'nullable|string',
        ]);

        $payment = $booking->payment;
        $previousStatus = $payment->status;
        $previousGatewayStatus = $payment->gateway_status;

        if ((string) $payment->invoice_number !== (string) $validated['order_id']) {
            return response()->json([
                'ok' => false,
                'message' => 'Order ID tidak sesuai dengan invoice booking ini.',
            ], 422);
        }

        $statusPayload = $validated;

        if ($this->midtransService->isConfigured()) {
            try {
                $statusPayload = $this->midtransService->getTransactionStatus((string) $validated['order_id']);
            } catch (\Throwable $exception) {
                // Fallback ke payload frontend jika pengecekan API gagal.
            }
        }

        $payment = $this->paymentService->syncMidtransTransaction($payment, $statusPayload);
        $this->dispatchPaymentNotificationByState($booking, $payment, $previousStatus, $previousGatewayStatus);

        if ($payment->status === 'paid') {
            return response()->json([
                'ok' => true,
                'state' => 'paid',
                'next_url' => route('user.bookings.payment.proof', $booking),
                'message' => 'Pembayaran berhasil. Lanjutkan upload bukti pembayaran.',
            ]);
        }

        if ($payment->gateway_status === 'failed') {
            return response()->json([
                'ok' => true,
                'state' => 'failed',
                'next_url' => route('user.bookings.payment', $booking),
                'message' => 'Pembayaran gagal atau kadaluarsa. Silakan ajukan ulang pembayaran.',
            ]);
        }

        return response()->json([
            'ok' => true,
            'state' => 'pending',
            'next_url' => route('user.bookings.payment', $booking),
            'message' => 'Pembayaran masih pending.',
        ]);
    }

    /**
     * Ajukan ulang pembayaran jika transaksi sebelumnya gagal/expired.
     */
    public function retryPayment(Booking $booking)
    {
        $this->authorizeOwnedBooking($booking);
        $booking->load('payment');

        if (!$booking->payment) {
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $payment = $booking->payment;

        if ($payment->status === 'paid') {
            return redirect()->route('user.bookings.payment.proof', $booking)
                ->with('success', 'Pembayaran sudah berhasil, lanjutkan upload bukti pembayaran.');
        }

        if (!$this->midtransService->isConfigured()) {
            return back()->with('error', 'Konfigurasi Midtrans belum lengkap.');
        }

        try {
            $payment = $this->paymentService->regenerateInvoice($payment);
            $payment->update([
                'snap_token' => $this->midtransService->createSnapTokenForBooking($booking),
            ]);
        } catch (\Throwable $exception) {
            return back()->with('error', 'Gagal membuat ulang invoice Midtrans: ' . $exception->getMessage());
        }

        return redirect()->route('user.bookings.payment', $booking)
            ->with('success', 'Invoice pembayaran baru berhasil dibuat. Silakan lanjutkan pembayaran.');
    }

    /**
     * Step 3: upload bukti pembayaran.
     */
    public function paymentProof(Booking $booking)
    {
        $this->authorizeOwnedBooking($booking);
        $booking->load('vehicle.vendor', 'payment', 'address.district');

        if (!$booking->payment) {
            return redirect()->route('user.bookings.show', $booking->id)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        return view('front.bookings.payment-proof', compact('booking'));
    }

    /**
     * Simpan bukti pembayaran dari step 3.
     */
    public function storePaymentProof(Request $request, Booking $booking)
    {
        $this->authorizeOwnedBooking($booking);
        $booking->load('payment');

        if (!$booking->payment) {
            return redirect()->route('user.bookings.show', $booking->id)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $validated = $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:6144',
            'proof_notes' => 'nullable|string|max:500',
        ]);

        $payment = $booking->payment;

        if ($payment->proof_path) {
            Storage::disk('public')->delete($payment->proof_path);
        }

        $proofPath = $validated['payment_proof']->store('payments/proofs', 'public');

        $payment->update([
            'proof_path' => $proofPath,
            'proof_notes' => $validated['proof_notes'] ?? null,
            'proof_status' => 'uploaded',
            'proof_uploaded_at' => now(),
            'proof_review_notes' => null,
            'proof_reviewed_at' => null,
            'proof_reviewer_role' => null,
            'status' => 'pending',
            'paid_at' => null,
        ]);

        return redirect()
            ->route('user.bookings.invoice', $booking)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin/vendor.');
    }

    /**
     * Step 4: halaman invoice.
     */
    public function invoice(Booking $booking)
    {
        $this->authorizeOwnedBooking($booking);
        $booking->load('vehicle.vendor', 'payment', 'address.district');

        if (!$booking->payment) {
            return redirect()->route('user.bookings.show', $booking->id)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if (!$booking->payment->proof_path) {
            return redirect()->route('user.bookings.payment.proof', $booking)
                ->with('error', 'Unggah bukti pembayaran terlebih dahulu untuk melanjutkan ke tahap selesai.');
        }

        return view('front.bookings.invoice', compact('booking'));
    }

    /**
     * Download invoice PDF yang siap kirim/arsip.
     */
    public function downloadInvoicePdf(Booking $booking)
    {
        $this->authorizeOwnedBooking($booking);
        $booking->load('user', 'vehicle.vendor', 'payment', 'address.district');

        if (!$booking->payment) {
            return redirect()->route('user.bookings.show', $booking->id)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if (!$booking->payment->proof_path) {
            return redirect()->route('user.bookings.payment.proof', $booking)
                ->with('error', 'Unggah bukti pembayaran terlebih dahulu sebelum mengunduh invoice PDF.');
        }

        $pdf = Pdf::loadView('front.bookings.invoice-pdf', [
            'booking' => $booking,
        ])->setPaper('a4', 'portrait');

        $filename = 'invoice-' . $booking->payment->invoice_number . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Detail booking
     */
    public function show($id)
    {
        $booking = auth()->user()->bookings()
            ->with('vehicle.vendor', 'payment', 'address.district')
            ->findOrFail($id);

        return view('front.bookings.show', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancel($id)
    {
        $booking = auth()->user()->bookings()->with('payment')->findOrFail($id);
        
        if ($booking->status === 'pending') {
            $this->bookingService->updateBookingStatus($booking, 'cancelled');
            return back()->with('success', 'Booking berhasil dibatalkan.');
        }

        return back()->with('error', 'Booking tidak dapat dibatalkan.');
    }

    private function authorizeOwnedBooking(Booking $booking): void
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }

    private function buildSummary(Vehicle $vehicle, string $startDate, string $endDate): array
    {
        $totalPrice = $this->bookingService->calculateTotalPrice($vehicle, $startDate, $endDate);
        $dpAmount = round($totalPrice * 0.30, 2);
        $days = max(1, Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)));

        return [
            'days' => $days,
            'total_price' => $totalPrice,
            'dp_amount' => $dpAmount,
            'remaining_amount' => max(0, $totalPrice - $dpAmount),
        ];
    }

    private function resolveDeliveryAddress(Request $request, User $user): Address
    {
        $useNewAddress = $request->boolean('use_new_address');
        $addressId = $request->input('address_id');

        if (!$useNewAddress) {
            if (!$addressId) {
                throw ValidationException::withMessages([
                    'address_id' => 'Pilih salah satu alamat pengantaran atau aktifkan alamat baru.',
                ]);
            }

            $address = $user->addresses()->with('district')->find($addressId);
            if ($address) {
                return $address;
            }

            throw ValidationException::withMessages([
                'address_id' => 'Alamat pengantaran tidak valid atau bukan milik akun kamu.',
            ]);
        }

        $validatedAddress = $request->validate([
            'new_address_label' => 'required|string|max:50',
            'new_address_type' => 'required|in:permanent,temporary',
            'new_address_street' => 'required|string|max:500',
            'new_address_district_id' => 'required|exists:districts,id',
            'new_address_city' => 'required|string|max:100',
            'new_address_postal_code' => 'required|string|max:10',
        ]);

        $address = $user->addresses()->create([
            'label' => $validatedAddress['new_address_label'],
            'address_type' => $validatedAddress['new_address_type'],
            'street' => $validatedAddress['new_address_street'],
            'district_id' => (int) $validatedAddress['new_address_district_id'],
            'city' => $validatedAddress['new_address_city'],
            'postal_code' => $validatedAddress['new_address_postal_code'],
            'is_default' => false,
        ]);

        return $address->load('district');
    }

    private function buildAddressSnapshot(Address $address): array
    {
        $address->loadMissing('district');

        return Arr::only([
            'label' => $address->label,
            'address_type' => $address->address_type,
            'street' => $address->street,
            'district' => $address->district?->name,
            'city' => $address->city,
            'postal_code' => $address->postal_code,
        ], ['label', 'address_type', 'street', 'district', 'city', 'postal_code']);
    }

    private function upsertUserDocument(User $user, string $type, UploadedFile $file): void
    {
        $existingDocument = $user->userDocuments()->where('type', $type)->first();

        if ($existingDocument && $existingDocument->file_path) {
            Storage::disk('public')->delete($existingDocument->file_path);
        }

        $path = $file->store('users/documents', 'public');

        UserDocument::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => $type,
            ],
            [
                'file_path' => $path,
                'status' => 'pending',
                'notes' => null,
            ]
        );
    }

    private function humanizeMidtransError(\Throwable $exception): string
    {
        $message = strtolower($exception->getMessage());

        if (str_contains($message, 'transaction_details.order_id is required')) {
            return 'Data pembayaran lama belum lengkap. Klik "Ajukan Ulang Pembayaran" untuk membuat invoice baru.';
        }

        if (str_contains($message, 'access denied') || str_contains($message, 'unauthorized') || str_contains($message, '401')) {
            return 'Integrasi Midtrans sandbox belum tervalidasi. Silakan hubungi admin untuk pengecekan kredensial.';
        }

        if (str_contains($message, 'gross_amount')) {
            return 'Nominal transaksi belum valid. Silakan ajukan ulang pembayaran untuk membuat invoice baru.';
        }

        return 'Widget pembayaran sedang tidak tersedia. Coba refresh halaman atau ajukan ulang pembayaran.';
    }

    private function dispatchPaymentNotificationByState(
        Booking $booking,
        \App\Models\Payment $payment,
        ?string $previousStatus,
        ?string $previousGatewayStatus
    ): void
    {
        if ($payment->status === 'paid' && $previousStatus !== 'paid') {
            $this->bookingNotificationService->notifyPaymentSuccess($booking, $payment);

            return;
        }

        if ($payment->gateway_status === 'failed' && $previousGatewayStatus !== 'failed') {
            $this->bookingNotificationService->notifyPaymentFailed($booking, $payment);
        }
    }
}
