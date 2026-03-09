<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'label'       => ['required', 'string', 'max:50'],
            'street'      => ['required', 'string', 'max:500'],
            'district_id' => ['required', 'exists:districts,id'],
            'city'        => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'is_default'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'label.required'       => 'Label alamat wajib diisi (contoh: Rumah, Kantor).',
            'street.required'      => 'Alamat jalan wajib diisi.',
            'district_id.required' => 'Kecamatan wajib dipilih.',
            'district_id.exists'   => 'Kecamatan tidak valid.',
            'city.required'        => 'Kota wajib diisi.',
            'postal_code.required' => 'Kode pos wajib diisi.',
        ];
    }
}
