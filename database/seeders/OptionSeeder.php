<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    private array $frameworks = [
        'Laravel',
        'Zend',
        'Symfony',
        'Others',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->frameworks as $framework) {
            DB::table('options')->insert([
                'option_text' => $framework,
                'question_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
