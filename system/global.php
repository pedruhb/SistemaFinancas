<?php

session_start();

define("PHB", 1);

require_once("./system/config.php");

if ($settings["php_debug"]) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

require_once("./system/class/class.db.php");
require_once("./system/class/class.error.handler.php");
require_once("./system/class/class.activity.logger.php");

new ErrorHandler();

if (!isset($_COOKIE["csrf-token"])) {
    setcookie("csrf-token", md5(uniqid() . time()), time() + (86400 * 30));
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

if (isset($_SESSION["userAgent"])) {
    if ($_SESSION["userAgent"] != $_SERVER['HTTP_USER_AGENT']) {
        header("Location: /logout");
        return;
    }
}

if (isset($_SESSION['id'])) {
    $getUser = Database::connection()->prepare("SELECT * FROM users WHERE id = ?");
    $getUser->bindValue(1, $_SESSION['id']);
    $getUser->execute();
    if ($getUser->rowCount() == 0) {
        header("Location: /logout");
        return;
    } else {
        $accountData = $getUser->fetch();
        if($accountData["password"] != $_SESSION['session_password']){
            header("Location: /logout");
            return;
        }
    }
}
