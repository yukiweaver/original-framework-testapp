<?php

/**
 * AccountServiceクラス
 */
class AccountService extends BaseService
{
  /**
   * コンストラクタ
   */
  public function __construct($dbManager)
  {
    parent::__construct($dbManager);
  }

  /**
   * ユーザ登録し、登録したユーザ情報を返す
   */
  public function registUser($userName, $password)
  {
    $this->dbManager->get('User')->insert($userName, $password);
    $user = $this->dbManager->get('User')->fetchByUserName($userName);
    return $user;
  }
}