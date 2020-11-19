<?php

/**
 * HTTPヘッダとHTMLなどのコンテンツを返すクラス
 */
class Response
{
  /**
   * HTMLなどのクライアントに返す内容
   */
  protected $content;

  /**
   * @var int ステータスコード
   */
  protected $statusCode = 200;

  /**
   * @var string ステータステキスト
   */
  protected $statusText = 'OK';

  /**
   * @var array Httpヘッダ
   */
  protected $httpHeaders = array();

  /**
   * レスポンス送信
   */
  public function send()
  {
    header('HTTP/1.1' . $this->statusCode . ' ' . $this->statusText);
    foreach ($this->httpHeaders as $name => $value) {
      header($name . ':' . $value);
    }
    echo $this->content;
  }

  public function setContent($content)
  {
    $this->content = $content;
  }

  public function setStatusCode($statusCode, $statusText = '')
  {
    $this->statusCode = $statusCode;
    $this->statusText = $statusText;
  }

  public function setHttpHeader($name, $value)
  {
    $this->httpHeaders[$name] = $value;
  }
}