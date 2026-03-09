<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class VendorRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_name'   => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:20'],
            'district_id'  => ['required', 'exists:districts,id'],
            'description'  => ['nullable', 'string', 'max:1000'],
            'address'      => ['nullable', 'string', 'max:500'],
            'bank_name'    => ['nullable', 'string', 'max:100'],
            'bank_account' => ['nullable', 'string', 'max:50'],

            // File uploads
            'ktp'          => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'permit'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'photo'        => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'store_name.required'  => 'Nama toko wajib diisi.',
            'phone.required'       => 'Nomor telepon wajib diisi.',
            'district_id.required' => 'Kecamatan wajib dipilih.',
            'district_id.exists'   => 'Kecamatan tidak valid.',
            'ktp.required'         => 'Foto KTP wajib diunggah.',
            'ktp.mimes'            => 'Format KTP harus JPG, PNG, atau PDF.',
            'ktp.max'              => 'Ukuran file KTP maksimal 2MB.',
            'permit.mimes'         => 'Format surat izin harus JPG, PNG, atau PDF.',
            'permit.max'           => 'Ukuran file surat izin maksimal 2MB.',
            'photo.mimes'          => 'Format foto toko harus JPG atau PNG.',
            'photo.max'            => 'Ukuran foto toko maksimal 2MB.',
        ];
    }
}
