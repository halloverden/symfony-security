<?php


namespace HalloVerden\Security\Voters;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use HalloVerden\Security\Exceptions\InvalidSubjectException;
use HalloVerden\Security\Interfaces\SecurityInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class BaseVoter
 *
 * @package HalloVerden\Security\Voters
 */
abstract class BaseVoter extends Voter {

  /**
   * @var SecurityInterface
   */
  protected $security;

  /**
   * BaseVoter constructor.
   *
   * @param SecurityInterface $security
   */
  public function __construct(SecurityInterface $security) {
    $this->security = $security;
  }

  protected abstract function getSupportedAttributes(): array;
  protected abstract function getSupportedClasses(): array;

  /**
   * Determines if the attribute and subject are supported by this voter.
   *
   * @param string $attribute
   * @param $subjects
   * @return bool True if the attribute and subject are supported, false otherwise
   */
  protected function supports($attribute, $subjects) {
    if (!in_array($attribute, $this->getSupportedAttributes())) {
      return false;
    }

    $supportedClasses = $this->getSupportedClasses();

    if (!is_array($subjects) && !($subjects instanceof Collection)) {
      return false;
    }

    foreach ($subjects as $subject) {
      $isSupported = false;

      foreach ($supportedClasses as $supportedClass) {
        if ($this->isSupported($subject, $supportedClass)) {
          $isSupported = true;
          break;
        }
      }

      if (!$isSupported) {
        return false;
      }
    }

    return true;
  }

  /**
   * @param $subject
   * @param $supportedClass
   *
   * @return bool
   */
  private function isSupported($subject, $supportedClass): bool {
    $type = strtolower($supportedClass);
    $type = 'boolean' == $type ? 'bool' : $supportedClass;
    $isFunction = 'is_'.$type;
    $ctypeFunction = 'ctype_'.$type;

    if (\function_exists($isFunction) && $isFunction($subject)) {
      return true;
    } elseif (\function_exists($ctypeFunction) && $ctypeFunction($subject)) {
      return true;
    } elseif ($subject instanceof $supportedClass) {
      return true;
    }

    return false;
  }

  /**
   * Sorts subjects according to supportedClasses.
   *
   * @param array|Collection $subjects
   * @param array            $supportedClasses
   * @param bool             $strictLength
   * @return array|Collection
   */
  protected function sortSubjects($subjects, array $supportedClasses, bool $strictLength = true) {
    $isCollection = false;
    if ($subjects instanceof Collection) {
      $subjects = $subjects->toArray();
      $isCollection = true;
    }

    uasort($subjects, function ($a, $b) use ($supportedClasses) {
      $supportedClassIndexA = $this->getSupportedClassIndex($a, $supportedClasses);
      $supportedClassIndexB = $this->getSupportedClassIndex($b, $supportedClasses);

      if ($supportedClassIndexA === $supportedClassIndexB) {
        return 0;
      }

      return $supportedClassIndexA < $supportedClassIndexB ? -1 : 1;
    });

    if ($strictLength && count($subjects) !== count($supportedClasses)) {
      throw new InvalidSubjectException();
    }

    return $isCollection ? new ArrayCollection($subjects) : $subjects;
  }

  /**
   * @param $subject
   * @param array $supportedClasses
   * @return int
   */
  private function getSupportedClassIndex($subject, array $supportedClasses): int {
    foreach ($supportedClasses as $i => $supportedClass) {
      if (is_array($supportedClass)) {
        if ($this->isSupportedMulti($subject, $supportedClass)) {
          return $i;
        }

        continue;
      }

      if ($this->isSupported($subject, $supportedClass)) {
        return $i;
      }
    }

    throw new InvalidSubjectException($subject);
  }

  /**
   * @param       $subject
   * @param array $supportedClasses
   *
   * @return bool
   */
  private function isSupportedMulti($subject, array $supportedClasses): bool {
    foreach ($supportedClasses as $supportedClass) {
      if ($this->isSupported($subject, $supportedClass)) {
        return true;
      }
    }

    return false;
  }

}
