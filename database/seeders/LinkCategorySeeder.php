<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LinkCategory;

class LinkCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Development',
                'icon' => 'code',
                'caption' => 'Programming & software resources',
                'children' => [
                    [
                        'name' => 'Laravel',
                        'icon' => 'laravel',
                        'caption' => 'Laravel framework resources',
                    ],
                    [
                        'name' => 'JavaScript',
                        'icon' => 'js',
                        'caption' => 'JS tutorials & tools',
                    ],
                ],
            ],
            [
                'name' => 'Design',
                'icon' => 'palette',
                'caption' => 'UI/UX and graphics resources',
                'children' => [
                    [
                        'name' => 'UI Kits',
                        'icon' => 'layout',
                        'caption' => 'UI components and kits',
                    ],
                    [
                        'name' => 'Icons',
                        'icon' => 'star',
                        'caption' => 'Icon packs and libraries',
                    ],
                ],
            ],
            [
                'name' => 'Business',
                'icon' => 'briefcase',
                'caption' => 'Business tools and references',
            ],
            [
                'name' => 'Education',
                'icon' => 'book',
                'caption' => 'Learning resources',
            ],
            [
                'name' => 'Finance',
                'icon' => 'bank',
                'caption' => 'Money management resources',
            ],
        ];

        foreach ($categories as $cat) {

            $parent = LinkCategory::updateOrCreate(
                [
                    'user_id' => 1,
                    'name' => $cat['name'],
                    'parent_id' => null,
                ],
                [
                    'icon' => $cat['icon'] ?? null,
                    'caption' => $cat['caption'] ?? null,
                    'is_active' => true,
                ]
            );

            if (!empty($cat['children'])) {
                foreach ($cat['children'] as $child) {
                    LinkCategory::updateOrCreate(
                        [
                            'user_id' => 1,
                            'name' => $child['name'],
                            'parent_id' => $parent->id,
                        ],
                        [
                            'icon' => $child['icon'] ?? null,
                            'caption' => $child['caption'] ?? null,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}
