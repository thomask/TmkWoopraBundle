<?php

namespace Tmk\WoopraBundle\Lib\Analytics;

class AnalyticsManager
{
  protected $host;
  protected $accessId;
  protected $accessSecret;

  public function __construct ($host, $accessId, $accessSecret)
  {
    $this->host = $host;
    $this->accessId = $accessId;
    $this->accessSecret = $accessSecret;
  }

  private function getBaseObj()
  {
    return new Analytics($this->host, $this->accessId, $this->accessSecret);
  }

  public function getVisitorsForUrl($url)
  {
    $obj = $this->getBaseObj();
    $obj->addFilter($url, 'actions', 'url');

    $result = $obj->fetch();

    return $result->total->cells[0];
  }

  public function getVisitsForUrl($url)
  {
    $obj = $this->getBaseObj();
    $obj->addFilter($url, 'actions', 'url');
    $obj->addColumn('visits');

    $result = $obj->fetch();

    return $result->total->cells[0];
  }
}
