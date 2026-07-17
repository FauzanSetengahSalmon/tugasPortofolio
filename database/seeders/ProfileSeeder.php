<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profile = Profile::create([
            'name' => 'Ahmad Fauzan Ramadhan',
            'headline' => 'Software Engineering Student & Fullstack Developer & AR/VR Enthusiast',
            'biography' => 'Mahasiswa  yang fokus pada pengembangan web fullstack dan juga mengeksplorasi teknologi AR/VR.',
            'email' => 'fauzangg83728@gmail.com',
            'linkedin' => 'https://linkedin.com/in/fauzan',
            'github' => 'https://github.com/FauzanSetengahSalmon'
        ]);

        $profile->experiences()->create([
            'position' => 'PIC & Team Lead',
            'institution' => 'Organisasi Kampus',
            'period' => 'Maret 2026',
            'description' => 'Memimpin koordinasi tim divisi Penilaian dan Staff Acara.',
            'order' => 1
        ]);

        $profile->educations()->create([
            'institution' => 'Telkom University',
            'degree' => 'D3 Rekayasa Perangkat Lunak Aplikasi',
            'period' => '2024 - Sekarang',
            'description' => 'Fokus pada rekayasa perangkat lunak.',
            'order' => 1
        ]);

        $profile->projects()->create([
            'name' => 'Digital Catalog KWT (Kelompok Wanita Tani)',
            'description' => 'Platform pemasaran digital dan katalog hasil panen sendiri yang dibangun menggunakan metodologi Scrum.',
            'link' => 'https://github.com/FauzanSetengahSalmon/Website-Digital-Marketing',
            'tech_stack' => 'Laravel, Tailwind CSS, MySQL',
            'order' => 1
        ]);

        $profile->skills()->createMany([
            ['name' => 'PHP', 'level' => 'Intermediate', 'order' => 1],
            ['name' => 'Laravel', 'level' => 'Intermediate', 'order' => 2],
            ['name' => 'JavaScript', 'level' => 'Intermediate', 'order' => 3],
            ['name' => 'MySQL', 'level' => 'Intermediate', 'order' => 4],
        ]);
    }
}
