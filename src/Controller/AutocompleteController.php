<?php

namespace Drupal\autocomplete_endpoint\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller.
 */
class AutocompleteController extends ControllerBase {

  /**
   * Handler for autocomplete request.
   *
   * @param string $data_source_plugin_id
   *   String identifying the data source plugin.
   *
   * @return $response
   */
  public function main($data_source_plugin_id, Request $request) {
    $query = $request->query->get('q');
    $current_path = \Drupal::service('path.current')->getPath();
    $path_parts = explode('/', ltrim($current_path, '/'));
    // devel_debug($path_parts);

    $results = [];
    if (!$query) {
      return new JsonResponse($results);
    }

    $data_source = \Drupal::service('autocomplete_endpoint.datasource.sample');
    $results = $data_source->getData($query);

    return new JsonResponse($results);
  }
}
