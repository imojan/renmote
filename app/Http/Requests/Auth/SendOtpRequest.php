<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // Format Indonesia: 08xx / +628xx / 628xx (10-15 digit)
            'phone_number' => [
                'required',
                'string',
                'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.regex'    => 'Format nomor telepon tidak valid. Gunakan format 08xx atau +628xx.',
        ];
    }

    /**
     * Normalize phone number to 08xx format after validation.
     */
    public function normalizedPhone(): string
    {
        $phone = $this->phone_number;

        // Convert +628xx or 628xx to 08xx
        if (str_starts_with($phone, '+62')) {
            $phone = '0' . substr($phone, 3);
        } elseif (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        return $phone;
    }
}
