<?php


namespace HalloVerden\Security\Traits;

use HalloVerden\Security\Exceptions\NoSuchPropertyException;

trait BaseAccessDefinitionTrait {

  /**
   * @param string $name
   * @param mixed  $value
   *
   * @throws NoSuchPropertyException
   */
  protected function setProperty(string $name, $value): void {
    if (!property_exists($this, $name)) {
      throw new NoSuchPropertyException($name);
    }

    $this->{$name} = $value;
  }
}
