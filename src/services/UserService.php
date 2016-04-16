<?php
namespace app\services;

class UserService {
    protected $conn;

    public function __construct() {
        $this->conn = RedisService::openConnection();
    }

    public function findFriendList($sessionHash) {
        $session = $this->conn->get(
            join(':', ['PHPREDIS_SESSION', $sessionHash])
        );
        if (!empty($session['default']['id'])) {

            $key = str_replace('{:userId}',
                               $session['default']['id'],
                               FRIENDS_CACHE_PREFIX_KEY);

            $friendsList = $this->conn->get($key);
            $friendUserIds = $friendsList->getUserIds();

            if (!empty($friendUserIds)) {
                $keys = array_map(function ($userId) {
                    return str_replace('{:userId}', $userId, ONLINE_CACHE_PREFIX_KEY);
                }, $friendUserIds);

                // multi-get for faster operations
                $result = $this->conn->mget($keys);

                $onlineUsers = array_filter(
                    array_combine(
                        $friendUserIds,
                        $result
                    )
                );

                if ($onlineUsers) {
                    $friendsList->setOnline($onlineUsers);
                }

            }
            return $friendsList->toArray(); 
        }
        throw new SessionNotFound(); 
    } 
}
