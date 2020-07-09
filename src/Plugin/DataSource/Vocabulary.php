<?php

namespace Drupal\autocomplete_endpoint\Plugin\DataSource;

use Drupal\autocomplete_endpoint\Entity\AutocompleteEndpoint;

/**
 * Plugin that returns labels and URIs for terms in a vocabulary.
 */
class Vocabulary implements AutocompleteEndpointDataSourceInterface {

  /**
   * {@inheritdoc}
   */
  public function getData($endpoint, $query_string) {
    devel_debug($endpoint);
    devel_debug($query_string);
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

    $uri_field_names = explode(',', $query_array['uri_fields']);

    // Get all terms in the specified vocab, plus their URIs. Only return terms
    // if they have a URI.
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree(trim($query_array['vid']));
    $results = [];
    foreach ($terms as $term) {
      if (preg_match('/^' . $query_array['q'] . '/i', $term->name)) {
        $term_entity = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term->tid);
        foreach ($uri_field_names as $uri_field_name) {
          if ($term_entity && $term_entity->hasField($uri_field_name)) {
            $uri = $term_entity->get($uri_field_name)->getValue();
            // Assumes that the URI field has the key 'uri'.
            if (array_key_exists('uri', $uri[0]) && !is_null($uri[0]['uri'])) {
              $results[] = ['label' => $term->name, 'uri' => $uri[0]['uri']];
            }
          }
        }
      }
    }

    return $results;
  }

}
