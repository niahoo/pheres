<?php

use Illuminate\Database\Seeder;
use App\User;
use App\ApiClient;
use App\FeedChannel;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createTester1();
        $this->createTester2();
    }

    public function createTester1()
    {
        $tester = (new User([
            'name' => 'Tester 1',
            'email' => 'test1@localhost',
            'password' => bcrypt('test'),
        ]));
        $tester->save();
        $readUUID = '11111111-read-4448-962b-d9fd10b18344';
        $tester->createApiClient([
            'api_key' => $readUUID,
            'name' => 'My readonly client',
            'authorizations' => [
                FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_READ),
            ]
        ]);
        $writeUUID = '11111111-push-44af-b0f9-8e2091362f1d';
        $tester->createApiClient([
            'api_key' => $writeUUID,
            'name' => 'My writeonly client',
            'authorizations' => [
                FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_PUSH),
            ],
        ]);
    }

    public function createTester2()
    {
        $tester = (new User([
            'name' => 'Tester 2',
            'email' => 'test2@localhost',
            'password' => bcrypt('test'),
        ]));
        $tester->save();
        $readUUID = '22222222-read-4448-962b-d9fd10b18344';
        $tester->createApiClient([
            'api_key' => $readUUID,
            'name' => 'My readonly client',
            'authorizations' => [
                FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_READ),
            ]
        ]);
        $writeUUID = '22222222-push-44af-b0f9-8e2091362f1d';
        $tester->createApiClient([
            'api_key' => $writeUUID,
            'name' => 'My writeonly client',
            'authorizations' => [
                FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_PUSH),
            ],
        ]);
    }
}
