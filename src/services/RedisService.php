<?php
namespace app\services;


/**
 * Class to handle redis connection 
 *
 * @author Martin Dimondo <martin.dimondo@gmail.com>
 */
class RedisService {
    private function __construct() {}

    /**
     * Main function to handle a request
     * @throws Exception if it can't connect to the server
     * @return \Redis
     */
    public static function openConnection() {
        $redisHost = getenv('REDIS_HOST');
        $redisPort = getenv('REDIS_PORT');

        $redis = new \Redis();
        $redis->connect($redisHost, $redisPort);

        if ($redis->isConnected()) {
            $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
            return $redis;
        }
        throw new \Exception('Server error, can\'t connect.'); 
    }
}
