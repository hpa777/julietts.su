<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

define('MODX_API_MODE', true);
require_once 'index.php';

//$calculator = $modx->getService('calculator', 'calculator', MODX_CORE_PATH . 'components/calculator/model/');
$calculator = $modx->getService('calculator', 'calculator', MODX_BASE_PATH . 'calculator/core/components/calculator/model/');
//$calculator->checkMessageQueue();
$calculator->sendMessage();