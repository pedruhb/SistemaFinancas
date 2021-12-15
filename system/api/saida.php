<?php

if (!defined("PHB")) die();

require_once("./system/global.php");

if (!isset($_SESSION["id"])) {
    die(json_encode(["success" => false, "message" => "Invalid Session"]));
}

match ($_GET["method"]) {
    "adicionar" => adicionar($_POST),
    default => die(json_encode(["success" => false, "message" => "Invalid Method!"]));
}

function adicionar($data)
{

    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(["success" => false, "message" => "Token inválido!"]));
    }

    if (!isset($data["banco"]) || !is_numeric($data["banco"])) {
        die(json_encode(["success" => false, "message" => "Banco inválido!"]));
    }

    if (!isset($data["categoria"]) || !is_numeric($data["categoria"])) {
        die(json_encode(["success" => false, "message" => "Banco inválido!"]));
    }

    if (!isset($data["valor"]) || !is_numeric((int)$data["valor"])) {
        die(json_encode(["success" => false, "message" => "Valor inválido!"]));
    }

    if (!isset($data["data"]) || !DateTime::createFromFormat('d/m/Y', $data["data"])) {
        die(json_encode(["success" => false, "message" => "Data inválida!"]));
    }

    if (!isset($data["observacoes"])) {
        die(json_encode(["success" => false, "message" => "Observações inválida!"]));
    }

    $date = DateTime::createFromFormat('d/m/Y', $data["data"])->format('U');
    if (!is_numeric($date)) {
        die(json_encode(["success" => false, "message" => "Data inválida!"]));
    }

    $edita = Database::connection()->prepare("INSERT INTO `gastos` (`user_id`, `nome`, `valor`, `data`, `categoria_id`, `banco_id`, `observacoes`) VALUES (?, ?, ?, ?, ?, ?, ?);");
    $edita->bindValue(1, $_SESSION["id"]);
    $edita->bindValue(2, $data["nome"]);
    $edita->bindValue(3, (int)$data["valor"]);
    $edita->bindValue(4, $date);
    $edita->bindValue(5, $data["categoria"]);
    $edita->bindValue(6, $data["banco"]);
    $edita->bindValue(7, $data["observacoes"]);
    $edita->execute();

    ActivityLogger::add("Saída a entrada \"" . $data["nome"] . "\" no valor de " . (int)$data["valor"] . ".");

    die(json_encode(["success" => true, "message" => "Saída adicionada com sucesso!"]));
}
