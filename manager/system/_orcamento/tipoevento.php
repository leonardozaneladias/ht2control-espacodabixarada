<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;


$orcamentoId = (int)$_SESSION['orcamento']['id'];
$Read = new Read;
$Read->ExeRead("app_orcamento_tipoeventos", "WHERE orcamento_id = :id", "id={$orcamentoId}");
$Data = [];
if ($Read->getResult()):
    foreach ($Read->getResult() as $TipoEventos):
        extract($TipoEventos);
        $Data[$tipoevento_id]['orcamento_tipoevento_qt_formandos'] = $orcamento_tipoevento_qt_formandos;
        $Data[$tipoevento_id]['orcamento_tipoevento_qt_convites'] = $orcamento_tipoevento_qt_convites;
        $Data[$tipoevento_id]['orcamento_tipoevento_qt_mesas'] = $orcamento_tipoevento_qt_mesas;
        $Data[$tipoevento_id]['orcamento_tipoevento_extra_vl_convites'] = $orcamento_tipoevento_extra_vl_convites;
        $Data[$tipoevento_id]['orcamento_tipoevento_extra_qt_convites'] = $orcamento_tipoevento_extra_qt_convites;
        $Data[$tipoevento_id]['orcamento_tipoevento_extra_vl_mesas'] = $orcamento_tipoevento_extra_vl_mesas;
        $Data[$tipoevento_id]['orcamento_tipoevento_extra_qt_mesas'] = $orcamento_tipoevento_extra_qt_mesas;
        $Data['checkeds'][] = $tipoevento_id;
    endforeach;
endif;
var_dump($Data);
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
                <a href="?exe=orcamento/painel"> <i class="glyphicon glyphicon-ok" style="font-size: 30px; color: #999b9e"></i> </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">

        <!-- Categoria form -->

        <div class="bs-callout bs-callout-default">
            <h4>Tipos de Eventos</h4>
            <p>Neste módulo você adiciona comissões ao seu orçamento.</p>
        </div>


        <?php
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($data['Atualizar'])):
            unset($data['Atualizar']);
            var_dump($data);

            foreach ($data['tipoevento_id'] as $id => $valor):
                if ($data['tipoevento_checked'][$id]):
                    echo "{$id} => on<br/>";
                else:
                    echo "{$id} => off<br/>";
                endif;

            endforeach;

            //header("Location: ?exe=orcamento/painel");
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

    </div>
</div>

<div class="clearfix"></div>

<hr>

<div class="row">
    <form name="orcamento_tipoevento" action="" method="post">
        <div class="col-md-12">

            <?php
            $Read->ExeRead("app_tipoevento", "WHERE tipoevento_status = 1 ORDER BY tipoevento_posicao");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $dados):
                    extract($dados);
                    $addClass = "inputHidden AjaxLive";
                    ?>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <?= $tipoevento_nome ?>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-3 text-center">
                                <div class="switch-wrapper">
                                    <?php if(isset($Data['checkeds'])){$Data['checkeds'] = $Data['checkeds'];}else{$Data['checkeds'][] = 0;}?>
                                    <?php $Checked = (in_array($tipoevento_id, $Data['checkeds'])) ? "checked" : ""; ?>
                                    <input type="checkbox"
                                           class="inputOnOff"
                                           name="tipoevento_checked[<?= $tipoevento_id ?>]" <?= $Checked ?>
                                           value="<?= $tipoevento_id ?>">
                                </div>
                            </div>
                            <div class="col-md-3"><input type="number"
                                                         name="orcamento_tipoevento_qt_formandos"
                                                         value="<?php if(isset($Data[$tipoevento_id]['orcamento_tipoevento_qt_formandos'])){echo Form::Value($Data[$tipoevento_id]['orcamento_tipoevento_qt_formandos']);}?>"
                                                         placeholder="Quant. Formandos"
                                                         class="form-control <?= $addClass ?>"
                                                         data-id="<?= $tipoevento_id ?>"/></div>
                            <div class="col-md-3"><input type="number"
                                                         name="orcamento_tipoevento_qt_convites"
                                                         value="<?php if(isset($Data[$tipoevento_id]['orcamento_tipoevento_qt_convites'])){echo Form::Value($Data[$tipoevento_id]['orcamento_tipoevento_qt_convites']);}?>"
                                                         placeholder="Quant. Convites por Formando"
                                                         class="form-control <?= $addClass ?>"
                                                         data-id="<?= $tipoevento_id ?>"/></div>
                            <div class="col-md-3"><input type="number"
                                                         name="orcamento_tipoevento_qt_mesas"
                                                         value="<?php if(isset($Data[$tipoevento_id]['orcamento_tipoevento_qt_mesas'])){echo Form::Value($Data[$tipoevento_id]['orcamento_tipoevento_qt_mesas']);}?>"
                                                         placeholder="Quant. Mesas por Formando"
                                                         class="form-control <?= $addClass ?>"
                                                         data-id="<?= $tipoevento_id ?>"/></div>

                            <div class="clearfix"></div>
                            <hr style="margin: 10px;">

                            <div class="col-md-3"><input type="text"
                                                         name="orcamento_tipoevento_extra_vl_convites"
                                                         value="<?php if(isset($Data[$tipoevento_id]['orcamento_tipoevento_extra_vl_convites'])){echo Form::Value($Data[$tipoevento_id]['orcamento_tipoevento_extra_vl_convites']);}?>"
                                                         placeholder="Valor Convites Extra"
                                                         class="form-control <?= $addClass ?>"
                                                         data-id="<?= $tipoevento_id ?>"/></div>
                            <div class="col-md-3"><input type="number"
                                                         name="orcamento_tipoevento_extra_qt_convites"
                                                         value="<?php if(isset($Data[$tipoevento_id]['orcamento_tipoevento_extra_qt_convites'])){echo Form::Value($Data[$tipoevento_id]['orcamento_tipoevento_extra_qt_convites']);}?>"
                                                         placeholder="Quant. Convites Extras"
                                                         class="form-control <?= $addClass ?>"
                                                         data-id="<?= $tipoevento_id ?>"/></div>
                            <div class="col-md-3"><input type="text"
                                                         name="orcamento_tipoevento_extra_vl_mesas"
                                                         value="<?php if(isset($Data[$tipoevento_id]['orcamento_tipoevento_extra_vl_mesas'])){echo Form::Value($Data[$tipoevento_id]['orcamento_tipoevento_extra_vl_mesas']);}?>"
                                                         placeholder="Valor Mesas Extras"
                                                         class="form-control <?= $addClass ?>"
                                                         data-id="<?= $tipoevento_id ?>"/></div>
                            <div class="col-md-3"><input type="number"
                                                         name="orcamento_tipoevento_extra_qt_mesas"
                                                         value="<?php if(isset($Data[$tipoevento_id]['orcamento_tipoevento_extra_qt_mesas'])){echo Form::Value($Data[$tipoevento_id]['orcamento_tipoevento_extra_qt_mesas']);}?>"
                                                         placeholder="Quant. Mesas Extras"
                                                         class="form-control <?= $addClass ?>"
                                                         data-id="<?= $tipoevento_id ?>"/></div>
                        </div>
                    </div>
                    <input type="hidden" name="tipoevento_id[<?= $tipoevento_id ?>]"
                           value="<?= $tipoevento_id ?>">

                    <?php
                    if ($Checked == 'checked'):
                        echo '
                        <script>
                        $(function () {
                            $("[data-id=' . $tipoevento_id . ']").slideDown(200);
                        });
                        </script>
                        ';
                    endif;
                endforeach;
            else:
            endif;
            ?>
        </div>

    </form>
</div>


<br/><br/>
<br/>


<script>

    $(function () {
        $(".inputOnOff").bootstrapSwitch();
        
        $(".AjaxLive").focusout(function () {
            var id = $(this).data('id');
            var campoName = $(this).attr('name');
            var campoValor = $(this).val();
            var data = {id:id,campoName:campoName,campoValor:campoValor};

            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=LiveUpdateOrcamentoTipoEvento",
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function () {
                    
                }
            });
        });
    });

    $(".inputOnOff").on('switchChange.bootstrapSwitch', function (event, state) {

        var id = $(this).val();
        var StateAjax;
        if (state) {
            $("[data-id=" + id + "]").slideDown(200);
            StateAjax = 'true';

        } else {
            $("[data-id=" + id + "]").slideUp(200);
            StateAjax = 'false';
        }

        //Exclui e cria tipos de eventos no orcamento
        var dados = {state:StateAjax, id:id};
        
        $.ajax({
            url: "../manager/system/helpers/ws.php?fnc=OnOffOrcamentoTipoEvento",
            type: 'POST',
            dataType: 'json',
            data: dados,
            success: function (data) {
                /*
                $.each(data.data, function(index, value){
                    var ul = '';
                    for(var i=0;i<=value.cursos.length-1;i++){
                        ul += "<li>"+value.cursos[i]+"</li>";
                    }

                    tr += "<tr><td>"+value.comissoes_nome+"</td><td>"+value.instituicao_nome+"</td><td>"+value.conclusao+"</td><td><ul>"+ul+"</ul></td><td><a class=\"btn btn-danger delComissao\" data-id=\""+index+"\">-</a></td></tr>";
                });
                $("#ComissoesCadastradas tbody").html(tr);
                */
                if(StateAjax == 'false'){
                    $("[data-id=" + id + "]").val('');
                }


            }
        });
        
    });
    


</script>
<style>
    .switch-button-label {
        font-size: 30px;;
    }

    .inputHidden {
        display: none;
    }
</style>


