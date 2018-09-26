<?php
if($_SERVER['REMOTE_ADDR']==='127.0.0.1'){
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

define('ROOT_PATH', realpath(__DIR__));

require_once ROOT_PATH.'/core/ActionRouter.php';

$actionRouter = new \core\ActionRouter(ROOT_PATH, '\Actions\\');
$actionRouter->routeToAction();