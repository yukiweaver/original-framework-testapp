<?php

/**
 * UserRepositoryクラス
 */
class UserRepository extends DbRepository
{

  /**
   * userNameをキーにしてレコード1件取得
   * @param string $userName
   * @return array
   */
  public function fetchByUserName($userName)
  {
    $sql = <<< EOM
SELECT *
FROM user
WHERE user_name = :user_name
EOM;
    return $this->fetch($sql, [':user_name' => $userName]);
  }

  /**
   * userNameをキーにして既にレコードが存在する、しないを判定する
   * @param string $userName
   * @return boolean ture: レコード存在しない, false: レコード存在する
   */
  public function isUniqueUserName($userName)
  {
    $sql = <<< EOM
SELECT COUNT(id) as count
FROM user
WHERE user_name = :user_name
EOM;
    $row = $this->fetch($sql, [':user_name' => $userName]);
    if ($row['count'] === '0') {
      return true;
    }
    return false;
  }

  /**
   * userテーブルへインサート
   * @param string $userName
   * @param string $password ハッシュ化前の文字列
   * @return void
   */
  public function insert($userName, $password)
  {
    $password = $this->hashPassword($password);
    $now = new Datetime();

    $sql = <<< EOM
INSERT INTO user (
  user_name, password, created_at
) VALUES (:user_name, :password, :created_at)
EOM;

    $stmt = $this->execute($sql, [
      ':user_name'  => $userName,
      ':password'   => $password,
      ':created_at' => $now->format('Y-m-d H:i:s'),
    ]);
  }

  /**
   * 指定の文字列をハッシュ化して、ハッシュ化した文字列を返す
   * @param string $password
   * @return string
   */
  public function hashPassword($password)
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }
}