<?php

use Illuminate\Database\Seeder;
use App\User;
use App\ApiClient;
use App\FeedChannel;

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

        $tester = (new User([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('test'),
        ]));
        $tester->save();
        $readUUID = '96262b26-28f4-4448-962b-d9fd10b18344';
        $tester->createApiClient([
            'api_key' => $readUUID,
            'authorizations' => [
                FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_READ),
            ]
        ]);
        $writeUUID = '117b48e8-eed5-44af-b0f9-8e2091362f1d';
        $tester->createApiClient([
            'api_key' => $writeUUID,
            'authorizations' => [
                FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_PUSH),
            ],
        ]);
    }
}
