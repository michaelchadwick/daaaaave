<?php

namespace Src\Controller;

class BaseController {
  private $config;

  /**
   * __call magic method.
   */
  public function __call($name, $arguments) {
    $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
  }

  /**
   * Get URI elements.
   *
   * @return array
   */
  protected function getUriSegments() {
    if (isset($_SERVER['REQUEST_URI'])) {
      $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      $uri = explode( '/', $uri );

      return $uri;
    } else {
      return [];
    }
  }

  /**
   * Get querystring params.
   *
   * @return array
   */
  protected function getQueryStringParams() {
    if (isset($_SERVER['QUERY_STRING'])) {
      parse_str($_SERVER['QUERY_STRING'], $query);
      return $query;
    } else {
      return [];
    }
  }

  /**
   * Send API output.
   *
   * @param mixed  $data
   * @param string $httpHeader
   */
  protected function sendOutput($data, $httpHeaders=array()) {
    header_remove('Set-Cookie');

    if (is_array($httpHeaders) && count($httpHeaders)) {
      foreach ($httpHeaders as $httpHeader) {
        header($httpHeader);
      }
    }

    echo $data;
    exit;
  }
}