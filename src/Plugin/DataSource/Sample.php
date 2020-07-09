<?php

namespace Drupal\autocomplete_endpoint\Plugin\DataSource;

/**
 * Sample data source plugin for the Autocomplete Endpoint module.
 */
class Sample implements AutocompleteEndpointDataSourceInterface {

  /**
   * {@inheritdoc}
   */
  public function getData($endpoint, $query_string) {
    parse_str($query_string, $query_array);
    if (!array_key_exists('q', $query_array)) {
      return ['The Sample data source requires a q= query parameter.'];
    }
    $data = [
      ['label' => 'one', 'uri' => 'http://example.com/one'],
      ['label' => 'two', 'uri' => 'http://example.com/two'],
      ['label' => 'three', 'uri' => 'http://example.com/three'],
      ['label' => 'four', 'uri' => 'http://example.com/four'],
      ['label' => 'five', 'uri' => 'http://example.com/five'],
      ['label' => 'fifteen', 'uri' => 'http://example.com/fifteen'],
    ];

    // Even though this sample plugin just iterates over the members
    // of the above array, a plugin could do a db query, or load a set
    // of entities from storage.
    foreach ($data as $datum) {
      if (preg_match('/^' . $query_array['q'] . '/', $datum['label'])) {
        $results[] = $datum;
      }
    }

    // Results is an array of label => uri pairs matching the user's input
    // in this case, that input is in the 'q' URL parameter.
    return $results;
  }

}
