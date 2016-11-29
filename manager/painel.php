<?php
ob_start();
session_start();
require('../_app/Config.inc.php');

$login = new Login(3);
$logoff = filter_input(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);
$getexe = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);

if (!$login->CheckLogin()):
    unset($_SESSION['userlogin']);
    header('Location: index.php?exe=restrito');
else:
    $userlogin = $_SESSION['userlogin'];
endif;

if ($logoff):
    unset($_SESSION['userlogin']);
    header('Location: index.php?exe=logoff');
endif;

//ATIVA MENU
if (isset($getexe)):
    $linkto = explode('/', $getexe);
else:
    $linkto = array();
endif;


?>
    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <title>Espaço da Bixarada Manager</title>

        <!-- BOOTSTRAP JQUERY -->
        <link rel="stylesheet" href="../res/bootstrap/css/bootstrap.css"/>
        <script type="text/javascript" src="../res/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="../res/bootstrap/js/bootstrap.js"></script>

        <!-- CSS -->
        <link rel="stylesheet" href="css/reset.css"/>
        <link rel="stylesheet" href="css/admin.css"/>
        <link rel="stylesheet" href="css/timeline.css"/>
        <link rel="stylesheet" href="css/profile.css"/>

        <!-- Data Table -->
        <link rel="stylesheet" type="text/css" href="../res/datatable/css/dataTables.bootstrap.css">
        <script type="text/javascript" charset="utf8" src="../res/datatable/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="../res/datatable/js/dataTables.bootstrap.js"></script>


        <!-- BOOTSTRAP DIALOG -->
        <link href="../res/bootstrap3-dialog/css/bootstrap-dialog.css" rel="stylesheet" type="text/css" />
        <script src="../res/bootstrap3-dialog/js/bootstrap-dialog.js"></script>

        <!-- JQUERY UI -->
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>


        <!-- BOOTSTRAP SWITCH BUTTON CSS -->
        <link href="../res/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">

        <!-- BOOTSTRAP ADD CLEAR -->
        <script src="../res/bootstrap-add-clear/bootstrap-add-clear.min.js"></script>


    </head>

    <body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Menu Mobile</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img id="" src="images/siforme-logo-300.png" style="height: 40px;"></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="divider-vertical"></li>
                    <li class="dropdown <?php if (in_array('agenda', $linkto)) echo ' active'; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false"><i class="glyphicon glyphicon-list-alt"></i> Agenda <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="glyphicon glyphicon-ok"></i> Novo Agendamento</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#"><i class="glyphicon glyphicon-pencil"></i> Gerenciar Agendamentos</a></li>
                        </ul>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown <?php if (in_array('cliente', $linkto) || in_array('animal', $linkto)) echo ' active'; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false"><i class="glyphicon glyphicon-user"></i> Clientes <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="?exe=cliente/create"><i class="glyphicon glyphicon-ok"></i> Novo</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="?exe=cliente/index"><i class="glyphicon glyphicon-pencil"></i> Gerenciar</a>
                            <li role="separator" class="divider"></li>
                            <li><a href="?exe=animal/index"><i class="glyphicon glyphicon-pencil"></i> Animais</a>
                            </li>
                        </ul>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown <?php if (in_array('servico', $linkto)) echo ' active'; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false"><i class="glyphicon glyphicon-briefcase"></i> Serviços <span
                                class="caret"></span></a>

                        <ul class="dropdown-menu">
                            <li class="dropdown-header"><i class="glyphicon glyphicon-chevron-right"></i> Serviços
                            </li>
                            <li><a href="#"><i class="glyphicon glyphicon-ok"></i> Criar</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#"><i class="glyphicon glyphicon-pencil"></i> Listar</a></li>
                            <li role="separator" class="divider"></li>

                        </ul>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown <?php if (in_array('relatorio', $linkto)) echo ' active'; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false"><i class="glyphicon glyphicon-object-align-bottom"></i> Relatórios
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="glyphicon glyphicon-ok"></i> Rel Tipo 1</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#"><i class="glyphicon glyphicon-pencil"></i> Lista de Formandos</a></li>
                        </ul>
                    </li>
                    <li class="divider-vertical"></li>
                    <li class="dropdown <?php if (in_array('usuario', $linkto) || in_array('raca', $linkto) || in_array('tipoanimal', $linkto)) echo ' active'; ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false"><i class="glyphicon glyphicon-cog"></i> Admin <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu">

                            <li><a href="?exe=tipoanimal/index"><i class="glyphicon glyphicon-pencil"></i> Tipo de Animal</a></li>
                            <li role="separator" class="divider"></li>

                            <li><a href="?exe=raca/index"><i class="glyphicon glyphicon-pencil"></i> Raças</a></li>
                            <li role="separator" class="divider"></li>

                            <li><a href="?exe=usuario/index"><i class="glyphicon glyphicon-pencil"></i> Usuários</a></li>


                        </ul>
                    </li>
                    <li class="divider-vertical"></li>
                </ul>


                <!-- DADOS USER -->
                <ul class="nav navbar-nav navbar-right">
                    <li class="divider-vertical"></li>
                    <li class="dropdown logo-avatar">
                        <?php
                        if (Check::image("../".$_SESSION['userlogin']['user_img'], "")):
                            $AvatarImg = Check::image("../".$_SESSION['userlogin']['user_img'], "Avatar ".$_SESSION['userlogin']['user_name'], 50);
                        else:
                            $AvatarImg = '<img src="'.HOME.'/manager/images/default-user-avatar.jpg" style="width: 50px;">';
                        endif;
                        ?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false" style="padding: 0 10px 0 0">
                            <?=$AvatarImg?>
                            <?= $userlogin['user_name']; ?>
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="?exe=usuario/update">Meus Dados</a></li>
                            <li><a href="#">Trocar Senha</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="?logoff=true">Sair</a></li>

                        </ul>
                    </li>
                    <li class="divider-vertical"></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>

    <div class="container">
        <?php
        //QUERY STRING
        if (!empty($getexe)):
            $includepatch = __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . strip_tags(trim($getexe) . '.php');
        else:
            $includepatch = __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'home.php';
        endif;

        if (file_exists($includepatch)):
            require_once($includepatch);
        else:
            $exeexplode = explode("/", $getexe);
            if (strlen($exeexplode[0]) > 0):
                $includepatch = __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . strip_tags(trim($exeexplode[0]) . '/index.php');
                if (file_exists($includepatch)):
                    require_once($includepatch);
                else:
                    echo "<div class=\"content notfound\">";
                    WSErro("<b>Erro ao incluir tela:</b> Erro ao incluir o controller /{$getexe}.php!", WS_ERROR);
                    echo "</div>";
                endif;
            else:
                echo "<div class=\"content notfound\">";
                WSErro("<b>Erro ao incluir tela:</b> Erro ao incluir o controller /{$getexe}.php!", WS_ERROR);
                echo "</div>";
            endif;;
        endif;
        ?>
    </div>


    <footer class="footer">
        <div class="container">
            <div class="row">
                <p class="text-muted left">ESPAÇO DA BIXARADA &copy; 2016</p>
                <p class="text-muted right">Desenvolvimento: ht2ml</p>
            </div>
        </div>
    </footer>
    <script>
        alertRemove = setTimeout(function () {
            $(".alert-fadeout").slideUp();
            clearTimeout(alertRemove);
        }, 10000);
    </script>



    <script src="../_cdn/jmask.js"></script>
    <script src="../_cdn/combo.js"></script>
    <script src="__jsc/tiny_mce/tiny_mce.js"></script>
    <script src="__jsc/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>
    <script src="__jsc/admin.js"></script>
    <script src="js/app.js"></script>

    <!-- BOOTSTRAP SWITCH BUTTON JS -->

    <script src="../res/bootstrap-switch-master/dist/js/bootstrap-switch.js"></script>

    </body>
    </html>
<?php
ob_end_flush();
