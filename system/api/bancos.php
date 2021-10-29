<?php

if (!defined("PHB")) die();

require_once("./system/global.php");

if (!isset($_SESSION["id"])) {
    die(json_encode(array("success" => false, "message" => "Invalid Session")));
}

match ($_GET["method"]) {
    "get" => get(),
    "listagem" => listagem(),
    "salvar" => salvar($_POST),
    "adicionar" => adicionar($_POST),
    "apagar" => apagar($_POST),
    default => die(json_encode(array("success" => false, "message" => "Invalid Method!")))
};

function obterBancosUsuario()
{
    $get = Database::connection()->prepare("SELECT *, 0 as lancamentos FROM bancos WHERE user_id = ? ORDER BY id DESC");
    $get->bindValue(1, $_SESSION["id"]);
    $get->execute();
    return $get->fetchAll(PDO::FETCH_ASSOC);
}

function get()
{
    die(json_encode(array("success" => true, "data" => obterBancosUsuario()), JSON_PRETTY_PRINT));
}

function listagem()
{
    if (file_exists("./system/data/bancos.json")) {
        $json = json_decode(file_get_contents("./system/data/bancos.json"));
        if (!$json) {
            die(json_encode(array("success" => false, "message" => "Erro ao obter listagem de bancos.")));
        }
        die(json_encode(array("success" => true, "data" => $json)));
    } else {
        die(json_encode(array("success" => false, "message" => "Erro ao obter listagem de bancos.")));
    }
}

function salvar($data)
{

    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(array("success" => false, "message" => "Token inválido!")));
    }

    if (!isset($data["banco"]) || !is_numeric($data["banco"])) {
        die(json_encode(array("success" => false, "message" => "Banco inválido!")));
    }

    if (!isset($data["agencia"])) {
        die(json_encode(array("success" => false, "message" => "Agência inválida!")));
    }

    if (!isset($data["conta"])) {
        die(json_encode(array("success" => false, "message" => "Conta inválida!")));
    }

    if (!isset($data["digito"])) {
        die(json_encode(array("success" => false, "message" => "Dígito inválido!")));
    }

    if (!isset($data["observacoes"])) {
        die(json_encode(array("success" => false, "message" => "Observações inválida!")));
    }

    if (!isset($data["id"]) || !is_numeric($data["id"])) {
        die(json_encode(array("success" => false, "message" => "ID inválido!")));
    }

    $verificaExiste = Database::connection()->prepare("SELECT id FROM bancos WHERE id = ? AND user_id = ?");
    $verificaExiste->bindValue(1, $data["id"]);
    $verificaExiste->bindValue(2, $_SESSION["id"]);
    $verificaExiste->execute();

    if ($verificaExiste->rowCount() != 1) {
        die(json_encode(array("success" => false, "message" => "Conta bancária inválida!")));
    }

    $edita = Database::connection()->prepare("UPDATE bancos SET banco = ?, agencia = ?, conta = ?, digito = ?, observacoes = ? WHERE id = ?");
    $edita->bindValue(1, $data["banco"]);
    $edita->bindValue(2, $data["agencia"]);
    $edita->bindValue(3, $data["conta"]);
    $edita->bindValue(4, $data["digito"]);
    $edita->bindValue(5, $data["observacoes"]);
    $edita->bindValue(6, $data["id"]);
    $edita->execute();

    die(json_encode(array("success" => true, "message" => "Conta bancária editada com sucesso!", "data" => obterBancosUsuario()), JSON_PRETTY_PRINT));
}

function adicionar($data)
{

    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(array("success" => false, "message" => "Token inválido!")));
    }

    if (!isset($data["banco"]) || !is_numeric($data["banco"])) {
        die(json_encode(array("success" => false, "message" => "Banco inválido!")));
    }

    if (!isset($data["agencia"])) {
        die(json_encode(array("success" => false, "message" => "Agência inválida!")));
    }

    if (!isset($data["conta"])) {
        die(json_encode(array("success" => false, "message" => "Conta inválida!")));
    }

    if (!isset($data["digito"])) {
        die(json_encode(array("success" => false, "message" => "Dígito inválido!")));
    }

    if (!isset($data["observacoes"])) {
        die(json_encode(array("success" => false, "message" => "Observações inválida!")));
    }

    $verificaExiste = Database::connection()->prepare("SELECT id FROM bancos WHERE conta = ? AND digito = ? AND user_id = ?");
    $verificaExiste->bindValue(1, $data["conta"]);
    $verificaExiste->bindValue(2, $data["digito"]);
    $verificaExiste->bindValue(3, $_SESSION["id"]);
    $verificaExiste->execute();

    if ($verificaExiste->rowCount() > 0) {
        die(json_encode(array("success" => false, "message" => "Já existe uma conta bancária com os mesmos dados.")));
    }

    $edita = Database::connection()->prepare("INSERT INTO `bancos` (`user_id`, `banco`, `agencia`, `conta`, `digito`, `observacoes`) VALUES (?, ?, ?, ?, ?, ?);");
    $edita->bindValue(1, $_SESSION["id"]);
    $edita->bindValue(2, $data["banco"]);
    $edita->bindValue(3, $data["agencia"]);
    $edita->bindValue(4, $data["conta"]);
    $edita->bindValue(5, $data["digito"]);
    $edita->bindValue(6, $data["observacoes"]);
    $edita->execute();

    die(json_encode(array("success" => true, "message" => "Conta bancária adicionada com sucesso!", "data" => obterBancosUsuario()), JSON_PRETTY_PRINT));
}


function apagar($data)
{
    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(array("success" => false, "message" => "Token inválido!")));
    }

    if (!isset($data["id"]) || !is_numeric($data["id"])) {
        die(json_encode(array("success" => false, "message" => "ID inválido!")));
    }

    $verificaExiste = Database::connection()->prepare("SELECT id FROM bancos WHERE id = ? AND user_id = ?");
    $verificaExiste->bindValue(1, $data["id"]);
    $verificaExiste->bindValue(2, $_SESSION["id"]);
    $verificaExiste->execute();

    if ($verificaExiste->rowCount() != 1) {
        die(json_encode(array("success" => false, "message" => "Conta bancária inválida!")));
    }

    /* INSERIR CÓDIGO PARA REMOVER A CONTA DOS LANÇAMENTOS */

    $remover = Database::connection()->prepare("DELETE FROM bancos WHERE id = ?");
    $remover->bindValue(1, $data["id"]);
    $remover->execute();

    die(json_encode(array("success" => true, "message" => "Conta bancária removida com sucesso!", "data" => obterBancosUsuario()), JSON_PRETTY_PRINT));
}
