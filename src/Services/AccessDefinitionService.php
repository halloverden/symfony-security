<?php


namespace HalloVerden\Security\Services;


use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionClassMetadata;
use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionPropertyMetadata;
use HalloVerden\Security\Interfaces\AccessDefinitionServiceInterface;
use HalloVerden\Security\Interfaces\SecurityInterface;
use HalloVerden\Security\Voters\OauthAuthorizationVoter;
use Metadata\MetadataFactoryInterface;

/**
 * Class AccessDefinitionService
 *
 * @package HalloVerden\Security\Services
 */
class AccessDefinitionService implements AccessDefinitionServiceInterface {

  /**
   * @var MetadataFactoryInterface
   */
  private $metadataFactory;

  /**
   * @var SecurityInterface
   */
  private $security;

  /**
   * @var bool
   */
  private $allowNoMetadata;

  /**
   * AccessDefinition2Service constructor.
   *
   * @param MetadataFactoryInterface $metadataFactory
   * @param SecurityInterface        $security
   * @param bool                     $allowNoMetadata
   */
  public function __construct(MetadataFactoryInterface $metadataFactory, SecurityInterface $security, bool $allowNoMetadata = true) {
    $this->metadataFactory = $metadataFactory;
    $this->security = $security;
    $this->allowNoMetadata = $allowNoMetadata;
  }

  /**
   * @param string $class
   *
   * @return bool
   */
  public function canCreate(string $class): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    return $this->canHandle($class, $metadata->canCreateScopes, $metadata->canCreateRoles, null);
  }

  /**
   * @param string $class
   *
   * @return bool
   */
  public function canRead(string $class): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    return $this->canHandle($class, $metadata->canReadScopes, $metadata->canReadRoles, null);
  }

  /**
   * @param string $class
   *
   * @return bool
   */
  public function canUpdate(string $class): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    return $this->canHandle($class, $metadata->canUpdateScopes, $metadata->canUpdateRoles, null);
  }

  /**
   * @param string $class
   *
   * @return bool
   */
  public function canDelete(string $class): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    return $this->canHandle($class, $metadata->canDeleteScopes, $metadata->canDeleteRoles, null);
  }

  /**
   * @param string $class
   * @param string $property
   *
   * @return bool
   */
  public function canReadProperty(string $class, string $property): bool {
    if (null === $propertyMetadata = $this->getPropertyMetadata($class, $property)) {
      return $this->allowNoMetadata;
    }

    return $this->canHandle($class, $propertyMetadata->readScopes, $propertyMetadata->readRoles, $propertyMetadata->readMethod);
  }

  /**
   * @param string $class
   * @param string $property
   *
   * @return bool
   */
  public function canWriteProperty(string $class, string $property): bool {
    if (null === $propertyMetadata = $this->getPropertyMetadata($class, $property)) {
      return $this->allowNoMetadata;
    }

    return $this->canHandle($class, $propertyMetadata->writeScopes, $propertyMetadata->writeRoles, $propertyMetadata->writeMethod);
  }

  /**
   * @param string $class
   * @param string $property
   *
   * @return AccessDefinitionPropertyMetadata|null
   */
  private function getPropertyMetadata(string $class, string $property): ?AccessDefinitionPropertyMetadata {
    if (null === $metadata = $this->getMetadata($class)) {
      return null;
    }

    return $metadata->getPropertyMetadata($property);
  }

  /**
   * @param string $class
   *
   * @return AccessDefinitionClassMetadata|null
   */
  private function getMetadata(string $class): ?AccessDefinitionClassMetadata {
    $metadata = $this->metadataFactory->getMetadataForClass($class);

    if (!$metadata instanceof AccessDefinitionClassMetadata) {
      return null;
    }

    return $metadata;
  }

  /**
   * @param string      $class
   * @param array|null  $scopes
   * @param array|null  $roles
   * @param string|null $method
   *
   * @return bool
   */
  private function canHandle(string $class, ?array $scopes, ?array $roles, ?string $method): bool {
    // If a token exists with any of the given scopes, access is granted
    if ($scopes !== null && $this->security->isGranted(OauthAuthorizationVoter::OAUTH_SCOPE, $scopes)) {
      return true;
    }

    // Otherwise, either the correct role needs to be present, or a method needs to grant access
    if ($roles !== null && $this->security->isGrantedEitherOf($roles)) {
      return true;
    }

    if ($method !== null && $method($class, $scopes, $roles, $method)) {
      return true;
    }

    return false;
  }

}
