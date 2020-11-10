<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $token_account = bcrypt(Str::random(60));

        return [
            'user_identifier' => mt_rand(000000, 999999),
            'name' => $this->faker->lastName,
            'firstname' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->userName,
            'password' =>  bcrypt('k&|X+a45*2['),
            'rank_id' => 2,
            'token_account' =>str_replace('/', '$', $token_account)
        ];
    }
}
