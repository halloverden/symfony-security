<?php


namespace HalloVerden\Security\Helpers;


use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestBearerTokenHelper
 *
 * @package HalloVerden\Security\Entity\Helpers
 */
class RequestBearerTokenHelper {
  const HEADER_AUTHORIZATION = 'Authorization';
  const AUTHORIZATION_TYPE_BEARER = 'Bearer';

  /**
   * Get bearer token from a Request
   *
   * @param Request $request
   * @param bool    $checkParameters
   *
   * @return string|null
   */
  public static function getBearerToken(Request $request, bool $checkParameters = true): ?string {
    if ($request->headers->has(self::HEADER_AUTHORIZATION)) {
      // index 0 = type, index 1 = token
      $typeAndToken = explode(' ', $request->headers->get(self::HEADER_AUTHORIZATION));

      if (count($typeAndToken) !== 2) {
        return null;
      }

      if ($typeAndToken[0] === self::AUTHORIZATION_TYPE_BEARER) {
        return $typeAndToken[1];
      }
    }

    if ($checkParameters && ($token = $request->get('token'))) {
      return $token;
    }

    return null;
  }

}
