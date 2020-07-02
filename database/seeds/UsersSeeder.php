<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 5)->create();

        User::create([
            'name' => 'user test',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'admin test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'is_admin' => 1,
            'email_verified_at' => now()
        ]);
    }
}
