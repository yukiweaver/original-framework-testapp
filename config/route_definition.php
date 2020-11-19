<?php

/** ルーティング定義配列 **/

$g_routeDefinition = [
  '/account' => [
    'controller'  => 'account',
    'action'      => 'index',
  ],
  '/account/:action' => [
    'controller'  => 'account',
  ],
];