<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $workshop = Category::create(['name' => 'Workshop', 'slug' => 'workshop']);
        $seminar = Category::create(['name' => 'Seminar', 'slug' => 'seminar']);
        Category::create(['name' => 'Lomba', 'slug' => 'lomba']); // sengaja tidak dipakai

        $activities = [
            ['Workshop Git Dasar', $workshop, '2026-10-05', 'Planned'],
            ['Seminar Web Quality', $seminar, '2026-10-12', 'Ongoing'],
            ['Workshop Laravel Basic', $workshop, '2026-10-19', 'Done'],
        ];

        foreach ($activities as [$title, $category, $date, $status]) {
            Activity::create([
                'title' => $title,
                'category_id' => $category->id,
                'activity_date' => $date,
                'status' => $status,
            ]);
        }
    }
}