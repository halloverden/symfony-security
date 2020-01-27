<?php

namespace HalloVerden\Security;

use HalloVerden\Security\Interfaces\SecurityInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security as BaseSecurity;

class Security implements SecurityInterface {

  /**
   * @var BaseSecurity
   */
  private $security;

  /**
   * @var AccessDecisionManagerInterface
   */
  private $accessDecisionManager;

  /**
   * @var array
   */
  private $adminRoles;

  /**
   * Security constructor.
   *
   * @param BaseSecurity                   $security
   * @param AccessDecisionManagerInterface $accessDecisionManager
   * @param array                          $adminRoles
   */
  public function __construct(BaseSecurity $security, AccessDecisionManagerInterface $accessDecisionManager, array $adminRoles = []) {
    $this->security = $security;
    $this->accessDecisionManager = $accessDecisionManager;
    $this->adminRoles = $adminRoles;
  }

  /**
   * @inheritDoc
   */
  public function isGrantedOnUser(UserInterface $user, $attributes, $subject = null): bool {
    if (!\is_array($attributes)) {
      $attributes = [$attributes];
    }

    $token = new UsernamePasswordToken($user, 'none', 'none', $user->getRoles());
    return $this->accessDecisionManager->decide($token, $attributes, $subject);
  }

  /**
   * @inheritDoc
   */
  public function isGrantedAnyAdmin(?UserInterface $user = null): bool {
    foreach ($this->adminRoles as $adminRole) {
      if ($this->_isGranted($adminRole, $user)) {
        return true;
      }
    }

    return false;
  }

  /**
   * @inheritDoc
   */
  public function isGrantedEitherOf(array $attributes): bool {
    foreach ($attributes as $attribute) {
      if ($this->security->isGranted($attribute)) {
        return true;
      }
    }

    return false;
  }

  /**
   * @inheritDoc
   */
  public function getUser() {
    return $this->security->getUser();
  }

  /**
   * @inheritDoc
   */
  public function isGranted($attributes, $subject = null) {
    return $this->security->isGranted($attributes, $subject);
  }

  /**
   * @inheritDoc
   */
  public function getToken() {
    return $this->security->getToken();
  }

  /**
   * @param                    $attributes
   * @param UserInterface|null $user
   *
   * @return bool
   */
  private function _isGranted($attributes, ?UserInterface $user = null): bool {
    if ($user === null) {
      return $this->security->isGranted($attributes);
    }

    return $this->isGrantedOnUser($user, $attributes);
  }
}
