<?php

namespace Database\Seeders;

use App\Models\NotesModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PHPUnit\TextUI\Application;

class NotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotesModel::factory()->count(50)->create();
    }
}
