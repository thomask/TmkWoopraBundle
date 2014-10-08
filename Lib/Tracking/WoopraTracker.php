<?php

namespace Tmk\WoopraBundle\Lib\Tracking;

abstract class WoopraTracker
{
  protected $host;
  protected $name;
  protected $email;
  protected $cookie;
  protected $timeout;
  protected $responseFormat = 'json';
  protected $urlExtraComponents = array();

  public static $apiUrl = "https://www.woopra.com/track/ce/?";

  public function __construct ($host)
  {
    $this->host = $host;
  }

  public function setEmail($email)
  {
    $this->email = $email;
    $this->setCookie();
  }

  private function setCookie()
  {
    $this->cookie = md5($this->email);
  }

  public function setTimeout($timeout)
  {
    $this->timeout = $timeout;
  }

  public function setHost($host)
  {
    $this->host = $host;
  }

  public function createUrl()
  {
    if (!$this->email) {
      throw new \Exception('Email should be set');
    }

    $url = self::$apiUrl;
    $url .= "host={$this->host}";
    $url .= "&response={$this->responseFormat}";
    $url .= "&cookie={$this->cookie}";
    $url .= ($this->timeout) ? "&timeout={$this->timeout}" : null;
    $url .= "&cv_email={$this->email}";

    foreach ($this->urlExtraComponents as $component) {
      $url .= $component;
    }

    return $url;
  }

  public function addUrlComponent($component)
  {
    array_push($this->urlExtraComponents, $component);
  }

  public function track()
  {
    $url = $this->createUrl();
    $response = file_get_contents($url);

    return $response;
  }
}
