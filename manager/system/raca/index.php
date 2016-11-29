<div class="row">
    <div class="col-lg-12 bs-callout bs-callout-default">
        <div class="col-lg-10">
            <h4>Raças</h4>
        </div>
        <div class="col-lg-2">
            <a href="painel.php?exe=raca/create" title="Cadastrar Novo" class="btn btn-primary">Cadastrar</a>
        </div>
    </div>

    <div class="clearfix"></div>
    <hr style="margin: 8px 0;">
    <?php

    Message::FlashMsg("msgAlert");

    $delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($delete):
        require('_models/Admincategoria.class.php');
        $Del = new AdminCategoria();
        $Del->ExeDelete($delete);
        WSErro("Categoria excluida com sucesso!", WS_ACCEPT);
    endif;
    ?>

    <?php
    $msgCreated = filter_input(INPUT_GET, 'create', FILTER_DEFAULT);
    $MarkId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($msgCreated == 'true'):
        WSErro("Categoria cadsatrada com sucesso!", WS_ACCEPT);
    endif;
    ?>
    <table class="table table-hover" id="datatable">
        <thead>
        <tr>
            <th>COD:</th>
            <th>Imagem:</th>
            <th>Nome</th>
            <th>Tipo Evento</th>
            <th>Status</th>
            <th><i class="glyphicon glyphicon-list-alt"></i></th>
        </tr>
        </thead>
        <tbody>
        <!--
        <ul class="ultable">
        <li class="t_title">
            <span class="ui center">Res:</span>
            <span class="un">Nome:</span>
            <span class="ue">E-mail:</span>
            <span class="ur center">Registro:</span>
            <span class="ua center">Atualização:</span>
            <span class="ul center">Nível:</span>
            <span class="ed center">-</span>
        </li>-->

        <?php
        $readTpEventos = new Read;
        $readTpEventos->ExeRead("app_tipoevento");
        foreach ($readTpEventos->getResult() as $tipoevento):
            extract($tipoevento);
            $TPEVENTO[$tipoevento_id] = $tipoevento_nome;
        endforeach;;

        $read = new Read;
        $read->ExeRead("app_categorias", "ORDER BY categoria_nome ASC");
        if ($read->getResult()):
            foreach ($read->getResult() as $categoria):
                extract($categoria);
                ?>
                <tr id="id_mark_<?= $categoria_id; ?>">
                    <td><?= $categoria_id ?></td>
                    <td><img src="<?= HOME . "/" . $categoria_img ?>" style="width: 30px;"></td>
                    <td><?= $categoria_nome; ?></td>
                    <td><?= $TPEVENTO[$tipoevento_cod]; ?></td>
                    <td><?= $STATUS[$categoria_status]; ?></td>
                    <td>
                        <a href="painel.php?exe=raca/update&id=<?= $categoria_id; ?>"><i
                                class="glyphicon glyphicon-edit" style="font-size: 18px;"></i></a>
                        <a href="painel.php?exe=raca/index&delete=<?= $categoria_id; ?>" style="font-size: 18px;"><i
                                class="glyphicon glyphicon-remove"></i></a>
                    </td>
                </tr>


                <!--<li>
                        <span class="ui center"><?= $user_id ?></span>
                        <span class="un"><?= $user_name . ' ' . $user_lastname; ?></span>
                        <span class="ue"><?= $user_email; ?></span>
                        <span class="ur center"><?= date('d/m/Y', strtotime($user_registration)); ?></span>
                        <span class="ua center"><?= $user_lastupdate; ?></span>
                        <span class="ul center"><?= $nivel[$user_level]; ?></span>
                        <span class="ed center">
                            <a href="painel.php?exe=users/update&userid=<?= $user_id; ?>" title="Editar" class="action user_edit">Editar</a>
                            <a href="painel.php?exe=users/users&delete=<?= $user_id; ?>" title="Deletar" class="action user_dele">Deletar</a>
                        </span>
                    </li>-->
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