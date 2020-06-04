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
   * Autocomplete request handler.
   *
   * @param string $data_source_plugin_id
   *   String identifying the data source plugin.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request object containing the query string.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response containing the autocomplete suggestions.
   */
  public function main($data_source_plugin_id, Request $request) {
    $query = $request->query->get('q');

    $results = [];
    if (!$query) {
      return new JsonResponse($results);
    }

    $data_source_service_id = 'autocomplete_endpoint.datasource.' . $data_source_plugin_id;
    if (!empty(\Drupal::hasService($data_source_service_id))) {
      $data_source = \Drupal::service($data_source_service_id);
    }
    else {
      $message = t('The requested data source, @ds, is not available.', ['@ds' => $data_source_plugin_id]);
      \Drupal::logger('autocomplete_endpoint')->error($message);
      return new JsonResponse([$message]);
    }

    $results = $data_source->getData($query);
    return new JsonResponse($results);
  }
}
