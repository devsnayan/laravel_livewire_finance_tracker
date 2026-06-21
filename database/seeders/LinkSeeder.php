<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Link;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $links = [
            [
                'category_id' => 1,
                'title' => 'Laravel Documentation',
                'url' => 'https://laravel.com/docs',
                'icon' => 'laravel',
                'notes' => 'Official Laravel docs',
            ],
            [
                'category_id' => 1,
                'title' => 'PHP Manual',
                'url' => 'https://www.php.net/manual/en/',
                'icon' => 'php',
                'notes' => 'Official PHP documentation',
            ],
            [
                'category_id' => 2,
                'title' => 'MDN Web Docs',
                'url' => 'https://developer.mozilla.org',
                'icon' => 'web',
                'notes' => 'Frontend web docs',
            ],
            [
                'category_id' => 2,
                'title' => 'JavaScript Info',
                'url' => 'https://javascript.info',
                'icon' => 'js',
                'notes' => 'Modern JavaScript tutorial',
            ],
            [
                'category_id' => 3,
                'title' => 'Notion',
                'url' => 'https://notion.so',
                'icon' => 'notion',
                'notes' => 'Productivity tool',
            ],
        ];

        foreach ($links as $link) {
            Link::updateOrCreate(
                [
                    'user_id' => 1,
                    'title' => $link['title'],
                ],
                [
                    'category_id' => $link['category_id'],
                    'url' => $link['url'],
                    'icon' => $link['icon'] ?? null,
                    'notes' => $link['notes'] ?? null,
                    'is_star' => false,
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );
        }
    }
}
