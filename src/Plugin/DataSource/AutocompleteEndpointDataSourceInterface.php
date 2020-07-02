<?php

namespace Drupal\autocomplete_endpoint\Plugin\DataSource;

/**
 * Gets data for returning through the autocomplete endpoint.
 */
interface AutocompleteEndpointDataSourceInterface {

  /**
   * Gets the data.
   *
   * @param string $query_string
   *   The raw query string from the controller. Plugins can use parse_str()
   *   to ge the parameters.
   *
   * @return array
   *   An array of assocative arrays containing label => uri members.
   *   Q: what about errors, like a missing required query parameter?
   */
  public function getData($query_string);

}
