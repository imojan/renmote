<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'vendor';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|in:matic,manual,sport',
            'price_per_day' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kendaraan harus diisi.',
            'category.required' => 'Kategori harus dipilih.',
            'category.in' => 'Kategori tidak valid.',
            'price_per_day.required' => 'Harga per hari harus diisi.',
            'price_per_day.numeric' => 'Harga harus berupa angka.',
            'price_per_day.min' => 'Harga tidak boleh negatif.',
            'year.required' => 'Tahun harus diisi.',
            'year.integer' => 'Tahun harus berupa angka.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'stock.required' => 'Stok harus diisi.',
            'stock.min' => 'Stok minimal 1.',
        ];
    }
}
