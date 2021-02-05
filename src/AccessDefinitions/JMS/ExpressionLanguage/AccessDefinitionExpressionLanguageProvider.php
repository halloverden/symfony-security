<?php


namespace HalloVerden\Security\AccessDefinitions\JMS\ExpressionLanguage;

use HalloVerden\Security\AccessDefinitions\JMS\ExpressionLanguage\ExpressionFunction\HasAccessFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class AccessDefinitionExpressionLanguageProvider
 *
 * @package HalloVerden\Security\AccessDefinitions\JMS\ExpressionLanguage
 */
class AccessDefinitionExpressionLanguageProvider implements ExpressionFunctionProviderInterface {

  /**
   * @var HasAccessFunction
   */
  private $hasAccessFunction;

  /**
   * HasAccessExpressionLanguageProvider constructor.
   *
   * @param HasAccessFunction $hasAccessFunction
   */
  public function __construct(HasAccessFunction $hasAccessFunction) {
    $this->hasAccessFunction = $hasAccessFunction;
  }

  /**
   * @inheritDoc
   */
  public function getFunctions(): array {
    return [
      new ExpressionFunction('hasAccess', function () {}, $this->hasAccessFunction)
    ];
  }

}
