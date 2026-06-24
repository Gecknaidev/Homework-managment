<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Employee::insert([
            [
                'username'   => 'sokha',
                'fullname'   => 'Chan Sokha',
                'gender'     => 'Male',
                'password'   => Hash::make('123456'),
                'phone'      => '012345678',
                'address'    => 'Phnom Penh',
                'position'   => 'Developer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'dara',
                'fullname'   => 'Kim Dara',
                'gender'     => 'Female',
                'password'   => Hash::make('123456'),
                'phone'      => '098765432',
                'address'    => 'Siem Reap',
                'position'   => 'Designer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}