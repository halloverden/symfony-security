<?php


namespace App\Security\Voters;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use HalloVerden\Security\Exceptions\InvalidSubjectException;
use HalloVerden\Security\Interfaces\SecurityInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

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
        if ($subject instanceof $supportedClass) {
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
   * Sorts subjects according to supportedClasses.
   *
   * @param array|Collection $subjects
   * @param array            $supportedClasses
   * @param bool             $strictLength
   *
   * @return array|Collection
   * @throws InvalidSubjectException
   */
  protected function sortSubjects($subjects, array $supportedClasses, bool $strictLength = true) {
    $isCollection = false;
    if ($subjects instanceof Collection) {
      $subjects = $subjects->toArray();
      $isCollection = true;
    }

    uasort($subjects, function ($a, $b) use ($supportedClasses) {
      if ($this->getSupportedClassIndex($a, $supportedClasses) === $this->getSupportedClassIndex($b, $supportedClasses)) {
        return 0;
      }
      return $this->getSupportedClassIndex($a, $supportedClasses) < $this->getSupportedClassIndex($b, $supportedClasses) ? -1 : 1;
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
      if ($subject instanceof $supportedClass) {
        return $i;
      }
    }

    throw new InvalidSubjectException($subject);
  }

}
