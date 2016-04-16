<?php
/**
 * Some constants
 */
define('FRIENDS_CACHE_PREFIX_KEY', 'chat:friends:{:userId}');
define('ONLINE_CACHE_PREFIX_KEY', 'chat:online:{:userId}');

/**
 * Load composer libraries
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * Load .env
 */
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();
new app\domain\chat\FriendsList();


class FriendListResource extends app\rest\Rest {

    public function get($request) {
        $userService = new app\services\UserService();
        try {
            return new app\rest\JSONResponse(
                200, 
                $userService->findFriendList('hash')
              );
        } catch (app\services\SessionNotFound $e) {
            return new app\rest\JSONResponse(
                404, 
                ['message' => 'Session not found']
            );
        } catch (Exception $e) {
            return new app\rest\JSONResponse(
                500, 
                ['message' => 'Inernal server error']
            );
        }
    }

}

(new FriendListResource())->execute();
