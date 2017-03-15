<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add a development testing user account if it doesn't already exist
        if (DB::table('users')->where('name', 'dev')->count() === 0) {
            DB::table('users')->insert([
                'name'     => 'dev',
                'email'    => 'dev@example.com',
                'password' => bcrypt('secret'),
            ]);
        }
    }
}
