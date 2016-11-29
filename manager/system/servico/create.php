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
                <!--  form -->

                <div class="bs-callout bs-callout-default">
                    <h4>Cadastro de Serviço</h4>
                </div>


                <?php
                $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (!empty($data['enviaFormTipoEvento'])):
                    unset($data['enviaFormTipoEvento']);

                    require('_models/AdminServico.class.php');
                    $cadastra = new AdminServico();
                    $cadastra->ExeCreate($data);

                    if (!$cadastra->getResult()):
                        WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
                    else:
                        header('Location: painel.php?exe=servico/index&create=true&id=' . $cadastra->getResult());
                    endif;
                endif;
                ?>


                <div class="col-md-12">
                    <label for="basic-url">Nome</label>
                    <input type="text" class="form-control" name="tipoevento_nome" maxlength="50" value="<?php echo Form::Value($data['tipoevento_nome']);?>">
                </div>
                <div class="col-md-12">
                    <label for="basic-url">Categoria</label>
                    <select class="form-control" name="categoria_servico_id">
                        <option value="" selected>Selecione...</option>
                        <?php

                        foreach ($CATEGORIA_SERVICO as $id => $dados):
                            $Selected = ($categoria_id == $id ? "selected" : "");
                            echo "<option value=\"{$id}\" {$Selected}>{$dados}</option>";
                        endforeach;
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="basic-url">Valor</label>
                    <input type="text" class="form-control" name="valor"><?php echo Form::Value($data['valor']);?>
                </div>
                <div class="col-md-6">
                    <label for="basic-url">Tempo (*em minutos)</label>
                    <select class="form-control" name="categoria_servico_id">
                        <option value="" selected disabled>Selecione...</option>
                        <?php

                        for ($i=20; $i<= 120; $i+=20):
                            //$Selected = ($categoria_id == $id ? "selected" : "");
                            echo "<option value=\"{$i}\">{$i} Minutos</option>";
                        endfor;
                        ?>
                    </select>
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
                        <option value="1" <?php echo Form::SelectOption($data['tipoevento_status'],"1"); ?>>Ativo</option>
                        <option value="0" <?php echo Form::SelectOption($data['tipoevento_status'],"0"); ?>>Inativo</option>
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