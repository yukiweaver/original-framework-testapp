<?php

require dirname(__FILE__) . '/core/ClassLoader.php';
require_once dirname(__FILE__) . '/config/message.php';
require_once dirname(__FILE__) . '/config/route_definition.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';

if (file_exists(dirname(__FILE__) . '/config/env-develop.php')) {
  // 開発環境
  require_once dirname(__FILE__) . '/config/env-develop.php';
} else {
  // 本番環境
  require_once dirname(__FILE__) . '/config/env-production.php';
}

$loader = new ClassLoader();

// 以下のディレクトリ配下が読み込み対象
$loader->registerDir(dirname(__FILE__) . '/core');
$loader->registerDir(dirname(__FILE__) . '/models');
$loader->registerDir(dirname(__FILE__) . '/validates');
$loader->registerDir(dirname(__FILE__) . '/services');
$loader->registerDir(dirname(__FILE__) . '/utils');

$loader->register();