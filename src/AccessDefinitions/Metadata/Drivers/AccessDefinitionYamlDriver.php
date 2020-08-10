<?php


namespace HalloVerden\Security\AccessDefinitions\Metadata\Drivers;


use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionClassMetadata;
use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionConfiguration;
use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionPropertyMetadata;
use Metadata\ClassMetadata;
use Metadata\Driver\AbstractFileDriver;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class AccessDefinitionYamlDriver extends AbstractFileDriver {

  /**
   * @inheritDoc
   */
  protected function loadMetadataFromFile(\ReflectionClass $class, string $file): ?ClassMetadata {
    $metadata = new AccessDefinitionClassMetadata($name = $class->name);

    $configs = [Yaml::parseFile($file)];

    $processor = new Processor();

    $config = $processor->processConfiguration(new AccessDefinitionConfiguration(), $configs);


    if (!isset($config[$name])) {
      return null;
    }

    $data = $config[$name];

    $metadata->setClassMetadataFromConfigData($data);

    foreach ($data['properties'] as $propertyName => $propertyData) {
      if (!$class->hasProperty($propertyName)) {
        throw new InvalidConfigurationException(\sprintf('Class %s does not have property %s. Check access definition config %s', $name, $propertyName, $file));
      }

      $propertyMetadata = new AccessDefinitionPropertyMetadata($name, $propertyName);
      $propertyMetadata->setPropertyMetadataFromConfigData($propertyData);

      $metadata->addPropertyMetadata($propertyMetadata);
    }
    
    return $metadata;
  }

  /**
   * @inheritDoc
   */
  protected function getExtension(): string {
    return 'yaml';
  }

}