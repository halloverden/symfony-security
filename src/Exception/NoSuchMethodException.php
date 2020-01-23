<?php


namespace HalloVerden\Security\Exceptions;


class NoSuchMethodException extends \Exception {
  public function __construct(string $methodName) {
    $message = "NO_SUCH_METHOD: " . $methodName;
    parent::__construct($message, 0, null);
  }
}
