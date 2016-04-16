<?php
namespace app\services;


define('FRIENDS_CACHE_PREFIX_KEY', 'chat:friends:{:userId}');
define('ONLINE_CACHE_PREFIX_KEY', 'chat:online:{:userId}');


/**
 * User service 
 *
 * @author Martin Dimondo <martin.dimondo@gmail.com>
 */
class UserService {
    protected $conn;

    public function __construct() {
        $this->conn = RedisService::openConnection();
    }

    /**
     * Function to find a Friend List by session hash 
     * @param $sessionHash 
     *              User session
     * @throws Exception
     *              if it can't connect to the server
     * @return \app\domain\chat\FriendsList
     */
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
