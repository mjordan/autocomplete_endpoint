<?php

namespace Drupal\autocomplete_endpoint\Plugin\DataSource;

/**
 * Gets data for returning through the autocomplete endpoint.
 */
interface AutocompleteEndpointDataSourceInterface {

  /**
   * Gets the data.
   *
   * @param Drupal\autocomplete_endpoint\Entity\AutocompleteEndpoint $endpoint
   *   The endpoint entity.
   * @param string $query_string
   *   The raw query string from the controller. Plugins can use parse_str()
   *   to ge the parameters.
   *
   * @return array
   *   An array of assocative arrays containing label => uri members.
   *
   *   Errors, like a missing required query parameter, are returned as
   *   a single-member array. See examples in the Node and Vocabulary
   *   plugins.
   */
  public function getData($endpoint, $query_string);

}
