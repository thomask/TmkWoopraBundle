<?php

namespace Tmk\WoopraBundle\Lib\Tracking;

class WoopraEvent extends WoopraTracker
{

  protected $eventName;
  protected $eventVars = array();

  function __construct ($host)
  {
    parent::__construct($host);
  }

  public function setName($name) {
    $this->eventName = $name;
  }

  public function addEventVar($name, $value) {
    $var['name'] = $name;
    $var['value'] = urlencode($value);

    array_push($this->eventVars, $var);
  }

  public function createUrl() {

    $this->addUrlComponent("&ce_name={$this->eventName}");

    foreach ($this->eventVars as $var) {
      $this->addUrlComponent("&ce_{$var['name']}={$var['value']}");
    }

    $url = parent::createUrl();

    return $url;

  }

}

