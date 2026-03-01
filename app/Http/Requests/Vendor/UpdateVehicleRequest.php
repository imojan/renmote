<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if user is vendor and owns this vehicle
        if (!auth()->check() || auth()->user()->role !== 'vendor') {
            return false;
        }

        $vehicle = $this->route('vehicle');
        return $vehicle && auth()->user()->vendor && 
               $vehicle->vendor_id === auth()->user()->vendor->id;
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
            'status' => 'required|in:available,unavailable',
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
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
        ];
    }
}
