<?php

require_once("system/global.php");

if (!isset($_SESSION['id'])) {
    header("Location: /login");
    return;
}

$selectedPage = "dashboard";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Financeiro - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php require_once("system/includes/menu.php"); ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Gerar Relatório</a>
            </div>

            <!-- Content Row -->
            <div class="row">
                <?php
                $pegaGanhosMensal = Database::connection()->prepare("SELECT SUM(valor) AS total FROM ganhos WHERE user_id = ? AND from_unixtime(data, \"%Y-%m\") = ?");
                $pegaGanhosMensal->bindValue(1, $_SESSION["id"]);
                $pegaGanhosMensal->bindValue(2, date("Y-m"));
                $pegaGanhosMensal->execute();
                $totalGanhosMensal = $pegaGanhosMensal->fetch()["total"];

                $pegaGanhosAnual = Database::connection()->prepare("SELECT SUM(valor) AS total FROM ganhos WHERE user_id = ? AND from_unixtime(data, \"%Y\") = ?");
                $pegaGanhosAnual->bindValue(1, $_SESSION["id"]);
                $pegaGanhosAnual->bindValue(2, date("Y"));
                $pegaGanhosAnual->execute();
                $totalGanhosAnual = $pegaGanhosAnual->fetch()["total"];

                $pegaGastosMensal = Database::connection()->prepare("SELECT SUM(valor) AS total FROM gastos WHERE user_id = ? AND from_unixtime(data, \"%Y-%m\") = ?");
                $pegaGastosMensal->bindValue(1, $_SESSION["id"]);
                $pegaGastosMensal->bindValue(2, date("Y-m"));
                $pegaGastosMensal->execute();
                $totalGastosMensal = $pegaGastosMensal->fetch()["total"];

                $pegaGastosAnual = Database::connection()->prepare("SELECT SUM(valor) AS total FROM gastos WHERE user_id = ? AND from_unixtime(data, \"%Y\") = ?");
                $pegaGastosAnual->bindValue(1, $_SESSION["id"]);
                $pegaGastosAnual->bindValue(2, date("Y"));
                $pegaGastosAnual->execute();
                $totalGastosAnual = $pegaGastosAnual->fetch()["total"];

                ?>
                <!-- Ganhos mensal -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Ganhos (Mensal)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $settings["allowed_currencys"][$accountData["currency"]]; ?> <?= $totalGanhosMensal > 0 ? $totalGanhosMensal : 0; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ganhos anual -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Ganhos (Anual)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $settings["allowed_currencys"][$accountData["currency"]]; ?> <?= $totalGanhosAnual > 0 ? $totalGanhosAnual : 0; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gastos mensal -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Gastos (Mensal)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $settings["allowed_currencys"][$accountData["currency"]]; ?> <?= $totalGastosMensal > 0 ? $totalGastosMensal : 0; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Gastos anual -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Gastos (Anual)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $settings["allowed_currencys"][$accountData["currency"]]; ?> <?= $totalGastosAnual > 0 ? $totalGastosAnual : 0; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->

            <div class="row">

                <!-- Area Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Visão geral dos ganhos</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="chartGanhos"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Fontes de receita</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="pieGanhos"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                <?php
                                $getCategorias = Database::connection()->prepare("SELECT * FROM categorias_ganhos WHERE user_id = ?");
                                $getCategorias->bindValue(1, $_SESSION['id']);
                                $getCategorias->execute();
                                while ($categoria = $getCategorias->fetch()) {
                                    echo '<span class="mr-2"><i class="fas fa-circle text-primary" style="color:' . htmlspecialchars($categoria["cor_hex"]) . '!important"></i> ' . htmlspecialchars($categoria["nome"]) . '</span>' . "\n";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">

                <!-- Area Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-danger">Visão geral dos gastos</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="chartGastos"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-danger">Fontes de gastos</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="pieGastos"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                <?php
                                $getCategorias = Database::connection()->prepare("SELECT * FROM categorias_gastos WHERE user_id = ?");
                                $getCategorias->bindValue(1, $_SESSION['id']);
                                $getCategorias->execute();
                                while ($categoria = $getCategorias->fetch()) {
                                    echo '<span class="mr-2"><i class="fas fa-circle text-primary" style="color:' . htmlspecialchars($categoria["cor_hex"]) . '!important"></i> ' . htmlspecialchars($categoria["nome"]) . '</span>' . "\n";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <?php require_once("system/includes/footer.php"); ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/phb/index.js"></script>

</body>

</html>