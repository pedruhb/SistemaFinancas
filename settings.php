<?php

require_once("system/global.php");

if (!isset($_SESSION['id'])) {
    header("Location: /login");
    return;
}

$selectedPage = "settings";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Financeiro - Configurações</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

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
            <h1 class="h3 mb-2 text-gray-800">Configurações</h1>
            <p class="mb-4">Ajuste as configurações da sua conta.</p>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Minhas configurações</h6>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_POST["save"])) {

                        $changeMail = false;
                        $changePassword = false;
                        $continue = true;

                        if (!isset($_POST["csrf-token"]) || $_POST["csrf-token"] != $_COOKIE["csrf-token"]) {
                            echo '<div class="alert alert-danger" role="alert">CSRF Token inválido!</div>';
                        } else if (!isset($_POST["firstname"]) || strlen($_POST["firstname"]) > 100) {
                            echo '<div class="alert alert-danger" role="alert">Primeiro nome inválido!</div>';
                        } else if (!isset($_POST["lastname"]) || strlen($_POST["lastname"]) > 100) {
                            echo '<div class="alert alert-danger" role="alert">Último nome inválido!</div>';
                        } else if (!isset($_POST["mail"]) || strlen($_POST["mail"]) > 200) {
                            echo '<div class="alert alert-danger" role="alert">Endereço de email inválido!</div>';
                        } else if (!isset($_POST["currency"]) || !array_key_exists($_POST["currency"], $settings["allowed_currencys"])) {
                            echo '<div class="alert alert-danger" role="alert">Moeda inválida!</div>';
                        } else if (!isset($_POST["discord_url"])) {
                            echo '<div class="alert alert-danger" role="alert">Discord URL inválido!</div>';
                        } else if (!isset($_POST["discord_enabled"]) || !in_array($_POST["discord_enabled"], array("true", "false"))) {
                            echo '<div class="alert alert-danger" role="alert">Discord Enabled inválido!</div>';
                        } else {

                            if ($_POST["mail"] != $accountData["mail"]) {
                                $verificaEmail = Database::connection()->prepare("SELECT * FROM users WHERE mail = ?");
                                $verificaEmail->bindValue(1, $_POST["mail"]);
                                $verificaEmail->execute();
                                if ($verificaEmail->rowCount() != 0) {
                                    echo '<div class="alert alert-danger" role="alert">Já existe uma conta cadastrada com o novo email informado!</div>';
                                    $continue = false;
                                } else if (!filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)) {
                                    echo '<div class="alert alert-danger" role="alert">O novo email informado é inválido!</div>';
                                    $continue = false;
                                } else {
                                    $changeMail = true;
                                }
                            } else if (isset($_POST["old_pass"]) && isset($_POST["new_pass"]) && !empty($_POST["old_pass"]) && !empty($_POST["new_pass"])) {
                                if (strlen($_POST["new_pass"]) < 5 || strlen($_POST["new_pass"]) > 30) {
                                    echo '<div class="alert alert-danger" role="alert">A nova senha deve ter entre 6 e 30 caracteres.</div>';
                                    $continue = false;
                                } else if (!password_verify($_POST["old_pass"], $accountData["password"])) {
                                    echo '<div class="alert alert-danger" role="alert">A sua senha antiga está incorreta!</div>';
                                    $continue = false;
                                } else if ($_POST["old_pass"] == $_POST['new_pass']) {
                                    echo '<div class="alert alert-danger" role="alert">A nova senha é a mesma senha atual!</div>';
                                    $continue = false;
                                } else {
                                    $changePassword = true;
                                }
                            }

                            if ($continue) {

                                if ($changeMail) {
                                    $alterarEmail = Database::connection()->prepare("UPDATE users SET mail = ? WHERE id = ?");
                                    $alterarEmail->bindValue(1, $_POST["mail"]);
                                    $alterarEmail->bindValue(2, $_SESSION["id"]);
                                    if ($alterarEmail->execute()) {
                                        ActivityLogger::add("Alterou o email de \"" . $accountData["mail"] . "\" para \"" . $_POST["mail"] . "\" com sucesso.");
                                        echo '<div class="alert alert-success" role="alert">Seu email foi alterado com sucesso!</div>';
                                    } else {
                                        ActivityLogger::add("Tentou alterar o email de \"" . $accountData["mail"] . "\" para \"" . $_POST["mail"] . "\" porém ocorreu um erro.");
                                        echo '<div class="alert alert-danger" role="alert">Houve um erro ao alterar seu email!</div>';
                                    }
                                }

                                if ($changePassword) {
                                    $alterarSenha = Database::connection()->prepare("UPDATE users SET password = ? WHERE id = ?");
                                    $alterarSenha->bindValue(1, password_hash($_POST["new_pass"], PASSWORD_BCRYPT));
                                    $alterarSenha->bindValue(2, $_SESSION['id']);
                                    if ($alterarSenha->execute()) {
                                        ActivityLogger::add("Alterou a senha para \"" . $_POST["new_pass"] . "\" com sucesso.");
                                        echo '<div class="alert alert-success" role="alert">Sua senha foi alterada com sucesso! você precisará reentrar.</div>';
                                    } else {
                                        ActivityLogger::add("Tentou alterar a senha para \"" . $_POST["new_pass"] . "\" porém ocorreu um erro.");
                                        echo '<div class="alert alert-danger" role="alert">Houve um erro ao alterar sua senha!</div>';
                                    }
                                }

                                $alterarDados = Database::connection()->prepare("UPDATE users SET first_name = ?, last_name = ?, currency = ?, discord_log_webhook_url = ?, discord_log_webhook_enabled = ? WHERE id = ?");
                                $alterarDados->bindValue(1, $_POST["firstname"]);
                                $alterarDados->bindValue(2, $_POST["lastname"]);
                                $alterarDados->bindValue(3, $_POST["currency"]);
                                $alterarDados->bindValue(4, $_POST["discord_url"]);
                                $alterarDados->bindValue(5, $_POST["discord_enabled"]);
                                $alterarDados->bindValue(6, $_SESSION['id']);
                                if ($alterarDados->execute()) {
                                    ActivityLogger::add("Fez alterações nas configurações da conta.");
                                    echo '<div class="alert alert-success" role="alert">Seus dados foram alterados com sucesso!</div>';
                                } else {
                                    ActivityLogger::add("Houve um erro ao realizar alterações nas configurações da conta.");
                                    echo '<div class="alert alert-danger" role="alert">Houve um erro ao realizar alterações nas configurações da conta!</div>';
                                }

                                $getUser = Database::connection()->prepare("SELECT * FROM users WHERE id = ?");
                                $getUser->bindValue(1, $_SESSION['id']);
                                $getUser->execute();
                                if ($getUser->rowCount() == 0) {
                                    header("Location: /logout");
                                    return;
                                } else {
                                    $accountData = $getUser->fetch();
                                    if ($accountData["password"] != $_SESSION['session_password']) {
                                        header("Location: /logout");
                                        return;
                                    }
                                }
                            }
                        }
                    }
                    ?>

                    <form method="post">
                        <div class="row">
                            <div class="form-group col">
                                <label for="exampleInputEmail1">Primeiro Nome</label>
                                <input type="text" class="form-control" name="firstname" value="<?= $accountData["first_name"]; ?>">
                            </div>
                            <div class="form-group col">
                                <label for="exampleInputEmail1">Último Nome</label>
                                <input type="text" class="form-control" name="lastname" value="<?= $accountData["last_name"]; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label for="exampleInputEmail1">Endereço de Email</label>
                                <input type="email" class="form-control" name="mail" value="<?= $accountData["mail"]; ?>">
                                <small class="form-text text-muted">Use um endereço válido, ele pode ser útil para recuperar seu acesso.</small>
                            </div>
                            <div class="form-group col-3">
                                <label for="exampleInputEmail1">Moeda</label>
                                <select class="form-control" name="currency">
                                    <?php
                                    foreach ($settings["allowed_currencys"] as $name => $simbolo) {
                                        echo '<option value="' . $name . '"' . ($accountData["currency"] == $name ? " selected" : "") . '>' . $name . ' - ' . $simbolo . '</option>' . "\n";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col">
                                <label for="exampleInputEmail1">Link Discord Webhook</label>
                                <input type="text" class="form-control" name="discord_url" value="<?= $accountData["discord_log_webhook_url"]; ?>">
                                <small class="form-text text-muted">Caso ative essa opção, você receberá notificações no Discord sempre que alguma ação for realizada no painel.</small>
                            </div>
                            <div class="form-group col-4 form-check">
                                <label for="exampleInputEmail1">Integração com Discord</label>
                                <select class="form-control" name="discord_enabled">
                                    <option value="true" <?= $accountData["discord_log_webhook_enabled"] == "true" ? "selected" : ""; ?>>Ativado</option>
                                    <option value="false" <?= $accountData["discord_log_webhook_enabled"] == "true" ? "" : "selected"; ?>>Desativado</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col">
                                <label for="exampleInputEmail1">Senha Antiga</label>
                                <input type="password" class="form-control" name="old_pass">
                                <small class="form-text text-muted">Preencha somente se quiser alterar a sua senha</small>
                            </div>
                            <div class="form-group col">
                                <label for="exampleInputEmail1">Nova Senha</label>
                                <input type="password" class="form-control" name="new_pass">
                                <small class="form-text text-muted">Ao alterar, você receberá um email com a nova senha.</small>
                            </div>
                        </div>
                        <input type="hidden" name="csrf-token" value="">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-primary" type="submit" name="save">Salvar Alterações</button>
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

    <!-- Sistema JS -->
    <script src="js/phb/global.js"></script>

</body>

</html>