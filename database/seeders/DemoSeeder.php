<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Districts
        $districts = [
            ['name' => 'Jakarta Pusat'],
            ['name' => 'Jakarta Selatan'],
            ['name' => 'Jakarta Barat'],
            ['name' => 'Bandung'],
            ['name' => 'Yogyakarta'],
        ];

        foreach ($districts as $district) {
            District::create($district);
        }

        // Create Admin
        User::create([
            'name' => 'Admin Renmote',
            'email' => 'admin@renmote.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Vendors
        $vendorUsers = [
            [
                'name' => 'Budi Rental',
                'email' => 'budi@rental.com',
                'store_name' => 'Budi Motor Rental',
                'district_id' => 1,
                'phone' => '081234567890',
                'address' => 'Jl. Menteng Raya No. 10, Jakarta Pusat',
            ],
            [
                'name' => 'Jaya Motor',
                'email' => 'jaya@motor.com',
                'store_name' => 'Jaya Motor Yogyakarta',
                'district_id' => 5,
                'phone' => '082345678901',
                'address' => 'Jl. Malioboro No. 50, Yogyakarta',
            ],
        ];

        foreach ($vendorUsers as $vendorData) {
            $user = User::create([
                'name' => $vendorData['name'],
                'email' => $vendorData['email'],
                'password' => Hash::make('password'),
                'role' => 'vendor',
            ]);

            Vendor::create([
                'user_id' => $user->id,
                'district_id' => $vendorData['district_id'],
                'store_name' => $vendorData['store_name'],
                'phone' => $vendorData['phone'],
                'address' => $vendorData['address'],
                'status' => 'approved',
            ]);
        }

        // Create User
        User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create Vehicles
        $vehicles = [
            [
                'vendor_id' => 1,
                'name' => 'Honda Beat 2023',
                'category' => 'matic',
                'price_per_day' => 75000,
                'year' => 2023,
                'description' => 'Motor matic hemat bahan bakar, cocok untuk harian.',
                'stock' => 5,
                'status' => 'available',
            ],
            [
                'vendor_id' => 1,
                'name' => 'Honda Vario 160',
                'category' => 'matic',
                'price_per_day' => 100000,
                'year' => 2023,
                'description' => 'Motor matic premium dengan fitur lengkap.',
                'stock' => 3,
                'status' => 'available',
            ],
            [
                'vendor_id' => 1,
                'name' => 'Yamaha NMAX 2023',
                'category' => 'matic',
                'price_per_day' => 150000,
                'year' => 2023,
                'description' => 'Motor matic besar, nyaman untuk touring.',
                'stock' => 2,
                'status' => 'available',
            ],
            [
                'vendor_id' => 2,
                'name' => 'Honda Supra X 125',
                'category' => 'manual',
                'price_per_day' => 60000,
                'year' => 2022,
                'description' => 'Motor bebek irit dan tangguh.',
                'stock' => 4,
                'status' => 'available',
            ],
            [
                'vendor_id' => 2,
                'name' => 'Yamaha Aerox 155',
                'category' => 'matic',
                'price_per_day' => 125000,
                'year' => 2023,
                'description' => 'Motor sporty matic dengan performa tinggi.',
                'stock' => 2,
                'status' => 'available',
            ],
            [
                'vendor_id' => 2,
                'name' => 'Kawasaki Ninja 250',
                'category' => 'sport',
                'price_per_day' => 250000,
                'year' => 2022,
                'description' => 'Motor sport untuk pecinta kecepatan.',
                'stock' => 1,
                'status' => 'available',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('- Admin: admin@renmote.com / password');
        $this->command->info('- Vendor: budi@rental.com / password');
        $this->command->info('- User: user@test.com / password');
    }
}
