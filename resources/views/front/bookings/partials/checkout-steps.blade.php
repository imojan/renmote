@php
    $steps = [
        1 => 'Konfirmasi Penyewaan',
        2 => 'Bayar',
        3 => 'Upload Bukti',
        4 => 'Selesai',
    ];
@endphp

<div class="booking-checkout-steps" aria-label="Langkah checkout penyewaan">
    @foreach($steps as $stepNumber => $stepLabel)
        <div class="booking-checkout-step {{ $currentStep > $stepNumber ? 'is-done' : '' }} {{ $currentStep === $stepNumber ? 'is-active' : '' }}">
            <div class="booking-checkout-step-bullet">
                @if($currentStep > $stepNumber)
                    <i class="fa fa-check"></i>
                @else
                    {{ $stepNumber }}
                @endif
            </div>
            <div class="booking-checkout-step-label">{{ $stepLabel }}</div>
            @if(!$loop->last)
                <div class="booking-checkout-step-line"></div>
            @endif
        </div>
    @endforeach
</div>
