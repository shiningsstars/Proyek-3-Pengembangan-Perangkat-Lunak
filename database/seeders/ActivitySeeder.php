<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $workshop = Category::firstOrCreate(['slug' => 'workshop'], ['name' => 'Workshop']);
        $seminar = Category::firstOrCreate(['slug' => 'seminar'], ['name' => 'Seminar']);
        $pelatihan = Category::firstOrCreate(['slug' => 'pelatihan'], ['name' => 'Pelatihan']);
        // Sengaja tidak dipakai activity manapun (untuk uji restrictOnDelete).
        Category::firstOrCreate(['slug' => 'lomba'], ['name' => 'Lomba']);

        // [code, title, category, start_at, end_at, capacity, status]
        $activities = [
            // Draft lengkap -> bisa dipublish
            ['WS-001', 'Workshop Git Dasar', $workshop, '2026-10-05 09:00', '2026-10-05 12:00', 30, Activity::STATUS_DRAFT],
            ['SM-001', 'Seminar Web Quality', $seminar, '2026-10-12 13:00', '2026-10-12 15:30', 100, Activity::STATUS_DRAFT],
            ['PL-001', 'Pelatihan Docker untuk Pemula', $pelatihan, '2026-11-02 09:00', '2026-11-02 16:00', 25, Activity::STATUS_DRAFT],

            // Draft TIDAK lengkap -> publish harus ditolak (BR-05)
            ['WS-002', 'Workshop Laravel Eloquent', $workshop, null, null, null, Activity::STATUS_DRAFT],
            ['SM-002', 'Seminar Keamanan Aplikasi Web', $seminar, '2026-11-20 10:00', null, 80, Activity::STATUS_DRAFT],
            ['PL-002', 'Pelatihan Testing dengan PHPUnit', $pelatihan, null, null, 20, Activity::STATUS_DRAFT],

            // Published -> bisa di-complete
            ['WS-003', 'Workshop REST API', $workshop, '2026-10-19 09:00', '2026-10-19 15:00', 40, Activity::STATUS_PUBLISHED],
            ['SM-003', 'Seminar Clean Code', $seminar, '2026-10-26 13:00', '2026-10-26 16:00', 120, Activity::STATUS_PUBLISHED],
            ['PL-003', 'Pelatihan Git Lanjutan', $pelatihan, '2026-11-09 09:00', '2026-11-09 14:00', 35, Activity::STATUS_PUBLISHED],
            ['WS-004', 'Workshop Blade Components', $workshop, '2026-12-03 09:00', '2026-12-03 12:00', 30, Activity::STATUS_PUBLISHED],

            // Completed -> tidak bisa publish/complete lagi
            ['WS-005', 'Workshop HTML dan CSS', $workshop, '2026-08-10 09:00', '2026-08-10 12:00', 50, Activity::STATUS_COMPLETED],
            ['SM-004', 'Seminar Karier di Dunia IT', $seminar, '2026-08-24 13:00', '2026-08-24 15:00', 150, Activity::STATUS_COMPLETED],
            ['SM-005', 'Seminar Pengenalan Cloud', $seminar, '2026-09-07 10:00', '2026-09-07 12:00', 90, Activity::STATUS_COMPLETED],
            ['PL-004', 'Pelatihan SQL Dasar', $pelatihan, '2026-09-14 09:00', '2026-09-14 15:00', 30, Activity::STATUS_COMPLETED],
            ['WS-006', 'Workshop JavaScript Dasar', $workshop, '2026-09-21 09:00', '2026-09-21 12:00', 45, Activity::STATUS_COMPLETED],
        ];

        foreach ($activities as [$code, $title, $category, $startAt, $endAt, $capacity, $status]) {
            Activity::updateOrCreate(
                ['code' => $code],
                [
                    'category_id' => $category->id,
                    'title' => $title,
                    'description' => "Deskripsi untuk {$title}.",
                    'start_at' => $startAt,
                    'end_at' => $endAt,
                    'capacity' => $capacity,
                    'status' => $status,
                ]
            );
        }
    }
}