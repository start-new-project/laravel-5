<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'marwenhlaoui',
            'name' => 'Marwen Hlaoui',
            'email'=> 'marwenhlaoui@gmail.com',
            'password'=> bcrypt('mapass@2019'),
            'role'=>'admin',
            'active'=>true,
            'birthday'=>'05-07-1991',
            'gender'=>true,
        ]);
        factory(User::class,10)->create();
    }
}
