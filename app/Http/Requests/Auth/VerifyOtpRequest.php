<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'phone_number' => [
                'required',
                'string',
                'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/',
            ],
            'otp' => [
                'required',
                'string',
                'digits:6',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.regex'    => 'Format nomor telepon tidak valid.',
            'otp.required'          => 'Kode OTP wajib diisi.',
            'otp.digits'            => 'Kode OTP harus 6 digit.',
        ];
    }

    /**
     * Normalize phone number to 08xx format.
     */
    public function normalizedPhone(): string
    {
        $phone = $this->phone_number;

        if (str_starts_with($phone, '+62')) {
            $phone = '0' . substr($phone, 3);
        } elseif (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        return $phone;
    }
}
