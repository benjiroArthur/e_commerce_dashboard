<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userType = UserType::where('title', 'admin')->first();
        if(!empty($userType)){
            User::updateOrCreate(['email' => 'admin@admin.com'],[
                'name' => 'Admin Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'user_type_id' => $userType->id,
                'gender' => 'male',
                'phone_number' => '0244556677',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]);
        }

        User::factory(20)->create();

    }
}
