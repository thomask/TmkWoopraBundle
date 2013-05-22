<?php

namespace Tmk\WoopraBundle\Lib\Analytics;

class AnalyticsManager
{
  protected $host;
  protected $accessId;
  protected $accessSecret;

  function __construct ($host, $accessId, $accessSecret)
  {
    $this->host = $host;
    $this->accessId = $accessId;
    $this->accessSecret = $accessSecret;
  }

  private function getBaseObj() {
    return new Analytics($this->host, $this->accessId, $this->accessSecret);
  }

  public function getVisitorsForUrl($url) {

    $obj = $this->getBaseObj();
    $obj->addFilter($url, 'actions', 'url');

    return $obj->fetch();

  }

}

