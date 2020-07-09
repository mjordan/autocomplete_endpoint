<?php

namespace Drupal\autocomplete_endpoint\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\autocomplete_endpoint\Entity\AutocompleteEndpoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller.
 */
class AutocompleteController extends ControllerBase {

  /**
   * Autocomplete request handler.
   *
   * @param string $endpoint_machine_name
   *   String identifying the endpoint configuration entity.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request object containing the query string.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response containing the autocomplete suggestions.
   */
  public function main($endpoint_machine_name, Request $request) {
    $query_string = $request->getQueryString();

    $endpoint = AutocompleteEndpoint::load($endpoint_machine_name);

    $data_source_service_id = 'autocomplete_endpoint.datasource.' . $endpoint->type;
    if (empty(\Drupal::hasService($data_source_service_id))) {
      $message = t('The requested data source, @ds, is not available.', ['@ds' => $endpoint_machine_name]);
      \Drupal::logger('autocomplete_endpoint')->error($message);
      return new JsonResponse([$message]);
    }

    $data_source = \Drupal::service($data_source_service_id);

    $results = [];
    // Autocomplete queries need some variable input.
    if (!$query_string) {
      return new JsonResponse($results);
    }

    $results = $data_source->getData($endpoint, $query_string);
    return new JsonResponse($results);
  }

}
