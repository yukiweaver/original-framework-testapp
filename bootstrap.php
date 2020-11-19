<?php

require dirname(__FILE__) . '/core/ClassLoader.php';
require_once dirname(__FILE__) . '/config/message.php';
require_once dirname(__FILE__) . '/config/env.php';
require_once dirname(__FILE__) . '/config/route_definition.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';

$loader = new ClassLoader();

// 以下のディレクトリ配下が読み込み対象
$loader->registerDir(dirname(__FILE__) . '/core');
$loader->registerDir(dirname(__FILE__) . '/models');
$loader->registerDir(dirname(__FILE__) . '/validates');
$loader->registerDir(dirname(__FILE__) . '/services');
$loader->registerDir(dirname(__FILE__) . '/utils');

$loader->register();