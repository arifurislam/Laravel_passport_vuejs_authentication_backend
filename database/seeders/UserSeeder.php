<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for($i = 0; $i <= 100; $i++){
        $user = new User ();
        $user->name = $faker->name;
        $user->email = $faker->email;
        $user->phone = $faker->phoneNumber;
        $user->photo = 'avatar.png';
        $user->password = Hash::make('11111111');
        $user->save();
    }
    }
}
