<?php

/**
 * ベースとなるサービスクラス
 */
class BaseService
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