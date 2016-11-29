<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;


//var_dump($_SESSION);
require_once('_models/AdminOrcamento.class.php');
$List = new AdminOrcamento();
$DadosOrcamento = $List->ListData();

$List->getQtIntegranteComissao();

//var_dump($DadosOrcamento);
?>
<style>
    .maginXY {
        margin: 10px 0;
    }

    .ButtomCustom:hover {
        opacity: 0.6;
    }
</style>

<div class="row">

    <!--
    <div class="col-lg-1 col-xs-1">
        <a href=""> <i class="glyphicon glyphicon-arrow-left" style="font-size: 30px; color: #999b9e"></i> </a>
    </div>
    -->
    <div class="col-lg-12 col-xs-12 text-center maginXY">
        <ol class="breadcrumb" style="margin-bottom: 5px;">
            <li class="active">Orçamento</li>
        </ol>
    </div>
    <!--
    <div class="col-lg-1 col-xs-1">
        <a href=""><i class="glyphicon glyphicon-arrow-right" style="font-size: 30px; color: #999b9e"></i></a>
    </div>
    -->
</div>

<?php
Message::FlashMsg("AlertMsg");
?>

<div class="row">

    <!-- painel configurações -->
    <div class="col-lg-5 col-md-4 col-sm-12 col-xs-12 toopad">

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Sheena Kristin A.Eschor</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 col-lg-3" style="border-right: 1px solid #d9d9d9;" align="center">
                        <img alt="User Pic" src="images/contratos/1140.jpg" class="img-circle img-thumbnail"
                             style="margin-bottom: 5px; width: 100px;">
                        <img alt="User Pic" src="images/contratos/1141.jpg" class="img-circle img-thumbnail"
                             style="margin-bottom: 5px; width: 100px;">
                        <img alt="User Pic" src="images/contratos/1142.jpg" class="img-circle img-thumbnail"
                             style="margin-bottom: 5px; width: 100px;">
                    </div>

                    <div class=" col-md-9 col-lg-9 ">
                        <table class="table table-user-information">
                            <tbody>
                            <tr>
                                <td>Nome:</td>
                                <td><?php echo $DadosOrcamento['orcamento_nome']; ?></td>
                            </tr>
                            <tr>
                                <td>Observações:</td>
                                <td><?php echo $DadosOrcamento['orcamento_obs']; ?></td>
                            </tr>
                            <tr>
                                <td>Vencimento:</td>
                                <td>30/08/2017</td>
                            </tr>

                            <tr>
                            <tr>
                                <td>Tipos de Eventos:</td>
                                <td>
                                    <ul>
                                        <li>Formatura</li>
                                        <li>Colação de Graú</li>
                                    </ul>
                                </td>
                            </tr>

                            <tr>
                                <td>Valor Total do Projeto:</td>
                                <td>R$ 565.565,23</td>
                            </tr>
                            <tr>
                                <td>Valor Total por Formando:</td>
                                <td>R$ 3.234,23</td>
                            </tr>
                            <tr>
                                <td>Parcelamento:</td>
                                <td>Até 10X R$ 323,42</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar"
                                 aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:69%">
                                69%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-warning active" role="progressbar"
                                 aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:85%">
                                85%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-danger active" role="progressbar"
                                 aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:105%">
                                105%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <a data-original-title="Broadcast Message" id="myBtn" type="button" class="btn btn-sm btn-primary"><i
                        class="glyphicon glyphicon-envelope"></i> Enviar e-mail para comissão. </a> <span style="color: #666666; padding: 5px;"> </span>
            </div>

        </div>
    </div>

    <div class="col-lg-7 col-md-8 col-sm-12 col-xs-12">

        <div class="col-md-3 col-sm-4 col-xs-6">
            <a href="?exe=orcamento/config" class="btn btn-sq-lg btn-info ButtomCustom">
                <br>
                <i class="fa fa-cog fa-5x"></i><br/>
                <br> Configurações
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6">
            <a href="?exe=orcamento/comissao" class="btn btn-sq-lg btn-success ButtomCustom">
                <br>
                <i class="fa fa-group fa-5x"></i><br/>
                <br> Comissões
            </a>
        </div>

        <div class="col-md-3  col-sm-4 col-xs-6">
            <a href="?exe=orcamento/tipoevento" class="btn btn-sq-lg btn-warning ButtomCustom">
                <br>
                <i class="fa fa-flag-o fa-5x"></i><br/>
                <br> Tipos Eventos
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6">
            <a href="?exe=orcamento/album" class="btn btn-sq-lg ButtomCustom"
               style="background: #9932CC !important; color: white !important;">
                <br>
                <i class="fa fa-book fa-5x"></i><br/>
                <br> Álbuns
            </a>
        </div>

        <div class="col-md-3  col-sm-4 col-xs-6">
            <a href="?exe=orcamento/local" class="btn btn-sq-lg ButtomCustom"
               style="background: #0e76a8 !important; color: white !important;">
                <br>
                <i class="fa fa-university fa-5x"></i><br/>
                <br> Local
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6">
            <a href="?exe=orcamento/bar_tpev" class="btn btn-sq-lg ButtomCustom"
               style="background: #68838B !important; color: white !important;">
                <br>
                <i class="fa fa-beer fa-5x"></i><br/>
                <br> Bar
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6">
            <a href="?exe=orcamento/produtos" class="btn btn-sq-lg ButtomCustom"
               style="background: #FF8247 !important; color: white !important;">
                <br>
                <i class="fa fa-pie-chart fa-5x"></i><br/>
                <br> Produtos
            </a>
        </div>



        <div class="col-md-3 col-sm-4 col-xs-6">
            <a href="?exe=orcamento/brindes" class="btn btn-sq-lg ButtomCustom"
               style="background: #FF4500 !important; color: white !important;">
                <br>
                <i class="fa fa-gift fa-5x"></i><br/>
                <br> Brindes
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6">
            <a href="?exe=orcamento/detalhes" class="btn btn-sq-lg ButtomCustom"
               style="background: #008B8B !important; color: white !important;">
                <br>
                <i class="fa fa-bookmark-o fa-5x"></i><br/>
                <br> Detalhes Turma
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6">
            <a href="?exe=orcamento/" class="btn btn-sq-lg ButtomCustom"
               style="background: #4682B4 !important; color: white !important;">
                <br>
                <i class="fa fa-envelope fa-5x"></i><br/>
                <br> Disparar Links
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6">
            <a href="#" class="btn btn-sq-lg ButtomCustom"
               style="background: #228B22 !important; color: white !important;">
                <br>
                <i class="fa fa-eye fa-5x"></i><br/>
                <br> Vizualizar Link
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6">
            <a href="#" class="btn btn-sq-lg ButtomCustom"
               style="background: #985f0d !important; color: white !important;">
                <br>
                <i class="fa fa-file-pdf-o fa-5x"></i><br/>
                <br> Gerar Contrato
            </a>
        </div>
    </div>
</div>


<!-- MODAL -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="padding:35px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><span class="glyphicon glyphicon-lock"></span> Enviar email para comissão </h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
                <form role="form">
                    <div class="form-group">
                        <label for="usrname"><span class="glyphicon glyphicon-user"></span> Assunto:</label>
                        <input type="text" class="form-control" id="email-assunto" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Mensagem </label>
                        <textarea type="text" class="form-control" id="psw" placeholder=""></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-default btn-block" data-dismiss="modal"><span
                        class="glyphicon glyphicon-send"></span> Enviar
                </button>
            </div>
        </div>

    </div>
</div>
<script>
    $(document).ready(function () {
        $("#myBtn").click(function () {
            $("#myModal").modal();
        });
    });
</script>