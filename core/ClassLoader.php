<?php

/**
 * オートローダークラス
 */
class ClassLoader
{
  /**
   * @var array
   */
  protected $dirs;

  /**
   * オートローダクラスを登録する
   * @return boolean
   */
  public function register()
  {
    spl_autoload_register(array($this, 'loadClass'));
  }

  /**
   * @param string $dir ディレクトリへの絶対パス
   * @return void
   */
  public function registerDir($dir)
  {
    $this->dirs[] = $dir;
  }

  /**
   * クラスファイル読み込み
   * @param string $class
   */
  public function loadClass($class)
  {
    foreach ($this->dirs as $dir) {
      $file = $dir . '/' . $class . '.php';
      if (is_readable($file)) {
        require $file;
        return;
      }
    }
  }
}