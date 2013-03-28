<?php
 error_reporting(E_ALL ^ E_NOTICE);
    ini_set('error_reporting', E_ALL ^ E_NOTICE);
    ini_set("display_errors", 1);

require 'libs/config.php';

//load needed Class-Files
function __autoload($class) {
    require LIBS . $class .".php";
}

// Load the Bootstrap!
$bootstrap = new Bootstrap();

// Optional Path Settingsnj
//$bootstrap->setControllerPath();
//$bootstrap->setModelPath();
//$bootstrap->setDefaultFile();
//$bootstrap->setErrorFile();

$bootstrap->init();