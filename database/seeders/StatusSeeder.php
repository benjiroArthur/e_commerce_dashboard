<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
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
                'name' => 'submitted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'confirmed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'initiated',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'pending confirmation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        foreach ($items as $item){
            Status::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
