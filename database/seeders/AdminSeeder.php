<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'Mahmoud Abdelrahim' ,
            'email' => 'mahmoud@gmail.com' ,
            'password' => Hash::make('123456789') ,
            'role_id' => 1 ,
            'address' => 'Egypt' ,
            'phone' => '01201955377' ,
        ]) ;
    }
}
