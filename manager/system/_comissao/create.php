<?php if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;

$prx_model = "comissoes"; // nome ou prefixo da tabela do banco de dados
$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

?>

<div class="row row-centered">
    <div class="col-lg-12" id="crop-avatar">

        <!-- Categoria form -->

        <div class="bs-callout bs-callout-default">
            <h4>Cadastrar Comissão de Formatura</h4>
        </div>

        <!-- Current avatar -->
        <div class="avatar-view" title="">

            <img src="<?php if (!empty($data['comissao_img'])) {
                echo $data['comissao_img'];
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
                                <div class="avatar-upload form-group">
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

                if ($data['Cadastrar']):
                    unset($data['Cadastrar']);

                    require('_models/AdminComissao.class.php');

                    $cadastra = new AdminComissao();

                    $cadastra->ExeCreate($data);
                    if (!$cadastra->getResult()):
                        WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
                    else:
                        Message::FlashMsg("AlertMsg",WS_ACCEPT,"Comissão cadastrada com sucesso", true);
                        header("Location: ?exe=comissao/index&id=".$cadastra->getResult());
                    endif;


                endif;
                ?>
                <div class="col-md-12">
                    <label for="basic-url">Nome</label>
                    <input type="text" class="form-control" name="<?= $prx_model . "_nome" ?>" maxlength="50"
                           data-toggle="popover" data-trigger="hover" data-placement="bottom"
                           data-content="Nome ou apelido da TURMA!"
                           value="<?php if (isset($data[$prx_model . "_nome"])) {
                               echo $data[$prx_model . "_nome"];
                           } ?>" required>
                </div>

                <div class="col-md-12">
                    <label for="basic-url">E-mail</label>
                    <input type="email" class="form-control" name="<?= $prx_model . "_email" ?>" maxlength="50"
                           data-toggle="popover" data-trigger="hover" data-placement="bottom"
                           data-content="Inserir e-mail geral da comissão de formatura, melhor se for um grupo onde todos recebam!"
                           value="<?php if (isset($data[$prx_model . "_email"])) {
                               echo $data[$prx_model . "_email"];
                           } ?>" required
                    >
                </div>

                <div class="col-md-12">
                    <label for="basic-url">Instituição</label>
                    <select class="form-control" name="instituicao_cod" id="selectInstituicao" required>
                        <option value="" disabled selected>Selecione...</option>
                        <?php
                        $selectAction = new Read;
                        $selectAction->ExeRead("app_instituicoes", "WHERE instituicao_status = 1 ORDER BY instituicao_nome");
                        if ($selectAction->getResult()):
                            foreach ($selectAction->getResult() as $selectResult):
                                extract($selectResult);
                                ?>
                                <option
                                    value="<?= $instituicao_id ?>" <?php if (isset($data['instituicao_cod']) and ($data['instituicao_cod'] == $instituicao_id)) {
                                    echo "selected";
                                } ?>>
                                    <?= $instituicao_nome ?>
                                </option>
                                <?php
                            endforeach;
                        else:
                        endif;
                        ?>
                    </select>
                </div>
                <?php
                if (isset($data['comissao_cursos']) && is_array($data['comissao_cursos'])):

                    $BuscaDadosSelect = new Read;
                    $BuscaDadosSelect->ExeRead("app_campus", "WHERE instituicao_cod = :id", "id={$data['instituicao_cod']}");
                    if ($BuscaDadosSelect->getResult()):
                        foreach ($BuscaDadosSelect->getResult() as $campus):
                            extract($campus);
                            $retorno['campus'][$campus_id] = $campus_nome;
                        endforeach;
                        $BuscaDadosSelect->ExeRead("app_instituicoes_cursos", "WHERE instituicao_cod = :id", "id={$data['instituicao_cod']}");
                        if ($BuscaDadosSelect->getResult()):
                            $Cursos = new Read;
                            $Cursos->ExeRead("app_cursos", "WHERE curso_status = :status", "status=1");
                            foreach ($Cursos->getResult() as $cursos_ids):
                                extract($cursos_ids);
                                $cursos_id[$curso_id] = $curso_nome;
                            endforeach;
                            foreach ($BuscaDadosSelect->getResult() as $cursos_inst):
                                extract($cursos_inst);
                                $retorno['cursos'][$curso_cod] = $cursos_id[$curso_cod];
                            endforeach;
                        endif;
                    endif;

                    ?>
                    <div class="col-md-12" style="margin-top: 20px; margin-bottom: -10px;">
                        <table class="table table-bordered" id="adicionaComissao">
                            <thead>
                            <tr>
                                <th style="width: 30%">Curso</th>
                                <th style="width: 30%">Campus</th>
                                <th style="width: 30%">Periodo</th>
                                <th style="width: 10%" class="text-center"><i
                                        class="glyphicon glyphicon-info-sign"></i>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            for ($i = 0; $i <= count($data['comissao_cursos']) - 1; $i++):
                                ?>
                                <tr>
                                    <td>
                                        <select class="form-control selectCursos" name="comissao_cursos[]">
                                            <option value="" selected>Selecione...</option>
                                            <?php
                                            foreach ($retorno['cursos'] as $id => $curso):
                                                $Selected = ($data['comissao_cursos'][$i] == $id ? "selected" : "");
                                                ?>
                                                <option
                                                    value="<?= $id ?>" <?= $Selected ?>>
                                                    <?= $curso ?></option>
                                                <?php
                                            endforeach;
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control selectCampus" name="comissao_campus[]">
                                            <option value="" selected>Selecione...</option>
                                            <?php
                                            foreach ($retorno['campus'] as $id => $campus):
                                                $Selected = ($data['comissao_campus'][$i] == $id ? "selected" : "");
                                                ?>
                                                <option
                                                    value="<?= $id ?>" <?= $Selected ?>>
                                                    <?= $campus ?></option>
                                                <?php
                                            endforeach;
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" name="comissao_periodo[]">
                                            <option value="" selected>Selecione...</option>
                                            <?php

                                            foreach ($PERIODO as $id => $periodo):
                                                $Selected = ($data['comissao_periodo'][$i] == $id ? "selected" : "");
                                                echo "<option value=\"{$id}\" {$Selected}>{$periodo}</option>";
                                            endforeach;
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <a class="btn btn-primary AddComissao"><i class="glyphicon glyphicon-plus"></i>
                                        </a>
                                        <a class="btn btn-danger DelComissao"><i class="glyphicon glyphicon-minus"
                                                                                 id="primeira_linha"></i> </a>
                                    </td>
                                </tr>
                                <?php
                            endfor;
                            ?>
                            </tbody>

                        </table>
                    </div>
                    <?php
                else:
                    ?>
                    <div class="col-md-12" style="margin-top: 20px; margin-bottom: -10px;">
                        <table class="table table-bordered" id="adicionaComissao">
                            <thead>
                            <tr>
                                <th style="width: 30%">Curso</th>
                                <th style="width: 30%">Campus</th>
                                <th style="width: 30%">Periodo</th>
                                <th style="width: 10%" class="text-center"><i
                                        class="glyphicon glyphicon-info-sign"></i>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select class="form-control selectCursos" name="comissao_cursos[]">
                                        <option value="" disabled selected>Selecione a instituição...</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control selectCampus" name="comissao_campus[]">
                                        <option value="" disabled selected>Selecione a instituição...</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" name="comissao_periodo[]">
                                        <option value="" selected>Selecione...</option>
                                        <?php
                                        foreach ($PERIODO as $id => $periodo):
                                            echo "<option value=\"{$id}\">{$periodo}</option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <a class="btn btn-primary AddComissao"><i class="glyphicon glyphicon-plus"></i>
                                    </a>
                                    <a class="btn btn-danger DelComissao"><i class="glyphicon glyphicon-minus"
                                                                             id="primeira_linha"></i> </a>
                                </td>
                            </tr>
                            </tbody>

                        </table>
                    </div>
                    <?php
                endif;
                ?>

                <div class="col-md-6">
                    <label for="basic-url">Conclusão Ano</label>
                    <select class="form-control" name="comissao_conclusao_ano">
                        <option disabled selected>Selecione...</option>
                        <?php
                        $ano = date("Y");
                        $limite = 6;
                        for ($i = $ano; $i <= ($ano + $limite); $i++):
                            $SelectedAno = ($data['comissao_conclusao_ano'] == $i) ? "selected" : "";
                            echo "<option value='{$i}' {$SelectedAno}>{$i}</option>";
                        endfor;
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="basic-url">Conclusão Mês</label>
                    <select class="form-control" name="comissao_conclusao_mes">
                        <option disabled selected>Selecione...</option>
                        <?php
                        $mes = [7 => "Julho", 12 => "Dezembro"];
                        foreach ($mes as $numeroMes => $nomeMes):
                            $SelectedMes = ($data['comissao_conclusao_mes'] == $numeroMes) ? "selected" : "";
                            echo "<option value='{$numeroMes}' {$SelectedMes}>{$nomeMes}</option>";
                        endforeach;
                        ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label for="basic-url">Representante</label>
                    <select class="form-control" name="representante_cod">
                        <?php
                        $Representantes = new Read;
                        $Representantes->ExeRead("app_user__perfil", "INNER JOIN ws_users ON app_user__perfil.user_id = ws_users.user_id WHERE app_user__perfil.perfil_id = :id", "id=1");
                        if (!$Representantes->getResult()):
                            echo "<option disabled selected>Nenhum representante encontrado!</option>";
                        else:
                            echo "<option disabled selected>Selecione...</option>";
                            foreach ($Representantes->getResult() as $dados):
                                extract($dados);
                                $SelectedRepre = ($user_id == $data['representante_cod']) ? "selected" : "";
                                echo "<option value=\"{$user_id}\" {$SelectedRepre}>{$user_name}</option>";
                            endforeach;

                        endif;
                        ?>

                    </select>
                </div>

                <div class="col-md-12">
                    <label for="basic-url">Observações</label>
                    <textarea type="text" class="form-control"
                              name="comissao_obs"><?php if (isset($data['comissao_obs'])) {
                            echo $data['comissao_obs'];
                        } ?></textarea>
                </div>

                <div class="col-md-12">
                    <label for="basic-url">Status</label>
                    <select class="form-control" name="comissao_status">
                        <option
                            value="1" <?php if (Check::Status($data['comissao_status'])) {
                            echo "selected";
                        } ?>>Ativo
                        <option
                            value="0" <?php if (Check::Status($data['comissao_status'])) {
                            echo "selected";
                        } ?>>Inativo
                        </option>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12" style="border-bottom: 1px solid #9d9d9d;">
                    <h3>Membros Integrantes</h3>
                </div>

                <div class="col-md-12" style="margin-top: 20px; margin-bottom: -10px;">
                    <table class="table table-bordered" id="adicionaIntegrante">
                        <thead>
                        <tr>
                            <th style="width: 30%">Nome</th>
                            <th style="width: 30%">E-mail</th>
                            <th style="width: 30%">Telefone</th>
                            <th style="width: 10%" class="text-center"><i class="glyphicon glyphicon-info-sign"></i>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (count($data['integrante_nome']) > 0):
                            for ($i = 0; $i <= count($data['integrante_nome']) - 1; $i++):
                                ?>
                                <tr>
                                    <td>
                                        <input type="text" name="integrante_nome[]"
                                               value="<?= $data['integrante_nome'][$i] ?>" class="form-control">
                                    </td>
                                    <td>
                                        <input type="email" name="integrante_email[]"
                                               value="<?= $data['integrante_email'][$i] ?>" class="form-control">
                                    </td>
                                    <td>
                                        <input type="telefone" name="integrante_telefone[]"
                                               value="<?= $data['integrante_telefone'][$i] ?>" class="form-control">
                                    </td>
                                    <td>
                                        <a class="btn btn-primary AddIntegrante"><i
                                                class="glyphicon glyphicon-plus"></i> </a>
                                        <a class="btn btn-danger DelIntegrante"><i class="glyphicon glyphicon-minus"
                                                                                   id="primeira_linha"></i> </a>
                                    </td>
                                </tr>
                                <?php
                            endfor;
                        else:
                            ?>
                            <tr>
                                <td>
                                    <input type="text" name="integrante_nome[]" class="form-control">
                                </td>
                                <td>
                                    <input type="email" name="integrante_email[]" class="form-control">
                                </td>
                                <td>
                                    <input type="telefone" name="integrante_telefone[]" class="form-control">
                                </td>
                                <td>
                                    <a class="btn btn-primary AddIntegrante"><i class="glyphicon glyphicon-plus"></i>
                                    </a>
                                    <a class="btn btn-danger DelIntegrante"><i class="glyphicon glyphicon-minus"
                                                                               id="primeira_linha"></i> </a>
                                </td>
                            </tr>
                            <?php
                        endif;
                        ?>
                        </tbody>

                    </table>
                </div>

                <input type="hidden" name="comissao_img" id="img_crop"
                       value="<?php if (isset($data['comissao_img'])) {
                           echo $data['comissao_img'];
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
    $(function () {
        $("#adicionaComissao").on("click", ".AddComissao", function (e) {
            var selectCursos = $("#adicionaComissao tbody > tr").html();
            $("#adicionaComissao tbody").prepend("<tr>" + selectCursos + "</tr>");
        });

        $("#adicionaComissao tbody").on("click", ".DelComissao", function (e) {
            var i = 0;
            $("#adicionaComissao tbody tr").each(function () {
                i++;
            });
            if (i == 1) {
                return false;
            }
            $(this).parent().parent().remove();
        })


        $("#adicionaIntegrante").on("click", ".AddIntegrante", function (e) {
            var selectCursos = $("#adicionaIntegrante tbody > tr").html();
            $("#adicionaIntegrante tbody").prepend("<tr>" + selectCursos + "</tr>");
        });

        $("#adicionaIntegrante tbody").on("click", ".DelIntegrante", function (e) {
            var i = 0;
            $("#adicionaIntegrante tbody tr").each(function () {
                i++;
            });
            if (i == 1) {
                return false;
            }
            $(this).parent().parent().remove();
        });

        $("#selectInstituicao").change(function () {
            var intId = $(this).val();
            $.ajax({
                url: "../manager/system/helpers/ws.php?fnc=getInstiuicoesDados",
                type: 'POST',
                data: {id: intId},
                dataType: 'json',
                success: function (data) {
                    var selectCursos = '';
                    var selectCampus = '';

                    if (data.error <= 0) {
                        console.log(data);
                        $('.selectCursos').html("");
                        $('.selectCursos').append('<option value="" selected>Selecione...</option>');
                        for (var curso in data.data.cursos) {
                            $(".selectCursos").append('<option value="' + data.data.cursos[curso]["id"] + '">' + data.data.cursos[curso]["nome"] + '</option>\n');
                        }
                        $('.selectCampus').html("");
                        $('.selectCampus').append('<option value="" selected>Selecione...</option>');
                        for (var campus in data.data.campus) {
                            $(".selectCampus").append('<option value="' + data.data.campus[campus]["id"] + '">' + data.data.campus[campus]["nome"] + '</option>\n');
                        }
                    }
                }
            });
        });
    })
</script>




