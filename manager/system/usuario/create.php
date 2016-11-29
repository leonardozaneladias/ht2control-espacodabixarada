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
            <h4>Criar Usuário</h4>
        </div>

        <!-- Current avatar -->
        <div class="avatar-view" title="Escolha a Foto!">
            <img src="<?php if(!empty($data['user_img'])){echo $data['user_img'];}else{echo "../uploads/avatar/avatar.jpg";}?>" alt="Avatar">
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

        <form class="form-horizontal" method="post" name="form_users" enctype="multipart/form-data">
            <fieldset>
                <?php

                if ($data['Cadastrar']):
                    unset($data['Cadastrar']);
                    var_dump($data);

                    require('_models/AdminUser.class.php');
                    $cadastra = new AdminUser();

                    $cadastra->ExeCreate($data);
                    if ($cadastra->getResult()):
                        header('Location: painel.php?exe=usuario/index&create=true&id=' . $cadastra->getResult());
                    else:
                        WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
                    endif;

                endif;
                ?>
                <div class="col-lg-6">
                    <label for="basic-url">Primeiro Nome</label>
                    <input required type="text" class="form-control" name="user_name" value="<?=Form::Value($data['user_name'])?>">
                </div>
                <div class="col-lg-6">
                    <label for="basic-url">Sobrenome</label>
                    <input required type="text" class="form-control" name="user_lastname" value="<?=Form::Value($data['user_lastname'])?>">
                </div>
                <div class="col-lg-6">
                    <label for="basic-url">E-mail</label>
                    <input required type="text" class="form-control" name="user_email" value="<?=Form::Value($data['user_email'])?>">
                </div>
                <div class="col-lg-6">
                    <label for="basic-url">Telefone</label>
                    <input required type="tel" class="form-control" name="user_tel" maxlength="13" value="<?=Form::Value($data['user_tel'])?>">
                </div>
                <div class="col-lg-12">
                    <label for="basic-url">Password</label>
                    <input required type="password" class="form-control" name="user_password" value="<?=Form::Value($data['user_password'])?>"
                           data-toggle="popover" data-trigger="hover" data-placement="bottom"
                           data-content="A senha deve ter entre 6 e 12 caracteres!">
                </div>
                <div class="col-lg-12">
                    <label for="basic-url">Nível</label>
                    <select required class="form-control" name="user_level">
                        <option value="" disabled selected>Selecione...</option>
                        <?php
                        foreach ($LEVEL as $id => $level_nome):
                            $level_selected = ($data['user_level'] == $id) ? "selected" : "";
                            if ($_SESSION['userlogin']['user_level'] >= $id):
                                echo "<option value=\"{$id}\" {$level_selected}>{$level_nome}</option>";
                            endif;
                        endforeach;
                        ?>
                    </select>
                </div>
                <input type="hidden" name="user_img" id="img_crop" value="<?php if(!empty($data['user_img'])){echo $data['user_img'];}?>">
                <div class="clearfix"></div>

                <hr>

                <div class="col-md-6">
                    <button class="btn btn-danger btn-block">Voltar</button>
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




