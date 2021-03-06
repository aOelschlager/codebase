<?php

namespace Drupal\islandora\Controller;

use Drupal\islandora\IslandoraUtils;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\RouteMatch;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

/**
 * Page to select new media type to add.
 */
class ManageMediaController extends ManageMembersController {

  /**
   * Renders a list of media types to add.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node you want to add a media to.
   *
   * @return array
   *   Array of media types to add.
   */
  public function addToNodePage(NodeInterface $node) {
    $field = IslandoraUtils::MEDIA_OF_FIELD;

    return $this->generateTypeList(
      'media',
      'media_type',
      'entity.media.add_form',
      'entity.media_type.add_form',
      $field,
      ['query' => ["edit[$field][widget][0][target_id]" => $node->id()]]
    );
  }

  /**
   * Check if the object being displayed "is Islandora".
   *
   * @param \Drupal\Core\Routing\RouteMatch $route_match
   *   The current routing match.
   *
   * @return \Drupal\Core\Access\AccessResultAllowed|\Drupal\Core\Access\AccessResultForbidden
   *   Whether we can or can't show the "thing".
   */
  public function access(RouteMatch $route_match) {
    if ($route_match->getParameters()->has('node')) {
      $node = $route_match->getParameter('node');
      if (!$node instanceof NodeInterface) {
        $node = Node::load($node);
      }
      if ($this->utils->isIslandoraType($node->getEntityTypeId(), $node->bundle())) {
        return AccessResult::allowed();
      }
    }
    return AccessResult::forbidden();
  }

}
