<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\PointPictures;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class pointPicturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PointPictures::factory(10)->create();
    }
}
