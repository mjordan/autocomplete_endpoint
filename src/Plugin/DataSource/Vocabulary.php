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
    parse_str($query_string, $query_array);
    if (strlen($endpoint->vid) < 1) {
      return ['No vocabulary ID configured for this endpoint.'];
    }
    if (strlen($endpoint->uri_field) < 0) {
      return ['No URI field configured for this endpoint.'];
    }
    if (!array_key_exists('q', $query_array)) {
      return ['No q= query parameter in request.'];
    }

    $uri_field_names = explode(',', $query_array['uri_fields']);

    // Get all terms in the specified vocab, plus their URIs. Only return terms
    // if they have a URI.
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($endpoint->vid);
    $results = [];
    foreach ($terms as $term) {
      if (preg_match('/^' . $query_array['q'] . '/i', $term->name)) {
        $term_entity = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term->tid);
        if ($term_entity && $term_entity->hasField($endpoint->uri_field)) {
          $uri = $term_entity->get($endpoint->uri_field)->getValue();
          // Assumes that the URI field has the key 'uri'.
          if (array_key_exists('uri', $uri[0]) && !is_null($uri[0]['uri'])) {
            $results[] = ['label' => $term->name, 'uri' => $uri[0]['uri']];
          }
          else if (array_key_exists('value', $uri[0]) && !is_null($uri[0]['value'])) {

            $results[] = ['label' => $term->name, 'uri' => $uri[0]['value']];
          }

        }
      }
    }

    return $results;
  }

}
