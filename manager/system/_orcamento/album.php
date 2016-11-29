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
var_dump($Read);
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
                    <li class="active">Comissões</li>
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
            <h4>Álbum</h4>
            <p>Neste módulo você adiciona albuns.</p>
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
                <form name="buscaComissoes" id="buscarAlbuns">
                    <div class="input-group">
                        <input type="text" class="form-control" name="buscar" id="buscar"
                               placeholder="Localizar Álbum">

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
                            <td>Repasse</td>
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
<div class="row">
    <form class="form-horizontal" method="post" name="form_tipoevento">
        <fieldset>
            <div class="col-md-6">
                <label for="basic-url">Tipo Exclusividade</label>
                <select class="form-control .AjaxLive" name="orcamento_config_album_cache" id="selectCache">
                    <?php
                    foreach ($ALBUM_CACHE as $id => $valor):
                        if($id == $Data['cache']){$Selected = 'selected';}else{$Selected = '';}
                        ?>
                        <option
                            value="<?= $id ?>" <?=$Selected?>>
                            <?= $valor ?>
                        </option>
                        <?php
                    endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="basic-url">Valor Cachê</label>
                <input type="text" class="form-control DecimalReal" name="orcamento_config_album_cache_valor" maxlength="50" value="<?php if(isset($Data['cacheValor'])){echo Form::Value($Data['cacheValor']);} ?>"
                       id="valorCache">
            </div>


            <div class="clearfix"></div>

            <hr>


            <br/><br/><br/>

        </fieldset>
    </form>
</div>

<script>

    var dialogInstanceLoading = new BootstrapDialog({
        closable: false
    });

    var dialogInstance = new BootstrapDialog({
        buttons: [{
            label: 'OK',
            cssClass: 'btn-primary',
            action: function () {
                dialogInstance.close();
            }
        }]
    });

    $("#buscarAlbuns").submit(function () {
        var nome = $("#buscar").val();
        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=getOrcamentoAlbuns",
            type: 'POST',
            data: {nome: nome},
            dataType: 'json',
            beforeSend: function () {
                dialogInstanceLoading
                    .setTitle('Buscando Álbuns!...')
                    .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                    .setType(BootstrapDialog.TYPE_SUCCESS)
                    .open();
            },
            success: function (data) {
                $("#tableResponse tbody").html("");
                if (data.error <= 0) {

                    for (var albuns in data.data.albuns) {
                        $("#tableResponse tbody").append('<tr><td>' + data.data.albuns[albuns]["nome"] + '</td><td>' + data.data.albuns[albuns]["descricao"] + '</td><td>' + data.data.albuns[albuns]["valor"] + '</td><td>' + data.data.albuns[albuns]["repasse"] + '</td><td><a class="btn btn-success addAlbum" data-id="' + data.data.albuns[albuns]["id"] + '">+</a></td><tr>');
                    }

                } else if (data.error == 2) {
                    $("#tableResponse tbody").append('<tr><td colspan="5">Nenhuma álbum encontrada com o nome "' + nome + '"</td><tr>');

                }
            },
            complete: function () {
                dialogInstanceLoading.close();
            }
        });
        return false;
    });

    $("#tableResponse").on("click", ".addAlbum", function () {

        var id = $(this).attr("data-id");
        var objeto = $(this);

        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=addOrcamentoAlbum",
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            beforeSend: function () {
                dialogInstanceLoading
                    .setTitle('Adicionado Álbum!...')
                    .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                    .setType(BootstrapDialog.TYPE_SUCCESS)
                    .open();
            },
            success: function (data) {

                $(objeto).parent().parent().fadeOut();
                listarOrcamentoAlbuns();
                dialogInstanceLoading.close();

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
                    .setMessage('Erro ao adicionar álbum!')
                    .setType(BootstrapDialog.TYPE_WARNING)
                    .open();
            },
            complete: function () {
            }
        });
    });

    $("#AlbunsCadastradas").on("focusout", ".AjaxLiveQt", function () {

        var id = $(this).data('id');
        var AlbumQt = $(this).val();
        
        var data = {id:id,album_qt:AlbumQt};

        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=LiveUpdateOrcamentoAlbumQt",
            type: 'POST',
            data: data,
            dataType: 'json',
            beforeSend: function () {
                dialogInstanceLoading
                    .setTitle('Atualizando no cadastro...')
                    .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                    .setType(BootstrapDialog.TYPE_SUCCESS)
                    .open();
            },
            success: function () {

            },
            complete: function () {
                dialogInstanceLoading.close();
            }
        });

    });

    $(function () {

        $("#selectCache").change(function () {
            var valor = $(this).val();
            if (valor == 1) {
                $("#valorCache").attr("disabled", false);
            } else {
                $("#valorCache").val("").attr("disabled", true);
            }
        });

        $("#buscar").addClear();


        $("#selectCache").change(function () {

            var selectCache = $('#selectCache').val();
            var campoValor = $('#valorCache').val();

            data = {selectCache:selectCache,campoValor:campoValor};

            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=LiveUpdateOrcamentoAlbumConfig",
                type: 'POST',
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    dialogInstanceLoading
                        .setTitle('Atualizando no cadastro...')
                        .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                        .setType(BootstrapDialog.TYPE_SUCCESS)
                        .open();
                },
                success: function () {

                },
                complete: function () {
                    dialogInstanceLoading.close();
                }
            })

        });

        $("#valorCache").focusout(function () {

            var selectCache = $('#selectCache').val();
            var campoValor = $('#valorCache').val();

            data = {selectCache:selectCache,campoValor:campoValor};

            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=LiveUpdateOrcamentoAlbumConfig",
                type: 'POST',
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    dialogInstanceLoading
                        .setTitle('Atualizando no cadastro...')
                        .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                        .setType(BootstrapDialog.TYPE_SUCCESS)
                        .open();
                },
                success: function () {

                },
                complete: function () {
                    dialogInstanceLoading.close();
                }
            })

        });

        $("#AlbunsCadastradas").on("click", ".delAlbum", function () {

            var id = $(this).data('id');
            var elemento = $(this);
            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=delOrcamentoAlbum",
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                beforeSend: function () {
                    dialogInstanceLoading
                        .setTitle('Retirando Álbum...')
                        .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                        .setType(BootstrapDialog.TYPE_SUCCESS)
                        .open();
                },
                success: function (data) {
                    if(data.error === 0){
                        $(elemento).parent().parent().fadeOut();
                    }else{

                    }

                },
                complete: function () {
                    dialogInstanceLoading.close();
                }

            });

        });

        <?php
        if(isset($Data['cache']) and $Data['cache'] == 2){
            echo '$("#valorCache").val("").attr("disabled", true);';
        }
        ?>

        $('.DecimalReal').mask("#.##0,00", {reverse: true});
        
    });
    

    /*
     * Funções
     */
    function listarOrcamentoAlbuns() {
        var tr = null;
        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=listarOrcamentoAlbuns",
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                dialogInstanceLoading
                    .setTitle('Buscando Álbuns!...')
                    .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                    .setType(BootstrapDialog.TYPE_SUCCESS)
                    .open();
            },
            success: function (data) {
                $.each(data.data, function(index, value){
                    var ul = '';
                    tr += "<tr style='display: none;'><td>"+value.album_nome+"</td><td>"+value.album_descricao+"</td><td>"+value.album_valor+"</td><td>"+value.album_repasse+"</td><td><input type='number' name='album_qt' class='form-control AjaxLiveQt' data-id='"+value.album_id+"' value='"+value.album_qt+"'/></td><td><a class=\"btn btn-danger delAlbum\" data-id=\""+index+"\">-</a></td></tr>";
                });
                $("#AlbunsCadastradas tbody").html(tr);
                $("#AlbunsCadastradas tbody tr").fadeIn();

            },
            complete: function () {
                dialogInstanceLoading.close();
            }
        });
    }

    listarOrcamentoAlbuns();

</script>



