<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\District;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vendor;
use App\Notifications\BookingPaymentFailedNotification;
use App\Notifications\BookingPaymentSucceededNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransWebhookNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.midtrans.server_key', 'test-server-key');
        config()->set('services.midtrans.client_key', 'test-client-key');
    }

    public function test_midtrans_webhook_marks_payment_paid_and_notifies_related_roles(): void
    {
        [$customer, $vendorOwner, $admin, $payment] = $this->createBookingFixture();

        $payload = $this->buildWebhookPayload($payment, [
            'transaction_status' => 'settlement',
            'status_code' => '200',
            'payment_type' => 'qris',
            'transaction_id' => 'trx-success-001',
        ]);

        $this->postJson('/api/midtrans/notifications', $payload)
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'status' => 'paid',
                'gateway_status' => 'paid',
            ]);

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertSame('paid', $payment->gateway_status);

        $this->assertTrue(
            $customer->fresh()->notifications->contains(fn ($item) => $item->type === BookingPaymentSucceededNotification::class)
        );
        $this->assertTrue(
            $vendorOwner->fresh()->notifications->contains(fn ($item) => $item->type === BookingPaymentSucceededNotification::class)
        );
        $this->assertTrue(
            $admin->fresh()->notifications->contains(fn ($item) => $item->type === BookingPaymentSucceededNotification::class)
        );
    }

    public function test_midtrans_webhook_pending_keeps_pending_without_notification(): void
    {
        [$customer, $vendorOwner, $admin, $payment] = $this->createBookingFixture();

        $payload = $this->buildWebhookPayload($payment, [
            'transaction_status' => 'pending',
            'status_code' => '201',
            'payment_type' => 'qris',
            'transaction_id' => 'trx-pending-001',
        ]);

        $this->postJson('/api/midtrans/notifications', $payload)
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'status' => 'pending',
                'gateway_status' => 'pending',
            ]);

        $payment->refresh();
        $this->assertSame('pending', $payment->status);
        $this->assertSame('pending', $payment->gateway_status);

        $this->assertCount(0, $customer->fresh()->notifications);
        $this->assertCount(0, $vendorOwner->fresh()->notifications);
        $this->assertCount(0, $admin->fresh()->notifications);
    }

    public function test_midtrans_webhook_failed_notifies_customer(): void
    {
        [$customer, $vendorOwner, $admin, $payment] = $this->createBookingFixture();

        $payload = $this->buildWebhookPayload($payment, [
            'transaction_status' => 'expire',
            'status_code' => '407',
            'payment_type' => 'qris',
            'transaction_id' => 'trx-failed-001',
        ]);

        $this->postJson('/api/midtrans/notifications', $payload)
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'status' => 'pending',
                'gateway_status' => 'failed',
            ]);

        $payment->refresh();
        $this->assertSame('pending', $payment->status);
        $this->assertSame('failed', $payment->gateway_status);

        $this->assertTrue(
            $customer->fresh()->notifications->contains(fn ($item) => $item->type === BookingPaymentFailedNotification::class)
        );
        $this->assertCount(0, $vendorOwner->fresh()->notifications);
        $this->assertCount(0, $admin->fresh()->notifications);
    }

    private function createBookingFixture(): array
    {
        $customer = User::factory()->create([
            'role' => 'user',
            'username' => 'cust_' . fake()->unique()->numerify('####'),
        ]);

        $vendorOwner = User::factory()->create([
            'role' => 'vendor',
            'username' => 'vendor_' . fake()->unique()->numerify('####'),
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_' . fake()->unique()->numerify('####'),
        ]);

        $district = District::create([
            'name' => 'Klojen',
        ]);

        $vendor = Vendor::create([
            'user_id' => $vendorOwner->id,
            'district_id' => $district->id,
            'store_name' => 'Renmote Test Vendor',
            'description' => 'Vendor untuk pengujian',
            'phone' => '081234567890',
            'address' => 'Jl. Test',
            'bank_name' => 'BCA',
            'bank_account' => '1234567890',
            'status' => 'approved',
            'verified' => true,
        ]);

        $vehicle = Vehicle::create([
            'vendor_id' => $vendor->id,
            'name' => 'Honda Vario 125',
            'category' => 'matic',
            'price_per_day' => 150000,
            'year' => 2022,
            'engine_cc' => 125,
            'description' => 'Unit test',
            'image' => null,
            'stock' => 1,
            'status' => 'available',
        ]);

        $booking = Booking::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(3)->toDateString(),
            'total_price' => 300000,
            'status' => 'pending',
            'fulfillment_method' => 'pickup',
            'address_id' => null,
            'delivery_address_snapshot' => null,
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => 90000,
            'payment_type' => 'dp',
            'status' => 'pending',
            'gateway_status' => 'pending',
            'provider' => 'midtrans_sandbox',
            'payment_method' => 'qris',
            'invoice_number' => 'INV-RNM-TEST-' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT),
            'proof_status' => 'not_uploaded',
        ]);

        return [$customer, $vendorOwner, $admin, $payment];
    }

    private function buildWebhookPayload(Payment $payment, array $overrides = []): array
    {
        $base = array_merge([
            'order_id' => $payment->invoice_number,
            'status_code' => '200',
            'gross_amount' => number_format((float) $payment->amount, 2, '.', ''),
            'transaction_status' => 'pending',
            'fraud_status' => 'accept',
            'payment_type' => 'qris',
            'transaction_id' => 'trx-default',
        ], $overrides);

        $base['signature_key'] = hash(
            'sha512',
            (string) $base['order_id'] . (string) $base['status_code'] . (string) $base['gross_amount'] . (string) config('services.midtrans.server_key')
        );

        return $base;
    }
}
