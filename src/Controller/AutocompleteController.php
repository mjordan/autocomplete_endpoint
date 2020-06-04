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
   * @param string $data_source_plugin
   *   String identifying the data source plugin.
   *
   * @return $response
   */
  public function main($data_source_plugin, Request $request) {
    $query = $request->query->get('q');
    $current_path = \Drupal::service('path.current')->getPath();
    $path_parts = explode('/', ltrim($current_path, '/'));
    devel_debug($path_parts);

    $results = [];
    if (!$query) {
      return new JsonResponse($results);
    }

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

    return new JsonResponse($results);
  }
}
