<?php

/**
 * DBの接続情報を管理するクラス
 */
class DbManager
{
  /**
   * @var array
   * ['接続名' => PDOインスタンス, ...]
   */
  protected $connections = [];

  /**
   * repositoryの情報を格納
   * @var array
   * ['repository名' => Repositoryインスタンス]
   */
  protected $repositories = [];

  protected $repositoryConnectionMap = [];

  /**
   * デストラクタ:データベースの接続を閉じる
   * @return void
   */
  public function __destruct()
  {
    foreach ($this->repositories as $repository) {
      unset($repository);
    }
    foreach ($this->connections as $con) {
      unset($con);
    }
  }

  /**
   * DBに接続
   * @param string $name 接続名
   * @param array $params 接続情報
   * @return void
   */
  public function connect($name, $params)
  {
    $params = array_merge([
      'dsn'       => null,
      'user'      => '',
      'password'  => '',
      'options'   => [],
    ], $params);

    $con = new PDO(
      $params['dsn'],
      $params['user'],
      $params['password'],
      $params['options']
    );

    // PDO内部エラーが発生した場合に例外を投げる
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $this->connections[$name] = $con;
  }

  /**
   * 接続したコネクションを取得する
   * @param string $name 接続名
   * @return \PDO
   */
  public function getConnection($name = null)
  {
    if (is_null($name)) {
      return current($this->connections);
    }
    return $this->connections[$name];
  }

  public function setRepositoryConnectionMap($repositoryName, $name)
  {
    $this->repositoryConnectionMap[$repositoryName] = $name;
  }

  /** Repository 関連 **/

  /**
   * Repositoryクラスに対応する接続コネクションを取得する
   * @param string $repositoryName
   * @return \PDO
   */
  public function getConnectionForRepository($repositoryName)
  {
    if (isset($this->repositoryConnectionMap[$repositoryName])) {
      $name = $this->repositoryConnectionMap[$repositoryName];
      $con = $this->getConnection($name);
    } else {
      $con = $this->getConnection();
    }
    return $con;
  }

  /**
   * Repositoryインスタンスを取得
   * @param string $repositoryName
   * @return Repository
   */
  public function get($repositoryName)
  {
    if (!isset($this->repositories[$repositoryName])) {
      // 命名規則: 名前の後にRepositryをつけたものをクラス名とする
      $repositoryClass = $repositoryName . 'Repository';
      $con = $this->getConnectionForRepository($repositoryName);
      $repository = new $repositoryClass($con);
      $this->repositories[$repositoryName] = $repository;
    }
    return $this->repositories[$repositoryName];
  }
}