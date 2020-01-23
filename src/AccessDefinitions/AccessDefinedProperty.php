<?php


namespace HalloVerden\Security\AccessDefinitions;


class AccessDefinedProperty {
  /**
   * @var string
   */
  private $accessDefinitionClass;

  /**
   * @var array
   */
  private $value;

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
   * @param string $accessDefinitionClass
   * @param array $value
   * @param bool $read
   * @param bool $write
   */
  public function __construct(string $accessDefinitionClass, array $value, bool $read = false, bool $write = true) {
    $this->accessDefinitionClass = $accessDefinitionClass;
    $this->value = $value;
    $this->read = $read;
    $this->write = $write;
  }

  /**
   * @return string
   */
  public function getAccessDefinitionClass(): string {
    return $this->accessDefinitionClass;
  }

  /**
   * @return array
   */
  public function getValue(): array {
    return $this->value;
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
