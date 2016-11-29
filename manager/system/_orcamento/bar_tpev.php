<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;

$orcamentoId = (int)$_SESSION['orcamento']['id'];

?>

<style>
    .glyphicon_custom {
        margin-bottom: 10px;
        margin-right: 10px;
    }

    small {
        display: block;
        line-height: 1.428571429;
        color: #999;
    }
</style>


<div class="row">
    <div class="panel">
        <div class="panel-body text-center">
            <div class="col-lg-1 col-xs-1">
                <a href="?exe=orcamento/painel"> <i class="glyphicon glyphicon-home"
                                                    style="font-size: 30px; color: #999b9e"></i> </a>
            </div>
            <div class="col-lg-10 col-xs-10">
                <ol class="breadcrumb" style="margin-bottom: 5px;">
                    <li><a href="?exe=orcamento/painel">Orçamento</a></li>
                    <li class="active">Bar Tipo de Eventos</li>
                </ol>
            </div>
            <div class="col-lg-1 col-xs-1">
                <a href="?exe=orcamento/painel"> <i class="glyphicon glyphicon-ok"
                                                    style="font-size: 30px; color: #999b9e"></i> </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">

        <!-- Categoria form -->

        <div class="bs-callout bs-callout-default">
            <h4>Show Bar</h4>
            <p>Neste módulo você seleciona para qual tipo de evento deseja incluir bar</p>
        </div>

    </div>
</div>

<div class="clearfix"></div>

<hr>

<div class="row">
    <div class="col-md-12">

        <?php

        include_once("_models/AdminOrcamento.class.php");
        $Admin = new AdminOrcamento();

        $TiposEventosRes = $Admin->getTiposEventos();

        if ($TiposEventosRes):
            foreach ($TiposEventosRes as $TipoEventos):
                extract($TipoEventos);
                ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <?php echo $tipoevento_nome; ?>
                    </div>
                    <div class="panel-body" id="<?php echo $tipoevento_id; ?>">

                        <?php
                        include_once("_models/AdminOrcamento.class.php");
                        $Admin = new AdminOrcamento();
                        //if ($Admin->buscarLocal($orcamentoId, $tipoevento_id)):
                        //else:
                            echo '
                                <div class="col-md-12 text-center">
                                    <a href="?exe=orcamento/bar&tpev='.$tipoevento_id.'" data-id="'.$tipoevento_id.'"
                                            class="btn btn-primary btn-block">
                                        Adicionar
                                    </a>
                                </div>
                            ';
                        //endif;
                        ?>
                    </div>
                </div>
                <?php
            endforeach;
        else:
            echo "<div class=\"panel panel-info\">
                        <div class=\"panel-heading\">
                                Nenhum Tipo de Evento Ativado
                        </div>
                        <div class=\"panel-body\" >
                            <div class=\"col-md-12 text-center\">
                                    <a href=\"?exe=orcamento/tipoevento\"
                                            class=\"btn btn-warning btn-block\">
                                        Clique aqui para ativar os Tipos de Eventos
                                    </a>
                                </div>
                        </div>
                  </div>";
        endif;
        ?>

    </div>
</div>


<br/><br/>
<br/>


<!-- Modal -->
<style>
    .modal-body {
        overflow-y: auto;
    }
</style>
<div class="modal fade" id="ModelLocais" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="padding:15px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><span class="glyphicon glyphicon-lock"></span> Selecione o Local </h4>
            </div>
            <div class="modal-body" id="ResultLocais" style="padding:20px 30px; max-height: 500px; overflow-x: auto">

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<script>
    $(function () {

        var TipoEvento = 0;

        //Seleciona locais de acordo com o tipo de evento
        $(".panel-body").on("click", ".AjaxSelecionaLocais", function () {

            var tipoEventoId = $(this).data('id');
            TipoEvento = tipoEventoId;

            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=ListarOrcamentoLocais",
                type: 'POST',
                data: {id: tipoEventoId},
                dataType: 'json',
                success: function (data) {
                    //console.log(data);
                    var tr = '';
                    var td = '';
                    $.each(data.data, function (index, value) {

                        for (var i = 0; i <= index.length - 1; i++) {

                            td += "<div class=\"col-md-4\">" +
                                "<div class=\"thumbnail\">" +
                                "<img src='" + value.local_img + "' class=\"img-responsive img-thumbnail\" />" +
                                "<div class=\"caption\">" +
                                "<h3 style='text-align: center; border-bottom: 1px solid #d6e9c6; margin: 0 0 5px 0; padding: 5px 0;'>" + value.local_nome + "</h3>" +
                                "<p style='font-size: 16px; font-weight: bold'><i class='glyphicon glyphicon-record'></i> " + value.local_mesas + "<br/>" +
                                "<i class='glyphicon glyphicon-user'></i> " + value.local_capacidade + "<br/>" +
                                "<i class='glyphicon glyphicon-education'></i> " + value.local_max_formandos + "<br/>" +
                                "<span style='font-size: 19px;'>R$ " + value.local_valor + "</span><br/><br/>" +
                                "<button data-id='" + index + "' class=\"btn btn-primary btn-block AjaxAddLocais\"><i class=\"glyphicon glyphicon-ok\"></i></button>" +
                                "</p>" +
                                "</div>" +
                                "</div>" +
                                "</div>";
                        }
                    });
                    tr += "<ul class=\"thumbnails\">" + td + "</ul>";
                    $("#ResultLocais").html(tr);
                }
            });

            $("#ModelLocais").modal();
        });


        $("#ResultLocais").on('click', ".AjaxAddLocais", function () {

            var LocalId = $(this).data('id');

            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=AddOrcamentoLocais",
                type: 'POST',
                data: {id: LocalId, tipoeventoId: TipoEvento},
                dataType: 'html',
                success: function (data) {


                    $(".panel-body#" + TipoEvento).html(data);

                    $("#ModelLocais").modal('hide');

                }
            });
        });

    });

</script>


