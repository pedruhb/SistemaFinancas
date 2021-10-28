<?php

require_once("system/global.php");

if (!isset($_SESSION['id'])) {
    header("Location: /login");
    return;
}

if (!isset($_GET['type']) || !in_array($_GET["type"], array("ganhos", "gastos"))) {
    header("Location: /index");
    return;
}

$selectedPage = "categorias_" . strtolower($_GET['type']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="https://github.com/pedruhb">

    <title>Financeiro - Categorias</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php require_once("system/includes/menu.php"); ?>

        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Categorias</h1>
            <p class="mb-4">Confira, adicione e edite suas categorias de <?= strtolower($_GET['type']); ?>.</p>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">


                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Categorias de <?= strtolower($_GET['type']); ?></h6>
                    <div class="dropdown no-arrow">
                        <button type="button" class="btn btn-primary" title="Verificar lançamentos" onclick="addCategoria()"><i class="fas fa-plus"></i> Adicionar Categoria</button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="categorias" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nome da Categoria</th>
                                    <th>Cor</th>
                                    <th>Lançamentos</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Nome da Categoria</th>
                                    <th>Cor</th>
                                    <th>Lançamentos</th>
                                    <th>Ações</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <?php require_once("system/includes/footer.php"); ?>

    <!-- Editar Modal-->
    <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editando Categoria</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="post" id="editar">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nome" placeholder="Nome da categoria">
                        </div>
                        <div class="form-group">
                            <label>Cor</label>
                            <input type="color" class="form-control" name="cor">
                        </div>
                        <input type="hidden" name="id">
                        <input type="hidden" name="csrf-token" value="">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary" type="submit" id="botaoEditar">Salvar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Adicionar Modal-->
    <div class="modal fade" id="adicionarModal" tabindex="-1" role="dialog" aria-labelledby="adicionarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adicionarModalLabel">Adicionar Categoria</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="post" id="adicionar">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nome" placeholder="Nome da categoria">
                        </div>
                        <div class="form-group">
                            <label>Cor</label>
                            <input type="color" class="form-control" name="cor">
                        </div>
                        <input type="hidden" name="csrf-token" value="">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary" type="submit" id="botaoAdicionar">Adicionar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Apagar Modal-->
    <div class="modal fade" id="apagarModal" tabindex="-1" role="dialog" aria-labelledby="apagarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="apagarModalLabel">Apagar</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Tem certeza que deseja apagar essa categoria? Caso você apague, todos os lançamentos agregados à essa categoria ficarão listados como "Sem Categoria".</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <form method="post" id="apagar">
                        <input type="hidden" name="csrf-token" value="">
                        <input type="hidden" name="id">
                        <button class="btn btn-primary" type="submit">Apagar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.min.js"></script>
    <script src="/js/moment-with-locales.min.js"></script>

    <!-- Page level plugins -->
    <script src="/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Sistema JS -->
    <script src="/js/phb/global.js"></script>
    <script src="/js/phb/categorias_<?= strtolower($_GET['type']); ?>.js"></script>

</body>

</html>