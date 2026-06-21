<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ledger;
use App\LedgerStatus;

class LedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ledgers = [
            [
                'ledger_type_id' => 1,
                'name' => 'Personal Finance',
                'title' => 'Personal Income & Expense',
            ],
            [
                'ledger_type_id' => 2,
                'name' => 'Family Expense',
                'title' => 'Family Monthly Expenses',
            ],
            [
                'ledger_type_id' => 3,
                'name' => 'Business Account',
                'title' => 'Business Transactions',
            ],
            [
                'ledger_type_id' => 4,
                'name' => 'Rahim Loan',
                'title' => 'Loan Given to Rahim',
            ],
            [
                'ledger_type_id' => 4,
                'name' => 'Bank Loan',
                'title' => 'Personal Loan Account',
            ],
            [
                'ledger_type_id' => 5,
                'name' => 'ERP Project',
                'title' => 'ERP Development Project',
            ],
            [
                'ledger_type_id' => 5,
                'name' => 'Website Project',
                'title' => 'Client Website Development',
            ],
            [
                'ledger_type_id' => 6,
                'name' => 'Coxs Bazar Tour',
                'title' => 'Friends Tour Expense',
            ],
            [
                'ledger_type_id' => 6,
                'name' => 'Wedding Event',
                'title' => 'Wedding Budget Tracking',
            ],
            [
                'ledger_type_id' => 1,
                'name' => 'Tuition Income',
                'title' => 'Tuition Payment Records',
            ],
        ];

        foreach ($ledgers as $ledger) {
            Ledger::updateOrCreate(
                [
                    'user_id' => 1,
                    'name' => $ledger['name'],
                ],
                [
                    'ledger_type_id' => $ledger['ledger_type_id'],
                    'title' => $ledger['title'],
                    'notes' => null,
                    'opened_at' => now(),
                    'closed_at' => null,
                    'is_star' => false,
                    'is_active' => true,
                ]
            );
        }
    }
}
