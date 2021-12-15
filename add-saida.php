<?php

require_once("system/global.php");

if (!isset($_SESSION['id'])) {
    header("Location: /login");
    return;
}

$selectedPage = "add-saida";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Financeiro - Cadastrar Saída</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php require_once("system/includes/menu.php"); ?>

        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Cadastrar Saída</h1>
            <p class="mb-4">Adicione uma saída em sua situação financeira</p>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cadastrar Saída</h6>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="form-group col">
                                <label for="exampleInputEmail1">Nome</label>
                                <input type="text" class="form-control" name="nome" placeholder="Exemplo: Conta de Luz">
                            </div>
                            <div class="form-group col">
                                <label for="exampleInputEmail1">Valor</label>
                                <input type="text" class="form-control money" name="valor" value="<?= $accountData["last_name"]; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-3">
                                <label for="exampleInputEmail1">Data</label>
                                <input type="text" class="form-control date" name="data" placeholder="__/__/____" value="<?= date('d/m/Y', time()); ?>">
                            </div>
                            <div class="form-group col-4">
                                <label for="exampleInputEmail1">Categoria</label>
                                <select class="form-control" name="categoria">
                                    <option value="0">Sem categoria</option>
                                    <?php
                                    $getCategorias = Database::connection()->prepare("SELECT * FROM categorias_gastos WHERE user_id = ?");
                                    $getCategorias->bindValue(1, $_SESSION['id']);
                                    $getCategorias->execute();
                                    while ($categoria = $getCategorias->fetch()) {
                                        echo '<option value="' . $categoria["id"] . '">' . htmlspecialchars($categoria["nome"]) . '</option>' . "\n";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-5">
                                <label for="exampleInputEmail1">Banco</label>
                                <select class="form-control" name="banco">
                                    <option value="0">Sem banco</option>
                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Observações</label>
                            <textarea class="form-control" name="observacoes"></textarea>
                        </div>
                        <input type="hidden" name="csrf-token" value="">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-primary" type="submit" name="save" id="botao">Cadastrar</button>
                        </div>
                    </form>
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
    <script src="js/moment-with-locales.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Sistema JS -->
    <script src="js/phb/global.js"></script>
    <script src="js/phb/add-saida.js"></script>

    <script src="js/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.date').mask('00/00/0000');
            $('.money').mask('000.000.000.000.000,00', {
                reverse: true
            });
        });
    </script>

</body>

</html>