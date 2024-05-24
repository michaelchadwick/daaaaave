<?php

namespace Src\Controller;

class BaseController
{
  private $config;

  /**
   * __call magic method.
   */
  public function __call($name, $arguments)
  {
    $this->sendJSONOutput('', array('HTTP/1.1 404 Not Found'));
  }

  /**
   * Get URI elements.
   *
   * @return array
   */
  protected function getUriSegments()
  {
    if (isset($_SERVER['REQUEST_URI'])) {
      $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      $uri = explode('/', $uri);

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
  protected function getQueryStringParams()
  {
    if (isset($_SERVER['QUERY_STRING'])) {
      parse_str($_SERVER['QUERY_STRING'], $query);
      return $query;
    } else {
      return [];
    }
  }

  /**
   * Send JSON API output.
   *
   * @param mixed  $json
   * @param string $httpHeader
   */
  protected function sendJSONOutput($json, $httpHeaders = array())
  {
    header_remove('Set-Cookie');

    // send HTTP headers
    if (is_array($httpHeaders) && count($httpHeaders)) {
      foreach ($httpHeaders as $httpHeader) {
        header($httpHeader);
      }
    }

    // send JSON string
    echo $json;
    exit;
  }
}
