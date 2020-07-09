<?php

namespace Drupal\autocomplete_endpoint\Plugin\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AutocompleteEndpointDeleteForm.
 *
 * @ingroup autocomplete_endpoint
 */
class AutocompleteEndpointDeleteForm extends EntityConfirmFormBase {

  /**
   * Gathers a confirmation question.
   *
   * @return string
   *   Translated string.
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete autocomplete endpoint %label?', [
      '%label' => $this->entity->label(),
    ]);
  }

  /**
   * Gather the confirmation text.
   *
   * @return string
   *   Translated string.
   */
  public function getConfirmText() {
    return $this->t('Delete autocomplete endpoint');
  }

  /**
   * Gets the cancel URL.
   *
   * @return \Drupal\Core\Url
   *   The URL to go to if the user cancels the deletion.
   */
  public function getCancelUrl() {
    return new Url('entity.autocomplete_endpoint.list');
  }

  /**
   * The submit handler for the confirm form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->entity->delete();

    $this->messenger()->addMessage($this->t('Autocomplete endpoint %label deleted.', [
      '%label' => $this->entity->label(),
    ]));
    $this->logger('autocomplete_endpoint')->notice('Autocomplete endpoint %label has been deleted.', ['%label' => $this->entity->label()]);

    // Redirect the user to the list controller when complete.
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
