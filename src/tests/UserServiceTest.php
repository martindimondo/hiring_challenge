<?php

/**
 * Load .env
 */
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../../');
$dotenv->load();

class UserServiceTest extends PHPUnit_Framework_TestCase {
    public $conn;
    public $service;

    public function setUp() {
      $this->service = new \app\services\UserService();
      $this->conn = \app\services\RedisService::openConnection();

      echo 'Saving key "PHPREDIS_SESSION:hash" to Redis...' . PHP_EOL;
      $this->conn->set('PHPREDIS_SESSION:hash', ['default' => ['id' => 1]]);
      echo 'Saving key "chat:online:176733" to Redis...' . PHP_EOL;
      $this->conn->set('chat:online:176733', true);
      echo 'Saving key "chat:friends:1" to Redis...' . PHP_EOL;
      $this->conn->set('chat:friends:1', new \app\domain\chat\FriendsList([
            [
                'id' => 1,
                'name' => 'Project 1',
                'threads' => [
                    [
                        'online' => false,
                        'other_party' => [
                            'user_id' => 176733,
                        ]
                    ]
                ]
            ],
            [
                'id' => 2,
                'name' => 'Project 2',
                'threads' => [
                    [
                        'online' => false,
                        'other_party' => [
                            'user_id' => 176733,
                        ]
                    ]
                ]
            ]
          ]));

    }

    public function testUserFriendList() {
        $friends = $this->service->findFriendList('hash');        
        $this->assertTrue(!empty($friends));
        $this->assertTrue(count($friends) == 2);
        $this->assertTrue($friends[0]['id'] == 1);
    }  

    public function testSessionNotFound() {
        $this->setExpectedException('\app\services\SessionNotFound');
        $friends = $this->service->findFriendList('hash1');        
    }  
}
