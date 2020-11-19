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

  public function __construct(DbManager $dbManager)
  {
    $this->dbManager = $dbManager;
  }
}