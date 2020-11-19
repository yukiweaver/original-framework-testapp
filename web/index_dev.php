<?php

/**
 * 開発用（デバックモードがtrue）
 */

/********** 開発ルール ここから **********/

/** テンプレート系 **/ 
// views/コントローラー名/テンプレートファイルを置く
// 例: views/account/signup.blade.php （AccountControllerクラスなら）

/** コントローラー系 **/
// 一つのアクションで一つのページを担当する
// ページの存在しない処理のみの場合もありえる

/** モデル系 **/
// 〇〇Repository.phpというファイル名にする
// 例:userテーブルに関する処理を書く場合はUserRepository.phpとする

/** サービス系 **/
// アクションから直接モデルにアクセスするのではなく、サービス経由でアクセスする

/** 設定系 **/
// ルーティング定義はroute_definition.php
// エラーメッセージなどメッセージはmessage.php
// その他設定はenv.inc

/********** 開発ルール ここまで **********/


require '../bootstrap.php';
require '../MiniBlogApplication.php';

$app = new MiniBlogApplication(true);
$app->run();
