<?php

/**
 * フレームワークの中心となるクラス
 * Request,Router,Response,Sessionの管理、ルーティング定義,コントローラ実行,レスポンス送信など
 */
Use eftec\bladeone\BladeOne;

abstract class Application
{
  /**
   * ログイン画面のアクション情報を格納
   * $loginAction[0] = controller名
   * $loginAction[1] = action名
   * @var array
   */
  protected $loginAction = [];

  /**
   * @var boolean
   */
  protected $debug = false;

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
   * @var Router
   */
  protected $router;

  /**
   * @var BladeOne
   */
  protected $blade;

  public function __construct($debug = false)
  {
    $this->setDebugMode($debug);
    $this->initialize();
    $this->configure();
  }

  /**
   * エラー表示モードをセットし、表示レベルを変更
   * @param boolean $debug
   * @return void
   */
  protected function setDebugMode($debug)
  {
    if ($debug) {
      $this->debug = true;
      ini_set('display_errors', 1);
      error_reporting(-1);
    } else {
      $this->debug = false;
      ini_set('display_errors', 0);
    }
  }

  /**
   * 各インスタンス初期化
   * @return void
   */
  public function initialize()
  {
    $this->request    = new Request();
    $this->response   = new Response();
    $this->session    = new Session();
    $this->dbManager  = new DbManager();
    $this->router     = new Router($this->registerRoutes());
    $this->blade      = new BladeOne($this->getViewDir(), $this->getCompileDir(), BladeOne::MODE_AUTO);
  }

  /**
   * 個別設定を行う
   */
  public function configure()
  {
    //
  }

  /**
   * Routerからコントローラーを特定し、レスポンス送信を行う
   */
  public function run()
  {
    try {
      $params = $this->router->resolve($this->request->getPathInfo());
      if ($params === false) {
        // ルーティングがどれともマッチしない場合
        throw new HttpNotFoundException('No route found for ' . $this->request->getPathInfo());
      }
      $controller = $params['controller'];
      $action = $params['action'];
      $this->runAction($controller, $action, $params);
    } catch (HttpNotFoundException $e) {
      $this->render404Page($e);
    } catch (UnauthorizedActionException $e) {
      list($controller, $action) = $this->loginAction;
      $this->runAction($controller, $action);
    }
    // $this->response->send();
  }

  /**
   * コントローラークラスのrunを実行する
   * @param string $controllerName
   * @param string $action
   * @param array $params
   * @return void
   */
  public function runAction($controllerName, $action, $params = [])
  {
    $controllerClass = ucfirst($controllerName) . 'Controller';
    $controller = $this->findController($controllerClass);
    if ($controller === false) {
      // コントローラーが見つからない場合
      throw new HttpNotFoundException($controllerClass . ' controller is not found');
    }
    $content = $controller->run($action, $params);
    $this->response->setContent($content);
  }

  /**
   * 指定のコントローラークラスのインスタンスを生成
   * @param string $controllerClass
   * @return 指定クラスのインスタンス|false
   */
  protected function findController($controllerClass)
  {
    if (!class_exists($controllerClass)) {
      // クラス定義が存在しない場合
      $controllerFile = $this->getControllerDir() . '/' . $controllerClass . '.php';
      if (!is_readable($controllerFile)) {
        // クラスファイルが存在しない場合
        return false;
      } else {
        // クラスファイルが存在する場合はファイル読み込み後、クラス定義の存在性チェック再トライ
        require_once $controllerFile;
        if (!class_exists($controllerClass)) {
          return false;
        }
      }
    }

    return new $controllerClass($this);
  }

  /**
   * 404エラーページを描画する
   * @param HttpNotFoundException $e
   * @return void
   */
  protected function render404Page($e)
  {
    $message = $this->isDebugMode() ? $e->getMessage() : 'Page not found.'; // デバックモードなら例外エラー表示
    $viewParams = [
      'base_url'  => $this->request->getBaseUrl(),
      'message'   => $message,
    ];
    echo $this->blade->run('error404', $viewParams);
  }

  /**
   * ルートディレクトリを取得
   * @return string
   */
  abstract public function getRootDir();

  abstract public function registerRoutes();


  /*** 以下ゲッター ***/

  /**
   * デバッグモードを取得
   * @return boolean
   */
  public function isDebugMode()
  {
    return $this->debug;
  }

  /**
   * Requestインスタンスを取得
   * @return Request
   */
  public function getRequest()
  {
    return $this->request;
  }

  /**
   * Reponseインスタンスを取得
   * @return Response
   */
  public function getResponse()
  {
    return $this->response;
  }

  /**
   * Sessionインスタンスを取得
   * @return Session
   */
  public function getSession()
  {
    return $this->session;
  }

  /**
   * DbManagerインスタンスを取得
   * @return DbManager
   */
  public function getDbManager()
  {
    return $this->dbManager;
  }

  /**
   * Bladeインスタンスを取得
   * @return BladeOne
   */
  public function getBlade()
  {
    return $this->blade;
  }

  /**
   * controllersディレクトリまでのパスを取得
   * @return string
   */
  public function getControllerDir()
  {
    return $this->getRootDir() . '/controllers';
  }

  /**
   * viewsディレクトリまでのパスを取得
   * @return string
   */
  public function getViewDir()
  {
    return $this->getRootDir() . '/views';
  }

  /**
   * modelsディレクトリまでのパスを取得
   * @return string
   */
  public function getModelDir()
  {
    return $this->getRootDir() . '/models';
  }

  /**
   * validatesディレクトリまでのパスを取得
   * @return string
   */
  public function getValidateDir()
  {
    return $this->getRootDir() . '/validates';
  }

  /**
   * webディレクトリまでのパスを取得
   * @return string
   */
  public function getWebDir()
  {
    return $this->getRootDir() . '/web';
  }

  /**
   * compilesディレクトリまでのパスを取得
   * @return string
   */
  public function getCompileDir()
  {
    return $this->getRootDir() . '/compiles';
  }
}