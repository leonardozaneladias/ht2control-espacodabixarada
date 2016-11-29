<?php

/**
 * AdminOrcamento.class [ MODEL ADMIN ]
 * Responável por gerenciar todos os orçamentos do sistema no manager!
 *
 * @copyright(c) 2016, Leonardo Zanela - ht2mlTech
 */
class AdminBar
{

    private $Data;
    private $Id;
    private $Error;
    private $Result;
    private $Config = [];
    private $TipoEvento;

    //Nome das tabelas no banco de dados!
    const Entity_app_bar_fornecedores = 'app_bar_fornecedores';
    const Entity_app_bar_bebidas = 'app_bar_bebidas';
    const Entity_app_bar_cardapio_bebidas = 'app_bar_cardapio_bebidas';
    const Entity_app_bar_cardapios = 'app_bar_cardapios';

    const app_orcamento_bar = 'app_orcamento_bar';
    const app_orcamento_bar_bebidas = 'app_orcamento_bar_bebidas';

    /**
     * <b>Cadastrar Orçamento:</b> Envelope os dados e envie para cadastrar
     * @param ARRAY $Data = Atribuitivo
     */
    public function listaFornecedores()
    {
        $Read = new Read;
        $Read->ExeRead(self::Entity_app_bar_fornecedores, "WHERE bar_fornecedor_status = :status ORDER BY bar_fornecedor_nome", "status=1");
        if ($Read->getResult()):
            return $Read->getResult();
        else:
            return false;
        endif;
    }


    public function listaCardapios($fornecedorId)
    {
        $Read = new Read;
        $Read->ExeRead(self::Entity_app_bar_cardapios, "WHERE bar_fornecedor_cod = :fid AND bar_cardapio_status = :status ORDER BY bar_cardapio_nome", "fid={$fornecedorId}&status=1");
        if ($Read->getResult()):
            return $Read->getResult();
        else:
            return false;
        endif;
    }


    public function verificaBarTipoEvento($OrcamentoId, $TipoEventoId)
    {
        $Read = new Read;
        $Read->ExeRead(self::app_orcamento_bar, "WHERE bar_fornecedor_cod = :fid AND bar_cardapio_status = :status ORDER BY bar_cardapio_nome", "fid={$fornecedorId}&status=1");
        if ($Read->getResult()):
            return $Read->getResult();
        else:
            return false;
        endif;
    }


    public function listaCardapiosBebidas($cardapioId)
    {
        $Read = new Read;
        $Read->FullRead("SELECT app_bar_cardapio_bebidas.dose_qt, app_bar_bebidas.bebida_id, app_bar_bebidas.bebida_nome, app_bar_bebidas.bebida_img, app_bar_bebidas.bebida_valor_dose, app_bar_bebidas.bebida_ml_alcool, app_bar_bebidas.bebida_categoria
                          FROM app_bar_bebidas INNER JOIN app_bar_cardapio_bebidas ON app_bar_bebidas.bebida_id = app_bar_cardapio_bebidas.bar_bebida_id
                          WHERE app_bar_cardapio_bebidas.bar_cardapio_id = :id", "id={$cardapioId}");
        if ($Read->getResult()):
            return $Read->getResult();
        else:
            return false;
        endif;
    }



}
