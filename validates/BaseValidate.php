<?php

class BaseValidate
{
  /**
   * DbManagerオブジェクト
   * @var DbManager
   */
  protected $dbManager;

  public function __construct(DbManager $dbManager)
  {
    $this->dbManager = $dbManager;
  }
}