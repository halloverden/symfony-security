<?php


namespace HalloVerden\Security\Services;


use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionClassMetadata;
use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionPropertyMetadata;
use HalloVerden\Security\Interfaces\AccessDefinitionAccessDeciderServiceInterface;
use HalloVerden\Security\Interfaces\AccessDefinitionServiceInterface;
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
   * @var bool
   */
  private $allowNoMetadata;

  /**
   * @var AccessDefinitionAccessDeciderServiceInterface
   */
  private $accessDeciderService;

  /**
   * AccessDefinitionService constructor.
   *
   * @param MetadataFactoryInterface                      $metadataFactory
   * @param AccessDefinitionAccessDeciderServiceInterface $accessDeciderService
   * @param bool                                          $allowNoMetadata
   */
  public function __construct(MetadataFactoryInterface $metadataFactory, AccessDefinitionAccessDeciderServiceInterface $accessDeciderService, bool $allowNoMetadata = true) {
    $this->metadataFactory = $metadataFactory;
    $this->allowNoMetadata = $allowNoMetadata;
    $this->accessDeciderService = $accessDeciderService;
  }

  /**
   * @inheritDoc
   */
  public function canCreate(string $class, bool $isOwner = false): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    if ($isOwner && $this->accessDeciderService->canHandle($metadata->canCreateOwner)) {
      return true;
    }

    return $this->accessDeciderService->canHandle($metadata->canCreateEveryone);
  }

  /**
   * @inheritDoc
   */
  public function canRead(string $class, bool $isOwner = false): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    if ($isOwner && $this->accessDeciderService->canHandle($metadata->canReadOwner)) {
      return true;
    }

    return $this->accessDeciderService->canHandle($metadata->canReadEveryone);
  }

  /**
   * @inheritDoc
   */
  public function canUpdate(string $class, bool $isOwner = false): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    if ($isOwner && $this->accessDeciderService->canHandle($metadata->canUpdateOwner)) {
      return true;
    }

    return $this->accessDeciderService->canHandle($metadata->canUpdateEveryone);
  }

  /**
   * @inheritDoc
   */
  public function canDelete(string $class, bool $isOwner = false): bool {
    if (null === $metadata = $this->getMetadata($class)) {
      return $this->allowNoMetadata;
    }

    if ($isOwner && $this->accessDeciderService->canHandle($metadata->canDeleteOwner)) {
      return true;
    }

    return $this->accessDeciderService->canHandle($metadata->canDeleteEveryone);
  }

  /**
   * @inheritDoc
   */
  public function canReadProperty(string $class, string $property, bool $isOwner = false): bool {
    if (null === $propertyMetadata = $this->getPropertyMetadata($class, $property)) {
      return $this->allowNoMetadata;
    }

    if ($isOwner && $this->accessDeciderService->canHandle($propertyMetadata->canReadOwner)) {
      return true;
    }

    return $this->accessDeciderService->canHandle($propertyMetadata->canReadEveryone);
  }

  /**
   * @inheritDoc
   */
  public function canWriteProperty(string $class, string $property, bool $isOwner = false): bool {
    if (null === $propertyMetadata = $this->getPropertyMetadata($class, $property)) {
      return $this->allowNoMetadata;
    }

    if ($isOwner && $this->accessDeciderService->canHandle($propertyMetadata->canWriteOwner)) {
      return true;
    }

    return $this->accessDeciderService->canHandle($propertyMetadata->canWriteEveryone);
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

}
