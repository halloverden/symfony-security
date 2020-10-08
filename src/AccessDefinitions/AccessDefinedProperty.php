<?php


namespace HalloVerden\Security\AccessDefinitions;

/**
 * Class AccessDefinedProperty
 *
 * @package HalloVerden\Security\AccessDefinitions
 */
class AccessDefinedProperty {

  /**
   * @var string
   */
  private $class;

  /**
   * @var array
   */
  private $data;

  /**
   * @var bool
   */
  private $read;

  /**
   * @var bool
   */
  private $write;

  /**
   * AccessDefinitionValueType constructor.
   *
   * @param string $class
   * @param array  $data
   * @param bool   $read
   * @param bool   $write
   */
  public function __construct(string $class, array $data, bool $read = false, bool $write = true) {
    $this->class = $class;
    $this->data = $data;
    $this->read = $read;
    $this->write = $write;
  }

  /**
   * @return string
   */
  public function getClass(): string {
    return $this->class;
  }

  /**
   * @return array
   */
  public function getData(): array {
    return $this->data;
  }

  /**
   * @return bool
   */
  public function isRead(): bool {
    return $this->read;
  }

  /**
   * @return bool
   */
  public function isWrite(): bool {
    return $this->write;
  }
}
