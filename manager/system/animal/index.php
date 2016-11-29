<?php $varPrefix = 'animal'; ?>
<div class="row">
    <div class="col-lg-12 bs-callout bs-callout-default">
        <div class="col-lg-10">
            <h4>Animais</h4>
        </div>
        <div class="col-lg-2">
            <a href="painel.php?exe=<?=$varPrefix?>/create" title="Cadastrar Novo" class="btn btn-primary">Cadastrar</a>
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
        $Del = new AdminAnimal();
        $Del->ExeDelete($delete);
        WSErro("Animal excluido com sucesso!", WS_ACCEPT);
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
        $read->ExeRead("app_animal", "ORDER BY nome ASC");
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