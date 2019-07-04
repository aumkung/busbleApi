<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class FakerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = new User();
        $user1->name = 'BubuMan';
        $user1->telno = '0840984671';
        $user1->password = Hash::make('123456');
        $user1->email = 'test@gmail.com';
        $user1->gender = 'male';
        $user1->thumbnail = 'https://dummyimage.com/100x100/#fff/#fff.png';
        $user1->save();

        $user2 = new User();
        $user2->name = 'Nobody';
        $user2->telno = '0843985621';
        $user2->password = Hash::make('123456');
        $user2->email = 'test2@gmail.com';
        $user2->gender = 'female';
        $user2->thumbnail = 'https://dummyimage.com/100x100/#fff/#fff.png';
        $user2->save();
    }
}
