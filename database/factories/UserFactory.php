<?php

namespace Database\Factories;

use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use function Spatie\array_rand_value;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'user_type_id' => $this->getRandomUserType(),
            'gender' => $this->getRandomGender(),
            'phone_number' => $this->faker->unique()->phoneNumber(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function getRandomUserType(){
        $userTypes = UserType::all()->pluck('id')->toArray();
        return array_rand_value((array)$userTypes, 1);
    }
    public function getRandomGender(){
        $genders = ['male', 'female'];
        return array_rand_value($genders, 1);
    }
}
