<?php

namespace Drupal\autocomplete_endpoint;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines an access controller for the autocomplete_endpoint entity.
 *
 * @ingroup autocomplete_endpoint
 */
class AutocompleteEndpointAccessController extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  public function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    // The $opereration parameter tells you what sort of operation access is
    // being checked for.
    if ($operation == 'view') {
      return AccessResult::allowed();
    }
    // Other than the view operation, we're going to be insanely lax about
    // access. Don't try this at home!
    return parent::checkAccess($entity, $operation, $account);
  }

}
