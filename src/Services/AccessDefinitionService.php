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
   * AccessDefinitionService constructor.
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
   * @inheritDoc
   */
  public function canCreate(string $class): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    return $this->canHandle($class, $metadata->canCreateScopes, $metadata->canCreateRoles, $metadata->canCreateMethod);
  }

  /**
   * @inheritDoc
   */
  public function canRead(string $class): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    return $this->canHandle($class, $metadata->canReadScopes, $metadata->canReadRoles, $metadata->canReadMethod);
  }

  /**
   * @inheritDoc
   */
  public function canUpdate(string $class): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    return $this->canHandle($class, $metadata->canUpdateScopes, $metadata->canUpdateRoles, $metadata->canUpdateMethod);
  }

  /**
   * @inheritDoc
   */
  public function canDelete(string $class): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    return $this->canHandle($class, $metadata->canDeleteScopes, $metadata->canDeleteRoles, $metadata->canDeleteMethod);
  }

  /**
   * @inheritDoc
   */
  public function canReadProperty(string $class, string $property): bool {
    if (null === $propertyMetadata = $this->getPropertyMetadata($class, $property)) {
      return $this->allowNoMetadata;
    }

    return $this->canHandle($class, $propertyMetadata->readScopes, $propertyMetadata->readRoles, $propertyMetadata->readMethod);
  }

  /**
   * @inheritDoc
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

    return $this->allowNoMetadata && $scopes === null && $roles === null && $method === null;
  }

}
