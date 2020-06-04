<?php

namespace Drupal\autocomplete_endpoint\Plugin\DataSource;

/**
 * Gets data for returning through the autocomplete endpoint.
 */
interface AutocompleteEndpointDataSourceInterface {

  /**
   * Gets the data.
   *
   * @return array
   *   An array of assocative arrays containing label => uri members.
   */
  public function getData($query);

}
