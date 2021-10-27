<?php

session_start();

require_once("./system/config.php");

if ($settings["php_debug"]) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

/// Carrega classes
require_once("./system/class/class.db.php");
require_once("./system/class/class.error.handler.php");

new ErrorHandler();

if (!isset($_COOKIE["csrf-token"])) {
    setcookie("csrf-token", md5(uniqid().time()), time() + (86400 * 30));
}

function getUserIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

if(isset($_SESSION["userAgent"])){
    if($_SESSION["userAgent"] != $_SERVER['HTTP_USER_AGENT']){
        header("Location: /logout");
        return;
    }
}