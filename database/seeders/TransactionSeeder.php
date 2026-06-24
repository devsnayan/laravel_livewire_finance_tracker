<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\TransactionType;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            [
                'ledger_id' => 1,
                'date' => '2026-06-01',
                'trx_type' => TransactionType::Credit,
                'notes' => 'Salary received',
                'payment_method_id' => 1,
                'trx_no' => 'SAL-001',
                'amount' => 30000
            ],
            [
                'ledger_id' => 1,
                'date' => '2026-06-02',
                'trx_type' => TransactionType::Debit,
                'notes' => 'Monthly groceries',
                'payment_method_id' => 1,
                'trx_no' => 'EXP-001',
                'amount' => 534
            ],
            [
                'ledger_id' => 1,
                'date' => '2026-06-03',
                'trx_type' => TransactionType::Debit,
                'notes' => 'Family expense',
                'payment_method_id' => 2,
                'trx_no' => 'FAM-001',
                'amount' => 6000
            ],
            [
                'ledger_id' => 1,
                'date' => '2026-06-04',
                'trx_type' => TransactionType::Credit,
                'notes' => 'Client payment received',
                'payment_method_id' => 3,
                'trx_no' => 'BUS-001',
                'amount' => 534
            ],
            [
                'ledger_id' => 1,
                'date' => '2026-06-05',
                'trx_type' => TransactionType::Debit,
                'notes' => 'Loan given to friend',
                'payment_method_id' => 1,
                'trx_no' => 'LOAN-001',
                'amount' => 4432
            ],
            [
                'ledger_id' => 1,
                'date' => '2026-06-06',
                'trx_type' => TransactionType::Debit,
                'notes' => 'Project hosting cost',
                'payment_method_id' => 2,
                'trx_no' => 'PRJ-001',
                'amount' => 534
            ],
            [
                'ledger_id' => 1,
                'date' => '2026-06-07',
                'trx_type' => TransactionType::Debit,
                'notes' => 'Tour transport expense',
                'payment_method_id' => 1,
                'trx_no' => 'TOUR-001',
                'amount' => 6786
            ],
            [
                'ledger_id' => 1,
                'date' => '2026-06-08',
                'trx_type' => TransactionType::Credit,
                'notes' => 'Tuition payment received',
                'payment_method_id' => 2,
                'trx_no' => 'TUI-001',
                'amount' => 534
            ],
            [
                'ledger_id' => 1,
                'date' => '2026-06-09',
                'trx_type' => TransactionType::Debit,
                'notes' => 'Office internet bill',
                'payment_method_id' => 3,
                'trx_no' => 'BUS-002',
                'amount' => 534
            ],
            [
                'ledger_id' => 1,
                'date' => '2026-06-10',
                'trx_type' => TransactionType::Debit,
                'notes' => 'Medical expense',
                'payment_method_id' => 1,
                'trx_no' => 'MED-001',
                'amount' => 456
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::updateOrCreate(
                [
                    'user_id' => 3,
                    'trx_no' => $transaction['trx_no'],
                ],
                [
                    'user_id' => 3,
                    'ledger_id' => $transaction['ledger_id'],
                    'date' => $transaction['date'],
                    'trx_type' => $transaction['trx_type'],
                    'notes' => $transaction['notes'],
                    'payment_method_id' => $transaction['payment_method_id'],
                ]
            );
        }
    }
}
