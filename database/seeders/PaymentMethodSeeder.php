<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;
class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Cash',
                'notes' => 'Cash transactions',
            ],
            [
                'name' => 'Credit Card',
                'notes' => 'Credit card transactions',
            ],
            [
                'name' => 'Bank Transfer',
                'notes' => 'Bank transfer transactions',
            ],
            [
                'name' => 'Mobile Payment',
                'notes' => 'Mobile payment transactions',
            ],
            [
                'name' => 'Cheque',
                'notes' => 'Cheque transactions',
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['name' => $method['name']],
                [
                    'notes' => $method['notes'],
                    'is_active' => true,
                    'user_id' => null,
                ]
            );
        }
    }
}
