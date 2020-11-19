<?php

/**
 * コントローラークラス
 */
abstract Class Controller
{
  /**
   * ログインが必要なアクションを配列で格納
   * @var array
   */
  protected $authActions = [];

  /**
   * @var string
   */
  protected $controllerName;

  /**
   * @var string
   */
  protected $actionName;

  /**
   * @var Application
   */
  protected $application;

  /**
   * @var Request
   */
  protected $request;

  /**
   * @var Response
   */
  protected $response;

  /**
   * @var Session
   */
  protected $session;

  /**
   * @var DbManager
   */
  protected $dbManager;

  /**
   * @var BladeOne
   */
  protected $blade;

  public function __construct($application)
  {
    $this->controllerName = strtolower(substr(get_class($this), 0, -10)); // 例えばUserControllerクラスなら「user」を格納
    $this->application    = $application;
    $this->request        = $application->getRequest();
    $this->response       = $application->getResponse();
    $this->session        = $application->getSession();
    $this->dbManager      = $application->getDbManager();
    $this->blade          = $application->getBlade();
  }

  /**
   * アクションを実行
   * @param string $action
   * @param array $params
   * @return mixed
   */
  public function run($action, $params = [])
  {
    $this->actionName = $action;
    $actionMethod = $action . 'Action';
    if (!method_exists($this, $actionMethod)) {
      $this->forward404();
    }
    // 認証チェック
    if ($this->needsAuthentication($action) && !$this->session->isAuthenticated()) {
      throw new UnauthorizedActionException();
    }
    // アクション実行
    $content = $this->$actionMethod($params); // 可変関数

    return $content;
  }

  /**
   * レンダリング
   * @param array $vaiables テンプレートに渡す変数群
   * @param string $tempPath 描画するテンプレートのpath
   * @return void
   */
  public function render($variables = [], $tempPath = null)
  {
    $defaults = [
      'request'   => $this->request,
      'base_url'  => $this->request->getBaseUrl(),
      'session'   => $this->session,
    ];

    if (is_null($tempPath)) {
      $tempPath = $this->controllerName . '.' . $this->actionName;
    }

    $viewParams = array_merge($variables, $defaults);
    echo $this->blade->run($tempPath, $viewParams);
  }

  /**
   * 404 Not Found用の例外をスローする
   */
  protected function forward404()
  {
    throw new HttpNotFoundException('Forward 404 page from ' . $this->controllerName . '/' . $this->actionName);
  }

  /**
   * 指定URLにリダイレクトする
   * @param string $url
   * @return void
   */
  protected function redirect($url)
  {
    if (!preg_match('#https?://#', $url)) {
      $protocol = $this->request->isSsl() ? 'https://' : 'http://';
      $host = $this->request->getHost();
      $baseUrl = $this->request->getBaseUrl();
      $url = $protocol . $host . $baseUrl . $url;
    }
    header("Location: $url");
    exit;
  }

  /**
   * 生成したトークンをセッションに格納し、そのトークンを返す
   * @param string $formName
   * @return string 生成したトークン
   */
  protected function generateCsrfToken($formName)
  {
    $key = 'csrf_tokens/' . $formName;
    $tokens = $this->session->get($key, []);
    if (count($tokens) >= 10) {
      array_shift($tokens);
    }
    $token = sha1($formName . session_id() . microtime()); // ハッシュ化
    $tokens[] = $token;

    $this->session->set($key, $tokens);
    return $token;
  }

  /**
   * 指定のトークンをチェックする
   * @param string $formName
   * @param string $token
   * @return boolean true: OK false: NG
   */
  protected function checkCsrfToken($formName, $token)
  {
    $key = 'csrf_tokens/' . $formName;
    $tokens = $this->session->get($key, []);
    if (false !== ($pos = array_search($token, $tokens, true))) {
      // 用いたトークンは削除、削除後セッションに格納
      unset($tokens[$pos]);
      $this->session->set($key, $tokens);
      return true;
    }
    return false;
  }

  /**
   * 指定のアクションがログイン必須かどうか判定する
   * @param string $action
   * @return boolean true: ログイン必須 false: 必須ではない
   */
  protected function needsAuthentication($action)
  {
    if ($this->authActions === true || (is_array($this->authActions) && in_array($action, $this->authActions))) {
      return true;
    }
    return false;
  }
}