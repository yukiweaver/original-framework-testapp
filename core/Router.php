<?php

/**
 * ルーティング定義配列とPATH_INFOの情報からルーティングを特定するクラス
 */
class Router
{
  /**
   * @var array
   */
  protected $routes;

  /**
   * @param array $definitions ルーティング定義配列
   */
  public function __construct($definitions)
  {
    $this->routes = $this->compileRoutes($definitions);
  }

  /**
   * 指定のルーティング定義配列の動的パラメータを正規表現で扱える形式に変換する
   * @param array $definitions ルーティング定義配列
   * @return array
   */
  public function compileRoutes($definitions)
  {
    $routes = array();
    foreach ($definitions as $url => $params) {
      $tokens = explode('/', ltrim($url, '/'));
      foreach ($tokens as $i => $token) {
        if (0 === strpos($token, ':')) {
          $name = substr($token, 1);
          $token = '(?P<' . $name . '>[^/]+)';
        }
        $tokens[$i] = $token;
      }
      $pattern = '/' . implode('/', $tokens);
      $routes[$pattern] = $params;
    }
    return $routes;
  }

  /**
   * 変換済みのルーティング定義配列とPATH_INFOのマッチングを行い、ルーティングを特定する
   * @param string $pathInfo
   * @return array|boolean どれともマッチングしない場合false
   */
  public function resolve($pathInfo)
  {
    if ('/' !== substr($pathInfo, 0, 1)) {
      $pathInfo = '/' . $pathInfo;
    }
    foreach ($this->routes as $pattern => $params) {
      if (preg_match('#^' . $pattern . '$#', $pathInfo, $matches)) {
        $params = array_merge($params, $matches);
        return $params;
      }
    }
    return false;
  }
}