<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            'Kecamatan Klojen',
            'Kecamatan Blimbing',
            'Kecamatan Kedungkandang',
            'Kecamatan Sukun',
            'Kecamatan Lowokwaru',
        ];

        foreach ($districts as $name) {
            District::firstOrCreate(['name' => $name]);
        }
    }
}
