<?php

namespace Drupal\autocomplete_endpoint\Plugin\DataSource;

/**
 * Plugin that returns titles and URIs of nodes of a specific content type.
 */
class Node implements AutocompleteEndpointDataSourceInterface {

  /**
   * {@inheritdoc}
   */
  public function getData($endpoint, $query_string) {
    parse_str($query_string, $query_array);
    if (strlen($endpoint->content_type) < 1) {
      return ['No content type configured for this endpoint.'];
    }
    if (strlen($endpoint->uri_field) < 0) {
      return ['No URI field configured for this endpoint.'];
    }
    if (!array_key_exists('q', $query_array)) {
      return ['No q= query parameter in request.'];
    }

    $uri_field_names = explode(',', $query_array['uri_fields']);

    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', $endpoint->content_type);
    $query->condition('title.value', $query_array['q'], 'STARTS_WITH');
    $entity_ids = $query->execute();
    $nids = array_values($entity_ids);

    $node_storage = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);
    $results = [];
    foreach ($node_storage as $node) {
      $label = $node->get('title')->value;
      if (preg_match('/^' . $query_array['q'] . '/i', $label)) {
        if ($node->hasField($endpoint->uri_field)) {
          $uri = $node->get($endpoint->uri_field)->value;
          $results[] = ['label' => $label, 'uri' => $uri];
        }
      }
    }
    return $results;
  }

}
