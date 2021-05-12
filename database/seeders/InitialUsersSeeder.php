<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class InitialUsersSeeder extends Seeder
{
    use WithFaker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = collect([['name' => 'Amy', 'email' => 'amy.azmim@gmail.com']]);

        $users->each(function ($user) {
            $userExists = User::where('email', $user['email'])->exists();
            if (!$userExists) {
                $attr = array_merge($user, ['password' => Hash::make('password')]);
                User::create($attr);
            }
        });
    }
}
