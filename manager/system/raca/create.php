<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;

$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
?>

<div class="row row-centered">
    <div class="col-lg-12" id="crop-avatar">

        <!-- Categoria form -->

        <div class="bs-callout bs-callout-default">
            <h4>Cadastrar Raça</h4>
        </div>

        <!-- Current avatar -->
        <div class="avatar-view" title="">

            <img src="<?php if (!empty($data['categoria_img'])) {
                echo $data['categoria_img'];
            } else {
                echo "../uploads/avatar/avatar.jpg";
            } ?>" alt="Avatar">
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

                if (isset($data['Cadastrar']) or isset($data)):
                    unset($data['Cadastrar']);
                    require('_models/AdminCategoria.class.php');
                    $cadastra = new AdminCategoria;
                    $upload = new AdminCategoria;

                    $cadastra->ExeCreate($data);
                    if ($cadastra->getResult()):
                        header('Location: painel.php?exe=raca/index&create=true&id=' . $cadastra->getResult());
                    else:
                        WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
                    endif;
                endif;
                ?>
                <div class="col-md-12">
                    <label for="basic-url">Nome da Raça</label>
                    <input type="text" class="form-control" name="categoria_nome" maxlength="50"
                           value="<?php if (isset($data['categoria_nome'])) {
                               echo $data['categoria_nome'];
                           } ?>">
                </div>
                <div class="col-md-12">
                    <label for="basic-url">Tipo de Animal</label>
                    <select class="form-control" name="tipoevento_cod">
                        <option value="" disabled selected>Selecione...</option>
                        <?php
                        $tipoevento = new Read;
                        $tipoevento->ExeRead("app_tipoevento", "WHERE tipoevento_status = 1 ORDER BY tipoevento_nome");
                        if ($tipoevento->getResult()):
                            foreach ($tipoevento->getResult() as $tipoevento):
                                extract($tipoevento);
                                ?>
                                <option
                                    value="<?= $tipoevento_id ?>" <?php if (isset($data['tipoevento_cod']) and ($data['tipoevento_cod'] == $tipoevento_id)) {
                                    echo "selected";
                                } ?>>
                                    <?= $tipoevento_nome ?>
                                </option>
                                <?php
                            endforeach;
                        else:
                        endif;
                        ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label for="basic-url">Descrição</label>
                    <textarea type="text" class="form-control"
                              name="categoria_descricao"><?php if (isset($data['categoria_descricao'])) {
                            echo $data['categoria_descricao'];
                        } ?></textarea>
                </div>

                <div class="col-md-12">
                    <label for="basic-url">Status</label>
                    <select class="form-control" name="categoria_status">
                        <option
                            value="1" <?php if (isset($data['categoria_status']) and ($data['categoria_status'] == "1")) {
                            echo "selected";
                        } ?>>Ativo
                        </option>
                        <option
                            value="0" <?php if (isset($data['categoria_status']) and ($data['categoria_status'] == "0")) {
                            echo "selected";
                        } ?>>Inativo
                        </option>

                    </select>
                </div>
                <input type="hidden" name="categoria_img" id="img_crop"
                       value="<?php if (!empty($data['categoria_img'])) {
                           echo $data['categoria_img'];
                       } ?>">

                <div class="clearfix"></div>

                <hr>

                <div class="col-md-6">
                    <a href="?exe=raca" class="btn btn-danger btn-block">Voltar</a>
                </div>
                <div class="col-md-6">
                    <input type="submit" class="btn btn-primary btn-block" value="Enviar" name="Cadastrar">
                </div>

            </fieldset>
        </form>
    </div>
</div>

<!-- Cropper JS  -->
<link rel="stylesheet" href="../res/cropper/cropper.min.css">
<link rel="stylesheet" href="../res/cropper/main.css">
<script src="../res/cropper/cropper.min.js"></script>
<script src="../res/cropper/main.js"></script>





