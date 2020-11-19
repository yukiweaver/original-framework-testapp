<?php

class MiniBlogApplication extends Application
{
  /**
   * ログイン画面のアクション情報
   * $loginAction[0] = controller名
   * $loginAction[1] = action名
   * @var array
   */
  protected $loginAction = ['account', 'signin'];

  /**
   * ルートディレクトリまでのパスを返す
   * @return string
   */
  public function getRootDir()
  {
    return dirname(__FILE__);
  }

  /**
   * ルーティング定義配列を返す
   * @return array
   */
  public function registerRoutes()
  {
    global $g_routeDefinition;
    return $g_routeDefinition;
  }

  public function configure()
  {
    global $g_dbInfo;
    $this->dbManager->connect('master', [
      'dsn'       => "mysql:host=$g_dbInfo[host];dbname=$g_dbInfo[dbname]",
      'user'      => "$g_dbInfo[user]",
      'password'  => "$g_dbInfo[password]",
    ]);
  }
}