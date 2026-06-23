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
                'name_bn' => 'সাধারণ',
                'notes' => 'General purpose ledger',
                'icon' => 'folder',
                'color' => '#6B7280',
            ],
            [
                'name' => 'Personal',
                'name_bn' => 'ব্যক্তিগত',
                'notes' => 'Personal income and expense ledger',
                'icon' => 'user',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Business',
                'name_bn' => 'ব্যবসা',
                'notes' => 'Business transactions ledger',
                'icon' => 'building-office',
                'color' => '#10B981',
            ],
            [
                'name' => 'Loan',
                'name_bn' => 'লোন',
                'notes' => 'Loan and borrowing ledger',
                'icon' => 'banknotes',
                'color' => '#EF4444',
            ],
            [
                'name' => 'Project',
                'name_bn' => 'প্রজেক্ট',
                'notes' => 'Project income and expense ledger',
                'icon' => 'briefcase',
                'color' => '#8B5CF6',
            ],
            [
                'name' => 'Event',
                'name_bn' => 'অনুষ্ঠান',
                'notes' => 'Event management ledger',
                'icon' => 'calendar-days',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Tuition',
                'name_bn' => 'টিউশন',
                'notes' => 'Tuition income ledger',
                'icon' => 'academic-cap',
                'color' => '#06B6D4',
            ],
            [
                'name' => 'Salary',
                'name_bn' => 'বেতন',
                'notes' => 'Salary income and payment ledger',
                'icon' => 'wallet',
                'color' => '#22C55E',
            ],
            [
                'name' => 'Rent',
                'name_bn' => 'ভাড়া',
                'notes' => 'House and property rent ledger',
                'icon' => 'home',
                'color' => '#F97316',
            ],
            [
                'name' => 'Tour',
                'name_bn' => 'ভ্রমন',
                'notes' => 'Tour and travel expense ledger',
                'icon' => 'map',
                'color' => '#14B8A6',
            ],
            [
                'name' => 'Shop Due',
                'name_bn' => 'দোকানের বাকী',
                'notes' => 'Due and credit purchase ledger',
                'icon' => 'shopping-cart',
                'color' => '#EC4899',
            ],
            [
                'name' => 'Client',
                'name_bn' => 'কাষ্টমার',
                'notes' => 'Client payment and receivable ledger',
                'icon' => 'users',
                'color' => '#0EA5E9',
            ],
            [
                'name' => 'Supplier',
                'name_bn' => 'সাফলাইয়ার',
                'notes' => 'Supplier payment ledger',
                'icon' => 'truck',
                'color' => '#84CC16',
            ],
            [
                'name' => 'Savings',
                'name_bn' => 'জমা',
                'notes' => 'Savings and investment ledger',
                'icon' => 'piggy-bank',
                'color' => '#16A34A',
            ],
            [
                'name' => 'Family',
                'name_bn' => 'পরিবার',
                'notes' => 'Family expense ledger',
                'icon' => 'heart',
                'color' => '#DC2626',
            ],
        ];

        foreach ($types as $type) {
            LedgerType::updateOrCreate(
                [
                    'name' => $type['name'],
                    'name_bn' => $type['name_bn']
                ],
                [
                    
                    'icon' => $type['icon'],
                    'color' => $type['color'],
                    'notes' => $type['notes'],
                    'is_active' => true,
                    'user_id' => null,
                ]
            );
        }
    }
}
