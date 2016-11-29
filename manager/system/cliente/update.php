<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;

$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$Id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($data && $data['Atualiza']):

    unset($data['Atualiza']);

    require('_models/AdminCliente.class.php');
    $cadastra = new AdminCliente();
    $cadastra->ExeUpdate($Id, $data);

    if ($cadastra->getResult()):
        Message::FlashMsg("msgAlert", WS_ACCEPT ,"Categoria ".$data['categoria_nome']." foi atualizado com sucesso!", true);
        header('Location: painel.php?exe=cliente/index&bold='.$Id);
    else:
        WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
    endif;
else:
    $ReadUser = new Read;
    $ReadUser->ExeRead("app_cliente", "WHERE cliente_id = :id", "id={$Id}");
    if (!$ReadUser->getResult()):
        Message::FlashMsg("msgAlert", WS_ERROR ,"Cliente não encontrado", true);
        header('Location: painel.php?exe=cliente/index');
    else:
        $data = $ReadUser->getResult()[0];

    endif;
endif;
?>

<div class="row row-centered">
    <div class="col-lg-12" id="crop-avatar">

        <!-- Cliente form -->

        <div class="bs-callout bs-callout-default">
            <h4>Editar Cliente</h4>
        </div>

        <!-- Current avatar -->
        <div class="avatar-view" title="">

            <img src="../<?php if (!empty($data['cliente_img'])) {
                echo $data['cliente_img'];
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

                <div class="col-md-12">
                    <label for="basic-url">Nome</label>
                    <input type="text" class="form-control" name="cliente_nome" maxlength="50"
                           value="<?php if (isset($data['cliente_nome'])) {
                               echo $data['cliente_nome'];
                           } ?>">
                </div>
                <div class="col-md-6">
                    <label for="basic-url">Telefone</label>
                    <input type="text" class="form-control" maxlength="11" name="cliente_telefone"
                           value="<?php if (isset($data['cliente_telefone'])) {
                               echo $data['cliente_telefone'];
                           } ?>">
                </div>
                <div class="col-md-6">
                    <label for="basic-url">E-mail</label>
                    <input type="text" class="form-control" name="cliente_email"
                           value="<?php if (isset($data['cliente_email'])) {
                               echo $data['cliente_email'];
                           } ?>">
                </div>
                <div class="col-md-6">
                    <label for="basic-url">CPF (*apenas números)</label>
                    <input type="text" class="form-control" maxlength="11" name="cliente_cpf"
                           id="valida-cpf"
                           value="<?php if (isset($data['cliente_cpf'])) {
                               echo $data['cliente_cpf'];
                           } ?>">
                </div>
                <div class="col-md-6">
                    <label for="basic-url">CEP (*apenas números)</label>
                    <input type="text" class="form-control" maxlength="8" name="cliente_cep"
                           id="cep"
                           value="<?php if (isset($data['cliente_cep'])) {
                               echo $data['cliente_cep'];
                           } ?>">
                </div>
                <div class="col-md-12">
                    <label for="basic-url">Endereço</label>
                    <input type="text" class="form-control" name="cliente_rua"
                           id="rua"
                           value="<?php if (isset($data['cliente_rua'])) {
                               echo $data['cliente_rua'];
                           } ?>">
                </div>

                <div class="col-md-6">
                    <label for="basic-url">Número</label>
                    <input type="text" class="form-control" maxlength="15" name="cliente_numero"
                           value="<?php if (isset($data['cliente_numero'])) {
                               echo $data['cliente_numero'];
                           } ?>">
                </div>

                <div class="col-md-6">
                    <label for="basic-url">Complemento</label>
                    <input type="text" class="form-control" name="cliente_complemento"
                           value="<?php if (isset($data['cliente_complemento'])) {
                               echo $data['cliente_complemento'];
                           } ?>">
                </div>

                <div class="col-md-6">
                    <label for="basic-url">Bairro</label>
                    <input type="text" class="form-control" name="cliente_bairro"
                           id="bairro"
                           value="<?php if (isset($data['cliente_bairro'])) {
                               echo $data['cliente_bairro'];
                           } ?>">
                </div>

                <div class="col-md-6">
                    <label for="basic-url">Cidade</label>
                    <input type="text" class="form-control" name="cliente_cidade"
                           id="cidade"
                           value="<?php if (isset($data['cliente_cidade'])) {
                               echo $data['cliente_cidade'];
                           } ?>">
                </div>

                <div class="col-md-6">
                    <label for="basic-url">Estado</label>
                    <input type="text" class="form-control" name="cliente_uf"
                           id="estado"
                           value="<?php if (isset($data['cliente_uf'])) {
                               echo $data['cliente_uf'];
                           } ?>">
                </div>

                <div class="col-md-6">
                    <label for="basic-url">Status</label>
                    <select class="form-control" name="cliente_status">
                        <option
                            value="1" <?php if (isset($data['cliente_status']) and ($data['cliente_status'] == "1")) {
                            echo "selected";
                        } ?>>Ativo
                        </option>
                        <option
                            value="0" <?php if (isset($data['cliente_status']) and ($data['cliente_status'] == "0")) {
                            echo "selected";
                        } ?>>Inativo
                        </option>

                    </select>
                </div>

                <div class="col-md-12">
                    <label for="basic-url">Observações</label>
                    <textarea type="text" class="form-control"
                              name="cliente_descricao"><?php if (isset($data['cliente_descricao'])) {
                            echo $data['cliente_descricao'];
                        } ?></textarea>
                </div>

                <input type="hidden" name="cliente_img" id="img_crop"
                       value="<?php if (!empty($data['cliente_img'])) {
                           echo $data['cliente_img'];
                       } ?>">

                <div class="clearfix"></div>

                <hr>

                <div class="col-md-6">
                    <button class="btn btn-danger btn-block">Voltar</button>
                </div>
                <div class="col-md-6">
                    <input type="submit" class="btn btn-primary btn-block" value="Enviar" name="Atualiza">
                </div>

            </fieldset>
        </form>
    </div>
</div>
<br>
<hr>
<br>
<div class="row">
    <div class="col-lg-12 bs-callout bs-callout-default">
        <div class="col-lg-10">
            <h5>Animais deste Cliente</h5>
        </div>
        <div class="col-lg-2">
            <a href="painel.php?exe=animal/create" title="Cadastrar Novo" class="btn btn-primary">Ir até Lista de Animais</a>
        </div>
    </div>

    <div class="clearfix"></div>
    <hr style="margin: 8px 0;">
    <?php

    $clientes = new Read;
    $clientes->ExeRead("app_cliente");
    foreach ($clientes->getResult() as $dados):
        $clienteNome[$dados['cliente_id']] = $dados['cliente_nome'];
    endforeach;

    $raca = new Read;
    $raca->ExeRead("app_categorias");
    foreach ($raca->getResult() as $dados):
        $racaNome[$dados['categoria_id']] = $dados['categoria_nome'];
    endforeach;


    Message::FlashMsg("msgAlert");

    $delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($delete):
        require('_models/AdminAnimal.class.php');
        $Del = new AdminCliente();
        $Del->ExeDelete($delete);
        WSErro("Excluida com sucesso!", WS_ACCEPT);
    endif;
    ?>

    <?php
    $msgCreated = filter_input(INPUT_GET, 'create', FILTER_DEFAULT);
    $MarkId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($msgCreated == 'true'):
        WSErro("Cadastrado com sucesso!", WS_ACCEPT);
    endif;
    ?>
    <table class="table table-hover" id="datatable">
        <thead>
        <tr>
            <th>COD:</th>
            <th>Imagem:</th>
            <th>Nome</th>
            <th>Cliente</th>
            <th>Raça</th>
            <th>Status</th>
            <th><i class="glyphicon glyphicon-list-alt"></i></th>
        </tr>
        </thead>
        <tbody>

        <?php

        $read = new Read;
        $read->ExeRead("app_animal", "WHERE cliente_id = :id ORDER BY nome ASC", "id=".$data['cliente_id']);
        if ($read->getResult()):
            foreach ($read->getResult() as $dados):
                extract($dados);
                $foto = HOME . "/" . $animal_img;
                if(is_file($foto)){
                    $foto = $foto;
                }else{
                    $foto = HOME.'/manager/images/animal-avatar.jpg';
                }
                ?>
                <tr id="id_mark_<?= $id; ?>">
                    <td><?= $id ?></td>
                    <td><img src="<?= $foto ?>" style="width: 30px;"></td>
                    <td><?= $nome; ?></td>
                    <td><?= $racaNome[$raca_id]; ?></td>
                    <td><?= $clienteNome[$cliente_id]; ?></td>
                    <td><?= $STATUS[$status]; ?></td>
                    <td>
                        <a href="painel.php?exe=animal/update&id=<?= $id; ?>"><i
                                class="glyphicon glyphicon-edit" style="font-size: 18px;"></i></a>
                        <a href="painel.php?exe=animal/index&delete=<?= $id; ?>" style="font-size: 18px;"><i
                                class="glyphicon glyphicon-remove"></i></a>
                    </td>
                </tr>


                <?php
            endforeach;
        endif;
        ?>
        </tbody>
    </table>
    <?php
    if ($MarkId):
        echo "
                <style>
                #id_mark_{$MarkId} {
                    background: #B4F8AD;
                }
                </style>
            ";
    endif;
    ?>

    <div class="clear"></div>
</div>


<script>
    $(document).ready(function () {
        $('#datatable').DataTable({
            "oLanguage": {
                "sProcessing": "Aguarde enquanto os dados são carregados ...",
                "sLengthMenu": "Mostrar _MENU_ registros por pagina",
                "sZeroRecords": "Nenhum registro correspondente ao criterio encontrado",
                "sInfoEmtpy": "Exibindo 0 a 0 de 0 registros",
                "sInfo": "Exibindo de _START_ a _END_ de _TOTAL_ registros",
                "sInfoFiltered": "",
                "sSearch": "Procurar",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sPrevious": "Anterior",
                    "sNext": "Próximo",
                    "sLast": "Último"
                }
            }
        });

    });


</script>

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





