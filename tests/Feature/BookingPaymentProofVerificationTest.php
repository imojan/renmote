<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\District;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookingPaymentProofVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_can_approve_uploaded_payment_proof_for_owned_booking(): void
    {
        $context = $this->createBookingContext();

        $response = $this
            ->actingAs($context['vendorUser'])
            ->from(route('vendor.bookings.show', $context['booking']))
            ->post(route('vendor.bookings.paymentProof.approve', $context['booking']), [
                'review_notes' => 'Nominal dan bukti transfer sudah sesuai.',
            ]);

        $response
            ->assertRedirect(route('vendor.bookings.show', $context['booking']))
            ->assertSessionHas('success');

        $context['payment']->refresh();

        $this->assertSame('paid', $context['payment']->status);
        $this->assertSame('verified', $context['payment']->proof_status);
        $this->assertSame('vendor', $context['payment']->proof_reviewer_role);
        $this->assertSame('Nominal dan bukti transfer sudah sesuai.', $context['payment']->proof_review_notes);
        $this->assertNotNull($context['payment']->paid_at);
        $this->assertNotNull($context['payment']->proof_reviewed_at);
    }

    public function test_vendor_cannot_verify_other_vendor_booking_payment_proof(): void
    {
        $context = $this->createBookingContext();
        $otherVendorUser = $this->createVendorUser();

        $response = $this
            ->actingAs($otherVendorUser)
            ->post(route('vendor.bookings.paymentProof.approve', $context['booking']), [
                'review_notes' => 'Harusnya tidak boleh.',
            ]);

        $response->assertForbidden();

        $context['payment']->refresh();

        $this->assertSame('pending', $context['payment']->status);
        $this->assertSame('uploaded', $context['payment']->proof_status);
    }

    public function test_vendor_reject_requires_review_notes(): void
    {
        $context = $this->createBookingContext();

        $response = $this
            ->actingAs($context['vendorUser'])
            ->from(route('vendor.bookings.show', $context['booking']))
            ->post(route('vendor.bookings.paymentProof.reject', $context['booking']), [
                'review_notes' => '',
            ]);

        $response
            ->assertRedirect(route('vendor.bookings.show', $context['booking']))
            ->assertSessionHasErrors('review_notes');

        $context['payment']->refresh();

        $this->assertSame('uploaded', $context['payment']->proof_status);
        $this->assertNull($context['payment']->proof_reviewed_at);
    }

    public function test_admin_can_reject_uploaded_payment_proof(): void
    {
        $context = $this->createBookingContext();
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.bookings.show', $context['booking']))
            ->post(route('admin.bookings.paymentProof.reject', $context['booking']), [
                'review_notes' => 'Nominal pada bukti tidak sesuai invoice.',
            ]);

        $response
            ->assertRedirect(route('admin.bookings.show', $context['booking']))
            ->assertSessionHas('success');

        $context['payment']->refresh();

        $this->assertSame('pending', $context['payment']->status);
        $this->assertSame('rejected', $context['payment']->proof_status);
        $this->assertSame('admin', $context['payment']->proof_reviewer_role);
        $this->assertSame('Nominal pada bukti tidak sesuai invoice.', $context['payment']->proof_review_notes);
        $this->assertNull($context['payment']->paid_at);
        $this->assertNotNull($context['payment']->proof_reviewed_at);
    }

    public function test_reupload_payment_proof_resets_previous_review_metadata(): void
    {
        Storage::fake('public');

        $context = $this->createBookingContext([
            'status' => 'paid',
            'proof_status' => 'verified',
            'proof_review_notes' => 'Sudah diverifikasi sebelumnya.',
            'proof_reviewed_at' => now()->subHour(),
            'proof_reviewer_role' => 'admin',
            'paid_at' => now()->subHour(),
        ]);

        $response = $this
            ->actingAs($context['customer'])
            ->from(route('user.bookings.payment.proof', $context['booking']))
            ->post(route('user.bookings.payment.proof.store', $context['booking']), [
                'payment_proof' => UploadedFile::fake()->image('new-proof.jpg'),
                'proof_notes' => 'Upload ulang karena diminta revisi.',
            ]);

        $response
            ->assertRedirect(route('user.bookings.invoice', $context['booking']))
            ->assertSessionHas('success');

        $context['payment']->refresh();

        $this->assertSame('pending', $context['payment']->status);
        $this->assertSame('uploaded', $context['payment']->proof_status);
        $this->assertNull($context['payment']->proof_review_notes);
        $this->assertNull($context['payment']->proof_reviewed_at);
        $this->assertNull($context['payment']->proof_reviewer_role);
        $this->assertNull($context['payment']->paid_at);
        $this->assertNotNull($context['payment']->proof_uploaded_at);
        $this->assertNotNull($context['payment']->proof_path);

        Storage::disk('public')->assertExists($context['payment']->proof_path);
    }

    /**
     * @return array{customer: User, vendorUser: User, vendor: Vendor, vehicle: Vehicle, booking: Booking, payment: Payment}
     */
    private function createBookingContext(array $paymentOverrides = []): array
    {
        $district = District::create([
            'name' => 'Bandung',
        ]);

        $customer = User::factory()->create([
            'role' => 'user',
        ]);

        $vendorUser = User::factory()->create([
            'role' => 'vendor',
        ]);

        $vendor = Vendor::create([
            'user_id' => $vendorUser->id,
            'district_id' => $district->id,
            'store_name' => 'Vendor Test',
            'status' => 'approved',
            'verified' => true,
        ]);

        $vehicle = Vehicle::create([
            'vendor_id' => $vendor->id,
            'name' => 'Honda Beat',
            'category' => 'motor',
            'price_per_day' => 100000,
            'year' => 2023,
            'engine_cc' => 125,
            'stock' => 3,
            'status' => 'available',
        ]);

        $booking = Booking::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'total_price' => 100000,
            'status' => 'pending',
            'fulfillment_method' => 'pickup',
        ]);

        $payment = Payment::create(array_merge([
            'booking_id' => $booking->id,
            'amount' => 30000,
            'payment_type' => 'dp',
            'status' => 'pending',
            'provider' => 'manual_qris',
            'payment_method' => 'qris',
            'invoice_number' => 'INV-TEST-' . $booking->id,
            'proof_path' => 'payments/proofs/original-proof.jpg',
            'proof_status' => 'uploaded',
            'proof_uploaded_at' => now(),
        ], $paymentOverrides));

        return [
            'customer' => $customer,
            'vendorUser' => $vendorUser,
            'vendor' => $vendor,
            'vehicle' => $vehicle,
            'booking' => $booking,
            'payment' => $payment,
        ];
    }

    private function createVendorUser(): User
    {
        $district = District::create([
            'name' => 'Jakarta',
        ]);

        $vendorUser = User::factory()->create([
            'role' => 'vendor',
        ]);

        Vendor::create([
            'user_id' => $vendorUser->id,
            'district_id' => $district->id,
            'store_name' => 'Vendor Lain',
            'status' => 'approved',
            'verified' => true,
        ]);

        return $vendorUser;
    }
}
