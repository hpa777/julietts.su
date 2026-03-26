<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var calculator $calculator */
$calculator = $modx->getService('calculator', 'calculator', MODX_CORE_PATH . 'components/calculator/model/');
//$modx->lexicon->load('calculator:default');

// handle request
$corePath = $modx->getOption('calculator_core_path', null, $modx->getOption('core_path') . 'components/calculator/');
$path = $modx->getOption('processorsPath', $calculator->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;

if (in_array($_REQUEST['action'], ['mgr/exchange/export', 'mgr/exchange/import'])) {
    $version = $modx->getVersionData();
    if (version_compare($version['full_version'], '2.1.1-pl') >= 0) {
        if ($modx->user->hasSessionContext($modx->context->get('key'))) {
            $_SERVER['HTTP_MODAUTH'] = $_SESSION["modx.{$modx->context->get('key')}.user.token"];
        } else {
            $_SESSION["modx.{$modx->context->get('key')}.user.token"] = 0;
            $_SERVER['HTTP_MODAUTH'] = 0;
        }
    } else {
        $_SERVER['HTTP_MODAUTH'] = $modx->site_id;
    }
    $_REQUEST['HTTP_MODAUTH'] = $_SERVER['HTTP_MODAUTH'];
} 

$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);