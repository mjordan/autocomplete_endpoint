<?php

namespace Drupal\autocomplete_endpoint\Plugin\DataSource;

/**
 * Data source plugin for the Autocomplete Endpoint module that returns all terms in a vocabulary.
 */
class Vocabulary implements AutocompleteEndpointDataSourceInterface {

  /**
   * {@inheritdoc}
   */
  public function getData($query_string) {
    parse_str($query_string, $query_array);
    if (!array_key_exists('vid', $query_array)) {
      return ['The Vocabulary data source requires a vid= query parameter.']; 
    }
    if (!array_key_exists('q', $query_array)) {
      return ['The Vocabulary data source requires a q= query parameter.']; 
    }

    // @todo: Get all terms in a vocab, plus their URIs. Only returen terms if they have a URI.
    
    $data = [
      ['label' => 'one', 'uri' => 'http://example.com/one'],
      ['label' => 'two', 'uri' => 'http://example.com/two'],
      ['label' => 'three', 'uri' => 'http://example.com/three'],
      ['label' => 'four', 'uri' => 'http://example.com/four'],
      ['label' => 'five', 'uri' => 'http://example.com/five'],
      ['label' => 'fifteen', 'uri' => 'http://example.com/fifteen'],
    ];

    foreach ($data as $datum) {
      if (preg_match('/^' . $query_array['q'] . '/', $datum['label'])) {
        $results[] = $datum;
      }
    }    

    return $results;
  }

}
