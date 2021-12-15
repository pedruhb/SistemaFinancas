<?php

if (!defined("PHB")) die();

require_once("./system/global.php");

if (!isset($_SESSION["id"])) {
    die(json_encode(array("success" => false, "message" => "Invalid Session")));
}

match ($_GET["method"]) {
    "get" => get(),
    "salvar" => salvar($_POST),
    "adicionar" => adicionar($_POST),
    "apagar" => apagar($_POST),
    "bandeiras" => bandeiras(),
    default => die(json_encode(array("success" => false, "message" => "Invalid Method!")))
};

function obterCartoesUsuario()
{
    $get = Database::connection()->prepare("SELECT *, 0 as lancamentos FROM cartoes WHERE user_id = ? ORDER BY id DESC");
    $get->bindValue(1, $_SESSION["id"]);
    $get->execute();
    return $get->fetchAll(PDO::FETCH_ASSOC);
}

function get()
{
    die(json_encode(array("success" => true, "data" => obterCartoesUsuario()), JSON_PRETTY_PRINT));
}

function bandeiras()
{
    global $settings;
    die(json_encode(array("success" => true, "data" => $settings["bandeiras_cartoes"]), JSON_PRETTY_PRINT));
}

function salvar($data)
{
    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(array("success" => false, "message" => "Token inválido!")));
    }

    if (!isset($data["nome"]) || strlen($data["nome"]) < 2 || strlen($data["nome"]) > 60) {
        die(json_encode(array("success" => false, "message" => "Nome inválido!")));
    }

    if (!isset($data["bandeira"]) || strlen($data["bandeira"]) < 2 || strlen($data["bandeira"]) > 20) {
        die(json_encode(array("success" => false, "message" => "Bandeira inválida!")));
    }

    if (!isset($data["emissor"]) || strlen($data["emissor"]) < 2 || strlen($data["emissor"]) > 20) {
        die(json_encode(array("success" => false, "message" => "Emissor inválido!")));
    }

    if (!isset($data["ultimos"]) || strlen($data["ultimos"]) != 4 || !is_numeric($data["ultimos"])) {
        die(json_encode(array("success" => false, "message" => "Últimos 4 dígitos inválidos!")));
    }

    if (!isset($data["observacoes"])) {
        die(json_encode(array("success" => false, "message" => "Observações inválida!")));
    }

    if (!isset($data["id"]) || !is_numeric($data["id"])) {
        die(json_encode(array("success" => false, "message" => "ID inválido!")));
    }

    $verificaExiste = Database::connection()->prepare("SELECT id, ultimos_digitos FROM cartoes WHERE id = ? AND user_id = ?");
    $verificaExiste->bindValue(1, $data["id"]);
    $verificaExiste->bindValue(2, $_SESSION["id"]);
    $verificaExiste->execute();

    if ($verificaExiste->rowCount() != 1) {
        die(json_encode(array("success" => false, "message" => "Cartão inválido!")));
    }

    $cartao = $verificaExiste->fetch();

    $edita = Database::connection()->prepare("UPDATE cartoes SET nome = ?, emissor = ?, ultimos_digitos = ?, bandeira = ?, observacoes = ? WHERE id = ?");
    $edita->bindValue(1, $data["nome"]);
    $edita->bindValue(2, $data["emissor"]);
    $edita->bindValue(3, $data["ultimos"]);
    $edita->bindValue(4, $data["bandeira"]);
    $edita->bindValue(5, $data["observacoes"]);
    $edita->bindValue(6, $data["id"]);
    $edita->execute();

    ActivityLogger::add("Editou o cartão final " + $cartao["ultimos_digitos"] + ".");

    die(json_encode(array("success" => true, "message" => "Cartão editado com sucesso!", "data" => obterCartoesUsuario()), JSON_PRETTY_PRINT));
}

function adicionar($data)
{

    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(array("success" => false, "message" => "Token inválido!")));
    }

    if (!isset($data["nome"]) || strlen($data["nome"]) < 2 || strlen($data["nome"]) > 60) {
        die(json_encode(array("success" => false, "message" => "Nome inválido!")));
    }

    if (!isset($data["bandeira"]) || strlen($data["bandeira"]) < 2 || strlen($data["bandeira"]) > 20) {
        die(json_encode(array("success" => false, "message" => "Bandeira inválida!")));
    }

    if (!isset($data["emissor"]) || strlen($data["emissor"]) < 2 || strlen($data["emissor"]) > 20) {
        die(json_encode(array("success" => false, "message" => "Emissor inválido!")));
    }

    if (!isset($data["ultimos"]) || strlen($data["ultimos"]) != 4 || !is_numeric($data["ultimos"])) {
        die(json_encode(array("success" => false, "message" => "Últimos 4 dígitos inválidos!")));
    }

    if (!isset($data["observacoes"])) {
        die(json_encode(array("success" => false, "message" => "Observações inválida!")));
    }

    $verificaExiste = Database::connection()->prepare("SELECT id FROM cartoes WHERE ultimos_digitos = ? AND user_id = ?");
    $verificaExiste->bindValue(1, $data["ultimos"]);
    $verificaExiste->bindValue(2, $_SESSION["id"]);
    $verificaExiste->execute();

    if ($verificaExiste->rowCount() > 0) {
        die(json_encode(array("success" => false, "message" => "Já existe uma cartão com o mesmo número!")));
    }

    $edita = Database::connection()->prepare("INSERT INTO `cartoes` (`user_id`, `nome`, `emissor`, `ultimos_digitos`, `bandeira`, `observacoes`) VALUES (?, ?, ?, ?, ?, ?);");
    $edita->bindValue(1, $_SESSION["id"]);
    $edita->bindValue(2, $data["nome"]);
    $edita->bindValue(3, $data["emissor"]);
    $edita->bindValue(4, $data["ultimos"]);
    $edita->bindValue(5, $data["bandeira"]);
    $edita->bindValue(6, $data["observacoes"]);
    $edita->execute();

    ActivityLogger::add("Adicionou um cartão final " + $data["ultimos"] + ".");

    die(json_encode(array("success" => true, "message" => "Cartão adicionado com sucesso!", "data" => obterCartoesUsuario()), JSON_PRETTY_PRINT));
}

function apagar($data)
{
    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(array("success" => false, "message" => "Token inválido!")));
    }

    if (!isset($data["id"]) || !is_numeric($data["id"])) {
        die(json_encode(array("success" => false, "message" => "ID inválido!")));
    }

    $verificaExiste = Database::connection()->prepare("SELECT id, ultimos_digitos FROM cartoes WHERE id = ? AND user_id = ?");
    $verificaExiste->bindValue(1, $data["id"]);
    $verificaExiste->bindValue(2, $_SESSION["id"]);
    $verificaExiste->execute();

    if ($verificaExiste->rowCount() != 1) {
        die(json_encode(array("success" => false, "message" => "Cartão inválido!")));
    }

    $cartao = $verificaExiste->fetch();

    ActivityLogger::add("Adicionou um cartão final " + $cartao["ultimos_digitos"] + ".");


    /* INSERIR CÓDIGO PARA REMOVER O CARTAO DOS LANÇAMENTOS */

    $remover = Database::connection()->prepare("DELETE FROM cartoes WHERE id = ?");
    $remover->bindValue(1, $data["id"]);
    $remover->execute();

    die(json_encode(array("success" => true, "message" => "Cartão removido com sucesso!", "data" => obterCartoesUsuario()), JSON_PRETTY_PRINT));
}
