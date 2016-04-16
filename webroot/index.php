<?php

/**
 * Load composer libraries
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * Load .env
 */
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();


/**
 * @author Martin Dimondo <martin.dimondo@gmail.com>
 * @return \app\rest\JSONResponse
 *      status codes:
 *         HTTP 200 OK: request was valid, return the JSON representation of your FriendList.
 *         HTTP 403 Not Authorized
 *         HTTP 404 Not found: friends list is not available.
 *         HTTP 500 Internal Server Error: bad app configuration or Redis is down.
 */
class FriendListResource extends app\rest\Rest {

    public function get($request) {
        $userService = new app\services\UserService();
        try {
            return new app\rest\JSONResponse(
                200, 
                $userService->findFriendList($request->cookie('app'))
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
