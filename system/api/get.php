<?php

if (!defined("PHB")) die();

require_once("./system/global.php");

if (!isset($_SESSION["id"])) {
    die(json_encode(["success" => false, "message" => "Invalid Session"]));
}

match ($_GET["method"]) {
    "activity" => activity(),
    "indexData" => indexData(),
    default => die(json_encode(["success" => false, "message" => "Invalid Method!"]))
};

function activity()
{
    $get = Database::connection()->prepare("SELECT log, ip, timestamp FROM activity_log WHERE user_id = ? ORDER BY timestamp DESC");
    $get->bindValue(1, $_SESSION["id"]);
    $get->execute();
    die(json_encode(["data" => $get->fetchAll(PDO::FETCH_ASSOC)], JSON_PRETTY_PRINT));
}

function indexData()
{
    global $accountData;
    global $settings;
    die(json_encode(["success" => true, "currency" => $accountData["currency"], "currency_simbol" => $settings["allowed_currencys"][$accountData["currency"]], "chart" => chartData(), "pie" => pieData()], JSON_PRETTY_PRINT));
}

function chartData()
{
    $data = [];
    for ($i = 1; $i <= 12; $i++) {
        $get = Database::connection()->prepare("SELECT (SELECT SUM(valor) FROM ganhos WHERE user_id = ? AND from_unixtime(data, \"%Y-%m\") = ?) AS total_ganhos, (SELECT SUM(valor) FROM gastos WHERE user_id = ? AND from_unixtime(data, \"%Y-%m\") = ?) AS total_gastos");
        $get->bindValue(1, $_SESSION["id"]);
        $get->bindValue(2, date("Y") . "-" . $i);
        $get->bindValue(3, $_SESSION["id"]);
        $get->bindValue(4, date("Y") . "-" . $i);
        $get->execute();
        $fetch = $get->fetch();
        $ganhos = $fetch["total_ganhos"] > 0 ? $fetch["total_ganhos"] : 0;
        $gastos = $fetch["total_gastos"] > 0 ? $fetch["total_gastos"] : 0;
        array_push($data, ["mes" => $i, "ganhos" => $ganhos, "gastos" => $gastos]);
    }
    return $data;
}

function pieData()
{
    $gastos = [];
    $ganhos = [];

    /// Gastos
    $getCategoriasGastos = Database::connection()->prepare("SELECT id, nome, cor_hex FROM categorias_gastos WHERE user_id = ?");
    $getCategoriasGastos->bindValue(1, $_SESSION["id"]);
    $getCategoriasGastos->execute();
    while ($categoriaGasto = $getCategoriasGastos->fetch()) {
        $getValorGastos = Database::connection()->prepare("SELECT COUNT(id) as registros, SUM(valor) as valor FROM gastos WHERE user_id = ? AND categoria_id = ? AND from_unixtime(data, \"%Y-%m\") = ?");
        $getValorGastos->bindValue(1, $_SESSION["id"]);
        $getValorGastos->bindValue(2, $categoriaGasto["id"]);
        $getValorGastos->bindValue(3, date('Y-m'));
        $getValorGastos->execute();
        $fetch = $getValorGastos->fetch();
        $registros = $fetch["registros"];
        $getValorGastosF = $fetch["valor"];
        $valor = $getValorGastosF > 0 ? $getValorGastosF : 0;
        array_push($gastos, ["nome" => $categoriaGasto["nome"], "cor" => $categoriaGasto["cor_hex"], "valor" => $valor, "registros" => $registros]);
    }

    /// Gastos sem categoria
    $getValorGastos = Database::connection()->prepare("SELECT COUNT(id) as registros, SUM(valor) as valor FROM gastos WHERE user_id = ? AND categoria_id = ? AND from_unixtime(data, \"%Y-%m\") = ?");
    $getValorGastos->bindValue(1, $_SESSION["id"]);
    $getValorGastos->bindValue(2, 0);
    $getValorGastos->bindValue(3, date('Y-m'));
    $getValorGastos->execute();
    $fetch = $getValorGastos->fetch();
    $registros = $fetch["registros"];
    $getValorGastosF = $fetch["valor"];
    $valor = $getValorGastosF > 0 ? $getValorGastosF : 0;
    array_push($gastos, ["nome" => "Sem Categoria", "cor" => "grey", "valor" => $valor, "registros" => $registros]);

    /// Ganhos
    $getCategoriasGanhos = Database::connection()->prepare("SELECT id, nome, cor_hex FROM categorias_ganhos WHERE user_id = ?");
    $getCategoriasGanhos->bindValue(1, $_SESSION["id"]);
    $getCategoriasGanhos->execute();
    while ($categoriaGanho = $getCategoriasGanhos->fetch()) {
        $getValorGanhos = Database::connection()->prepare("SELECT COUNT(id) as registros, SUM(valor) as valor FROM ganhos WHERE user_id = ? AND categoria_id = ? AND from_unixtime(data, \"%Y-%m\") = ?");
        $getValorGanhos->bindValue(1, $_SESSION["id"]);
        $getValorGanhos->bindValue(2, $categoriaGanho["id"]);
        $getValorGanhos->bindValue(3, date('Y-m'));
        $getValorGanhos->execute();
        $fetch = $getValorGanhos->fetch();
        $getValorGanhosF = $fetch["valor"];
        $registros = $fetch["registros"];
        $valor = $getValorGanhosF > 0 ? $getValorGanhosF : 0;
        array_push($ganhos, ["nome" => $categoriaGanho["nome"], "cor" => $categoriaGanho["cor_hex"], "valor" => $valor, "registros" => $registros]);
    }

    /// Ganhos sem categoria
    $getValorGanhos = Database::connection()->prepare("SELECT COUNT(id) as registros, SUM(valor) as valor FROM ganhos WHERE user_id = ? AND categoria_id = ? AND from_unixtime(data, \"%Y-%m\") = ?");
    $getValorGanhos->bindValue(1, $_SESSION["id"]);
    $getValorGanhos->bindValue(2, 0);
    $getValorGanhos->bindValue(3, date('Y-m'));
    $getValorGanhos->execute();
    $fetch = $getValorGanhos->fetch();
    $getValorGanhosF = $fetch["valor"];
    $registros = $fetch["registros"];
    $valor = $getValorGanhosF > 0 ? $getValorGanhosF : 0;
    array_push($ganhos, ["nome" => "Sem Categoria", "cor" => "grey", "valor" => $valor, "registros" => $registros]);

    return ["ganhos" => $ganhos, "gastos" => $gastos];
}
