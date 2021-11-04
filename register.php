<?php

require_once("system/global.php");

if (isset($_SESSION['id'])) {
    header("Location: /index");
    return;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Financeiro - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Crie uma conta!</h1>
                            </div>
                            <form class="user" id="register">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" placeholder="Primeiro nome" name="firstname">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" placeholder="Último nome" name="lastname">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 mb-3 mb-sm-0">
                                        <input type="email" class="form-control form-control-user" placeholder="Endereço de email" name="mail">
                                    </div>
                                    <div class="col-sm-2 mb-3 mb-sm-0">
                                        <select class="form-control form-control-user" style="padding: 3px 10px 0px 10px; height: 50px;" name="currency">
                                            <option disabled selected>Moeda</option>
                                            <?php
                                            foreach ($settings["allowed_currencys"] as $name => $simbolo) {
                                                echo '<option value="' . $name . '">' . $name . ' - ' . $simbolo . '</option>' . "\n";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" placeholder="Senha" name="password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" placeholder="Repita sua senha" name="repeat_password">
                                    </div>
                                </div>
                                <input type="hidden" name="csrf-token" value="">
                                <button type="submit" class="btn btn-primary btn-user btn-block" id="botaoRegistrar">
                                    Registrar
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="/register">Crie já sua conta!</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="/login">Já tem uma conta? Faça login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Sistema JS -->
    <script src="js/phb/global.js"></script>
    <script src="js/phb/register.js"></script>

</body>

</html>