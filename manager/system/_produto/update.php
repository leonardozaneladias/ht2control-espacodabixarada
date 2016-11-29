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
                    <h4>Editar Produto</h4>
                </div>


                <?php
                $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $dataId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

                if (!empty($data['formAtualizaTipoEvento'])):
                    unset($data['formAtualizaTipoEvento']);

                    $dataId = $data['id'];
                    unset($data['id']);

                    //var_dump($data);


                    require('_models/AdminTipoEvento.class.php');
                    $cadastra = new AdminTipoEvento;
                    $cadastra->ExeUpdate($dataId, $data);

                    WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
                else:
                    $read = new Read;
                    $read->ExeRead("app_produtos", "WHERE produto_id = :id", "id={$dataId}");
                    if (!$read->getResult()):
                        //header('Location: painel.php?exe=tipoevento/index&bold='.$dataId);
                    else:
                        $data = $read->getResult()[0];
                    endif;
                endif;

                /*
                $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
                if($checkCreate && empty($cadastra)):
                    $tipo = ( empty($data['category_parent']) ? 'seção' : 'categoria');
                    WSErro("A {$tipo} <b>{$data['category_title']}</b> foi cadastrada com sucesso no sistema! Continue atualizando a mesma!", WS_ACCEPT);
                endif;
                */

                ?>


                <div class="col-lg-12">
                    <label for="basic-url">Nome</label>
                    <input type="text" class="form-control" name="produto_nome" maxlength="50" required value="<?php echo Form::Value($data['produto_nome']);?>">
                </div>
                <div class="col-lg-12">
                    <label for="basic-url">Categoria</label>
                    <select class="form-control" name="categoria_cod" required>
                        <option disabled selected>Selecione...</option>
                        <?php
                        foreach ($selectCategoria as $Id => $Cat):
                            echo "<option value=\"{$Id}\">{$Cat}</option>";
                        endforeach;
                        ?>
                    </select>
                </div>

                <div class="col-lg-4">
                    <label for="basic-url">Valor</label>
                    <input type="text" class="form-control valor" name="produto_valor" value="<?php echo Form::Value($data['produto_valor']);?>">
                </div>
                <div class="col-lg-4">
                    <label for="basic-url">Valor Mínimo</label>
                    <input type="text" class="form-control valor" name="produto_valor_minimo" value="<?php echo Form::Value($data['produto_valor_minimo']);?>">
                </div>
                <div class="col-lg-4">
                    <label for="basic-url">Posição</label>
                    <input type="number" class="form-control" name="produto_posicao" value="<?php echo Form::Value($data['produto_posicao']);?>">
                </div>

                <div class="col-lg-3">
                    <label for="basic-url">Mult. Formando</label>
                    <select class="form-control" name="produto_mult_formando">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="basic-url">Mult. Convites</label>
                    <select class="form-control" name="produto_mult_convites">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="basic-url">Convite Extra</label>
                    <select class="form-control" name="produto_extra_mult_mesa">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="basic-url">Mesa Extra</label>
                    <select class="form-control" name="produto_extra_mult_convite">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>

                <div class="col-lg-12">
                    <label for="basic-url">Sub produto de</label>
                    <select class="form-control" name="produto_alias">
                        <option selected value="0">Nenhum</option>
                    </select>
                </div>

                <div class="col-lg-12">
                    <label for="basic-url">Alteranativa Cortesia</label>
                    <select class="form-control" name="produto_alt_cortesia">
                        <option selected disabled>Selecione</option>
                        <?php
                        foreach ($ALTERNATIVA_CORTESIA as $Id => $Cortesia):
                            echo "<option value=\"{$Id}\">{$Cortesia}</option>";
                        endforeach;
                        ?>
                        ?>
                    </select>
                </div>

                <div class="col-lg-12">
                    <label for="basic-url">Descrição</label>
                    <textarea type="text" class="form-control" name="produto_descricao"><?php echo Form::Value($data['produto_descricao']);?></textarea>
                </div>

                <div class="col-lg-12">
                    <label for="basic-url">Observações</label>
                    <textarea type="text" class="form-control" name="produto_obs"><?php echo Form::Value($data['produto_obs']);?></textarea>
                </div>

                <div class="col-lg-12">
                    <label for="basic-url">Status</label>
                    <select class="form-control" name="produto_status">
                        <option selected disabled>Selecione</option>
                        <?php
                        foreach ($STATUS as $Id => $Cortesia):
                            echo "<option value=\"{$Id}\">{$Cortesia}</option>";
                        endforeach;
                        ?>
                        ?>
                    </select>
                </div>

                <div class="clearfix"></div>

                <hr>

                <div class="col-md-6">
                    <button class="btn btn-danger btn-block">Voltar</button>
                </div>
                <div class="col-md-6">
                    <input type="submit" class="btn btn-primary btn-block" value="Enviar" name="CadastrarProduto">
                </div>

            </fieldset>
        </form>
    </div>
</div>