<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemCategory;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Food',
                'notes' => 'Food and meal expenses',
            ],
            [
                'name' => 'Shopping',
                'notes' => 'Shopping expenses',
            ],
            [
                'name' => 'Transport',
                'notes' => 'Transportation expenses',
            ],
            [
                'name' => 'Rent',
                'notes' => 'House or office rent',
            ],
            [
                'name' => 'Salary',
                'notes' => 'Salary payments',
            ],
            [
                'name' => 'Tuition',
                'notes' => 'Tuition income and expenses',
            ],
            [
                'name' => 'Business',
                'notes' => 'Business related transactions',
            ],
            [
                'name' => 'Loan',
                'notes' => 'Loan related transactions',
            ],
            [
                'name' => 'Tour',
                'notes' => 'Tour and travel expenses',
            ],
            [
                'name' => 'Medical',
                'notes' => 'Medical and healthcare expenses',
            ],
        ];

        foreach ($categories as $category) {
            ItemCategory::updateOrCreate(
                ['name' => $category['name']],
                [
                    'notes' => $category['notes'],
                    'is_active' => true,
                ]
            );
        }
    }
}
