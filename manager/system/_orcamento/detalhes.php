<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;

$dataGet = filter_input(INPUT_GET, 'fnc', FILTER_DEFAULT);
?>

<div class="row row-centered">
    <div class="col-lg-12" id="crop-avatar">

        <div class="panel">
            <div class="panel-body text-center">
                <div class="col-lg-1 col-xs-1">
                    <a href="?exe=orcamento/painel"> <i class="glyphicon glyphicon-home"
                                                        style="font-size: 30px; color: #999b9e"></i> </a>
                </div>
                <div class="col-lg-10 col-xs-10">
                    <ol class="breadcrumb" style="margin-bottom: 5px;">
                        <li><a href="?exe=orcamento/painel">Orçamento</a></li>
                        <li class="active">Detalhes</li>
                    </ol>
                </div>
                <div class="col-lg-1 col-xs-1">
                    <a href="?exe=orcamento/painel"> <i class="glyphicon glyphicon-ok" style="font-size: 30px; color: #999b9e"></i> </a>
                </div>
            </div>
        </div>


        <!-- Categoria form -->

        <div class="bs-callout bs-callout-default">
            <h4>Detalhes da Tuma</h4>
        </div>


        <!-- Cropping modal -->
        <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog"
             tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form class="avatar-form" action="system/helpers/crop.php" enctype="multipart/form-data"
                          method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="avatar-modal-label">Seleciona a imagem</h4>
                        </div>
                        <div class="modal-body">
                            <div class="avatar-body">

                                <!-- Upload image and data -->
                                <div class="avatar-upload">
                                    <input type="hidden" class="avatar-src" name="avatar_src">
                                    <input type="hidden" class="avatar-data" name="avatar_data">
                                    <label for="avatarInput">Buscar:</label>
                                    <input type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                                </div>

                                <!-- Crop and preview -->
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="avatar-wrapper"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="avatar-preview preview-lg"></div>
                                        <div class="avatar-preview preview-md"></div>
                                        <div class="avatar-preview preview-sm"></div>
                                    </div>
                                </div>

                                <div class="row avatar-btns">
                                    <div class="col-md-9">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary" data-method="rotate"
                                                    data-option="-90" title="Rotate -90 degrees">Girar Esq.
                                            </button>
                                            <button type="button" class="btn btn-primary" data-method="rotate"
                                                    data-option="-15">-15deg
                                            </button>
                                            <button type="button" class="btn btn-primary" data-method="rotate"
                                                    data-option="-30">-30deg
                                            </button>
                                            <button type="button" class="btn btn-primary" data-method="rotate"
                                                    data-option="-45">-45deg
                                            </button>
                                        </div>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary" data-method="rotate"
                                                    data-option="45">45deg
                                            </button>
                                            <button type="button" class="btn btn-primary" data-method="rotate"
                                                    data-option="30">30deg
                                            </button>
                                            <button type="button" class="btn btn-primary" data-method="rotate"
                                                    data-option="15">15deg
                                            </button>
                                            <button type="button" class="btn btn-primary" data-method="rotate"
                                                    data-option="90" title="Rotate 90 degrees">Girar Dir.
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-block avatar-save">Salvar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div> -->
                    </form>
                </div>
            </div>
        </div><!-- /.modal -->


        <form class="form-horizontal" method="post" name="form_tipoevento" enctype="multipart/form-data">
            <fieldset>
                <?php

                include_once("_models/AdminOrcamento.class.php");
                $Orcamento = new AdminOrcamento;

                if (isset($dataGet) && $dataGet == 'add'):
                    $Orcamento->addDetalhes();
                endif;

                if (isset($dataGet) && $dataGet == 'del'):
                    $Orcamento->delDetalhes();
                endif;


                if ($data = $Orcamento->VerificaDetalhes()):


                    /*
                    if (isset($data['Cadastrar']) or isset($data)):
                        unset($data['Cadastrar']);
                        require('_models/AdminCategoria.class.php');
                        $cadastra = new AdminCategoria;
                        $upload = new AdminCategoria;

                        $cadastra->ExeCreate($data);
                        if ($cadastra->getResult()):
                            header('Location: painel.php?exe=categoria/index&create=true&id=' . $cadastra->getResult());
                        else:
                            WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
                        endif;
                    endif;
                    */

                    ?>

                    <!-- Current avatar -->
                    <div class="avatar-view" title="">

                        <img src="<?php if (!empty($data['orcamento_detalhes_img'])) {
                            echo HOME . '/' . $data['orcamento_detalhes_img'];
                        } else {
                            echo "../uploads/avatar/avatar.jpg";
                        } ?>" alt="Avatar">
                    </div>

                    <div class="col-md-12">
                        <label for="basic-url">Titulo</label>
                        <input type="text" class="form-control AjaxLive" name="orcamento_detalhes_titulo" maxlength="50"
                               value="<?php if (isset($data['orcamento_detalhes_titulo'])) {
                                   echo $data['orcamento_detalhes_titulo'];
                               } ?>">
                    </div>
                    <div class="col-md-12">
                        <label for="basic-url">Texto</label>
                    <textarea class="form-control AjaxLive"
                              rows="5"
                              name="orcamento_detalhes_texto"><?php if (isset($data['orcamento_detalhes_texto'])) {
                            echo $data['orcamento_detalhes_texto'];
                        } ?></textarea>
                    </div>

                    <div class="clearfix"></div>
                    <hr/>

                    <div class="col-md-12">
                        <a href="?exe=orcamento/detalhes&fnc=del" class="btn btn-danger btn-block">Excluir</a>
                    </div>

                    <?php
                else:
                    ?>
                    <div class="clearfix"></div>
                    <hr/>

                    <div class="col-md-12">
                        <a href="?exe=orcamento/detalhes&fnc=add" class="btn btn-primary btn-block">Adicionar</a>
                    </div>

                    <?php
                endif;
                ?>


            </fieldset>
        </form>
    </div>
</div>

<script>
    $(function () {
        $(".AjaxLive").focusout(function () {
            var campoName = $(this).attr('name');
            var campoValor = $(this).val();
            var data = {campoName: campoName, campoValor: campoValor};

            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=LiveUpdateOrcamentoDetalhes",
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function () {

                }
            });
        });
    });
</script>

<!-- Cropper JS -->
<link rel="stylesheet" href="../res/cropper/cropper.min.css">
<link rel="stylesheet" href="../res/cropper/main.css">
<script src="../res/cropper/cropper.min.js"></script>
<script src="<?php echo HOME ?>/manager/js/cropper-detalhes.js"></script>
