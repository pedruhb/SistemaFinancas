<?php

if (!defined("PHB")) die();

require_once("./system/global.php");

if (!isset($_SESSION["id"])) {
    die(json_encode(["success" => false, "message" => "Invalid Session"]));
}

match ($_GET['method']) {
    "editar_gastos" => editar_gastos($_POST),
    "editar_ganhos" => editar_ganhos($_POST),
    "add_gastos" => add_gastos($_POST),
    "add_ganhos" => add_ganhos($_POST),
    "ganhos" => categoriasGanhos(),
    "gastos" => categoriasGastos(),
    "apagar_gastos" => apagar_gastos($_POST),
    "apagar_ganhos" => apagar_ganhos($_POST),
    default => die(json_encode(["success" => false, "message" => "Invalid Method!"]))
};

/* Ganhos */
function add_ganhos($data)
{
    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(["success" => false, "message" => "Token inválido!"]));
    }

    if (!isset($data["nome"]) || strlen($data["nome"]) > 50 || strlen($data["nome"]) < 3) {
        die(json_encode(["success" => false, "message" => "Nome inválido!"]));
    }

    if (!isset($data["cor"]) || !str_starts_with($data["cor"], "#") || strlen($data["cor"]) > 12) {
        die(json_encode(["success" => false, "message" => "Cor inválida!"]));
    }

    $insertCategoria = Database::connection()->prepare("INSERT INTO `categorias_ganhos` (`user_id`, `nome`, `cor_hex`) VALUES (?, ?, ?);");
    $insertCategoria->bindValue(1, $_SESSION["id"]);
    $insertCategoria->bindValue(2, $data["nome"]);
    $insertCategoria->bindValue(3, $data["cor"]);
    if ($insertCategoria->execute()) {
        $get = Database::connection()->prepare("SELECT id, nome, cor_hex, 0 as lancamentos FROM categorias_ganhos WHERE user_id = ? ORDER BY id DESC");
        $get->bindValue(1, $_SESSION["id"]);
        $get->execute();
        ActivityLogger::add("Adicionou a categoria de ganhos \"" . $data["nome"] . "\".");
        die(json_encode(["success" => true, "message" => "Categoria adicionada com sucesso!", "data" => $get->fetchAll(PDO::FETCH_ASSOC)]));
    } else {
        die(json_encode(["success" => false, "message" => "Erro ao adicionar categoria!", "data" => null]));
    }
}

function editar_ganhos($data)
{
    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(["success" => false, "message" => "Token inválido!"]));
    }

    if (!isset($data["nome"]) || strlen($data["nome"]) > 50 || strlen($data["nome"]) < 3) {
        die(json_encode(["success" => false, "message" => "Nome inválido!"]));
    }

    if (!isset($data["cor"]) || !str_starts_with($data["cor"], "#") || strlen($data["cor"]) > 12) {
        die(json_encode(["success" => false, "message" => "Cor inválida!"]));
    }

    if (!isset($data["id"]) || !is_numeric($data["id"])) {
        die(json_encode(["success" => false, "message" => "ID Inválido!"]));
    }

    $check = Database::connection()->prepare("SELECT id FROM categorias_ganhos WHERE id = ? AND user_id = ?");
    $check->bindValue(1, $data["id"]);
    $check->bindValue(2, $_SESSION["id"]);
    $check->execute();

    if ($check->rowCount() == 0) {
        die(json_encode(["success" => false, "message" => "Categoria inválida!"]));
    }

    $update = Database::connection()->prepare("UPDATE categorias_ganhos SET nome = ?, cor_hex = ? WHERE id = ?");
    $update->bindValue(1, $data["nome"]);
    $update->bindValue(2, $data["cor"]);
    $update->bindValue(3, $data['id']);
    if ($update->execute()) {
        $get = Database::connection()->prepare("SELECT id, nome, cor_hex, 0 as lancamentos FROM categorias_ganhos WHERE user_id = ? ORDER BY id DESC");
        $get->bindValue(1, $_SESSION["id"]);
        $get->execute();
        ActivityLogger::add("Editou a categoria de ganhos \"" . $data["nome"] . "\".");
        die(json_encode(["success" => true, "message" => "Categoria editada com sucesso!", "data" => $get->fetchAll(PDO::FETCH_ASSOC)]));
    } else {
        die(json_encode(["success" => false, "message" => "Erro ao editar categoria!", "data" => null]));
    }
}

function categoriasGanhos()
{
    $get = Database::connection()->prepare("SELECT id, nome, cor_hex, 0 as lancamentos FROM categorias_ganhos WHERE user_id = ? ORDER BY id DESC");
    $get->bindValue(1, $_SESSION["id"]);
    $get->execute();
    die(json_encode(["data" => $get->fetchAll(PDO::FETCH_ASSOC)], JSON_PRETTY_PRINT));
}

function apagar_ganhos($data)
{

    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(["success" => false, "message" => "Token inválido!"]));
    }

    if (!isset($data["id"]) || !is_numeric($data["id"])) {
        die(json_encode(["success" => false, "message" => "ID Inválido!"]));
    }

    $check = Database::connection()->prepare("SELECT id FROM categorias_ganhos WHERE id = ? AND user_id = ?");
    $check->bindValue(1, $data["id"]);
    $check->bindValue(2, $_SESSION["id"]);
    $check->execute();

    if ($check->rowCount() == 0) {
        die(json_encode(["success" => false, "message" => "Categoria inválida!"]));
    }

    $update = Database::connection()->prepare("DELETE FROM categorias_ganhos WHERE id = ?");
    $update->bindValue(1, $data['id']);
    if ($update->execute()) {

        /* INSERIR CÓDIGO PARA REMOVER A CATEGORIA DOS LANÇAMENTOS */

        $get = Database::connection()->prepare("SELECT id, nome, cor_hex, 0 as lancamentos FROM categorias_ganhos WHERE user_id = ? ORDER BY id DESC");
        $get->bindValue(1, $_SESSION["id"]);
        $get->execute();
        die(json_encode(["success" => true, "message" => "Categoria removida com sucesso!", "data" => $get->fetchAll(PDO::FETCH_ASSOC)]));
    } else {
        die(json_encode(["success" => false, "message" => "Erro ao remover categoria!", "data" => null]));
    }
}


/* Gastos */
function add_gastos($data)
{
    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(array("success" => false, "message" => "Token inválido!")));
    }

    if (!isset($data["nome"]) || strlen($data["nome"]) > 50 || strlen($data["nome"]) < 3) {
        die(json_encode(array("success" => false, "message" => "Nome inválido!")));
    }

    if (!isset($data["cor"]) || !str_starts_with($data["cor"], "#") || strlen($data["cor"]) > 12) {
        die(json_encode(array("success" => false, "message" => "Cor inválida!")));
    }

    $insertCategoria = Database::connection()->prepare("INSERT INTO `categorias_gastos` (`user_id`, `nome`, `cor_hex`) VALUES (?, ?, ?);");
    $insertCategoria->bindValue(1, $_SESSION["id"]);
    $insertCategoria->bindValue(2, $data["nome"]);
    $insertCategoria->bindValue(3, $data["cor"]);
    if ($insertCategoria->execute()) {
        $get = Database::connection()->prepare("SELECT id, nome, cor_hex, 0 as lancamentos FROM categorias_gastos WHERE user_id = ? ORDER BY id DESC");
        $get->bindValue(1, $_SESSION["id"]);
        $get->execute();
        die(json_encode(array("success" => true, "message" => "Categoria adicionada com sucesso!", "data" => $get->fetchAll(PDO::FETCH_ASSOC))));
    } else {
        die(json_encode(array("success" => false, "message" => "Erro ao adicionar categoria!", "data" => null)));
    }
}

function editar_gastos($data)
{
    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(array("success" => false, "message" => "Token inválido!")));
    }

    if (!isset($data["nome"]) || strlen($data["nome"]) > 50 || strlen($data["nome"]) < 3) {
        die(json_encode(array("success" => false, "message" => "Nome inválido!")));
    }

    if (!isset($data["cor"]) || !str_starts_with($data["cor"], "#") || strlen($data["cor"]) > 12) {
        die(json_encode(array("success" => false, "message" => "Cor inválida!")));
    }

    if (!isset($data["id"]) || !is_numeric($data["id"])) {
        die(json_encode(array("success" => false, "message" => "ID Inválido!")));
    }

    $check = Database::connection()->prepare("SELECT id FROM categorias_gastos WHERE id = ? AND user_id = ?");
    $check->bindValue(1, $data["id"]);
    $check->bindValue(2, $_SESSION["id"]);
    $check->execute();

    if ($check->rowCount() == 0) {
        die(json_encode(array("success" => false, "message" => "Categoria inválida!")));
    }

    $update = Database::connection()->prepare("UPDATE categorias_gastos SET nome = ?, cor_hex = ? WHERE id = ?");
    $update->bindValue(1, $data["nome"]);
    $update->bindValue(2, $data["cor"]);
    $update->bindValue(3, $data['id']);
    if ($update->execute()) {
        $get = Database::connection()->prepare("SELECT id, nome, cor_hex, 0 as lancamentos FROM categorias_gastos WHERE user_id = ? ORDER BY id DESC");
        $get->bindValue(1, $_SESSION["id"]);
        $get->execute();
        die(json_encode(array("success" => true, "message" => "Categoria editada com sucesso!", "data" => $get->fetchAll(PDO::FETCH_ASSOC))));
    } else {
        die(json_encode(array("success" => false, "message" => "Erro ao editar categoria!", "data" => null)));
    }
}

function categoriasGastos()
{
    $get = Database::connection()->prepare("SELECT id, nome, cor_hex, 0 as lancamentos FROM categorias_gastos WHERE user_id = ? ORDER BY id DESC");
    $get->bindValue(1, $_SESSION["id"]);
    $get->execute();
    die(json_encode(array("data" => $get->fetchAll(PDO::FETCH_ASSOC)), JSON_PRETTY_PRINT));
}

function apagar_gastos($data)
{

    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(array("success" => false, "message" => "Token inválido!")));
    }

    if (!isset($data["id"]) || !is_numeric($data["id"])) {
        die(json_encode(array("success" => false, "message" => "ID Inválido!")));
    }

    $check = Database::connection()->prepare("SELECT id FROM categorias_gastos WHERE id = ? AND user_id = ?");
    $check->bindValue(1, $data["id"]);
    $check->bindValue(2, $_SESSION["id"]);
    $check->execute();

    if ($check->rowCount() == 0) {
        die(json_encode(array("success" => false, "message" => "Categoria inválida!")));
    }

    $update = Database::connection()->prepare("DELETE FROM categorias_gastos WHERE id = ?");
    $update->bindValue(1, $data['id']);
    if ($update->execute()) {

        /* INSERIR CÓDIGO PARA REMOVER A CATEGORIA DOS LANÇAMENTOS */

        $get = Database::connection()->prepare("SELECT id, nome, cor_hex, 0 as lancamentos FROM categorias_gastos WHERE user_id = ? ORDER BY id DESC");
        $get->bindValue(1, $_SESSION["id"]);
        $get->execute();
        die(json_encode(array("success" => true, "message" => "Categoria removida com sucesso!", "data" => $get->fetchAll(PDO::FETCH_ASSOC))));
    } else {
        die(json_encode(array("success" => false, "message" => "Erro ao remover categoria!", "data" => null)));
    }
}
