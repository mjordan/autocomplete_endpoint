<?php

namespace Drupal\autocomplete_endpoint\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the robot entity.
 *
 * The lines below, starting with '@ConfigEntityType,' are a plugin annotation.
 * These define the entity type to the entity type manager.
 *
 * The properties in the annotation are as follows:
 *  - id: The machine name of the entity type.
 *  - label: The human-readable label of the entity type. We pass this through
 *    the "@Translation" wrapper so that the multilingual system may
 *    translate it in the user interface.
 *  - handlers: An array of entity handler classes, keyed by handler type.
 *    - access: The class that is used for access checks.
 *    - list_builder: The class that provides listings of the entity.
 *    - form: An array of entity form classes keyed by their operation.
 *  - entity_keys: Specifies the class properties in which unique keys are
 *    stored for this entity type. Unique keys are properties which you know
 *    will be unique, and which the entity manager can use as unique in database
 *    queries.
 *  - links: entity URL definitions. These are mostly used for Field UI.
 *    Arbitrary keys can set here. For example, User sets cancel-form, while
 *    Node uses delete-form.
 *
 * @see http://previousnext.com.au/blog/understanding-drupal-8s-config-entities
 * @see annotation
 * @see Drupal\Core\Annotation\Translation
 *
 * @ingroup autocomplete_endpoint
 *
 * @ConfigEntityType(
 *   id = "autocomplete_endpoint",
 *   label = @Translation("Autocomplete Endpoint"),
 *   admin_permission = "administer site configurataion",
 *   handlers = {
 *     "access" = "Drupal\autocomplete_endpoint\AutocompleteEndpointAccessController",
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
