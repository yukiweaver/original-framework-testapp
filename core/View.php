<?php

/**
 * Viewファイルの読み込み、渡す変数の制御を行うクラス
 */
class View
{
  /**
   * viewsディレクトリまでの絶対パス
   * @var string
   */
  protected $baseDir;

  /**
   * viewファイルにデフォルトで渡す変数を連想配列で格納
   * @var array
   */
  protected $defaults;

  /**
   * レイアウトファイル（HTML共通部分）に渡す変数を連想配列で格納
   * @var array
   */
  protected $layoutVariables = [];


  public function __construct($baseDir, $defaults = [])
  {
    $this->baseDir  = $baseDir;
    $this->defaults = $defaults;
  }

  public function setLayoutVar($name, $value)
  {
    $this->layoutVariables[$name] = $value;
  }

  /**
   * 文字列で画面に出力する内容を取得する
   * @param string $_path viewファイルへのパス
   * @param array $_variables viewファイルに渡す変数をまとめた連想配列
   * @param string $_layout レイアウトファイルまでのパス（HTMLの共通部分）
   * @return string
   */
  public function render($_path, $_variables = [], $_layout = false)
  {
    $_file = $this->baseDir . '/' . $_path . '.php';

    // 連想配列のキーを変数名に値を変数の値として展開する
    extract(array_merge($this->defaults, $_variables));

    // アウトプットバッファリング開始
    // この状態でHTMLをrequireしても出力されずに内部のバッファにたまる
    ob_start();
    ob_implicit_flush(0);

    require $_file;

    // バッファに格納されている文字列を取得
    $content = ob_get_clean();

    if ($_layout) {
      // HTML共通部分を含めたHTML文字列を取得
      $content = $this->render($_layout, array_merge($this->layoutVariables, ['_content' => $content]));
    }

    return $content;
  }

  /**
   * 指定の文字列をエスケープする
   * @param string $string
   * @return string
   */
  public function escape($string)
  {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
  }
}