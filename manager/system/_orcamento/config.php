<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;

require_once('_models/AdminOrcamento.class.php');
$OrcamentoDados = new AdminOrcamento();
$QtComissao = $OrcamentoDados->getQtIntegranteComissao();


//var_dump($QtComissao);
if ($QtComissao > 0):
    $QtComissao = $QtComissao[0]['qt'];
else:
    $QtComissao = 0;
endif;

?>

<div class="row">

    <div class="panel">
        <div class="panel-body text-center">
            <div class="col-lg-1 col-xs-1">
                <a href=""> <i class="glyphicon glyphicon-home" style="font-size: 30px; color: #999b9e"></i> </a>
            </div>
            <div class="col-lg-11 col-xs-10">
                <ol class="breadcrumb" style="margin-bottom: 5px;">
                    <li><a href="?exe=orcamento/painel">Orçamento</a></li>
                    <li class="active">Configurações</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row row-centered">
    <div class="col-lg-12">
        <form class="form-horizontal" method="post" name="form_tipoevento" id="form_tipoevento">
            <fieldset>
                <!-- Categoria form -->

                <div class="bs-callout bs-callout-default">
                    <h4>Configurações</h4>
                    <p>Neste módulo você pode configurar os principais parametros do orçamento.</p>
                </div>


                <?php

                $SelectTableConfig = new Read;
                $SelectTableConfig->ExeRead("app_orcamento_config", "WHERE orcamento_id = :id", "id={$_SESSION['orcamento']['id']}");
                if (!$SelectTableConfig->getResult()):
                    $Validade = new DateTime();
                    $Validade->add(DateInterval::createFromDateString('+1 month')); // 1 Mês
                    $Dados = ['orcamento_id' => $_SESSION['orcamento']['id'], 'orcamento_config_validade' => $Validade->format('Y-m-d'), 'orcamento_config_album_cache' => 1, 'orcamento_config_album_cache_valor' => 250];
                    $CreateTableConfig = new Create;
                    $CreateTableConfig->ExeCreate('app_orcamento_config', $Dados);
                endif;


                $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                if ($data && $data['Salvar']):
                    unset($data['Salvar']);

                    $Salvar = new AdminOrcamento();
                    $Salvar->SaveConfig($_SESSION['orcamento']['id'], $data);
                    if ($Salvar->getResult()):
                        Message::FlashMsg("AlertMsg", WS_ACCEPT, "<b>Sucesso:</b> Configurações foram salvas com sucesso!");
                        header('Location: painel.php?exe=orcamento/painel');


                    else:
                        WSErro($Salvar->getError()[0], $Salvar->getError()[1]);
                    endif;

                /*
                $cadastra->ExeUpdate($userId, $data);

                if ($cadastra->getResult()):
                    header('Location: painel.php?exe=usuario/index&bold='.$userId);
                else:
                    WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
                endif;
                */

                else:
                    $Read = new Read;
                    $Read->ExeRead("app_orcamento_config", "
                        RIGHT OUTER JOIN app_orcamentos ON app_orcamento_config.orcamento_id = app_orcamentos.orcamento_id
                        WHERE app_orcamentos.orcamento_id = :id"
                        , "id={$_SESSION['orcamento']['id']}");
                    if (!$Read->getResult()):

                    else:
                        $data = $Read->getResult()[0];
                        //var_dump($data);
                    endif;
                endif;
                ?>


                <div class="col-md-12">
                    <label for="basic-url">Nome</label>
                    <input type="text" class="form-control" name="orcamento_nome" maxlength="50"
                           value="<?php echo Form::Value($data['orcamento_nome']); ?>" required>
                </div>

                <div class="col-md-12">
                    <label for="basic-url">Observações</label>
                    <textarea type="text" class="form-control"
                              name="orcamento_obs"><?php echo Form::Value($data['orcamento_obs']); ?></textarea>
                </div>

                <div class="col-md-12">
                    <label for="basic-url">Representantes</label>
                    <select class="form-control" name="representante_cod" required>
                        <option value="" disabled selected>Selecione...</option>
                        <?php
                        $selectOptions = new Read;
                        $selectOptions->ExeRead("app_user__perfil", "INNER JOIN ws_users ON app_user__perfil.user_id = ws_users.user_id WHERE app_user__perfil.perfil_id = :id", "id=1");
                        if ($selectOptions->getResult()):
                            foreach ($selectOptions->getResult() as $options):
                                extract($options);
                                $data['representante_cod'] = (isset($data['representante_cod']) ? $data['representante_cod'] : 0);
                                $SelectedRepre = ($user_id == $data['representante_cod'] ? 'seleted' : '');
                                ?>
                                <option
                                    value="<?= $user_id ?>" <?php echo Form::SelectOption($data['representante_cod'], $user_id); ?> <?= $SelectedRepre ?>>
                                    <?= $user_name ?>
                                </option>
                                <?php
                            endforeach;
                        else:
                        endif;
                        ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="basic-url">Parcelas</label>
                    <select class="form-control" name="orcamento_config_parcelas" required>
                        <?php
                        for ($i = 1; $i <= 30; $i++):
                            $Selected = ($data['orcamento_config_parcelas'] == $i ? 'selected' : '');
                            ?>
                            <option
                                value="<?= $i ?>" <?= $Selected ?>><?= $i ?>
                            </option>
                            <?php
                        endfor;
                        ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="basic-url">Fee</label>
                    <input type="number" max="16" min="10" class="form-control" name="orcamento_config_fee"
                           maxlength="50" value="<?php echo Form::Value($data['orcamento_config_fee']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="basic-url">Qt Integrantes</label>
                    <?php
                    if ($data['orcamento_config_qt_comissao'] <= 0):
                        $data['orcamento_config_qt_comissao'] = $QtComissao;
                    endif;
                    ?>
                    <input type="number" required class="form-control" name="orcamento_config_qt_comissao"
                           maxlength="50" value="<?php echo Form::Value($data['orcamento_config_qt_comissao']); ?>">
                </div>

                <div class="col-md-6">
                    <label for="basic-url">Média por formando</label>
                    <input type="number" step="any" required class="form-control"
                           name="orcamento_config_media_por_formando" maxlength="50"
                           value="<?php echo Form::Value($data['orcamento_config_media_por_formando']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="basic-url">Validade</label>
                    <input type="date" class="form-control" name="orcamento_config_validade" maxlength="50"
                           value="<?php echo Form::Value($data['orcamento_config_validade']); ?>">
                </div>

                <div class="col-md-6">
                    <label for="basic-url">Status</label>
                    <select class="form-control" name="orcamento_status">
                        <option value="" disabled selected>Selecione...</option>
                        <?php
                        foreach ($ORCAMENTO_STATUS as $id => $nome):
                            $_selected = ($id == $data['orcamento_status']) ? "selected" : "";
                            echo "<option value=\"{$id}\" {$_selected}>{$nome}</option>";
                        endforeach;
                        ?>
                    </select>
                </div>


                <div class="clearfix"></div>

                <hr>

                <div class="col-md-6">
                    <a href="?exe=tipoevento/index" class="btn btn-danger btn-block">Voltar</a>
                </div>
                <div class="col-md-6">
                    <input type="submit" class="btn btn-primary btn-block" value="Salvar" name="Salvar">
                </div>
                <br/><br/><br/>

            </fieldset>
        </form>
    </div>
</div>