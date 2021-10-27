<?php

define("PHB", 1);

if (!isset($_GET["type"])) {
    die(json_encode(array("success" => false, "message" => "Missing Type")));
}

if (!isset($_GET["method"])) {
    die(json_encode(array("success" => false, "message" => "Missing Method")));
}

switch ($_GET["type"]) {

    case "auth":
        require_once("system/api/auth.php");
        break;

    case "get":
        require_once("system/api/get.php");
        break;

}
