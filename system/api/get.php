<?php

if (!defined("PHB")) die();

require_once("./system/global.php");

if (!isset($_SESSION["id"])) {
    die(json_encode(array("success" => false, "message" => "Invalid Session")));
}

match ($_GET["method"]) {
    "activity" => activity(),
    default => die(json_encode(array("success" => false, "message" => "Invalid Method!")))
};

function activity()
{
    $get = Database::connection()->prepare("SELECT log, ip, timestamp FROM activity_log WHERE user_id = ? ORDER BY timestamp DESC");
    $get->bindValue(1, $_SESSION["id"]);
    $get->execute();
    die(json_encode(array("data" => $get->fetchAll(PDO::FETCH_ASSOC)), JSON_PRETTY_PRINT));
}