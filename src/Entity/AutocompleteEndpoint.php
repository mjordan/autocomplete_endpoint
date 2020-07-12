<?php

namespace Drupal\autocomplete_endpoint\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the autocomplete endpoint entity.
 *
 * @ingroup autocomplete_endpoint
 *
 * @ConfigEntityType(
 *   id = "autocomplete_endpoint",
 *   label = @Translation("Autocomplete Endpoint"),
 *   admin_permission = "administer site configurataion",
 *   handlers = {
 *     "list_builder" = "Drupal\autocomplete_endpoint\Controller\AutocompleteEndpointListBuilder",
 *     "form" = {
 *       "add" = "Drupal\autocomplete_endpoint\Plugin\Form\AutocompleteEndpointAddForm",
 *       "edit" = "Drupal\autocomplete_endpoint\Plugin\Form\AutocompleteEndpointEditForm",
 *       "delete" = "Drupal\autocomplete_endpoint\Plugin\Form\AutocompleteEndpointDeleteForm"
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   links = {
 *     "edit-form" = "/admin/autocomplete_endpoint/manage/{autocomplete_endpoint}",
 *     "delete-form" = "/admin/autocomplete_endpoint/manage/{autocomplete_endpoint}/delete"
 *   },
 *   config_export = {
 *     "id",
 *     "uuid",
 *     "label",
 *     "type",
 *     "vid",
 *     "content_type",
 *     "uri_field"
 *   }
 * )
 */
class AutocompleteEndpoint extends ConfigEntityBase {

  /**
   * The endpoint ID.
   *
   * @var string
   */
  public $id;

  /**
   * The endpoint UUID.
   *
   * @var string
   */
  public $uuid;

  /**
   * The endpoint label.
   *
   * @var string
   */
  public $label;

  /**
   * The endpoint type.
   *
   * @var string
   */
  public $type;

  /**
   * The endpoint vocabulary ID.
   *
   * @var string
   */
  public $vid;

  /**
   * The endpoint content type.
   *
   * @var string
   */
  public $content_type;

  /**
   * The endpoint URI field name.
   *
   * @var string
   */
  public $uri_field;

}
