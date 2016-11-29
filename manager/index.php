<?php
ob_start();
session_start();
require('../_app/Config.inc.php');
?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="mit" content="0017379">
        <title>Espaço da Bixarada - ht2Control</title>

        <link rel="stylesheet" href="../res/bootstrap/css/bootstrap.css" />
        <script type="text/javascript" src="../res/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="../res/bootstrap/js/bootstrap.js"></script>

        <link rel="stylesheet" href="css/admin.css" />

    </head>
    <body class="login">
    <!--
    <header class="row">
        <div class="col-lg-12">
            <img id="" src="images/siforme-logo-300.png">
        </div>
    </header>


    <nav class="navbar navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#"><img id="" src="images/siforme-logo-300.png" style="height: 40px;"></a>
            </div>
        </div>
    </nav>

    -->

    <div class="container">
        <div class="row" style="margin-top: 100px;">
            <div class="col-md-4 col-md-offset-4">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="text-align: center;"><img id="" src="images/siforme-logo-300.png" style="height: 170px;"></h3>
                    </div>

                    <div class="panel-body login">
                        <?php



                        $login = new Login(3);
                        if ($login->CheckLogin()):
                            header('Location: painel.php');
                        endif;
                        $dataLogin = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                        if (!empty($dataLogin['AdminLogin'])):

                            $login->ExeLogin($dataLogin);
                            if (!$login->getResult()):
                                WSErro($login->getError()[0], $login->getError()[1]);
                            else:
                                header('Location: painel.php');
                            endif;
                        endif;
                        $get = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
                        if (!empty($get)):
                            if ($get == 'restrito'):
                                WSErro('<b>Oppsss:</b> Acesso negado. Favor efetue login para acessar o painel!', WS_ALERT);
                            elseif ($get == 'logoff'):
                                WSErro('<b>Sucesso ao deslogar:</b> Sua sessão foi finalizada. Volte sempre!', WS_ACCEPT);
                            endif;
                        endif;
                        ?>
                        <form name="AdminLoginForm" action="" method="post" accept-charset="UTF-8" role="form">
                            <fieldset>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input class="form-control" placeholder="E-mail" name="user" type="text">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input class="form-control" placeholder="Senha" name="pass" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me"> Lembrar neste dispositivo
                                    </label>
                                </div>
                                <input class="btn btn-lg btn-success btn-block" type="submit" name="AdminLogin" value="Login">
                            </fieldset>

                        </form>

                    </div>
                    <div class="panel-footer" style="margin-top: 10px;">
                        <div>
                            Esqueceu sua senha?
                            <a href="#" onclick="$('#loginbox').hide(); $('#signupbox').show()">
                                Clique aqui!
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        alertRemove = setTimeout(function () {
            $(".alert").slideUp();
            clearTimeout(alertRemove);
        }, 5000);
    </script>
    </body>
    </html>

<?php
ob_end_flush();