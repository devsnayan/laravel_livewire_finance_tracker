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
                'name' => 'Bkash',
                'notes' => 'Bank transfer transactions',
            ],
            [
                'name' => 'Bank',
                'notes' => 'Bkash payment transactions',
            ],
            [
                'name' => 'Nogod',
                'notes' => 'Nogod payment transactions',
            ],
            [
                'name' => 'Other',
                'notes' => 'Other Method ',
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
