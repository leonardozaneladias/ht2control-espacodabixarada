<div class="row">
    <div class="col-lg-12 bs-callout bs-callout-default">
        <div class="col-lg-10">
            <h4>Tipo de Animal</h4>
        </div>
        <div class="col-lg-2">
            <a href="painel.php?exe=tipoanimal/create" title="Cadastrar Novo" class="btn btn-primary">Cadastrar</a>
        </div>
    </div>

    <div class="clearfix"></div>
    <hr style="margin: 8px 0;">
    <?php
    $delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($delete):
        require('_models/AdminTipoEvento.class.php');
        $TPEvento = new AdminTipoEvento;
        $TPEvento->ExeDelete($delete);
        if ($TPEvento->getResult()):
            WSErro($TPEvento->getError()[0], $TPEvento->getError()[1]);
        endif;
    endif;
    ?>

    <?php
    $msgCreated = filter_input(INPUT_GET, 'create', FILTER_DEFAULT);
    $MarkId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($msgCreated == 'true'):
        WSErro("Tipo de Animal cadsatrado com sucesso!", WS_ACCEPT);
    endif;
    ?>
    <table class="table table-hover" id="datatable">
        <thead>
        <tr>
            <th>COD:</th>
            <th>Nome</th>
            <th>Posição</th>
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
        $read = new Read;
        $read->ExeRead("app_tipoevento", "ORDER BY tipoevento_posicao ASC, tipoevento_nome ASC");
        if ($read->getResult()):
            foreach ($read->getResult() as $tipoevento):
                extract($tipoevento);
                ?>
                <tr id="id_mark_<?= $tipoevento_id; ?>">
                    <td><?= $tipoevento_id ?></td>
                    <td><?= $tipoevento_nome; ?></td>
                    <td><?= $tipoevento_posicao; ?></td>
                    <td><?= $tipoevento_status; ?></td>
                    <td>
                        <a href="painel.php?exe=tipoanimal/update&id=<?= $tipoevento_id; ?>"><i
                                class="glyphicon glyphicon-edit"></i></a>
                        <a href="painel.php?exe=tipoanimal/index&delete=<?= $tipoevento_id; ?>"><i
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