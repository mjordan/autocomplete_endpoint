<?php

namespace Drupal\autocomplete_endpoint\Plugin\DataSource;

/**
 * Data source plugin for the Autocomplete Endpoint module that returns nodes of a specific content type.
 */
class Node implements AutocompleteEndpointDataSourceInterface {

  /**
   * {@inheritdoc}
   */
  public function getData($query_string) {
    parse_str($query_string, $query_array);
    if (!array_key_exists('contenttype', $query_array)) {
      return ['The Node data source requires a contenttype= query parameter.']; 
    }
    // A comma-delimited list of field names where URIs are stored in nodes of
    // the type specified in contenttype=.
    if (!array_key_exists('uri_fields', $query_array)) {
      return ['uri_fields= query parameter is required.']; 
    }
    if (!array_key_exists('q', $query_array)) {
      return ['The Node data source requires a q= query parameter.']; 
    }

    $uri_field_names = explode(',', $query_array['uri_fields']);

    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', $query_array['contenttype']);
    $query->condition('title.value', $query_array['q'], 'STARTS_WITH');
    $entity_ids = $query->execute();
    $nids = array_values($entity_ids);

    $node_storage = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);
    $results = [];
    foreach ($node_storage as $node) {
      foreach ($uri_field_names as $uri_field_name) {
        $label = $node->get('title')->value;
        if (preg_match('/^' . $query_array['q'] . '/i', $label)) {
          if ($node->hasField($uri_field_name)) {
            $uri = $node->get($uri_field_name)->value;
            $results[] = ['label' => $label, 'uri' => $uri];
          }
        }
      }
    }
    return $results;
  }

}
