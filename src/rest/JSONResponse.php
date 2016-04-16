<?php
namespace app\rest;


class JSONResponse {
  protected $wrapped;
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
