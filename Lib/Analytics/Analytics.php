<?php

namespace Tmk\WoopraBundle\Lib\Analytics;

class Analytics
{

  protected $host;
  protected $accessId;
  protected $accessSecret;

  protected $dateFormat = 'yyyy-MM-dd';
  protected $phpDateFormat = 'Y-m-d';
  protected $startDay;
  protected $endDay;
  protected $limit = 100;
  protected $offset = 0;

  protected $columns = array();
  protected $filters = array();
  protected $groupBy = array();

  protected $constraintOperator = "AND";

  private $apiVersion = "2.0";

  public static $apiUrl = "https://www.woopra.com/rest/report";


  function __construct ($host, $accessId, $accessSecret)
  {
    $this->host = $host;
    $this->accessId = $accessId;
    $this->accessSecret = $accessSecret;

    $this->startDate = new \DateTime('2013-01-01');
    $this->endDate = new \DateTime();

  }

  public function setHost($host) {

    $this->host = $host;

  }

  public function setDateRange(\DateTime $from, \DateTime $to = null) {
    $this->startDate = $from;
    if ($to) {
      $this->endDate = $to;
    }
  }



  public function addColumn($scope = 'visitors', $method = 'count') {

    $column = new \stdClass;

    $name = ucfirst($scope);

    $column->scope = $scope;
    $column->hide = false;
    $column->_format = "#,##0";
    $column->name = $name;
    $column->method = $method;
    $column->render = "number_format(cell('{$name}'),'#,##0')";

    $this->columns[] = $column;

  }

  public function addFilter($value, $scope, $key, $match = "match") {

    $filter = new \stdClass;

    $filter->scope = $scope;
    $filter->value = $value;
    $filter->match = $match;
    $filter->key = $key;
    // $filter->_uikey = 'pv:url';

    $this->filters[] = $filter;

  }

  public function addGroupBy($scope = "visits", $key = "day") {

    $groupBy = new \stdClass;

    $groupBy->scope = $scope;
    $groupBy->key = $key;
    $groupBy->transforms = array();

    $this->groupBy[] = $groupBy;

  }

  public function getConstraints() {
    $constraints = new \stdClass;
    $constraints->filters = $this->filters;

    if(!empty($this->filters)) {
      $constraints->operator = $this->constraintOperator;
    }

    return $constraints;
  }

  public function createRequestObject() {

    $obj = new \stdClass;
    $report = new \stdClass;

    $obj->report = $report;

    $obj->website = $this->host;
    $obj->date_format = $this->dateFormat;
    $obj->start_day = $this->startDate->format($this->phpDateFormat);
    $obj->end_day = $this->endDate->format($this->phpDateFormat);
    $obj->limit = $this->limit;
    $obj->offset = $this->offset;

    /**
     * Add columns. We add at least one (Default = Visitors)
     */
    if (empty($this->columns)) {
      $this->addColumn();
    }

    $obj->report->columns = $this->columns;

    /**
     * Add constraints
     */
    $obj->report->constraints = $this->getConstraints();


    /**
     * Add group by. We add at least one groupBy (Default = Visitors, day)
     */
    if (empty($this->groupBy)) {
      $this->addGroupBy();
    }

    $obj->report->group_by = $this->groupBy;

    return $obj;

  }

  public function fetch() {

    $obj = $this->createRequestObject();
    $requestString = json_encode($obj);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, self::$apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json", "X-Api-Version: {$this->apiVersion}","X-Access-Id: {$this->accessId}", "X-Access-Secret: {$this->accessSecret}"));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "request={$requestString}");

    $response = curl_exec($ch);

    curl_close($ch);

    return json_decode($response);

  }

}
