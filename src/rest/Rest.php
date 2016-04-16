<?php
namespace app\rest;


/**
 * Base class to create a rest resource 
 *
 * @author Martin Dimondo <martin.dimondo@gmail.com>
 */
abstract class Rest {

    /**
     * Main function to handle a request
     *
     * @return \app\rest\JSONResponse
     */
    public function execute() {
        $method = $_SERVER['REQUEST_METHOD'];

        // it validates request method
        if (in_array($method, ["GET", "POST", "GET", "PUT", "DELETE"])) {
            if ($this->isAllowedOrigin()) {
                $response = $this->$method(new Request());
            } else {
                $response = new JSONResponse(403, [
                    'error' => true, 
                    'message' => 'Not a valid origin.'
                ]);
            }
            echo $response;
            exit();
        }
    }

    protected function isAllowedOrigin() {
        $allowedDomains = explode(',', getenv('ALLOWED_DOMAINS'));
        $allowBlankReferrer = getenv('ALLOW_BLANK_REFERRER') || false;
        $httpOrigin = !empty($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;

        if ($allowBlankReferrer || in_array($httpOrigin, $allowedDomains)) {
            header('Access-Control-Allow-Credentials: true');
            if ($httpOrigin) {
                header("Access-Control-Allow-Origin: $httpOrigin");
            }
            return true;
        }

        return false;
    }
}
