<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::firstOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'عن المدونة',
                'content' => '<p>الصفحة التعريفية للمدونة.</p>',
                'layout' => 'about',
                'featured_image' => null,
            ]
        );
    }
}
