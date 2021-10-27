<?php

if (!defined("PHB")) die();

class Database
{

    protected static $dbh;

    private function __construct()
    {
        global $settings;
        try {
            self::$dbh = new PDO("mysql:host=" . $settings['mysql']['host'] . ";charset=utf8;dbname=" . $settings['mysql']['db'], $settings['mysql']['user'], $settings['mysql']['pass']);
        } catch (PDOException $e) {
            Database::ShowErrorPage($e->getMessage());
        }
    }

    public static function connection()
    {
        if (!self::$dbh) new Database();
        return self::$dbh;
    }

    private static function ShowErrorPage(string $error)
    {
        die('<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="MySQL Error">
            <meta name="author" content="https://github.com/pedruhb">
            <title>MySQL Error</title>
            <!-- Custom fonts for this template-->
            <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
            <link
                href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
                rel="stylesheet">
            <!-- Custom styles for this template-->
            <link href="css/sb-admin-2.min.css" rel="stylesheet">
        </head>
        <body class="bg-gradient-primary">
            <div class="container">
                <!-- Outer Row -->
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="card o-hidden border-0 shadow-lg my-5">
                            <div class="card-body p-0">
                                <!-- Nested Row within Card Body -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="p-5">
                                            <div class="text-center">
                                                <h1 class="h4 text-gray-900 mb-4">MySQL Error</h1>
                                            </div>
                                           <p>' . $error . '</p>
                                            <hr>
                                            <div class="text-center">
                                                <a class="small" onclick="document.location.reload(true)" href="#">Atualizar Página</a>
                                            </div>
                                            <div class="text-center">
                                                <a class="small" href="https://github.com/pedruhb/SistemaFinancas/issues/new">Abrir issue</a>
                                            </div>
                                        </div>
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
        </body>
        </html>');
    }
}
