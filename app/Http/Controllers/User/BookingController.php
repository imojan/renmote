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
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected PaymentService $paymentService;
    protected AvailabilityService $availabilityService;

    public function __construct(
        BookingService $bookingService,
        PaymentService $paymentService,
        AvailabilityService $availabilityService
    )
    {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
        $this->availabilityService = $availabilityService;
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
     * Step 2: detail pembayaran QRIS.
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

        return view('front.bookings.payment', [
            'booking' => $booking,
            'summary' => $this->buildSummary($booking->vehicle, $booking->start_date, $booking->end_date),
            'qrisLogoUrl' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/QRIS_logo.svg/512px-QRIS_logo.svg.png',
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

        if ($booking->payment->expires_at && $booking->payment->expires_at->isPast()) {
            return back()->with('error', 'Waktu pembayaran sudah habis. Silakan hubungi admin untuk membuat invoice baru.');
        }

        return redirect()
            ->route('user.bookings.payment.proof', $booking)
            ->with('success', 'Lanjutkan ke upload bukti pembayaran untuk proses verifikasi admin/vendor.');
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
}
