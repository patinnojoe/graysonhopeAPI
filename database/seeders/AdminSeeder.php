<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        User::updateOrCreate(
            ['email' => 'graysonhopeinitiative@gmail.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'admin',
                'address' => '1234567890',
                'phone_number' => 'Admin Account',
                'skills' => 'admin',
                'volunteer_role' => 'admin',
                'extra_info' => 'This is the admin account for the Grayson Hope Initiative',
                // 'email' => 'graysonhopeinitiative@gmail.com',
                'password' => Hash::make('specialVolunteer011'),
                'role' => 'admin',
                'about' => 'this is an admin account for the Grayson Hope Initiative. It is used to manage the application and its users.',
            ]
        );
    }
}
