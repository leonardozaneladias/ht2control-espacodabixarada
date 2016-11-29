<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;


?>

<div class="row">
    <div class="panel">
        <div class="panel-body text-center">
            <div class="col-lg-1 col-xs-1">
                <a href="?exe=orcamento/painel"> <i class="glyphicon glyphicon-home" style="font-size: 30px; color: #999b9e"></i> </a>
            </div>
            <div class="col-lg-10 col-xs-10">
                <ol class="breadcrumb" style="margin-bottom: 5px;">
                    <li><a href="?exe=orcamento/painel">Orçamento</a></li>
                    <li class="active">Comissões</li>
                </ol>
            </div>
            <div class="col-lg-1 col-xs-1">
                <a href="?exe=orcamento/painel"> <i class="glyphicon glyphicon-ok" style="font-size: 30px; color: #999b9e"></i> </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">

        <!-- Categoria form -->

        <div class="bs-callout bs-callout-default">
            <h4>Comissôes</h4>
            <p>Neste módulo você adiciona comissões ao seu orçamento.</p>
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
                <form name="buscaComissoes" id="buscaComissoes">
                    <div class="input-group">
                        <input type="text" class="form-control" name="buscar" id="buscar"
                               placeholder="Localizar Comissão">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">Buscar</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="panel-body" style="max-height: 200px; overflow: auto;">
                <table class="table" id="tableResponse">
                    <tbody>
                    <td colspan="5">Aguardando busca...</td>
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
                Comissôes cadastradas
            </div>
            <div class="panel-body">
                <table class="table table-bordered" id="ComissoesCadastradas">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <td>Instituição</td>
                        <td>Conclusão</td>
                        <td>Cursos</td>
                        <td>#</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="noResult">
                        <td colspan="5">Nenhuma comissão cadastrada!</td>
                    </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_SESSION['orforcreat'])):
    unset($_SESSION['orforcreat']);
    echo '
        <div class="row">
            <div class="col-sm-12">
                <a href="?exe=orcamento/config" class="btn btn-block btn-primary">Continuar</a>
            </div>
        </div>
    ';
endif;
?>

<br/><br/><br/>

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

    $("#buscaComissoes").submit(function () {
        var nome = $("#buscar").val();
        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=getOrcamentoComissoes",
            type: 'POST',
            data: {nome: nome},
            dataType: 'json',
            beforeSend: function () {
                dialogInstanceLoading
                    .setTitle('Buscando Comissões!...')
                    .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                    .setType(BootstrapDialog.TYPE_SUCCESS)
                    .open();
            },
            success: function (data) {
                $("#tableResponse tbody").html("");
                if (data.error <= 0) {

                    for (var comissoes in data.data.comissoes) {
                        $("#tableResponse tbody").append('<tr><td>' + data.data.comissoes[comissoes]["nome"] + '</td><td>' + data.data.comissoes[comissoes]["instituicao"] + '</td><td>' + data.data.comissoes[comissoes]["conclusao_mes"] + '/' + data.data.comissoes[comissoes]["conclusao_ano"] + '</td><td>' + data.data.comissoes[comissoes]["cursos"] + '</td><td><a class="btn btn-success addComissao" data-id="' + data.data.comissoes[comissoes]["id"] + '">+</a></td><tr>');
                    }

                } else if (data.error == 2) {
                    $("#tableResponse tbody").append('<tr><td colspan="5">Nenhuma comissão encontrada com o nome "' + nome + '"</td><tr>');

                }
            },
            complete: function () {
                dialogInstanceLoading.close();
            }
        });
        return false;
    });

    $("#tableResponse").on("click", ".addComissao", function () {

        var id = $(this).data('id');
        var elemento = $(this);
        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=addOrcamentoComissao",
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            beforeSend: function () {
                dialogInstanceLoading
                    .setTitle('Adicionando Comissão!...')
                    .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                    .setType(BootstrapDialog.TYPE_SUCCESS)
                    .open();
            },
            success: function (data) {
                if(data.error == 0){
                    $(elemento).parent().parent().remove();
                    listarComissoes();
                }else{
                    if(data.error == 3){
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
                            .setMessage('Comissão já está cadastrada!')
                            .setType(BootstrapDialog.TYPE_WARNING)
                            .open();
                    }
                }
            },
            complete: function () {
                dialogInstanceLoading.close();
            }
        });

    });

    $("#ComissoesCadastradas").on("click", ".delComissao", function () {

        var id = $(this).data('id');
        var elemento = $(this);
        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=delOrcamentoComissao",
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            beforeSend: function () {
                dialogInstanceLoading
                    .setTitle('Retirando Comissão!...')
                    .setMessage('<div class="loadingGif"><img src="images/loading-default.gif"></div>')
                    .setType(BootstrapDialog.TYPE_SUCCESS)
                    .open();
            },
            success: function (data) {
                if(data.error === 0){
                    $(elemento).parent().parent().remove();
                }else{

                }

            },
            complete: function () {
                dialogInstanceLoading.close();
            }
        });

    });
    
    
    /*
     * Funções
     */
     function listarComissoes() {
         var tr = null;
         $.ajax({
             url: "../manager/system/helpers/ws.php?fnc=listarOrcamentoComissao",
             type: 'GET',
             dataType: 'json',
             success: function (data) {
                 $.each(data.data, function(index, value){
                     var ul = '';
                     for(var i=0;i<=value.cursos.length-1;i++){
                         ul += "<li>"+value.cursos[i]+"</li>";
                     }

                     tr += "<tr><td>"+value.comissoes_nome+"</td><td>"+value.instituicao_nome+"</td><td>"+value.conclusao+"</td><td><ul>"+ul+"</ul></td><td><a class=\"btn btn-danger delComissao\" data-id=\""+index+"\">-</a></td></tr>";
                 });
                 $("#ComissoesCadastradas tbody").html(tr);

             }
         });
     }

    listarComissoes();
</script>



