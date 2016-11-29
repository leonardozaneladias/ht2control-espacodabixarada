<div class="container">
    <div class="row">
        <div class="col-lg-12 bs-callout bs-callout-default">
            <div class="col-lg-10">
                <h4>Usuários</h4>
            </div>
            <div class="col-lg-2">
                <a href="painel.php?exe=usuario/create" title="Cadastrar Novo" class="btn btn-primary">Cadastrar Usuário</a>
            </div>
        </div>

        <div class="clearfix"></div>
        <hr style="margin: 8px 0;">
        <?php
        $delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
        $bold = filter_input(INPUT_GET, 'bold', FILTER_VALIDATE_INT);
        if ($delete):
            require('_models/AdminUser.class.php');
            $delUser = new AdminUser;
            $delUser->ExeDelete($delete);
            WSErro($delUser->getError()[0], $delUser->getError()[1]);
        endif;
        ?>
        <table class="table table-hover" id="datatable">
            <thead>
            <tr>
                <th>Res:</th>
                <th>IMG</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Registro</th>
                <th>Atualização</th>
                <th>Nível</th>
                <th><i class="glyphicon glyphicon-list-alt"></i> </th>
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
            $read->ExeRead("ws_users", "ORDER BY user_level DESC, user_name ASC");
            if ($read->getResult()):
                foreach ($read->getResult() as $user):
                    extract($user);
                    $printBold = ($user_id == $bold) ? "table-success" : "";
                    $user_lastupdate = ($user_lastupdate ? date('d/m/Y H:i', strtotime($user_lastupdate)) . ' hs' : '-');
                    ?>
                    <tr class="<?=$printBold?>">
                        <td><?= $user_id ?></td>
                        <td><?= Check::Image("../".$user_img, "User-".$user_name, 50) ?></td>
                        <td><?= $user_name . ' ' . $user_lastname; ?></td>
                        <td><?= $user_email; ?></td>
                        <td><?= date('d/m/Y', strtotime($user_registration)); ?></td>
                        <td><?= $user_lastupdate; ?></td>
                        <td><?= $LEVEL[$user_level]; ?></td>
                        <td>
                            <a href="painel.php?exe=usuario/update&userid=<?= $user_id; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                            <a href="painel.php?exe=usuario/users&delete=<?= $user_id; ?>"><i class="glyphicon glyphicon-remove"></i></a>
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

    <div class="clear"></div>
    </div>
</div> <!-- content home -->

<script>
    $(document).ready( function () {
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
                    "sFirst":    "Primeiro",
                    "sPrevious": "Anterior",
                    "sNext":     "Próximo",
                    "sLast":     "Último"
                }
            }
        });
    } );
</script>