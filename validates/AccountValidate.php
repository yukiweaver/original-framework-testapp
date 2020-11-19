<?php

class AccountValidate extends BaseValidate
{
  /**
   * ユーザ名
   * @var string
   */
  protected $userName;

  /**
   * パスワード
   * @var string
   */
  protected $password;

  /**
   * コンストラクタ
   * @param DbManager $dbManager
   */
  public function __construct($dbManager)
  {
    parent::__construct($dbManager);
  }

  /**
   * バリデーションを行うため、プロパティに値をセットする
   * @param array $userParams
   * ['user_name' => ユーザ名, 'password' => パスワード]
   * @return void
   */
  public function setValidate($userParams)
  {
    $this->userName = $userParams['user_name'];
    $this->password = $userParams['password'];
  }

  /**
   * ユーザ名、パスワードが共に空でないか判定する
   * @return boolean true: 空ではない, false: 空
   */
  public function isNotEmptyUserNameAndPassword()
  {
    if (!strlen($this->userName) || !strlen($this->password)) {
      return false;
    }
    return true;
  }

  /**
   * ユーザ名の文字種、長さに問題がないか判定する
   * @return boolean true: 問題なし, false: 問題あり
   */
  public function isCharactertypeUserName()
  {
    if (!preg_match('/^\w{3,20}$/', $this->userName)) {
      return false;
    }
    return true;
  }

  /**
   * ユーザ名に重複がないか判定する
   * @return boolean true: 重複なし, false: 重複あり
   */
  public function isNotDuplicateUserName()
  {
    if (!$this->dbManager->get('User')->isUniqueUserName($this->userName)) {
      return false;
    }
    return true;
  }

  /**
   * パスワードの長さを判定する
   * @return boolean true: 問題なし, false: 問題あり
   */
  public function isLengthPassword()
  {
    if (PASSWORD_MIN_LENGTH > strlen($this->password) || strlen($this->password) > PASSWORD_MAX_LENGTH) {
      return false;
    }
    return true;
  }

  /**
   * ユーザ登録のバリデーション
   * @return array
   */
  public function isValidateRegist()
  {
    $errors = [];

    if (!$this->isNotEmptyUserNameAndPassword()) {
      $errors[] = ERR_MSG_NOT_USER_ID_AND_PASSWORD_ENTERD;
      return $errors;
    }
    if (!$this->isCharactertypeUserName()) {
      $errors[] = ERR_MSG_USER_ID_VALIDATE;
      return $errors;
    }
    if (!$this->isNotDuplicateUserName()) {
      $errors[] = ERR_MSG_USER_ID_DUPLICATE;
      return $errors;
    }
    if (!$this->isLengthPassword()) {
      $errors[] = ERR_MSG_PASSWORD_VALIDATE;
      return $errors;
    }

    return $errors;
  }
}