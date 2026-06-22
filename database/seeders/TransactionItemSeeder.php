<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransactionItem;
class TransactionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $items = [
            [
                'transaction_id' => 1,
                'category_id' => 1,
                'name' => 'Lunch',
                'notes' => 'Restaurant lunch',
                'amount' => 350,
            ],
            [
                'transaction_id' => 2,
                'category_id' => 3,
                'name' => 'Rickshaw Fare',
                'notes' => 'Office travel',
                'amount' => 120,
            ],
            [
                'transaction_id' => 3,
                'category_id' => 2,
                'name' => 'Groceries',
                'notes' => 'Daily shopping',
                'amount' => 850,
            ],
            [
                'transaction_id' => 4,
                'category_id' => 10,
                'name' => 'Medicine',
                'notes' => 'Pharmacy purchase',
                'amount' => 450,
            ],
            [
                'transaction_id' => 5,
                'category_id' => 9,
                'name' => 'Hotel Booking',
                'notes' => 'Tour expense',
                'amount' => 2500,
            ],
        ];

        foreach ($items as $item) {
            TransactionItem::create($item);
        }
    }
}
