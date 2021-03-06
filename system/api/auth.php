<?php

if (!defined("PHB")) die();

require_once("./system/global.php");

match ($_GET['method']) {
    "login" => login($_POST),
    "registration" => registration($_POST),
    default => die(json_encode(array("success" => false, "message" => "Invalid Method!")))
};

function login($data)
{
    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(array("success" => false, "message" => "Token inválido!")));
    }

    if (!isset($data["mail"])) {
        die(json_encode(array("success" => false, "message" => "Email inválido!")));
    }

    if (!isset($data["password"])) {
        die(json_encode(array("success" => false, "message" => "Senha inválida!")));
    }

    $login = Database::connection()->prepare("SELECT * FROM users WHERE mail = ?");
    $login->bindValue(1, $data["mail"]);
    $login->execute();

    if ($login->rowCount() == 0) {
        die(json_encode(array("success" => false, "message" => "Não há conta nesse email.")));
    }

    $account = $login->fetch();

    if (!password_verify($data["password"], $account["password"])) {
        die(json_encode(array("success" => false, "message" => "Senha inválida!")));
    }

    $atualizarDados = Database::connection()->prepare("UPDATE users SET last_ip = ?, last_time = ? WHERE id = ?");
    $atualizarDados->bindValue(1, getUserIp());
    $atualizarDados->bindValue(2, time());
    $atualizarDados->bindValue(3, $account["id"]);
    $atualizarDados->execute();

    $_SESSION["id"] = $account["id"];
    $_SESSION["userAgent"] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['session_password'] = $account['password'];

    ActivityLogger::add("Logou no painel.");

    die(json_encode(array("success" => true, "message" => "Autenticação realizada com sucesso!", "account" => array("id" => $account["id"], "first_name" => $account["first_name"], "last_name" => $account["last_name"], "mail" => $account["mail"], "currency" => $account["currency"]))));
}

function registration($data)
{
    global $settings;

    if (!isset($data["csrf-token"]) || $data["csrf-token"] != $_COOKIE["csrf-token"]) {
        die(json_encode(array("success" => false, "message" => "Token inválido!")));
    }

    if (!isset($data["mail"]) || strlen($data["mail"]) > 200) {
        die(json_encode(array("success" => false, "message" => "Email inválido!")));
    }

    if (!isset($data["password"]) || strlen($data["password"]) < 5 || strlen($data["password"]) > 30) {
        die(json_encode(array("success" => false, "message" => "Senha inválida!")));
    }

    if (!isset($data["repeat_password"]) || strlen($data["repeat_password"]) < 5 || strlen($data["repeat_password"]) > 30) {
        die(json_encode(array("success" => false, "message" => "Repetir Senha inválida!")));
    }

    if ($data["repeat_password"] != $data["password"]) {
        die(json_encode(array("success" => false, "message" => "As senhas não coincidem!")));
    }

    if (!isset($data["firstname"]) || strlen($data["firstname"]) > 100) {
        die(json_encode(array("success" => false, "message" => "Primeiro nome inválido!")));
    }

    if (!isset($data["lastname"]) || strlen($data["lastname"]) > 100) {
        die(json_encode(array("success" => false, "message" => "Último nome inválido!")));
    }

    if (!isset($data["currency"]) || !array_key_exists($data["currency"], $settings["allowed_currencys"])) {
        die(json_encode(array("success" => false, "message" => "Moeda inválida!")));
    }

    $checkEmailAccounts = Database::connection()->prepare("SELECT id FROM users WHERE mail = ?");
    $checkEmailAccounts->bindValue(1, $data["mail"]);
    $checkEmailAccounts->execute();

    if ($checkEmailAccounts->rowCount() != 0) {
        die(json_encode(array("success" => false, "message" => "Já existe uma conta nesse email!")));
    }

    $insereUsuario = Database::connection()->prepare("INSERT INTO `users` (`mail`, `first_name`, `last_name`, `password`, `last_ip`, `last_time`, `reg_ip`, `reg_time`, `currency`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
    $insereUsuario->bindValue(1, $data["mail"]);
    $insereUsuario->bindValue(2, $data["firstname"]);
    $insereUsuario->bindValue(3, $data["lastname"]);
    $insereUsuario->bindValue(4, password_hash($data["password"], PASSWORD_BCRYPT));
    $insereUsuario->bindValue(5, "");
    $insereUsuario->bindValue(6, "");
    $insereUsuario->bindValue(7, getUserIp());
    $insereUsuario->bindValue(8, time());
    $insereUsuario->bindValue(9, $data["currency"]);
    if ($insereUsuario->execute()) {
        $myId = Database::connection()->lastInsertId();
        $getUserData = Database::connection()->prepare("SELECT * FROM users WHERE id = ? AND mail = ?");
        $getUserData->bindParam(1, $myId);
        $getUserData->bindValue(2, $data["mail"]);
        $getUserData->execute();
        if ($getUserData->rowCount() == 0) {
            die(json_encode(array("success" => false, "message" => "Erro ao inserir usuário! Entre em contato com um administrador.")));
        } else {
            $account = $getUserData->fetch();
            $_SESSION["id"] = $account["id"];
            $_SESSION["userAgent"] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['session_password'] = $account['password'];
            die(json_encode(array("success" => true, "message" => "Conta criada com sucesso!", "account" => array("id" => $account["id"], "first_name" => $account["first_name"], "last_name" => $account["last_name"], "mail" => $account["mail"], "currency" => $account["currency"]))));
        }
    } else {
        die(json_encode(array("success" => false, "message" => "Erro ao inserir usuário! Entre em contato com um administrador.")));
    }
}
