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
                    <h4>Tipo de Animal</h4>
                </div>


                <?php
                $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (!empty($data['enviaFormTipoEvento'])):
                    unset($data['enviaFormTipoEvento']);

                    require('_models/AdminTipoEvento.class.php');
                    $cadastra = new AdminTipoEvento();
                    $cadastra->ExeCreate($data);

                    if (!$cadastra->getResult()):
                        WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
                    else:
                        header('Location: painel.php?exe=tipoanimal/index&create=true&id=' . $cadastra->getResult());
                    endif;
                endif;
                ?>


                <div class="col-md-12">
                    <label for="basic-url">Nome</label>
                    <input type="text" class="form-control" name="tipoevento_nome" maxlength="50" value="<?php echo Form::Value($data['tipoevento_nome']);?>">
                </div>
                <div class="col-md-12">
                    <label for="basic-url">Descrição</label>
                    <textarea type="text" class="form-control" name="tipoevento_descricao"><?php echo Form::Value($data['tipoevento_descricao']);?></textarea>
                </div>
                <div class="col-md-6">
                    <label for="basic-url">Posição</label>
                    <input type="number" class="form-control" name="tipoevento_posicao"  value="<?php echo Form::Value($data['tipoevento_posicao']);?>">
                </div>
                <div class="col-md-6">
                    <label for="basic-url">Status</label>
                    <select class="form-control" name="tipoevento_status">
                        <option value="0" <?php echo Form::SelectOption($data['tipoevento_status'],"0"); ?>>Inativo</option>
                        <option value="1" <?php echo Form::SelectOption($data['tipoevento_status'],"1"); ?>>Ativo</option>
                    </select>
                </div>

                <div class="clearfix"></div>

                <hr>

                <div class="col-md-6">
                    <a href="?exe=tipoevento/index" class="btn btn-danger btn-block">Voltar</a>
                </div>
                <div class="col-md-6">
                    <input type="submit" class="btn btn-primary btn-block" value="Enviar" name="enviaFormTipoEvento">
                </div>

            </fieldset>
        </form>
    </div>
</div>