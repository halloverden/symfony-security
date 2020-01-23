<?php


namespace HalloVerden\Security\Interfaces;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface SecurityInterface {
  /**
   * @param UserInterface $user
   * @param mixed         $attributes
   * @param object|null   $subject
   *
   * @return bool
   */
  public function isGrantedOnUser(UserInterface $user, $attributes, $subject = null): bool;

  /**
   * @param UserInterface|null $user
   *
   * @return bool
   */
  public function isGrantedAnyAdmin(?UserInterface $user = null): bool;

  /**
   * @param array $attributes
   *
   * @return bool
   */
  public function isGrantedEitherOf(array $attributes): bool;

  /**
   * @return UserInterface|null
   */
  public function getUser();

  /**
   * @param mixed $attributes
   * @param mixed $subject
   *
   * @return bool
   */
  public function isGranted($attributes, $subject = null);

  /**
   * @return TokenInterface|null
   */
  public function getToken();
}
