<?php
// Define Constants
error_reporting(1);
session_start();
//session_destroy();
date_default_timezone_set('Asia/Ho_Chi_Minh');

define('IMG_DIR', dirname(__FILE__));
define('IMG_URL', 'https://api.vaobo.com');

global $AppDB;

include 'system/config.php';
include 'system/helper.php';
require_once 'system/MysqliDb.php';
$AppDB  = new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Get Controller
if (!isset($_GET['c'])) {
    $_GET['c'] = 'Default';
}
// Get Action
if (!isset($_GET['a'])) {
    $_GET['a'] = 'index';
}

// SET Controller & Action
$controller = ucfirst($_GET['c']);
$action     = $_GET['a'];

// Verify Controller
if (!file_exists(CONTROLLER . $controller . 'Controller.php')) {
    die('Controller: ' . $controller . ' file does not exist');
}

// Include Controller
include CONTROLLER . $controller . 'Controller.php';
$controllerClass = $controller. 'Controller';

// Verify Controller Class
if (!class_exists($controllerClass)) {
    die('Class: ' . $controllerClass . '  does not exist');
}

// Instantiate Controller Class
$objController = new $controllerClass;

// Verify Action Method
if (!method_exists($objController, $action)) {
    die('Class Method: ' . $action . ' does not exists');
}

// Start the App
$objController->$action();
