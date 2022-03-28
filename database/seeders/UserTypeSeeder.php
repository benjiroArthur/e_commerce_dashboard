<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items =[
            [
                'title' => 'admin',
                'description' => 'I want to manage the system',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'vendor',
                'description' => 'I want to sell products',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'customer',
                'description' => 'I want to buy items',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($items as $item){
            UserType::updateOrCreate(['title' => $item['title']], $item);
        }
    }
}
