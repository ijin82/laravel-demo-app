<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Seed the tags used by the demo posts.
     */
    public function run(): void
    {
        $names = [
            'Код',
            'Архитектура',
            'Инструменты',
            'Заметки',
            'Истории',
            'Мысли',
            'Карьера',
            'Чтение',
        ];

        foreach ($names as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}
