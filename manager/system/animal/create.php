<?php
$modelProfix = 'animal';
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;

$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
?>

<div class="row row-centered">
    <div class="col-lg-12" id="crop-avatar">

        <!-- Cliente form -->

        <div class="bs-callout bs-callout-default">
            <h4>Cadastrar <?=$modelProfix?></h4>
        </div>

        <!-- Current avatar -->
        <div class="avatar-view" title="">

            <img src="<?php if (!empty($data[$modelProfix.'_img'])) {
                echo $data[$modelProfix.'_img'];
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
                    require('_models/AdminAnimal.class.php');
                    $cadastra = new AdminAnimal();

                    $cadastra->ExeCreate($data);
                    if ($cadastra->getResult()):
                        header('Location: painel.php?exe=animal/index&create=true&id=' . $cadastra->getResult());
                    else:
                        WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
                    endif;
                endif;
                ?>
                <div class="col-md-12">
                    <label for="basic-url">Nome do Animal</label>
                    <input type="text" class="form-control" name="nome" maxlength="50"
                           value="<?php if (isset($data['nome'])) {
                               echo $data['nome'];
                           } ?>">
                </div>

                <div class="col-md-12">
                    <label for="basic-url">Cliente</label>
                    <select class="form-control" name="cliente_id"  required>
                        <option value="" disabled selected>Selecione...</option>
                        <?php
                        $selectAction = new Read;
                        $selectAction->ExeRead("app_cliente", "WHERE cliente_status = :status", "status=1");
                        if ($selectAction->getResult()):
                            foreach ($selectAction->getResult() as $selectResult):
                                ?>
                                <option
                                    value="<?= $selectResult['cliente_id'] ?>" <?php if (isset($data['cliente_id']) and ($data['cliente_id'] == $selectResult['cliente_id'])) {
                                    echo "selected";
                                } ?>>
                                    <?= $selectResult['cliente_nome']; ?>
                                </option>
                                <?php
                            endforeach;
                        else:
                        endif;
                        ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="basic-url">Raça</label>
                    <select class="form-control" name="raca_id"  required>
                        <option value="" disabled selected>Selecione...</option>
                        <?php

                        $selectAction->FullRead("SELECT app_tipoevento.tipoevento_nome, app_categorias.categoria_id, app_categorias.categoria_nome FROM app_categorias INNER JOIN app_tipoevento ON app_categorias.tipoevento_cod = app_tipoevento.tipoevento_id WHERE app_categorias.categoria_status = 1 AND app_tipoevento.tipoevento_status = 1 ORDER BY app_tipoevento.tipoevento_nome, app_categorias.categoria_nome");
                        if ($selectAction->getResult()):
                            foreach ($selectAction->getResult() as $selectResult):
                                ?>
                                <option
                                    value="<?= $selectResult['categoria_id'] ?>" <?php if (isset($data['raca_id']) and ($data['raca_id'] == $selectResult['categoria_id'])) {
                                    echo "selected";
                                } ?>>
                                    <?= $selectResult['tipoevento_nome']." / ".$selectResult['categoria_nome'] ?>
                                </option>
                                <?php
                            endforeach;
                        else:
                        endif;
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="basic-url">Tipo de Pelo</label>
                    <select class="form-control" name="tipo_pelo">
                        <option
                            value="Longo" <?php if (isset($data['tipo_pelo']) and ($data['tipo_pelo'] == "Longo")) {
                            echo "selected";
                        } ?>>Longo
                        </option>
                        <option
                            value="Curto" <?php if (isset($data['tipo_pelo']) and ($data['tipo_pelo'] == "Curto")) {
                            echo "selected";
                        } ?>>Curto
                        </option>

                    </select>
                </div>
                <div class="col-md-4">
                    <label for="basic-url">Peso (KG)</label>
                    <input type="text" class="form-control" maxlength="4" name="peso" placeholder="X.XX"
                           value="<?php if (isset($data['peso'])) {
                               echo $data['peso'];
                           } ?>">
                </div>

                <div class="col-md-4">
                    <label for="basic-url">Sexo</label>
                    <select class="form-control" name="sexo">
                        <option
                            value="MACHO" <?php if (isset($data['sexo']) and ($data['sexo'] == "MACHO")) {
                            echo "selected";
                        } ?>>MACHO
                        </option>
                        <option
                            value="FÊMEA" <?php if (isset($data['sexo']) and ($data['sexo'] == "FÊMEA")) {
                            echo "selected";
                        } ?>>FÊMEA
                        </option>

                    </select>
                </div>

                <div class="col-md-4">
                    <label for="basic-url">Data de Nascimento</label>
                    <input type="date" class="form-control" maxlength="11" name="nascimento"
                           value="<?php if (isset($data['nascimento'])) {
                               echo $data['nascimento'];
                           } ?>">
                </div>


                <div class="col-md-12">
                    <label for="basic-url">Status</label>
                    <select class="form-control" name="status">
                        <option
                            value="1" <?php if (isset($data['status']) and ($data['status'] == "1")) {
                            echo "selected";
                        } ?>>Ativo
                        </option>
                        <option
                            value="0" <?php if (isset($data['status']) and ($data['status'] == "0")) {
                            echo "selected";
                        } ?>>Inativo
                        </option>

                    </select>
                </div>

                <div class="col-md-12">
                    <label for="basic-url">Observações</label>
                    <textarea type="text" class="form-control"
                              name="descricao"><?php if (isset($data['descricao'])) {
                            echo $data['descricao'];
                        } ?></textarea>
                </div>

                <input type="hidden" name="animal_img" id="img_crop"
                       value="<?php if (!empty($data['animal_img'])) {
                           echo $data['animal_img'];
                       } ?>">

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

<script>

    $(document).ready( function() {
        /* Executa a requisição quando o campo CEP perder o foco */
        $('#cep').blur(function(){
            /* Configura a requisição AJAX */
            $.ajax({
                url : 'consulta_cep.php', /* URL que será chamada */
                type : 'POST', /* Tipo da requisição */
                data: 'cep=' + $('#cep').val(), /* dado que será enviado via POST */
                dataType: 'json', /* Tipo de transmissão */
                success: function(data){
                    if(data.sucesso == 1){
                        $('#rua').val(data.rua);
                        $('#bairro').val(data.bairro);
                        $('#cidade').val(data.cidade);
                        $('#estado').val(data.estado);

                        $('#numero').focus();
                    }else{
                        alert('Cep não encontrado!');
                    }
                }
            });
            return false;
        });

        $('#valida-cpf').keyup(function () {

            var value = $(this).val();
            if(value.length >= 11){
                if(!validarCPF(value)){
                    alert('CPF inválido, tente novamente.');
                    $(this).val('');
                }
            }


        });

    });

    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g,'');
        if(cpf == '') return false;
        // Elimina CPFs invalidos conhecidos
        if (cpf.length != 11 ||
            cpf == "00000000000" ||
            cpf == "11111111111" ||
            cpf == "22222222222" ||
            cpf == "33333333333" ||
            cpf == "44444444444" ||
            cpf == "55555555555" ||
            cpf == "66666666666" ||
            cpf == "77777777777" ||
            cpf == "88888888888" ||
            cpf == "99999999999")
            return false;
        // Valida 1o digito
        add = 0;
        for (i=0; i < 9; i ++)
            add += parseInt(cpf.charAt(i)) * (10 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(9)))
            return false;
        // Valida 2o digito
        add = 0;
        for (i = 0; i < 10; i ++)
            add += parseInt(cpf.charAt(i)) * (11 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(10)))
            return false;
        return true;
    }

</script>





