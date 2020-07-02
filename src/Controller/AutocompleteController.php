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
    $query_string = $request->getQueryString();

    $data_source_service_id = 'autocomplete_endpoint.datasource.' . $data_source_plugin_id;
    if (empty(\Drupal::hasService($data_source_service_id))) {
      $message = t('The requested data source, @ds, is not available.', ['@ds' => $data_source_plugin_id]);
      \Drupal::logger('autocomplete_endpoint')->error($message);
      return new JsonResponse([$message]);
    }

    $data_source = \Drupal::service($data_source_service_id);

    $results = [];
    // Autocomplete queries need some variable input.
    if (!$query_string) {
      return new JsonResponse($results);
    }

    $results = $data_source->getData($query_string);
    return new JsonResponse($results);
  }
}
