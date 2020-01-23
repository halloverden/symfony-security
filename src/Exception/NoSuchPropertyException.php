<?php


namespace HalloVerden\Security\Exceptions;


class NoSuchPropertyException extends \Exception {
  public function __construct(string $propertyName) {
    $message = "NO_SUCH_PROPERTY: " . $propertyName;
    parent::__construct($message, 0, null);
  }
}
