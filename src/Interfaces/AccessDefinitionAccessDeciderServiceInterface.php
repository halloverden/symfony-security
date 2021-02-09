<?php


namespace HalloVerden\Security\Interfaces;


use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionMetadata;

interface AccessDefinitionAccessDeciderServiceInterface {

  /**
   * @param AccessDefinitionMetadata|null $metadata
   *
   * @return bool
   */
  public function hasAccessDefinedAccess(?AccessDefinitionMetadata $metadata): bool;

}
