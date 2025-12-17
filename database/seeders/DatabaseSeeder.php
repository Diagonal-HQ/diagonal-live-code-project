<?php

namespace Database\Seeders;

use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        UserFactory::new()->create([
            'name' => 'Diagonal User',
        ]);
    }
}
