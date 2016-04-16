<?php
namespace app\rest;


/**
 * @author Martin Dimondo <martin.dimondo@gmail.com>
 *
 */
class JSONResponse {
  /*
   * @var mixed 
   */
  protected $wrapped;

  /*
   * @var int
   * STATUS CODE
   */
  protected $code;

  public function __construct($code, $value) {
    $this->code = $code;
    $this->wrapped = $value;    
  }

  public function __toString() {
    http_response_code($this->code);
    header('Content-Type: application/json; charset=utf-8');
    return json_encode($this->wrapped);
  }
}
