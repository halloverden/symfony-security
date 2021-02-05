<?php


namespace HalloVerden\Security\AccessDefinitions\Metadata;


use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class AccessDefinitionConfiguration implements ConfigurationInterface {

  /**
   * @inheritDoc
   */
  public function getConfigTreeBuilder() {
    $treeBuilder = new TreeBuilder('access_definitions');

    $root = $treeBuilder->getRootNode()
      ->useAttributeAsKey('class')
      ->arrayPrototype()
      ->children();

    $this->addSection($root, 'canCreate');
    $this->addSection($root, 'canRead');
    $this->addSection($root, 'canUpdate');
    $this->addSection($root, 'canDelete');

    $propertiesRoot = $root->arrayNode('properties')
      ->useAttributeAsKey('name')
      ->arrayPrototype()->children();

    $this->addSection($propertiesRoot, 'canRead');
    $this->addSection($propertiesRoot, 'canWrite');

    $root->end()->end();

    return $treeBuilder;
  }

  /**
   * @param NodeBuilder $root
   * @param string      $name
   */
  private function addSection(NodeBuilder $root, string $name): void {
    $root = $root->arrayNode($name)
      ->children();

    $ownerSection = $root->arrayNode('owner')->children();
    $this->addScopesRolesMethodSection($ownerSection);

    $everyoneSection = $root->arrayNode('everyone')->children();
    $this->addScopesRolesMethodSection($everyoneSection);

    $this->addScopesRolesMethodSection($root);

    $root->end();
  }

  /**
   * @param NodeBuilder $root
   */
  private function addScopesRolesMethodSection(NodeBuilder $root): void {
    $root->arrayNode('roles')
        ->scalarPrototype()->end()
      ->end()
      ->arrayNode('scopes')
        ->scalarPrototype()->end()
      ->end()
      ->scalarNode('method')->end();
  }

}
