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
        $writeUUID = '117b48e8-eed5-44af-b0f9-8e2091362f1d';
        $readUUID = '96262b26-28f4-4448-962b-d9fd10b18344';
        $readonly = new ApiClient([
            'id' => $readUUID,
            'authorizations' => ['read-channel'],
        ]);
        $writer = new ApiClient([
            'id' => $writeUUID,
            'authorizations' => ['read-channel', 'write-channel'],
        ]);
        $tester->apiClients()->save($readonly);
        $tester->apiClients()->save($writer);
    }
}
