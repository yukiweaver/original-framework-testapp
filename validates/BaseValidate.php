<?php

class BaseValidate
{
  /**
   * DbManagerオブジェクト
   * @var DbManager
   */
  protected $dbManager;

  public function __construct($dbManager)
  {
    $this->dbManager = $dbManager;
  }
}