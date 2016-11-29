<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="row row-centered">
    <div class="col-lg-12">
        <form class="form-horizontal" method="post" name="form_tipoevento">
            <fieldset>
                <!-- Categoria form -->

                <div class="bs-callout bs-callout-default">
                    <h4>Orcamento Cadastrar</h4>
                </div>


                <?php
                $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (!empty($data['Cadastrar'])):
     
                    unset($data['Cadastrar']);
                    //header("Location: ?exe=orcamento/painel");

                    require('_models/AdminOrcamento.class.php');
                    $orcamento = new AdminOrcamento();
                    $orcamento->ExeCreate($data);
                    
                    if (!$orcamento->getResult()):
                        WSErro($orcamento->getError()[0], $orcamento->getError()[1]);
                    else:
                        $_SESSION['orcamento']['id'] = $orcamento->getResult()['id'];
                        $_SESSION['orcamento']['crypt'] = $orcamento->getResult()['crypt'];
                        header('Location: painel.php?exe=orcamento/comissao');
                        $_SESSION['orforcreat'] = true;
                    endif;
                    
                endif;
                ?>


                <div class="col-md-12">
                    <label for="basic-url">Nome</label>
                    <input type="text" class="form-control" name="orcamento_nome" maxlength="50" value="<?php echo Form::Value($data['orcamento_nome']);?>" autofocus>
                </div>
                <div class="col-md-12">
                    <label for="basic-url">Observações</label>
                    <textarea type="text" class="form-control" name="orcamento_obs"><?php echo Form::Value($data['orcamento_obs']);?></textarea>
                </div>
                <div class="col-md-12">
                    <label for="basic-url">Representantes</label>
                    <select class="form-control" name="representante_cod">
                        <option value="" disabled selected>Selecione...</option>
                        <?php
                        $selectOptions = new Read;
                        $selectOptions->ExeRead("app_user__perfil", "INNER JOIN ws_users ON app_user__perfil.user_id = ws_users.user_id WHERE app_user__perfil.perfil_id = :id", "id=1");
                        if ($selectOptions->getResult()):
                            foreach ($selectOptions->getResult() as $options):
                                extract($options);
                                $data['representante_cod'] = (isset($data['representante_cod']) ? $data['representante_cod'] : "");
                                ?>
                                <option
                                    value="<?= $user_id ?>" <?php echo Form::SelectOption($data['representante_cod'],$user_id);?>>
                                    <?= $user_name ?>
                                </option>
                                <?php
                            endforeach;
                        else:
                        endif;
                        ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label for="basic-url">Status</label>
                    <select class="form-control" name="orcamento_status">
                        <?php
                        foreach ($ORCAMENTO_STATUS as $status_id => $status_nome):
                            ?>
                            <option
                                value="<?= $status_id ?>" <?php if(isset($data['orcamento_status'])){echo Form::SelectOption($data['orcamento_status'],$status_id);} ?>>
                                <?= $status_nome ?>
                            </option>
                            <?php
                        endforeach;
                        ?>
                    </select>
                </div>

                <div class="clearfix"></div>

                <hr>

                <div class="col-md-6">
                    <a href="?exe=painel" class="btn btn-danger btn-block">Voltar</a>
                </div>
                <div class="col-md-6">
                    <input type="submit" class="btn btn-primary btn-block" value="Enviar" name="Cadastrar">
                </div>
                <br/><br/><br/>

            </fieldset>
        </form>
    </div>
</div>