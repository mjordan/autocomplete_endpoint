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
    // This is a comma-delimited list of field names where URIs are stored
    // for the vocabulary identified in vid=.
    if (!array_key_exists('uri_fields', $query_array)) {
      return ['uri_fields= query parameter is required.']; 
    }
    if (!array_key_exists('q', $query_array)) {
      return ['The Vocabulary data source requires a q= query parameter.']; 
    }

    $uri_fields = explode(',', $query_array['uri_fields']);

    // @todo: Get all terms in a vocab, plus their URIs. Only return terms if they have a URI.
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree(trim($query_array['vid']));
    $results= [];
    foreach ($terms as $term) {
      if (preg_match('/^' . $query_array['q'] . '/i', $term->name)) {
        $term_entity = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term->tid);
        foreach ($uri_fields as $uri_field_name) {
          if ($term_entity && $term_entity->hasField($uri_field_name)) {
            $uri = $term->{$uri_field_name}->value;
	    if (!is_null($uri)) {
              $results[] = ['label' => $term->name, 'uri' => $uri];
            }
          }
        }
      }
    }
    
    return $results;
  }

}
