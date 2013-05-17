<?php

namespace Tmk\WoopraBundle\Lib\Tracking;

class WoopraEventEvent extends WoopraTracker
{

  protected $eventName;
  protected $eventVars = array();

  function __construct ($email)
  {
    parent::construct($email);
  }

  public function setEventName($name) {
    $this->eventName = $name;
  }

  public function addEventVar($name, $value) {
    $var['name'] = $name;
    $var['value'] = $value;
    array_push($this->eventVars, $var);
  }

  public function createUrl() {

    $this->addUrlComponent("&ce_name={$this->eventName}");

    foreach ($this->eventVars as $var) {
      $this->addUrlComponent("&ce_{$var['name']}={$var['value']}");
    }

    parent::createUrl();

  }

}

