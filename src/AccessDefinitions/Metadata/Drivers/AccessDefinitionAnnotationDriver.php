<?php


namespace HalloVerden\Security\AccessDefinitions\Metadata\Drivers;


use HalloVerden\Security\AccessDefinitions\Annotations\Read;
use HalloVerden\Security\AccessDefinitions\Annotations\ReadMethod;
use HalloVerden\Security\AccessDefinitions\Annotations\Write;
use HalloVerden\Security\AccessDefinitions\Annotations\WriteMethod;
use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionClassMetadata;
use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionPropertyMetadata;
use Doctrine\Common\Annotations\Reader;
use HalloVerden\Security\Exceptions\NoSuchMethodException;
use Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;

class AccessDefinitionAnnotationDriver implements DriverInterface {

  /**
   * @var Reader
   */
  private $reader;

  /**
   * AccessDefinitionAnnotationDriver constructor.
   *
   * @param Reader $reader
   */
  public function __construct(Reader $reader) {
    $this->reader = $reader;
  }

  /**
   * @param \ReflectionClass $class
   *
   * @return ClassMetadata|null
   * @throws NoSuchMethodException
   */
  public function loadMetadataForClass(\ReflectionClass $class): ?ClassMetadata {
    $classMetadata = new AccessDefinitionClassMetadata($name = $class->name);

    if (false !== $fileResource = $class->getFileName()) {
      $classMetadata->fileResources[] = $fileResource;
    }

    $propertiesMetadata = [];
    $propertiesAnnotations = [];

    foreach ($class->getProperties() as $property) {
      if ($property->class !== $name || (isset($property->info) && $property->info['class'] !== $name)) {
        continue;
      }
      $propertiesMetadata[] = new AccessDefinitionPropertyMetadata($name, $property->getName());
      $propertiesAnnotations[] = $this->reader->getPropertyAnnotations($property);
    }

    /** @var AccessDefinitionPropertyMetadata $propertyMetadata */
    foreach ($propertiesMetadata as $propertyKey => $propertyMetadata) {
      $propertyAnnotations = $propertiesAnnotations[$propertyKey];

      $managed = false;

      foreach ($propertyAnnotations as $annotation) {
        switch (true) {
          case $annotation instanceof Read:
            $managed = true;
            $propertyMetadata->readRoles = $annotation->roles;
            break;
          case $annotation instanceof Write:
            $managed = true;
            $propertyMetadata->writeRoles = $annotation->roles;
            break;
          case $annotation instanceof ReadMethod:
            $managed = true;
            if (!$class->hasMethod($annotation->method)) {
              throw new NoSuchMethodException($annotation->method);
            }
            $propertyMetadata->readMethod = $annotation->method;
            break;
          case  $annotation instanceof WriteMethod:
            $managed = true;
            if (!$class->hasMethod($annotation->method)) {
              throw new NoSuchMethodException($annotation->method);
            }
            $propertyMetadata->writeMethod = $annotation->method;
            break;
        }
      }

      $propertyMetadata->managed = $managed;

      $classMetadata->addPropertyMetadata($propertyMetadata);
    }



    return $classMetadata;
  }
}
