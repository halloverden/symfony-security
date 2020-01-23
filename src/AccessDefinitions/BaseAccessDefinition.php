<?php


namespace HalloVerden\Security\AccessDefinitions;

use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionClassMetadata;
use HalloVerden\Security\AccessDefinitions\Metadata\Drivers\AccessDefinitionAnnotationDriver;
use HalloVerden\Security\Exceptions\NoSuchPropertyException;
use HalloVerden\Security\Interfaces\AccessDefinitionInterface;
use HalloVerden\Security\Interfaces\SecurityInterface;
use Metadata\MetadataFactory;

abstract class BaseAccessDefinition implements AccessDefinitionInterface {

  /**
   * @var SecurityInterface
   */
  protected $security;

  /**
   * @var AccessDefinitionClassMetadata
   */
  protected $metadata;

  /**
   * @var bool
   */
  private $dataIsSet = false;

  /**
   * @param string $name
   * @param        $value
   *
   * @throws NoSuchPropertyException
   */
  abstract protected function setProperty(string $name, $value): void;

  /**
   * BaseAccessDefinition constructor.
   *
   * @param SecurityInterface                $security
   * @param AccessDefinitionAnnotationDriver $driver
   */
  public function __construct(AccessDefinitionAnnotationDriver $driver, SecurityInterface $security) {
    $factory = new MetadataFactory($driver);
    $this->metadata = $factory->getMetadataForClass(get_class($this));

    $this->security = $security;
  }

  /**
   * @param array $data
   *
   * @param bool  $force
   *
   * @return AccessDefinitionInterface
   * @throws NoSuchPropertyException
   */
  public function setData(array $data, bool $force = false): AccessDefinitionInterface {
    if ($this->dataIsSet && !$force) {
      return $this;
    }

    foreach ($data as $key => $value) {
      if ($this->metadata->getManagedPropertyMetadata($key)) {
        $this->setProperty($key, $value);
      }
    }

    $this->dataIsSet = true;

    return $this;
  }

  /**
   * @param string $propertyName
   *
   * @return bool
   */
  public final function canReadProperty(string $propertyName): bool {
    if ($propertyMetadata = $this->metadata->getManagedPropertyMetadata($propertyName)) {
      return $this->canHandleProperty($propertyMetadata->readRoles, $propertyMetadata->readMethod);
    }

    return false;
  }

  /**
   * @param string $propertyName
   *
   * @return bool
   */
  public final function canWriteProperty(string $propertyName): bool {
    if ($propertyMetadata = $this->metadata->getManagedPropertyMetadata($propertyName)) {
      return $this->canHandleProperty($propertyMetadata->writeRoles, $propertyMetadata->writeMethod);
    }

    return false;
  }

  /**
   * @param array|null  $roles
   * @param string|null $method
   *
   * @return bool
   */
  private function canHandleProperty(?array $roles, ?string $method): bool {
    if ($roles !== null && !$this->security->isGrantedEitherOf($roles)) {
      return false;
    }

    if ($method && !$this->{$method}()) {
      return false;
    }

    return true;
  }

  /**
   * @param AccessDefinedProperty $accessDefinitionValueType
   *
   * @return array
   * @throws NoSuchPropertyException
   */
  public function filterData(AccessDefinedProperty $accessDefinitionValueType): array {
    $this->setData($accessDefinitionValueType->getValue());

    return array_filter($accessDefinitionValueType->getValue(), function ($key) use ($accessDefinitionValueType) {
      return ($accessDefinitionValueType->isWrite() && $this->canWriteProperty($key))
        || ($accessDefinitionValueType->isRead() && $this->canReadProperty($key));
    }, ARRAY_FILTER_USE_KEY);
  }

}
