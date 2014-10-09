<?php

namespace Tmk\WoopraBundle\Lib\Tracking;

class WoopraEvent extends WoopraTracker
{
  protected $eventName;
  protected $eventVars = array();

  public function __construct($host)
  {
    parent::__construct($host);
  }

  public function setName($name)
  {
    $this->eventName = $name;
  }

  public function addEventVar($name, $value)
  {
    $var['name'] = $name;
    $var['value'] = urlencode($value);

    array_push($this->eventVars, $var);
  }

  public function createUrl()
  {
    $this->addUrlComponent("&ce_name={$this->eventName}");

    foreach ($this->eventVars as $var) {
      $nameEncoded = urlencode($var['name']);
      $valueEncoded = urlencode($var['value']);

      $this->addUrlComponent("&ce_{$nameEncoded}={$valueEncoded}");
    }

    $url = parent::createUrl();

    return $url;
  }
}

