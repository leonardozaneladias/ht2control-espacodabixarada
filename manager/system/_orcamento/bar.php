<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;

$tipoEventoId = filter_input(INPUT_GET, "tpev", FILTER_VALIDATE_INT);
if (empty($tipoEventoId)):
    header("Location: ?exe=orcamento/bar_tpev");
    exit;
else:
    include_once("_models/AdminOrcamento.class.php");
    $Admin = new AdminOrcamento();
    $TipoEvento = $Admin->getTiposEventos($tipoEventoId);


    $TipoEvento = $TipoEvento[0];
    if (!$TipoEvento):
        header("Location: ?exe=orcamento/bar_tpev");
        exit;
    endif;
endif;

include_once("_models/AdminBar.class.php");

$orcamentoId = (int)$_SESSION['orcamento']['id'];
$DadosDB = new Read;
$DadosDB->ExeRead('app_orcamento_bar', 'WHERE orcamento_id = :oid AND tipoevento_id = :tid', "oid={$orcamentoId}&tid={$TipoEvento['tipoevento_id']}");
if ($DadosDB->getResult()):
    $Data = $DadosDB->getResult()[0];
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
                    <li class="active">Show Bar</li>
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
        <form class="form-horizontal" method="post" name="form_tipoevento">
            <fieldset>
                <!-- Categoria form -->

                <div class="bs-callout bs-callout-default">
                    <h4>Show Bar </h4>
                    <p>Neste módulo você pode configurar os principais parametros.</p>
                </div>
            </fieldset>
        </form>
    </div>

    <div class="clearfix"></div>
    <hr/>

    <h2><?php echo $TipoEvento['tipoevento_nome']; ?></h2>

    <div class="clearfix"></div>
    <hr/>


    <div class="col-md-12">
        <select class="form-control" name="bar_fornecedor_id" id="bar_fornecedor_id">
            <option disabled selected>Selecione o fornecedor...</option>
            <?php
            $Bar = new AdminBar;
            $Fornecedores = $Bar->listaFornecedores();
            foreach ($Fornecedores as $fornecedor):
                extract($fornecedor);
                $Selected = ($Data['fornecedor_id'] == $bar_fornecedor_id) ? "selected" : "";

                echo "<option value='{$bar_fornecedor_id}' {$Selected}>{$bar_fornecedor_nome}</option>";
            endforeach;
            ?>
        </select>
    </div>

    <div class="clearfix"></div>
    <hr/>

    <div class="col-md-12">
        <select class="form-control" name="bar_cardapio_id" id="bar_cardapio_id">
            <?php
            if(isset($Data['fornecedor_id']) && $Data['fornecedor_id'] > 0){
                $DadosDB->ExeRead('app_bar_cardapios', 'WHERE bar_fornecedor_cod = :id', "id={$Data['fornecedor_id']}");
                if($DadosDB->getResult()):
                    foreach ($DadosDB->getResult() as $Cardapio):
                        $Selected = ($Cardapio['bar_cardapio_id'] == $Data['cardapio_id']) ? "selected" : "";
                        echo "<option value='{$Cardapio['bar_cardapio_id']}' {$Selected}>{$Cardapio['bar_cardapio_nome']}</option>";
                    endforeach;
                endif;
            }else {
                ?>
                <option disabled selected>Selecione o fornecedor acima</option>
                <?php
            }
            ?>
        </select>
    </div>

    <div class="clearfix"></div>
    <hr/>


    <div class="col-md-6">
        <style>
            .zi999 {
                z-index: 999;
            }
        </style>

        <div class="panel panel-info">
            <div class="panel-heading">Bebidas Escolher</div>
            <div class="panel-body BebidasBar" style="min-height: 150px">

                Selecione o Fornecedor e Cardápio...
            </div>
        </div>

    </div>
    <div class="col-md-6">

        <div class="panel panel-success">
            <div class="panel-heading">Bebidas Escolhidas</div>
            <div class="panel-body" id="droppable" style="min-height: 150px">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-list">
                        <thead>
                        <tr>
                            <th>Img</th>
                            <th>Nome</th>
                            <th>Teor(%)</th>
                            <th>Vl Dose</th>
                            <th>Qt Dose</th>
                            <th>Sub T.</th>
                            <th>#</th>
                        </tr>
                        </thead>
                        <tbody class="BebidasEscolhidas">
                        <tr>
                            <td colspan="7" id="msgInicial">Clique e arraste a bebida até aqui e solte!</td>
                        </tr>
                        <!-- MODELO
                        <tr data-status="disabled">
                            <td><img
                                    src="http://iacom.s8.com.br/produtos/01/00/item/5599/2/5599297_1GG.jpg"
                                    class="img-responsive img-circle da-img" alt="Responsive image" width="40"></td>

                            <td>Jack Daniels</td>
                            <td class="text-center">4%</td>
                            <td class="text-center">5,20</td>
                            <td>
                                <select class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </td>
                            <td>
                                52,00
                                <!-- <span class="label label-warning">Disabled</span>
                            </td>
                            <td align="center" class="text-left">
                                <a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Edit"><em class="fa fa-close"></em></a>
                            </td>
                        </tr>
                        -->
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" class="text-right">TOTAL:</td>
                            <td colspan="1" class="TotalTeor">0</td>
                            <td colspan="2" class=""></td>
                            <td colspan="2" class="Total">0</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <button class="btn btn-danger btn-block">Cancelar</button>
    </div>
    <div class="col-md-6">
        <input type="submit" class="btn btn-primary btn-block" value="Enviar" name="Salvar" onclick="save()">
    </div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min.js"></script>
<script>

    var listaProduto;
    var bebidas;
    var produtosList = [];
    var dataAjax = [];
    dataAjax.listBebidas = [];
    dataAjax.fornecedor;
    dataAjax.cardapio;
    dataAjax.tipoEvento = <?php echo $TipoEvento['tipoevento_id']; ?>;


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


    function getBebidasEscolhidas() {

        var idTipoEvento = <?=$TipoEvento['tipoevento_id']?>;
        var idCardapio = <?php if(isset($Data['cardapio_id'])){echo $Data['cardapio_id'];}else{echo "''";}?>;

        dataAjax.fornecedor = $('#bar_fornecedor_id').val();
        dataAjax.cardapio = idCardapio;

        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=getOrcamentoBarBebidasEscolhidas",
            type: 'POST',
            data: {
                tid: idTipoEvento,
                cid: idCardapio
            },
            dataType: 'json',
            beforeSend: function () {

            },
            success: function (data) {


                bebidas = data.data.bebidasFornecedor;

                $.each(bebidas, function (k, v) {
                    if(!data.data.bebidasEscolhidas[k]){
                        $(".BebidasBar").append('<img src="' + v.img + '" width="100" height="100" data-id="' + k + '" class="draggable ui-draggable" style="position: relative;">');
                    }
                });

                $(".BebidasEscolhidas").html('');
                $.each(bebidas, function (k, v) {
                    if(data.data.bebidasEscolhidas[k]){
                        var id_object = k;

                        var subTotal = bebidas[id_object].valor * bebidas[id_object].qt;

                        var template = '<tr class="BebAdd" id="BebidaId_' + bebidas[id_object].id + '">' +
                            '<td><img src="' + bebidas[id_object].img + '" class="img-responsive img-circle da-img" alt="" width="40"></td>' +
                            '<td>' + bebidas[id_object].nome + '</td>' +
                            '<td class="text-center">' + number_format(data.data.bebidasEscolhidas[k].teor, 2, ",", ".") + '</td>' +
                            '<td class="text-center">' + number_format(data.data.bebidasEscolhidas[k].valor, 2, ",", ".") + '</td><td><select name="qt" class="form-control" onchange="calculaTotal();">';

                        for (var i = 0; i <= 5; i += 0.25) {
                            if (i == data.data.bebidasEscolhidas[k].qt) {
                                var selecteD = 'selected'
                            } else {
                                var selecteD = ''
                            }

                            template += '<option value="' + i + '" ' + selecteD + '>' + number_format(i, 2, ",", ".") + '</option>';
                        }


                        template += +
                                '</select></td><td><span class="SubTotal"> ' + number_format(subTotal, 2, ",", ".") + ' </span></td>' +
                            '<td><span class="SubTotal"></span></td>' +
                            '<td align="center" class="text-left"> <a class="btn btn-danger RemoveBebida" data-id="' + id_object + '" data-placement="top" title="Edit"><em class="fa fa-close"></em></a></td>' +
                            '<input type="hidden" name="id" value="' + bebidas[id_object].id + '"> ' +
                            '</tr>';
                        $(".BebidasEscolhidas")
                            .append(template);

                    }
                });
                var optionsDraggable = {
                    revert: true,
                    cursor: "move",
                    cursorAt: {top: 56, left: 56},
                    zIndex: 999,
                    delay: 0,
                    revertDuration: 500,
                };

                $(".draggable").draggable(optionsDraggable);
                calculaTotal();


            },
            complete: function () {

            },
            error: function () {

            }
        })
    }


    function CarregaBebidas() {
        $(".BebidasBar").html('');
        $.each(bebidas, function (k, v) {
            $(".BebidasBar").append('<img src="' + v.img + '" width="100" height="100" data-id="' + k + '" class="draggable ui-draggable" style="position: relative;">');
        });
    }

    function calculaTotal() {

        listaProduto = new Array();

        var subTotal = 0;
        var subTotalTeor = 0;
        var valorProduto = 0;
        var qt = 0;
        var idProduto = 0;
        Total = 0;

        $('#droppable').find(".BebidasEscolhidas").each(function (index, element) {
            dataAjax.listBebidas = [];
            $(this).find('.BebAdd').each(function () {
                //var qt 		= element2;
                idProduto = $(this).find('input[name="id"]').val();
                qt = $(this).find('select[name="qt"]').val();
                valorProduto = bebidas[parseInt(idProduto)].valor;

                subTotalTeor += bebidas[parseInt(idProduto)].teor * qt;

                subTotal += valorProduto * qt;
                $(this).find(".SubTotal").text(number_format(valorProduto * qt, 2, ",", "."));

                dataAjax.listBebidas.push({id: parseInt(idProduto), qt: parseFloat(qt)});

                /*
                 //console.log(opcao);

                 listaProduto.push("id="+idProduto+"|qt="+qt+"|opcao="+opcao);

                 subTotal	   	= 	(valorProduto * qt);
                 Total	    = 	(subTotal + Total);
                 //var idProduto	= $('input[name="id"]').val();
                 //var qt	= $(' .img_prod_qt select[name="qt"]').val();
                 //$('#retornoTeste').append('Produto='+idProduto+', Quant.: '+qt+', Valor.: '+valorProduto+', Sub. T.: '+subTotal+', Total.: '+Total);
                 */
            });
            //var string = numeral(1000).format('0,0');
            $(".Total").text(number_format(subTotal, 2, ",", "."));
            $(".TotalTeor").text(number_format(subTotalTeor, 2, ",", "."));

            //console.log(listaProduto);
            //$(".total").text(formatNumber(Total,2,",","."));
            //produtos[qt]	=	{qt:qt,opcao:opcao};
            //return Total,listaProduto;
        });
    }

    function save() {

        if (dataAjax.fornecedor == null) {
            alert('Escolha o fornecedor');
            return false;
        }
        else if (dataAjax.cardapio == null) {
            alert('Escolha o cardapio');
            return false;
        }
        else if (dataAjax.listBebidas.length <= 0) {
            alert('Escolha as bebidas');
            return false;
        }


        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=saveOrcamentoBar",
            type: 'POST',
            data: {
                fornecedor_id: dataAjax.fornecedor,
                cardapio_id: dataAjax.cardapio,
                bebidas: dataAjax.listBebidas,
                tipoevento_id: dataAjax.tipoEvento
            },
            dataType: 'text',
            beforeSend: function () {

                dialogInstanceLoading
                    .setTitle('Salvando...')
                    .setMessage('<div class="loadingGif"><img src="images/loading.gif"></div>')
                    .setType(BootstrapDialog.TYPE_INFO)
                    .open();
            },
            success: function (data) {

                dialogInstanceLoading
                    .setTitle('salvo com sucesso!')
                    .setMessage('Bar salvo com sucesso!')
                    .setType(BootstrapDialog.TYPE_SUCCESS)
                    .open();
                setTimeout(function () {
                    location.href = "painel.php?exe=orcamento/painel";
                }, 2000)


            },
            complete: function () {
            },
            error: function () {
                alert("Erro ao Salvar Bar");
            }
        })


    }

    $(function () {

        getBebidasEscolhidas();

        CarregaBebidas();

        var optionsDraggable = {
            revert: true,
            cursor: "move",
            cursorAt: {top: 56, left: 56},
            zIndex: 999,
            delay: 0,
            revertDuration: 500,
        };

        $(".draggable").draggable(optionsDraggable);

        $("#droppable").droppable({
            tolerance: "pointer"
        });

        $("#droppable").on("drop", function (event, ui) {

            $("#msgInicial").remove();
            var id_object = ui.draggable.attr('data-id');

            var subTotal = bebidas[id_object].valor * bebidas[id_object].qt;

            var template = '<tr class="BebAdd" id="BebidaId_' + bebidas[id_object].id + '">' +
                '<td><img src="' + bebidas[id_object].img + '" class="img-responsive img-circle da-img" alt="" width="40"></td>' +
                '<td>' + bebidas[id_object].nome + '</td>' +
                '<td class="text-center">' + number_format(bebidas[id_object].teor, 2, ",", ".") + '</td>' +
                '<td class="text-center">' + number_format(bebidas[id_object].valor, 2, ",", ".") + '</td><td><select name="qt" class="form-control" onchange="calculaTotal();">';

            for (var i = 0; i <= 5; i += 0.25) {
                if (i == bebidas[id_object].qt) {
                    var selecteD = 'selected'
                } else {
                    var selecteD = ''
                }

                template += '<option value="' + i + '" ' + selecteD + '>' + number_format(i, 2, ",", ".") + '</option>';
            }


            template += +
                    '</select></td><td><span class="SubTotal"> ' + number_format(subTotal, 2, ",", ".") + ' </span></td>' +
                '<td><span class="SubTotal"></span></td>' +
                '<td align="center" class="text-left"> <a class="btn btn-danger RemoveBebida" data-id="' + id_object + '" data-placement="top" title="Edit"><em class="fa fa-close"></em></a></td>' +
                '<input type="hidden" name="id" value="' + bebidas[id_object].id + '"> ' +
                '</tr>';
            $(this).find(".BebidasEscolhidas")
                .append(template);
            ui.draggable.remove();

            calculaTotal();
        });


        $(".BebidasEscolhidas").on('click', '.RemoveBebida', function () {
            var idRemove = $(this).data('id');
            $(this).parent().parent().remove();
            $(".BebidasBar").append('<img src="' + bebidas[idRemove].img + '" width="100" height="100" data-id="' + bebidas[idRemove].id + '" class="draggable ui-draggable" style="position: relative;">');
            calculaTotal();
            $(".draggable").draggable(optionsDraggable);
        });


        //Buscar cardapio por fornecedor
        $("#bar_fornecedor_id").change(function () {

            $(".BebidasBar").html('');
            $(".BebidasEscolhidas").html('');
            dataAjax.listBebidas = [];

            var idForm = $(this).val();
            var data = {id: idForm};
            dataAjax.fornecedor = idForm;



            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=getOrcamentoBarCardapios",
                type: 'POST',
                data: data,
                dataType: 'json',
                beforeSend: function (xhr) {
                    dialogInstanceLoading
                        .setTitle('Carregando...')
                        .setMessage('<div class="loadingGif"><img src="images/loading.gif"></div>')
                        .setType(BootstrapDialog.TYPE_INFO)
                        .open();
                },
                success: function (data) {

                    if (data.error != 0) {
                        var options = "<option disabled selected>Nenhum cardápio cadastrado para este fornecedor...</option>";
                        $("#bar_cardapio_id").html(options);
                    } else {
                        var options = "<option disabled selected>Selecione o cardápio...</option>";

                        $.each(data.data, function (index, value) {
                            options += "<option value='" + index + "'>" + value.bar_cardapio_nome + "</option>";
                        });

                        $("#bar_cardapio_id").html(options);
                    }
                },
                complete: function () {
                    dialogInstanceLoading.close();
                },
                error: function () {
                    alert("Erro ao buscar os cardápios para este fornecedor!");
                }
            })
        });

        $("#bar_cardapio_id").change(function () {

            $(".BebidasBar").html('');
            $(".BebidasEscolhidas").html('');
            dataAjax.listBebidas = [];

            var idCard = $(this).val();
            var data = {id: idCard};
            dataAjax.cardapio = idCard;




            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=getOrcamentoBarCardapiosBebidas",
                type: 'POST',
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    dialogInstanceLoading
                        .setTitle('Carregando...')
                        .setMessage('<div class="loadingGif"><img src="images/loading.gif"></div>')
                        .setType(BootstrapDialog.TYPE_INFO)
                        .open();
                },
                success: function (data) {
                    //console.log(data.data.bebidasFornecedor);
                    if (data.data.bebidasFornecedor == 0) {
                        dialogInstanceLoading.close();
                        dialogInstance
                            .setTitle('Erro ao buscar bebidas')
                            .setMessage('Nehuma bebida cadastrada neste cardapio!')
                            .setType(BootstrapDialog.TYPE_DANGER)
                            .open();
                    } else {
                        bebidas = data.data.bebidasFornecedor;
                        CarregaBebidas();
                        $(".draggable").draggable(optionsDraggable);
                    }

                },
                complete: function () {
                    dialogInstanceLoading.close();
                }
            })
        });

    });
</script>