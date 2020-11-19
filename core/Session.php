<?php

/**
 * セッション情報を管理するクラス
 */
class Session
{
  protected static $sessionStarted = false;
  protected static $sessionIdRegenerated = false;

  public function __construct()
  {
    if (!self::$sessionStarted) {
      session_start();
      self::$sessionStarted = true;
    }
  }

  /**
   * セッションに値をセットする
   * @param string $name
   * @param mixed $value
   * @return void
   */
  public function set($name, $value)
  {
    $_SESSION[$name] = $value;
  }

  /**
   * セッションから指定したキーの値を取得する
   * @param string $name
   * @param mixed $default
   * @return mixed
   */
  public function get($name, $default = null)
  {
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    return $default;
  }

  /**
   * セッションから指定したキーに対応する値を削除する
   * @param string $name
   * @return void
   */
  public function remove($name)
  {
    unset($_SESSION[$name]);
  }

  /**
   * セッションを空にする
   * @return void
   */
  public function clear()
  {
    $_SESSION = [];
  }

  /**
   * セッションIDを新しく発行する
   * @param boolean $destroy trueで古いセッションを削除する
   * @return void
   */
  public function regenerate($destroy = true)
  {
    if (!self::$sessionIdRegenerated) {
      session_regenerate_id($destroy);
      self::$sessionIdRegenerated = true;
    }
  }

  /**
   * ログイン状態をセットする
   * @param boolean $bool
   * @return void
   */
  public function setAuthenticated($bool)
  {
    $this->set('_authenticated', (bool)$bool);
    $this->regenerate();
  }

  /**
   * ログイン状態を判定する
   * @return boolean false: 非ログイン状態, true: ログイン状態
   */
  public function isAuthenticated()
  {
    return $this->get('_authenticated', false);
  }
}