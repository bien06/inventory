<?php

use Illuminate\Database\Seeder;
use App\AdminUser;

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
         $user = new AdminUser();
        $user->name = "Administrator";
        $user->email = "admin@allcardtech.com.ph";
        $user->password = bcrypt('AllcardTech');
        $user->role = 1;
        $user->save();
    }
}
