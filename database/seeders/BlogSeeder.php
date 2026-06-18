<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\BlogCategory;
use App\Models\BlogTag;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Announcements', 'Tutorials', 'Case Studies', 'Product Updates'];
        foreach ($categories as $cat) {
            BlogCategory::firstOrCreate(['slug' => \Illuminate\Support\Str::slug($cat)], ['name' => $cat]);
        }

        $tags = ['Digital Signage', 'Marketing', 'Retail', 'Tech', 'Hardware'];
        foreach ($tags as $tag) {
            BlogTag::firstOrCreate(['slug' => \Illuminate\Support\Str::slug($tag)], ['name' => $tag]);
        }
    }
}
