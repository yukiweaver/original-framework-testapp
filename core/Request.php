<?php

/**
 * ユーザのリクエスト情報を制御するクラス
 */
class Request
{
  /**
   * POSTリクエストであるか判定する
   * @return boolean trueならPOST
   */
  public function isPost()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      return true;
    }
    return false;
  }

  /**
   * $_GETから値を取得する
   * @param string $name
   * @param $default
   * @return mixed|null
   */
  public function getGet($name, $default = null)
  {
    if (isset($_GET[$name])) {
      return $_GET[$name];
    }
    return $default;
  }

  /**
   * $_POSTから値を取得する
   * @param string $name
   * @param $default
   * @return mixed|null
   */
  public function getPost($name, $default = null)
  {
    if (isset($_POST[$name])) {
      return $_POST[$name];
    }
    return $default;
  }

  /**
   * サーバーのホスト名を取得する
   * @return string
   */
  public function getHost()
  {
    if (!empty($_SERVER['HTTP_HOST'])) {
      return $_SERVER['HTTP_HOST'];
    }
    return $_SERVER['SERVER_NAME'];
  }

  /**
   * HTTPSでアクセスされたか判定する
   * @return boolean trueならHTTPS
   */
  public function isSsl()
  {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      return true;
    }
    return false;
  }

  /**
   * ホスト部分以降のURIを取得する
   * @return string
   */
  public function getRequestUri()
  {
    return $_SERVER['REQUEST_URI'];
  }

  /**
   * ベースURLを取得する
   * @return string
   */
  public function getBaseUrl()
  {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $requestUri = $this->getRequestUri();

    if (0 === strpos($requestUri, $scriptName)) {
      return $scriptName;
    } elseif (0 === strpos($requestUri, dirname($scriptName))) {
      return rtrim(dirname($scriptName), '/');
    }
    return '';
  }

  public function getPathInfo()
  {
    $baseUrl = $this->getBaseUrl();
    $requestUri = $this->getRequestUri();

    if (false !== ($pos = strpos($requestUri, '?'))) {
      $requestUri = substr($requestUri, 0, $pos);
    }
    $pathInfo = (string)substr($requestUri, strlen($baseUrl));
    return $pathInfo;
  }
}