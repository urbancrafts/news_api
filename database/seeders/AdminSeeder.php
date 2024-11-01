<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        App\Models\Admin::create([
            'name' => 'admin',
            'email' => 'admin@localhost.com',
            'password' => bcrypt('admin@123')
        ]);

        // App\Movie::create([
        //     'name' => 'The Empire Strikes Back',
        //     'year' => '1980'
        // ]);
    }
}
