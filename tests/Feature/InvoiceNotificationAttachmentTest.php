<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\District;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vendor;
use App\Notifications\BookingPaymentSucceededNotification;
use App\Notifications\PaymentProofReviewedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceNotificationAttachmentTest extends TestCase
{
    use RefreshDatabase;

    private function createFixture(): array
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
            'status' => 'paid',
            'gateway_status' => 'paid',
            'provider' => 'midtrans_sandbox',
            'payment_method' => 'qris',
            'invoice_number' => 'INV-RNM-TEST-' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT),
            'proof_status' => 'verified',
        ]);

        return [$customer, $vendorOwner, $admin, $booking, $payment];
    }

    public function test_booking_payment_succeeded_notification_attaches_pdf_for_user(): void
    {
        [$customer, $vendorOwner, $admin, $booking, $payment] = $this->createFixture();

        $notification = new BookingPaymentSucceededNotification($booking, $payment);

        // Mail to user/customer
        $mailMessage = $notification->toMail($customer);
        $this->assertNotEmpty($mailMessage->rawAttachments);
        $this->assertSame("invoice-{$payment->invoice_number}.pdf", $mailMessage->rawAttachments[0]['name']);
        $this->assertSame("application/pdf", $mailMessage->rawAttachments[0]['options']['mime']);

        // Mail to vendor
        $vendorMail = $notification->toMail($vendorOwner);
        $this->assertEmpty($vendorMail->rawAttachments);

        // Mail to admin
        $adminMail = $notification->toMail($admin);
        $this->assertEmpty($adminMail->rawAttachments);
    }

    public function test_payment_proof_reviewed_notification_attaches_pdf_when_approved(): void
    {
        [$customer, $vendorOwner, $admin, $booking, $payment] = $this->createFixture();

        // Approved proof
        $approvedNotification = new PaymentProofReviewedNotification($booking, $payment, true, 'Looks good', 'vendor');
        $approvedMail = $approvedNotification->toMail($customer);
        $this->assertNotEmpty($approvedMail->rawAttachments);
        $this->assertSame("invoice-{$payment->invoice_number}.pdf", $approvedMail->rawAttachments[0]['name']);
        $this->assertSame("application/pdf", $approvedMail->rawAttachments[0]['options']['mime']);

        // Rejected proof
        $rejectedNotification = new PaymentProofReviewedNotification($booking, $payment, false, 'Invalid proof screenshot', 'vendor');
        $rejectedMail = $rejectedNotification->toMail($customer);
        $this->assertEmpty($rejectedMail->rawAttachments);
    }
}
