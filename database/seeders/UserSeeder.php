<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $super_admin1 = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'phone' => '1234567890',
            'title' => 'Super Administrator',
            'avatar' => null,
            'password' => Hash::make('password'),
        ]);

        $super_admin1->assignRole('super_admin');

        $user1 = User::create([
            'name' => 'Mohammad Nayan',
            'email' => 'nayan@gmail.com',
            'phone' => '01690091590',
            'title' => 'Software Developer',
            'avatar' => null,
            'password' => Hash::make('password'),
        ]);

        $user1->assignRole('user');

        $user2 = User::create([
            'name' => 'Yeasin Arafat',
            'email' => 'arafat@gmail.com',
            'phone' => '01690091590',
            'title' => 'Marketing Manager',
            'avatar' => null,
            'password' => Hash::make('password'),
        ]);

        $user2->assignRole('user');
        
    }
}
