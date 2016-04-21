<?php

//ini_set('display_errors', '0');
require_once dirname(__FILE__) . '/php/errorhandling/errorlogger.php';

//require_once dirname(__FILE__) . '/php/controller/Controller.php';

$controller;

spl_autoload_register(function ($class_name) {
    $root = dirname(__FILE__) . '/php/';
    $dirs = array(
        'controller/',
        'config/',
        'dao/',
        'dao/beer/',
        'dao/guestpost/',
        'dao/promoevent/',
        'dao/user/',
        'errorhandling/',
        'model/',
        'service/',
        'validation/'
    );
    foreach ($dirs as $dir) {
        if (file_exists($root . $dir . strtolower($class_name) . '.php')) {
            require_once($root . $dir . strtolower($class_name) . '.php');
            return;
        }
    }
});

try {
    $controller = new Controller();
} catch (Exception $ex) {
    ErrorLogger::logError($ex);
    exit;
}
$controller->processRequest();
