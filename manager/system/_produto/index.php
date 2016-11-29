<div class="row">
    <div class="col-lg-12 bs-callout bs-callout-default">
        <div class="col-lg-10">
            <h4>Produtos</h4>
        </div>
        <div class="col-lg-2">
            <a href="painel.php?exe=produto/create" title="Cadastrar Novo" class="btn btn-primary">Cadastrar</a>
        </div>
    </div>

    <div class="clearfix"></div>
    <hr style="margin: 8px 0;">
    <?php
    require('_models/AdminProduto.class.php');
    $delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($delete):
        $Delete = new AdminProduto;
        $Delete->ExeDelete($delete);
        WSErro($Delete->getError()[0], $Delete->getError()[1]);
    endif;
    ?>

    <?php
    $msgCreated = filter_input(INPUT_GET, 'create', FILTER_DEFAULT);
    $MarkId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if($msgCreated == 'true'):
        WSErro("Produto cadastrado com sucesso!", WS_ACCEPT);
    endif;
    ?>
    <table class="table table-hover" id="datatable">
        <thead>
        <tr>
            <th>COD:</th>
            <th>Nome</th>
            <th>Categoria</th>
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
        $read->ExeRead("app_produtos", "ORDER BY produto_nome ASC, categoria_cod ASC");
        if ($read->getResult()):

            $CATEGORIAS = new AdminProduto;
            $CATEGORIAS = $CATEGORIAS->ListCategorias();

            foreach ($read->getResult() as $produto):
                extract($produto);
                ?>
                <tr id="id_mark_<?= $produto_id; ?>">
                    <td><?= $produto_id ?></td>
                    <td><?= $produto_nome; ?></td>
                    <td><?= $CATEGORIAS[$categoria_cod]; ?></td>
                    <td><?= $STATUS[$produto_status]; ?></td>
                    <td>
                        <a href="painel.php?exe=produto/update&id=<?= $produto_id; ?>"><i
                                class="glyphicon glyphicon-edit"></i></a>
                        <a href="painel.php?exe=produto/index&delete=<?= $produto_id; ?>"><i
                                class="glyphicon glyphicon-remove"></i></a>
                    </td>
                </tr>
                <?php
            endforeach;
        endif;
        ?>
        </tbody>
        <tfoot>
        <tr>
            <th>COD:</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Status</th>
            <th></th>
        </tr>
        </tfoot>
    </table>
    <?php
        if($MarkId):
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
            },
            initComplete: function () {
                this.api().columns().every( function () {
                    var column = this;
                    var select = $('<select class="form-control"><option value=""></option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );

                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );
            }

        });
    });
</script>