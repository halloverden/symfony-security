<?php


namespace HalloVerden\Security\Voters;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Guard\AuthenticatorInterface;

/**
 * Class AuthenticationVoter
 *
 * @package HalloVerden\Security\Voters
 */
class AuthenticationVoter extends Voter {
  const AUTHENTICATOR = 'authenticator';

  /**
   * Determines if the attribute and subject are supported by this voter.
   *
   * @param string $attribute An attribute
   * @param mixed $subjects The subject to secure, e.g. an object the user wants to access or any other PHP type
   *
   * @return bool True if the attribute and subject are supported, false otherwise
   */
  protected function supports($attribute, $subjects) {
    if ($attribute !== self::AUTHENTICATOR) {
      return false;
    }

    if (!is_array($subjects)) {
      return false;
    }

    foreach ($subjects as $subject) {
      if (is_string($subject) && class_exists($subject) && in_array(AuthenticatorInterface::class, class_implements($subject))) {
        return true;
      }
    }

    return false;
  }

  /**
   * Perform a single access check operation on a given attribute, subject and token.
   * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
   *
   * @param string $attribute
   * @param mixed $subjects
   * @param TokenInterface $token
   *
   * @return bool
   */
  protected function voteOnAttribute($attribute, $subjects, TokenInterface $token) {
    if (!$token->hasAttribute(self::AUTHENTICATOR)) {
      return false;
    }

    foreach ($subjects as $subject) {
      if ($token->getAttribute(self::AUTHENTICATOR) === $subject) {
        return true;
      }
    }

    return false;
  }

}
