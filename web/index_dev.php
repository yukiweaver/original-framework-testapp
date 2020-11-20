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

/** 開発環境と本番環境 **/
// DBの接続情報はenv-developmentファイルがあるかどうかで判断する
// そのため、gitの追跡対象にenv-develop.phpは入れない
// また、開発でenv-develop.phpのコードを変更した場合は、同様の変更をenv-production.phpにもする

/********** 開発ルール ここまで **********/


require '../bootstrap.php';
require '../MiniBlogApplication.php';

$app = new MiniBlogApplication(true);
$app->run();
