<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Challenge;

class ChallengeSeeder extends Seeder
{
    public function run()
    {
        Challenge::truncate(); // opcional: limpa antes

        Challenge::create([
            'title'       => 'Reed 10 books this month',
            'description' => 'Complete 10 books before end this month',
            'target'      => 10,
            'period'      => 'monthly',
        ]);

        Challenge::create([
            'title'       => 'Write 5 reviews',
            'description' => 'Post 5 reviews on the site',
            'target'      => 5,
            'period'      => 'monthly',
        ]);
    }
}
