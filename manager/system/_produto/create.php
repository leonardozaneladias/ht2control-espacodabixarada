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
                    <h4>Cadastrar Produto</h4>
                </div>


                <?php
                require('_models/AdminProduto.class.php');
                $cadastra = new AdminProduto;
                $selectCategoria = $cadastra->ListCategorias();

                $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);


                if (!empty($data['CadastrarProduto'])):
                    unset($data['CadastrarProduto']);
 
                    $cadastra->ExeCreate($data);

                    if (!$cadastra->getResult()):
                        WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
                    else:
                        header('Location: painel.php?exe=produto/index&create=true&id=' . $cadastra->getResult());
                    endif;
                endif;
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

                <div class="col-lg-2">
                    <label for="basic-url">Mult. Formando</label>
                    <select class="form-control" name="produto_mult_formando" id="produto_mult_formando">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="basic-url">Mult. Convites</label>
                    <select class="form-control" name="produto_mult_convites" id="produto_mult_convites" disabled>
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="basic-url">Mult. Mesas</label>
                    <select class="form-control" name="produto_mult_mesas" id="produto_mult_mesas" disabled>
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="basic-url">+ Convite Extra</label>
                    <select class="form-control" name="produto_extra_mult_convite" id="produto_extra_mult_convite" disabled>
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="basic-url">+ Mesa Extra</label>
                    <select class="form-control" name="produto_extra_mult_mesa" id="produto_extra_mult_mesa" disabled>
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
<script>
    $(function () {
        $('.valor').mask("#.##0,00", {reverse: true});


        $('#produto_mult_formando, #produto_mult_convites, #produto_mult_mesas, #produto_extra_mult_convite, #produto_extra_mult_mesa').change(function () {

            var multFormando = parseInt($('#produto_mult_formando').val());
            var multConvite = parseInt($('#produto_mult_convites').val());
            var multMesa = parseInt($('#produto_mult_mesas').val());
            var extraConvite = parseInt($('#produto_extra_mult_convite').val());
            var extraMesa = parseInt($('#produto_extra_mult_mesa').val());

            if(
                multFormando == 0
            ){
                alert('1');
                $('#produto_mult_convites').attr('disabled', true).val(0);
                $('#produto_mult_mesas').attr('disabled', true).val(0);

                $('#produto_extra_mult_convite').attr('disabled', true).val(0);
                $('#produto_extra_mult_mesa').attr('disabled', true).val(0);

            }else if(
                multFormando == 1 && multConvite == 0 && multMesa == 0 && extraConvite == 0 && extraMesa == 0
            ){
                alert('2');
                $('#produto_mult_convites').attr('disabled', false);
                $('#produto_mult_mesas').attr('disabled', false);

                $('#produto_extra_mult_convite').attr('disabled', true).val(0);
                $('#produto_extra_mult_mesa').attr('disabled', true).val(0);
            }
            else if(
                multFormando == 1 && multConvite == 1 && multMesa == 0 && extraConvite == 0 && extraMesa == 0
            ){
                alert('3');
                $('#produto_mult_convites').attr('disabled', false);
                $('#produto_mult_mesas').attr('disabled', true).val(0);
                $('#produto_extra_mult_convite').attr('disabled', false);

            }else if(
                multFormando == 1 && multConvite == 0 && multMesa == 1 && extraConvite == 0 && extraMesa == 0
            ){
                alert('4');
                $('#produto_mult_convites').attr('disabled', true).val(0);
                $('#produto_mult_mesas').attr('disabled', false);
                $('#produto_extra_mult_mesa').attr('disabled', false);
            }
        });

    });
</script>