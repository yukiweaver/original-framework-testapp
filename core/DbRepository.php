<?php

/**
 * データベースへのアクセスを行うクラス
 */
abstract class DbRepository
{
  /**
   * @var \PDO
   */
  protected $con;

  public function __construct($con)
  {
    $this->setConnection($con);
  }

  /**
   * PDOインスタンスをセット
   * @param \PDO $con
   * @return void
   */
  public function setConnection($con)
  {
    $this->con = $con;
  }

  /**
   * SQLを実行する
   * @param string $sql
   * @param array $params
   * @return PDOStatement
   */
  public function execute($sql, $params = [])
  {
    $stmt = $this->con->prepare($sql); // SQLを実行する準備
    $stmt->execute($params); // SQL実行

    return $stmt;
  }

  /**
   * DBから1件連想配列で取得
   * @param string $sql
   * @param array $params
   * @return array|false
   */
  public function fetch($sql, $params = [])
  {
    return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * DBから全件連想配列で取得
   * @param string $sql
   * @param array $params
   * @return array|false
   */
  public function fetchAll($sql, $params = [])
  {
    return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  }
}