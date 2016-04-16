<?php
namespace app\rest;


/**
 * Class to standarize rest request
 *
 * @author Martin Dimondo <martin.dimondo@gmail.com>
 */
class Request {
  public function get($name) {
    return filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING); 
  }

  public function post($name) {
    return filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING); 
  }

  public function cookie($name) {
    return filter_input(INPUT_COOKIE, $name, FILTER_SANITIZE_STRING); 
  }
}
