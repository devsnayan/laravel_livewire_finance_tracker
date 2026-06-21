<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LedgerType;

class LedgerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'General',
                'notes' => 'General purpose ledger',
            ],
            [
                'name' => 'Personal',
                'notes' => 'Personal income and expense ledger',
            ],
            [
                'name' => 'Business',
                'notes' => 'Business transactions ledger',
            ],
            [
                'name' => 'Loan',
                'notes' => 'Loan and borrowing ledger',
            ],
            [
                'name' => 'Project',
                'notes' => 'Project based ledger',
            ],
            [
                'name' => 'Event',
                'notes' => 'Event expense and income ledger',
            ],
            [
                'name' => 'Tuition',
                'notes' => 'Tuition income ledger',
            ],
        ];

        foreach ($types as $type) {
            LedgerType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'notes' => $type['notes'],
                    'is_active' => true,
                    'user_id' => null,
                ]
            );
        }
    }
}
