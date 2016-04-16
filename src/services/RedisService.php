<?php
namespace app\services;




class RedisService {
    private function __construct() {}

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
