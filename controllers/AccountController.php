<?php

/**
 * AccountControllerクラス
 */
class AccountController extends Controller
{
  /**
   * AccountValidateオブジェクト
   * @var AccountValidate
   */
  protected $accountValidate;

  /**
   * AccountServiceオブジェクト
   * @var AccountService
   */
  protected $accountService;

  /**
   * コンストラクタ
   */
  public function __construct($application)
  {
    parent::__construct($application);

    $this->accountValidate  = new AccountValidate($this->dbManager);
    $this->accountService   = new AccountService($this->dbManager);
  }

  /**
   * アカウント登録画面表示アクション
   */
  public function signupAction()
  {
    $viewParams = [
      'user_name' => '',
      'password'  => '',
      '_token'    => $this->generateCsrfToken('account/signup'),
    ];
    return $this->render($viewParams, 'account.signup');
  }

  /**
   * ユーザ登録処理アクション
   */
  public function registerAction()
  {
    if (!$this->request->isPost()) {
      $this->forward404();
    }

    $token = $this->request->getPost('_token');
    if (!$this->checkCsrfToken('account/signup', $token)) {
      return $this->redirect('/account/signup');
    }

    $userName = $this->request->getPost('user_name');
    $password = $this->request->getPost('password');

    $userParams = [
      'user_name' => $userName,
      'password'  => $password,
    ];

    // バリデーション用に値をセット
    $this->accountValidate->setValidate($userParams);
    // バリデーション実行
    $errors = $this->accountValidate->isValidateRegist();

    if (count($errors) === 0) {
      $user = $this->accountService->registUser($userName, $password);
      $this->session->setAuthenticated(true);
      $this->session->set('user', $user);
      return $this->redirect('/');
    }

    $viewParams = [
      'user_name'   => $userName,
      'password'    => $password,
      'errors'      => $errors,
      '_token'      => $this->generateCsrfToken('account/signup'),
    ];
    return $this->render($viewParams, 'account.signup');
  }
}