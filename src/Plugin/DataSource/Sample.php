<?php

namespace Drupal\autocomplete_endpoint\Plugin\DataSource;

/**
 * Sample data source plugin for the Autocomplete Endpoint module.
 */
class Sample implements AutocompleteEndpointDataSourceInterface {

  /**
   * {@inheritdoc}
   */
  public function getData($query) {
    $data = [
      ['label' => 'one', 'uri' => 'http://example.com/one'],
      ['label' => 'two', 'uri' => 'http://example.com/two'],
      ['label' => 'three', 'uri' => 'http://example.com/three'],
      ['label' => 'four', 'uri' => 'http://example.com/four'],
      ['label' => 'five', 'uri' => 'http://example.com/five'],
      ['label' => 'fifteen', 'uri' => 'http://example.com/fifteen'],
    ];

    foreach ($data as $datum) {
      if (preg_match('/^' . $query . '/', $datum['label'])) {
        $results[] = $datum;
      }
    }    

    return $results;
  }

}
