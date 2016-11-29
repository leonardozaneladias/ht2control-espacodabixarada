<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;


$Read = new Read;
$Read->FullRead("SELECT orcamento_config_album_cache, orcamento_config_album_cache_valor FROM app_orcamento_config WHERE orcamento_id = :id", "id={$_SESSION['orcamento']['id']}");
if ($Read->getResult()):
    $Data['cache'] = $Read->getResult()[0]['orcamento_config_album_cache'];
    $Data['cacheValor'] = $Read->getResult()[0]['orcamento_config_album_cache_valor'];
endif;
?>

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
                    <li class="active">Brindes</li>
                </ol>
            </div>
            <div class="col-lg-1 col-xs-1">
                <a href=""> <i class="glyphicon glyphicon-arrow-right" style="font-size: 30px; color: #999b9e"></i> </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">

        <!-- Categoria form -->

        <div class="bs-callout bs-callout-default">
            <h4>Brindes</h4>
            <p>Neste módulo você adiciona brindes ao seu orçamento.</p>
        </div>

        <?php
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($data['Cadastrar'])):
            unset($data['Cadastrar']);
            header("Location: ?exe=orcamento/painel");
            /*
            require('_models/AdminTipoEvento.class.php');
            $cadastra = new AdminTipoEvento();
            $cadastra->ExeCreate($data);

            if (!$cadastra->getResult()):
                WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
            else:
                header('Location: painel.php?exe=tipoevento/index&create=true&id=' . $cadastra->getResult());
            endif;
            */
        endif;
        ?>
        <hr/>

        <div class="panel panel-default">
            <div class="panel-heading">
                <form name="buscaComissoes" id="buscarNome">
                    <div class="input-group">
                        <input type="text" class="form-control" name="buscar" id="buscar"
                               placeholder="Localizar Brinde">

                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit" id="buscar">Buscar</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="panel-body" style="max-height: 200px; overflow: auto;">
                <table class="table" id="tableResponse">
                    <tbody>
                    <td colspan="6">Aguardando busca...</td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>
<hr>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                Álbuns cadastrados
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="AlbunsCadastradas">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <td>Descrição</td>
                            <td>valor</td>
                            <td>Quantidade</td>
                            <td>#</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="noResult">
                            <td colspan="6">Nenhuma comissão cadastrada!</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="clearfix"></div>
<hr>

<script>

    $("#buscarNome").submit(function () {
        var nome = $("#buscar").val();
        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=getOrcamentoBrindes",
            type: 'POST',
            data: {nome: nome},
            dataType: 'json',
            success: function (data) {
                $("#tableResponse tbody").html("");
                if (data.error <= 0) {

                    for (var brindes in data.data.brindes) {
                        $("#tableResponse tbody").append('<tr><td>' + data.data.brindes[brindes]["nome"] + '</td><td>' + data.data.brindes[brindes]["descricao"] + '</td><td>' + data.data.brindes[brindes]["valor"] + '</td><td><a class="btn btn-success addAjax" data-id="' + data.data.brindes[brindes]["id"] + '">+</a></td><tr>');
                    }

                } else if (data.error == 2) {
                    $("#tableResponse tbody").append('<tr><td colspan="5">Nenhuma brinde encontrada com o nome "' + nome + '"</td><tr>');

                }
            }
        });
        return false;
    });

    $("#tableResponse").on("click", ".addAjax", function () {

        var id = $(this).attr("data-id");
        var objeto = $(this);

        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=addOrcamentoBrinde",
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            success: function (data) {

                $(objeto).parent().parent().fadeOut();
                listarOrcamentoBrindes();

            },
            error: function (){

                var dialogInstance = new BootstrapDialog({
                    buttons: [{
                        label: 'OK',
                        cssClass: 'btn-primary',
                        action: function() {
                            dialogInstance.close();
                        }
                    }]
                })
                    .setTitle('Atenção')
                    .setMessage('Erro ao adicionar album!')
                    .setType(BootstrapDialog.TYPE_WARNING)
                    .open();
            }
        });
    });

    $("#AlbunsCadastradas").on("focusout", ".AjaxLiveQt", function () {

        var id = $(this).data('id');
        var qt = $(this).val();
        
        var data = {id:id,qt:qt};

        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=LiveUpdateOrcamentoBrindeQt",
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function () {

            }
        });

    });

    $(function () {

        $("#AlbunsCadastradas").on("click", ".delAjax", function () {

            var id = $(this).data('id');
            var elemento = $(this);
            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=delOrcamentoBrinde",
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function (data) {
                    if(data.error === 0){
                        $(elemento).parent().parent().fadeOut();
                    }else{

                    }

                }
            });

        });
        
    });
    

    /*
     * Funções
     */
    function listarOrcamentoBrindes() {
        var tr = null;
        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=listarOrcamentoBrindes",
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $.each(data.data, function(index, value){
                    var ul = '';
                    tr += "<tr style='display: none;'><td>"+value.brinde_nome+"</td><td>"+value.brinde_descricao+"</td><td>"+value.orcamento_brinde_valor+"</td><td><input type='number' name='album_qt' class='form-control AjaxLiveQt' data-id='"+value.brinde_id+"' value='"+value.orcamento_brinde_qt+"'/></td><td><a class=\"btn btn-danger delAjax\" data-id=\""+index+"\">-</a></td></tr>";
                });
                $("#AlbunsCadastradas tbody").html(tr);
                $("#AlbunsCadastradas tbody tr").fadeIn();

            }
        });
    }

    listarOrcamentoBrindes();

</script>



