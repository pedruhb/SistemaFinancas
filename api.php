<?php

define("PHB", 1);

if (!isset($_GET["type"])) {
    die(json_encode(array("success" => false, "message" => "Missing Type")));
}

if (!isset($_GET["method"])) {
    die(json_encode(array("success" => false, "message" => "Missing Method")));
}

match ($_GET['type']) {
    "auth" => require_once("system/api/auth.php"),
    "get" => require_once("system/api/get.php"),
    "categorias" => require_once("system/api/categorias.php"),
    default => die(json_encode(array("success" => false, "message" => "Invalid Type")))
};
