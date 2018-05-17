<?php

use Illuminate\Database\Seeder;
use App\User;
use App\ApiClient;

const MAIN_TEST_ACCOUNT_EMAIL = 'test@test.com';

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tester = (new App\User([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('test'),
        ]));
        $tester->save();
        $readonly = ApiClient::generate(['read-channel']);
        $writer = ApiClient::generate(['read-channel', 'write-channel']);
        $tester->apiClients()->save($readonly);
        $tester->apiClients()->save($writer);
    }
}
