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
                    <li class="active">Produtos</li>
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
            <h4>Produtos</h4>
            <p>Neste módulo você adiciona produtos ao seu orçamento.</p>
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
                <form name="buscaComissoes" id="buscarForm">
                    <div class="input-group">
                        <input type="text" class="form-control" name="buscar" id="buscar"
                               placeholder="Digite o nome...">

                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit" id="buscarButton">Buscar</button>
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
                Produtos Cadastrados
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="ProdutosCadastradas">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <td>Categoria</td>
                            <td>Tipo de Evento</td>
                            <td>valor</td>
                            <td>Quant.</td>
                            <td>Subtotal</td>
                            <td>#</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="noResult" style="text-align: center;"><td colspan="7"><img src="images/Preloader_1.gif" style="width: 60px; padding: 20px 0;"></td></tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="5" class="text-right">TOTAL</td>
                            <td id="total"></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="clearfix"></div>
<hr>



<div class="modal fade" id="ModelEditProduto" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="padding:15px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><span class="glyphicon glyphicon-edit"></span> Editar Produto </h4>
            </div>
            <div class="modal-body" id="EditProduto" style="padding:20px 30px; max-height: 500px; overflow-x: auto">
                <form class="form-horizontal" method="post" name="salvar_produto" id="salvar_produto">
                    <fieldset >
                        <div class="col-md-12">
                            <label for="basic-url">Nome</label>
                            <input type="text" class="form-control" name="produto_nome" id="edit_produto_nome" maxlength="50">
                        </div>
                        <div class="col-md-12">
                            <label for="basic-url">Quantidade</label>
                            <input type="number" class="form-control" name="produto_qt" id="edit_produto_qt" minlength="1">
                        </div>
                        <div class="col-md-12">
                            <label for="basic-url">Valor</label>
                            <input type="text" class="form-control DecimalReal" name="produto_valor" id="edit_produto_valor">
                        </div>
                        <div class="col-md-12">
                            <label for="basic-url">Descrição</label>
                            <textarea type="text" class="form-control" name="produto_descricao" id="edit_produto_descricao"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="basic-url">Posição</label>
                            <input type="number" class="form-control" name="produto_posicao" id="edit_produto_posicao">
                        </div>

                        <div class="clearfix"></div>
                        <hr>


                        <div class="col-md-12">
                            <button class="btn btn-success btn-block editProdutoAjax">Salvar</button>
                        </div>

                        <input type="hidden" name="produto_id" id="edit_produto_id" value="">

                        <div class="clearfix"></div>

                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
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

    $("#buscarForm").submit(function () {
        var nome = $("#buscar").val();
        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=getOrcamentoProdutos",
            type: 'POST',
            data: {nome: nome},
            dataType: 'json',
            beforeSend: function () {
                dialogInstanceLoading
                    .setTitle('Buscando...')
                    .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                    .setType(BootstrapDialog.TYPE_SUCCESS)
                    .open();
            },
            success: function (data) {
                $("#tableResponse tbody").html('');
                if (data.error <= 0) {

                    $.each(data.data, function (k, v) {
                        $("#tableResponse tbody").append('<tr><td>' + v["nome"] + '</td><td>R$ ' + number_format(v["produto_valor"], 2, ",", ".") + '</td><td>' + v["categoria_nome"] + '</td><td>' + v["tipoevento_nome"] + '</td><td>' + v["categoria_nome"] + '</td><td><a class="btn btn-success addProd" data-id="' + k + '">+</a></td><tr>');
                    });

                } else if (data.error == 2) {
                    $("#tableResponse tbody").append('<tr><td colspan="5">Nenhuma produto encontrada com o nome "' + nome + '"</td><tr>');

                }
            },
            complete: function () {
                dialogInstanceLoading.close();
            }
        });
        return false;
    });

    $("#tableResponse").on("click", ".addProd", function () {

        var id = $(this).attr("data-id");
        var objeto = $(this);

        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=addOrcamentoProduto",
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            beforeSend: function () {
                dialogInstanceLoading
                    .setTitle('Adicionado Produto!...')
                    .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                    .setType(BootstrapDialog.TYPE_SUCCESS)
                    .open();
            },
            success: function (data) {

                if (data.error == 0) {
                    getOrcamentoProdutosCadastrados();
                } else {
                    dialogInstanceLoading.close();
                    dialogInstance
                        .setTitle('Atenção!')
                        .setMessage(data.error_msg)
                        .setType(BootstrapDialog.TYPE_WARNING)
                        .open();
                }
                $(objeto).parent().parent().remove();

            },
            error: function () {
                dialogInstanceLoading.close();
                var dialogInstance = new BootstrapDialog({
                    buttons: [{
                        label: 'OK',
                        cssClass: 'btn-primary',
                        action: function () {
                            dialogInstance.close();
                        }
                    }]
                })
                    .setTitle('Atenção!')
                    .setMessage("Erro ao tentar adicionar o produto!")
                    .setType(BootstrapDialog.TYPE_WARNING)
                    .open();
            },
            complete: function () {

            }
        });
    });

    $(function () {

        /*

         $("#valorCache").focusout(function () {

         var selectCache = $('#selectCache').val();
         var campoValor = $('#valorCache').val();

         data = {selectCache:selectCache,campoValor:campoValor};

         $.ajax({
         url: "../manager/system/helpers/ws.php?fnc=LiveUpdateOrcamentoAlbumConfig",
         type: 'POST',
         data: data,
         dataType: 'json',
         success: function () {

         }
         })

         });

         */

        $("#ProdutosCadastradas").on("click", ".delProd", function () {

            var id = $(this).data('id');
            var elemento = $(this);
            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=delOrcamentoProdutoCadastrado",
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                beforeSend: function () {
                    dialogInstanceLoading
                        .setTitle('Retirando produto do orçamento...')
                        .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                        .setType(BootstrapDialog.TYPE_SUCCESS)
                        .open();
                },
                success: function (data) {
                    if (data.error === 0) {
                        getOrcamentoProdutosCadastrados();
                    } else {

                    }

                },
                complete: function () {
                    dialogInstanceLoading.close();
                }
            });

        });

        $("#ProdutosCadastradas").on("click", ".editProd", function () {

            var Id = $(this).data('id');

            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=getOrcamentoProduto",
                type: 'POST',
                data: {id: Id},
                dataType: 'json',
                beforeSend: function () {
                    dialogInstanceLoading
                        .setTitle('Buscando produto do orçamento...')
                        .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                        .setType(BootstrapDialog.TYPE_SUCCESS)
                        .open();
                },
                success: function (data) {
                    if (data.error === 0) {
                        $("#edit_produto_nome").val(data.data.produto_nome);
                        $("#edit_produto_qt").val(data.data.produto_qt);
                        $("#edit_produto_valor").val(number_format(data.data.produto_valor,2,",","."));
                        $("#edit_produto_descricao").val(data.data.produto_descricao);
                        $("#edit_produto_posicao").val(data.data.produto_posicao);
                        $("#edit_produto_id").val(data.data.produto_id);
                    } else {

                    }

                },
                complete: function () {
                    dialogInstanceLoading.close();
                    $('#ModelEditProduto').modal({
                        backdrop: 'static'
                    });

                }
            });

        });

        $(".editProdutoAjax").click(function () {
            $('#ModelEditProduto').modal('hide');

            var dados = $("#salvar_produto").serialize();

            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=editOrcamentoProduto",
                type: 'POST',
                data: {data: dados},
                dataType: 'json',
                beforeSend: function () {
                    dialogInstanceLoading
                        .setTitle('Editando produto do orçamento...')
                        .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                        .setType(BootstrapDialog.TYPE_SUCCESS)
                        .open();
                },
                success: function (data) {

                    if(data.error == 0){
                        
                        getOrcamentoProdutosCadastrados();
                        dialogInstanceLoading.close();

                    }else{
                        var dialogInstance = new BootstrapDialog({
                            buttons: [{
                                label: 'OK',
                                cssClass: 'btn-primary',
                                action: function () {
                                    dialogInstance.close();
                                    $('#ModelEditProduto').modal({
                                        backdrop: 'static'
                                    });
                                }
                            }]
                        })
                            .setTitle('Atenção Erro!')
                            .setMessage(data.error_msg)
                            .setType(BootstrapDialog.TYPE_WARNING)
                            .open();
                    }
                },
                complete: function () {
                    dialogInstanceLoading.close();
                }
            });
            return false;
        });

        getOrcamentoProdutosCadastrados();

        /* MASKS */
        $('.DecimalReal').mask("#.##0,00", {reverse: true});


    });


    /*
     * Funções
     */
    function getOrcamentoProdutosCadastrados() {
        $("#ProdutosCadastradas tbody").html('<tr class="noResult" style="text-align: center;"><td colspan="7"><img src="images/Preloader_1.gif" style="width: 60px; padding: 20px 0;"></td></tr>');
        $("#total").text("R$ 0,00");
        var tr = '';
        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=getOrcamentoProdutoCadastrados",
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var subTotal = 0;
                if(data.data){
                    $.each(data.data, function (index, value) {
                        subTotal += value.produto_valor * value.produto_qt;
                        tr += "<tr><td>" + value.produto_nome + "</td><td>" + value.categoria_nome + "</td><td>" + value.tipoevento_nome + "</td><td>" + number_format(value.produto_valor, 2, ",", ".") + "</td><td>" + number_format(value.produto_qt, 0) + "</td><td>" + number_format(value.produto_valor * value.produto_qt, 2, ",", ".") + "</td><td><a class=\"btn btn-danger delProd\" data-id=\"" + value.produto_id + "\"><i class='glyphicon glyphicon-remove'></i></a> <a class=\"btn btn-info editProd\" data-id=\"" + value.produto_id + "\"><i class='glyphicon glyphicon-edit'></i></a></td></tr>";
                    });
                }else{
                    tr += "<tr><td colspan='7' style='height: 100px;'>Nenhum produto adicionado.</td></tr>";
                }


                $("#ProdutosCadastradas tbody").html(tr);
                $("#total").text("R$ " + number_format(subTotal, 2, ",", "."));
                dialogInstanceLoading.close();
                /*


                 <th>Nome</th>
                 <td>Categoria</td>
                 <td>Tipo de Evento</td>
                 <td>valor</td>
                 <td>Quant.</td>
                 <td>Subtotal</td>
                 <td>#</td>


                 $.each(data.data, function(index, value){
                 var ul = '';
                 tr += "<tr style='display: none;'><td>"+value.album_nome+"</td><td>"+value.album_descricao+"</td><td>"+value.album_valor+"</td><td>"+value.album_repasse+"</td><td><input type='number' name='album_qt' class='form-control AjaxLiveQt' data-id='"+value.album_id+"' value='"+value.album_qt+"'/></td><td><a class=\"btn btn-danger delAlbum\" data-id=\""+index+"\">-</a></td></tr>";
                 });
                 $("#AlbunsCadastradas tbody").html(tr);
                 $("#AlbunsCadastradas tbody tr").fadeIn();
                 */

            }
        });
    }

    //();

</script>



