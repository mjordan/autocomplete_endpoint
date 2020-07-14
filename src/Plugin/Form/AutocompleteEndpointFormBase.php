<?php

namespace Drupal\autocomplete_endpoint\Plugin\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AutocompleteEndpointFormBase.
 *
 * @ingroup autocomplete_endpoint
 */
class AutocompleteEndpointFormBase extends EntityForm {

  /**
   * An entity query factory for the endpoint entity type.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityStorage;

  /**
   * Construct the AutocompleteEndpointFormBase.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   An entity query factory for the autocomplet endpoint entity type.
   */
  public function __construct(EntityStorageInterface $entity_storage) {
    $this->entityStorage = $entity_storage;
  }

  /**
   * Factory method for AutocompleteEndpointFormBase..
   */
  public static function create(ContainerInterface $container) {
    $form = new static($container->get('entity_type.manager')->getStorage('autocomplete_endpoint'));
    $form->setMessenger($container->get('messenger'));
    return $form;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::form().
   *
   * Builds the entity add/edit form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   *
   * @return array
   *   An associative array containing the add/edit form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $autocomplete_endpoint = $this->entity;
    devel_debug($autocomplete_endpoint);

    // Build the form.
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $autocomplete_endpoint->label(),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#title' => $this->t('Machine name'),
      '#default_value' => $autocomplete_endpoint->id(),
      '#machine_name' => [
        'exists' => [$this, 'exists'],
        'replace_pattern' => '([^a-z0-9_]+)|(^custom$)',
        'error' => 'The machine-readable name must be unique, and can only contain lowercase letters, numbers, and underscores. Additionally, it cannot be the reserved word "custom".',
      ],
      '#disabled' => !$autocomplete_endpoint->isNew(),
    ];
    $form['type'] = [
      '#type' => 'select',
      '#options' => ['vocabulary' => $this->t('Vocabulary'), 'node' => $this->t('Node')],
      '#title' => $this->t('Type'),
      '#default_value' => $autocomplete_endpoint->type,
      '#attributes' => [
        'name' => 'type',
      ],      
    ];
    $form['vid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Vocabulary ID'),
      '#maxlength' => 255,
      '#default_value' => $autocomplete_endpoint->vid,
      '#description' => $this->t('Machine name of the vocabulary you are exposing.'),
      '#states' => [
        'visible' => [
          ':input[name="type"]' => ['value' => 'vocabulary'],
        ],
      ],
    ];
    $form['content_type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node content type'),
      '#maxlength' => 255,
      '#default_value' => $autocomplete_endpoint->content_type,
      '#description' => $this->t('Machine name of the node content type you are exposing.'),
      '#states' => [
        'visible' => [
          ':input[name="type"]' => ['value' => 'node'],
        ],
      ],
    ];
    $form['uri_field'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URI field'),
      '#description' => $this->t('Machine name of the field on the vocabulary or content type that contains the URI.'),
      '#maxlength' => 255,
      '#default_value' => $autocomplete_endpoint->uri_field,
    ];
    $form['provide_default_uri'] = [
      '#type' => 'checkbox',
      '#default_value' => $autocomplete_endpoint->provide_default_uri,
      '#title' => $this->t('Provide a default URI.'),
      '#attributes' => [
        'name' => 'provide_default_uri',
      ],
    ];
    $form['default_uri_prefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default URI prefix'),
      '#description' => $this->t('Prefix to use in default URIs. Should begin with "http://" and end with "/".'),
      '#maxlength' => 255,
      '#default_value' => $autocomplete_endpoint->default_uri_prefix,
      '#states' => [
        'visible' => [
          ':input[name="provide_default_uri"]' => ['checked' => TRUE],
        ],
      ],
    ];

    return $form;
  }

  /**
   * Checks for an existing endpoint.
   *
   * @param string|int $entity_id
   *   The entity ID.
   * @param array $element
   *   The form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return bool
   *   TRUE if this ID already exists, FALSE otherwise.
   */
  public function exists($entity_id, array $element, FormStateInterface $form_state) {
    $query = $this->entityStorage->getQuery();

    // Query the entity ID to see if its in use.
    $result = $query->condition('id', $element['#field_prefix'] . $entity_id)
      ->execute();

    // We don't need to return the ID, only if it exists or not.
    return (bool) $result;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::actions().
   *
   * To set the submit button text, we need to override actions().
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   *
   * @return array
   *   An array of supported actions for the current entity form.
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    // Get the basic actins from the base class.
    $actions = parent::actions($form, $form_state);

    // Change the submit button text.
    $actions['submit']['#value'] = $this->t('Save');

    // Return the result.
    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    if ($form_state->getValue('type') == 'vocabulary') {
      if (empty($form_state->getValue('vid'))) {
        $form_state->setErrorByName('vid', t('Vocabulary endpoints require a vocabulary ID.'));
      }
    }
    if ($form_state->getValue('type') == 'node') {
      if (empty($form_state->getValue('content_type'))) {
        $form_state->setErrorByName('content_type', t('Node endpoints require a content type.'));
      }
    }
    if ($form_state->getValue('provide_default_uri') === 1) {
      if (empty($form_state->getValue('default_uri_prefix'))) {
        $form_state->setErrorByName('default_uri_prefix', t('You must provide a default URI prefix.'));
      }
    }
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   */
  public function save(array $form, FormStateInterface $form_state) {
    $autocomplete_endpoint = $this->getEntity();

    $status = $autocomplete_endpoint->save();

    $url = $autocomplete_endpoint->toUrl();
    $edit_link = Link::fromTextAndUrl($this->t('Edit'), $url)->toString();

    if ($status == SAVED_UPDATED) {
      // If we edited an existing entity...
      $this->messenger()->addMessage($this->t('Autocomplete endpoint %label has been updated.', ['%label' => $autocomplete_endpoint->label()]));
      $this->logger('autocomplete_endpoint')->notice('Autocomplete endpoint %label has been updated.', ['%label' => $autocomplete_endpoint->label(), 'link' => $edit_link]);
    }
    else {
      // If we created a new entity...
      $this->messenger()->addMessage($this->t('Autocomplete endpoint %label has been added.', ['%label' => $autocomplete_endpoint->label()]));
      $this->logger('autocomplete_endpoint')->notice('Autocomplete endpoint %label has been added.', ['%label' => $autocomplete_endpoint->label(), 'link' => $edit_link]);
    }

    // Redirect the user back to the listing route after the save operation.
    $form_state->setRedirect('entity.autocomplete_endpoint.list');
  }

}
