<?php

/**
 * AdminOrcamento.class [ MODEL ADMIN ]
 * Responável por gerenciar todos os orçamentos do sistema no manager!
 *
 * @copyright(c) 2016, Leonardo Zanela - ht2mlTech
 */
class AdminOrcamento
{

    private $Data;
    private $Id;
    private $Error;
    private $Result;
    private $Config = [];
    private $TipoEvento;

    //Nome da tabela no banco de dados!
    const Entity = 'app_orcamentos';

    /**
     * <b>Cadastrar Orçamento:</b> Envelope os dados e envie para cadastrar
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data)
    {
        $this->Data = $Data;

        if (in_array('', $this->Data)):
            $this->Result = false;
            $this->Error = ["<b>Erro ao cadasatrar:</b> Para cadasatrar a orçamento {$this->Data['orcamento_nome']}, preencha todos os campos!", WS_ALERT];
        elseif (!isset($this->Data['representante_cod']) || empty($this->Data['representante_cod'])):
            $this->Result = false;
            $this->Error = ["<b>Erro ao cadasatrar:</b> Para cadasatrar a orçamento {$this->Data['orcamento_nome']}, selecione o representante!", WS_ALERT];
        else:
            $this->Data['orcamento_crypt'] = md5(date('Ydm') . time() . $Data['orcamento_nome']);
            $this->setData();
            $this->Create();

        endif;
    }


    /**
     * <b>Pega dados para painel do Orçamento:</b> Execute esta funçao e os dados virão em array
     * @param ARRAY $Data = Atribuitivo
     */
    public function ListData()
    {
        $this->Id = $_SESSION['orcamento']['id'];
        $Result = new Read;
        $Result->ExeRead('app_orcamentos', 'WHERE orcamento_id = :id', "id={$this->Id}");
        if ($Result->getResult()):
            return $Result->getResult()[0];
        else:
            return false;
        endif;
    }


    /**
     * <b>Atualizar Comissão:</b> Envelope os dados em uma array atribuitivo e informe o id de uma
     * comissão para atualiza-la!
     * @param INT id  = Id da comissão
     * @param ARRAY $Data = Atribuitivo
     */
    public function SaveConfig($Id, array $Data)
    {
        $this->Id = (int)$Id;
        $this->Data = $Data;
        $this->Config = $Data;

        unset($this->Data['orcamento_config_parcelas']);
        unset($this->Data['orcamento_config_fee']);
        unset($this->Data['orcamento_config_qt_comissao']);
        unset($this->Data['orcamento_config_media_por_formando']);
        unset($this->Data['orcamento_config_validade']);

        unset($this->Config['orcamento_nome']);
        unset($this->Config['orcamento_obs']);
        unset($this->Config['representante_cod']);
        unset($this->Config['orcamento_status']);

        if (in_array('', $this->Data)):
            $this->Result = false;
            $this->Error = ["<b>Erro ao atualizar:</b> Para atualizar a categoria {$this->Data['orcamento_nome']}, preencha todos os campos!", WS_ALERT];
        else:
            var_dump($this->Data, $this->Config);

            $this->setData();

            $this->Config = array_map('strip_tags', $this->Config);
            $this->Config = array_map('trim', $this->Config);

            $this->UpdateOrcamento();
            $this->UpdateConfig();

        endif;
    }


    /**
     *
     */
    public function VerificaDetalhes()
    {
        $this->Id = $_SESSION['orcamento']['id'];
        $Read = new Read();
        $Read->ExeRead('app_orcamento_detalhes', 'WHERE orcamento_id = :id', "id={$this->Id}");
        if ($Read->getResult()):
            return $Read->getResult()[0];
        else:
            return false;
        endif;
    }

    /**
     *
     */
    public function addDetalhes()
    {
        $this->Id = $_SESSION['orcamento']['id'];
        if (!$this->VerificaDetalhes()):
            $Create = new Create();
            $Create->ExeCreate('app_orcamento_detalhes', ['orcamento_id' => $this->Id]);
            if ($Create->getResult()):
                return $Create->getResult();
            else:
                return false;
            endif;
        else:
            return false;
        endif;
    }

    /**
     *
     */
    public function delDetalhes()
    {
        $this->Id = $_SESSION['orcamento']['id'];
        if ($this->VerificaDetalhes()):
            $Delete = new Delete();
            $Delete->ExeDelete('app_orcamento_detalhes', "WHERE orcamento_id = :id", "id={$this->Id}");
            if ($Delete->getResult()):
                return $Delete->getResult();
            else:
                return false;
            endif;
        else:
            return false;
        endif;
    }


    public function saveLocal($idOrcamento, $tipoeventoId, $idLocal)
    {

        $this->idOrcamento = $idOrcamento;
        $this->tipoeventoId = $tipoeventoId;
        $this->idLocal = $idLocal;

        $Read = new Read;
        $Read->ExeRead("app_orcamento_locais", "WHERE tipoevento_id = :id AND orcamento_id = :oid", "id={$tipoeventoId}&oid={$idOrcamento}");
        if ($Read->getResult()):
            $Delete = new Delete();
            $Delete->ExeDelete("app_orcamento_locais", "WHERE tipoevento_id = :id AND orcamento_id = :oid", "id={$tipoeventoId}&oid={$idOrcamento}");
        endif;

        $Read->ExeRead("app_locais", "WHERE local_id = :id", "id={$idLocal}");

        if ($Read->getResult()):
            $Local = $Read->getResult()[0];

            $Data = [
                'orcamento_id' => $idOrcamento,
                'local_id' => $idLocal,
                'tipoevento_id' => $tipoeventoId,
                'orcamento_local_valor' => $Local['local_valor']
            ];

            $Create = new Create;
            $Create->ExeCreate('app_orcamento_locais', $Data);

            if ($Create->getResult() == 0):

                $Read->ExeRead("app_locais_imgs", "WHERE local_id = :id AND local_img_capa = 1", "id={$idLocal}");
                $Img = $Read->getResult()[0];

                $ReadProdutos = new Read;
                $ReadProdutos->FullRead("
                SELECT app_locais_produtos.local_produto_quantidade, 
                    app_locais_produtos.local_produto_valor, 
                    app_produtos.produto_nome, 
                    app_produtos.produto_descricao
                FROM app_produtos INNER JOIN app_locais_produtos ON app_produtos.produto_id = app_locais_produtos.produto_id
                WHERE app_locais_produtos.local_id = :id
            ", "id={$idLocal}");


                if ($ReadProdutos->getResult()):
                    $ProdDepends = "";
                    $Total = 0;
                    foreach ($ReadProdutos->getResult() as $Value):

                        $subTotal = $Value['local_produto_valor'] * $Value['local_produto_quantidade'];
                        $Total += $subTotal;

                        $ProdDepends .= "<tr>";
                        $ProdDepends .= "<td>{$Value['produto_nome']}</td>";
                        $ProdDepends .= "<td>{$Value['produto_descricao']}</td>";
                        $ProdDepends .= "<td>{$Value['local_produto_valor']}</td>";
                        $ProdDepends .= "<td>{$Value['local_produto_quantidade']}</td>";
                        $ProdDepends .= "<td>{$subTotal}</td>";
                        $ProdDepends .= "</tr>";

                    endforeach;
                else:
                    $ProdDepends = "Nenhum produto dependente!";
                endif;

                echo '
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="row">
                    <div class="col-sm-4 col-md-2">
                        <img src="' . HOME . '/' . $Img['local_img'] . '" alt=""
                             class="img-rounded img-responsive"/>
                    </div>
                    <div class="col-sm-8 col-md-3">
                        <h4 style="border-bottom: 1px solid #d6e9c6; padding-bottom: 5px;"> ' . $Local['local_nome'] . '</h4>
                        <small><i class="glyphicon glyphicon-map-marker"></i> ' . $Local['local_endereco'] . '</small>
                        <p style="border-bottom: 1px solid #d6e9c6; padding-bottom: 5px; margin-bottom: 5px;">
                            <i class="glyphicon glyphicon-user"></i> ' . $Local['local_capacidade'] . ' (Pessoas)
                            <br/>
                            <i class="glyphicon glyphicon-education"></i> ' . $Local['local_max_formandos'] . ' (Max Formandos)
                            <br/>
                            <i class="glyphicon glyphicon-asterisk"></i> ' . $Local['local_mesas'] . ' (Mesas)
                        </p>
                        <span style="font-size: 20px;">R$ ' . $Local['local_valor'] . '</span>
                        
                    </div>
                    <div class="col-sm-12 col-md-7 table-responsive" style="border: 1px solid #EEEEEE;">
                        <table class="table">
                            <thead style="background-color: #eee">
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Quant.</th>
                                <th>Sub Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            ' . $ProdDepends . '
                            ' . $ProdDepends . '
                            ' . $ProdDepends . '
                            ' . $ProdDepends . '
                            </tbody>
                            <tfoot style="background-color: #eee; font-weight: bold;">
                                <tr>
                                    <td colspan="4" class="text-right">Total: </td>
                                    <td>' . $Total . '</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right">Total + Produtos: </td>
                                    <td>' . Check::DecimalReal($Total + $Local['local_valor']) . '</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <hr/>
                <button data-id="' . $tipoeventoId . '"
                        class="btn btn-info btn-block AjaxSelecionaLocais">
                        Alterar
                </button>
            </div>
            ';

            else:

            endif;
        else:

        endif;
    }


    /**
     *
     *
     */
    public function buscarLocal($idOrcamento, $tipoeventoId)
    {

        $this->Id = $idOrcamento;
        $this->TipoEvento = $tipoeventoId;

        $Read = new Read;
        $Read->ExeRead("app_orcamento_locais", "WHERE orcamento_id = :oid AND tipoevento_id = :tid", "oid={$this->Id}&tid={$this->TipoEvento}");
        if ($Read->getResult()):

            $LocalOrcamento = $Read->getResult()[0];

            $idLocal = $LocalOrcamento['local_id'];

            $Read->ExeRead("app_locais", "WHERE local_id = :id", "id={$idLocal}");

            if ($Read->getResult()):
                $Local = $Read->getResult()[0];

                $Read->ExeRead("app_locais_imgs", "WHERE local_id = :id AND local_img_capa = 1", "id={$idLocal}");
                $Img = $Read->getResult()[0];

                $ReadProdutos = new Read;
                $ReadProdutos->FullRead("
                SELECT app_locais_produtos.local_produto_quantidade, 
                    app_locais_produtos.local_produto_valor, 
                    app_produtos.produto_nome, 
                    app_produtos.produto_descricao
                FROM app_produtos INNER JOIN app_locais_produtos ON app_produtos.produto_id = app_locais_produtos.produto_id
                WHERE app_locais_produtos.local_id = :id
            ", "id={$idLocal}");


                if ($ReadProdutos->getResult()):
                    $ProdDepends = "";
                    $Total = 0;
                    foreach ($ReadProdutos->getResult() as $Value):

                        $subTotal = $Value['local_produto_valor'] * $Value['local_produto_quantidade'];
                        $Total += $subTotal;

                        $ProdDepends .= "<tr>";
                        $ProdDepends .= "<td>{$Value['produto_nome']}</td>";
                        $ProdDepends .= "<td>{$Value['produto_descricao']}</td>";
                        $ProdDepends .= "<td>" . Check::DecimalReal($Value['local_produto_valor']) . "</td>";
                        $ProdDepends .= "<td>{$Value['local_produto_quantidade']}</td>";
                        $ProdDepends .= "<td>" . Check::DecimalReal($subTotal) . "</td>";
                        $ProdDepends .= "</tr>";

                    endforeach;
                else:
                    $ProdDepends = "Nenhum produto dependente!";
                endif;

                echo '
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="row">
                    <div class="col-sm-4 col-md-2" style="margin-top: 20px;">
                        <img src="' . HOME . '/' . $Img['local_img'] . '" alt=""
                             class="img-rounded img-responsive"/>
                    </div>
                    <div class="col-sm-8 col-md-3">
                        <h4 > ' . $Local['local_nome'] . '</h4>
                        
                        <table class="table table-condensed">
                            <tbody>
                                <tr>
                                    <td colspan="3" style="text-align: left; font-size: 18px">R$ ' . Check::DecimalReal($LocalOrcamento['orcamento_local_valor']) . ' </td>
                                </tr>
                                <tr>
                                    <td><i class="glyphicon glyphicon-user"></td>
                                    <td>Capacidade</td>
                                    <td style="text-align: right;">' . $Local['local_capacidade'] . ' </td>
                                </tr>
                                <tr>
                                    <td><i class="glyphicon glyphicon-education"></td>
                                    <td>Max. Formandos</td>
                                    <td style="text-align: right;">' . $Local['local_max_formandos'] . '  </td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-sun-o fa" aria-hidden="true"></i></td>
                                    <td>Mesas</td>
                                    <td style="text-align: right;">' . $Local['local_mesas'] . '</td>
                                </tr>
                                
                            </tbody>
                        </table> 
                          <small style="margin-top: 5px;"><i class="glyphicon glyphicon-map-marker"></i> ' . $Local['local_endereco'] . '</small>
                    
                        <!--
                        <h4 style="border-bottom: 1px solid #d6e9c6; padding-bottom: 5px;"> ' . $Local['local_nome'] . '</h4>
                        <small><i class="glyphicon glyphicon-map-marker"></i> ' . $Local['local_endereco'] . '</small>
                        <p style="border-bottom: 1px solid #d6e9c6; padding-bottom: 5px; margin-bottom: 5px;">
                            <i class="glyphicon glyphicon-user"></i> ' . $Local['local_capacidade'] . ' (Pessoas)
                            <br/>
                            <i class="glyphicon glyphicon-education"></i> ' . $Local['local_max_formandos'] . ' (Max Formandos)
                            <br/>
                            <i class="glyphicon glyphicon-asterisk"></i> ' . $Local['local_mesas'] . ' (Mesas)
                        </p>
                        <span style="font-size: 20px;">R$ ' . $Local['local_valor'] . '</span>
                        -->
                        
                    </div>
                    <div class="col-sm-12 col-md-7 table-responsive">
                        <table class="table" style="border: 1px solid #EEEEEE;">
                            <thead style="background-color: #eee">
                            <tr>
                                <th colspan="5" style="text-align: center; background-color: #FDFFBA;"><b>Produtos Dependentes</b></th>
                            </tr>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Quant.</th>
                                <th>Sub Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            ' . $ProdDepends . '
                            ' . $ProdDepends . '
                            ' . $ProdDepends . '
                            </tbody>
                            <tfoot style="background-color: #eee; font-weight: bold;">
                                <tr>
                                    <td colspan="4" class="text-right">Total: </td>
                                    <td>' . Check::DecimalReal($Total) . '</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right">Total + Produtos: </td>
                                    <td>' . Check::DecimalReal($Total + $Local['local_valor']) . '</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <hr/>
                <button data-id="' . $this->TipoEvento . '"
                        class="btn btn-info btn-block AjaxSelecionaLocais">
                        Alterar
                </button>
            </div>
            ';

                return true;
            else:

            endif;

        else:

            return false;

        endif;
    }


    public function getTiposEventos($TipoEventoId = null)
    {
        if ($TipoEventoId):
            $WhereTipoId = "AND app_orcamento_tipoeventos.tipoevento_id = :tpid";
            $WhereTipoIdBind = "&tpid={$TipoEventoId}";
        else:
            $WhereTipoId = "";
            $WhereTipoIdBind = "";
        endif;
        $this->Id = (int)$_SESSION['orcamento']['id'];
        $Read = new Read();
        $Read->FullRead("SELECT app_tipoevento.tipoevento_nome, app_orcamento_tipoeventos.tipoevento_id FROM app_orcamento_tipoeventos LEFT OUTER JOIN app_tipoevento ON app_orcamento_tipoeventos.tipoevento_id = app_tipoevento.tipoevento_id WHERE app_orcamento_tipoeventos.orcamento_id = :id {$WhereTipoId}", "id={$this->Id}{$WhereTipoIdBind}");
        if ($Read->getResult()):
            return $Read->getResult();
        else:
            return false;
        endif;
    }

    public function getTiposEventosProduto($TipoEventoId = null)
    {
        if ($TipoEventoId):
            $WhereTipoId = "AND app_orcamento_tipoeventos.tipoevento_id = :tpid";
            $WhereTipoIdBind = "&tpid={$TipoEventoId}";
        else:
            $WhereTipoId = "";
            $WhereTipoIdBind = "";
        endif;
        $this->Id = (int)$_SESSION['orcamento']['id'];
        $Read = new Read();
        $Read->FullRead("
            SELECT
            app_tipoevento.tipoevento_nome,
            app_orcamento_tipoeventos.tipoevento_id,
            app_orcamento_tipoeventos.orcamento_tipoevento_qt_formandos,
            app_orcamento_tipoeventos.orcamento_tipoevento_qt_convites,
            app_orcamento_tipoeventos.orcamento_tipoevento_qt_mesas,
            app_orcamento_tipoeventos.orcamento_tipoevento_extra_vl_convites,
            app_orcamento_tipoeventos.orcamento_tipoevento_extra_qt_convites,
            app_orcamento_tipoeventos.orcamento_tipoevento_extra_vl_mesas,
            app_orcamento_tipoeventos.orcamento_tipoevento_extra_qt_mesas
            FROM app_orcamento_tipoeventos LEFT OUTER JOIN app_tipoevento ON app_orcamento_tipoeventos.tipoevento_id = app_tipoevento.tipoevento_id
            WHERE app_orcamento_tipoeventos.orcamento_id = :id {$WhereTipoId}", "id={$this->Id}{$WhereTipoIdBind}");
        if ($Read->getResult()):
            return $Read->getResult();
        else:
            return false;
        endif;
    }

    public function getQtIntegranteComissao()
    {
        $this->Id = (int)$_SESSION['orcamento']['id'];

        $Read = new Read;
        $Read->FullRead("SELECT 
	COUNT(app_comissoes.comissao_id) as qt
FROM app_orcamento_comissoes INNER JOIN app_comissoes ON app_orcamento_comissoes.comissao_id = app_comissoes.comissao_id
	 INNER JOIN app_comissoes_integrantes ON app_comissoes_integrantes.comissao_cod = app_comissoes.comissao_id
	WHERE app_orcamento_comissoes.orcamento_id = :oid", "oid={$this->Id}");
        if ($Read->getResult()):
            return $Read->getResult();
        else:
            return false;
        endif;

    }


    function saveBar(array $Data, $idOrcamento)
    {

        $data['orcamento_id'] = $idOrcamento;
        $data['tipoevento_id'] = $Data['tipoevento_id'];
        $data['fornecedor_id'] = $Data['fornecedor_id'];
        $data['cardapio_id'] = $Data['cardapio_id'];

        // Seleciona e grava o bar com fornecedor e cardapio
        $Read = new Read;
        $Read->ExeRead('app_orcamento_bar', 'WHERE orcamento_id = :oid AND tipoevento_id = :tid', "oid={$idOrcamento}&tid={$data['tipoevento_id']}");

        if ($Read->getResult()):
            $Update = new Update;
            $Update->ExeUpdate('app_orcamento_bar', $data, 'WHERE orcamento_id = :oid AND tipoevento_id = :tid', "oid={$idOrcamento}&tid={$data['tipoevento_id']}");
        else:
            $Create = new Create;
            $Create->ExeCreate('app_orcamento_bar', $data);
        endif;

        if (is_array($Data['bebidas']) && count($Data['bebidas']) > 0):

            $queryIn = "";

            foreach ($Data['bebidas'] as $bebidas):
                $queryIn .= ",";
                $queryIn .= (int)$bebidas['id'];
            endforeach;

            $queryIn = substr($queryIn, 1);
            $In = "WHERE bebida_id IN ({$queryIn})";
            $Read->ExeRead('app_bar_bebidas', $In);
            foreach ($Read->getResult() as $bebida):
                extract($bebida);
                $resBebida[$bebida_id]['valor'] = $bebida_valor_dose;
            endforeach;

            $Delete = new Delete;
            $Delete->ExeDelete('app_orcamento_bar_bebidas', 'WHERE orcamento_id = :oid AND tipoevento_id = :tid', "oid={$idOrcamento}&tid={$data['tipoevento_id']}");

            $Create = new Create;

            foreach ($Data['bebidas'] as $bebidaInsert):

                $dataInsert['orcamento_id'] = $idOrcamento;
                $dataInsert['tipoevento_id'] = $Data['tipoevento_id'];
                $dataInsert['bebida_id'] = $bebidaInsert['id'];
                $dataInsert['valor'] = $resBebida[$bebidaInsert['id']]['valor'];
                $dataInsert['qt'] = $bebidaInsert['qt'];

                $Create->ExeCreate('app_orcamento_bar_bebidas', $dataInsert);
            endforeach;

        endif;
    }


    function SelectBebidas($idOrcamento, $idTipoEvento, $idCardapio)
    {

        // Seleciona e grava o bar com fornecedor e cardapio
        $Read = new Read;

        $Read->ExeRead('app_orcamento_bar_bebidas', 'WHERE orcamento_id = :oid AND tipoevento_id = :tid', "oid={$idOrcamento}&tid={$idTipoEvento}");
        if ($Read->getResult()):
            $bebidasEscolhidas = $Read->getResult();
            foreach ($bebidasEscolhidas as $bebidasEscolhida):
                extract($bebidasEscolhida);
                $retorno['data']['bebidasEscolhidas'][$bebida_id]['id'] = $bebida_id ;
                $retorno['data']['bebidasEscolhidas'][$bebida_id]['valor'] = $valor;
                $retorno['data']['bebidasEscolhidas'][$bebida_id]['qt'] = $qt;
            endforeach;
        endif;

        include_once("AdminBar.class.php");
        $CardapioBebidas = new AdminBar;
        $CardapioBebidas = $CardapioBebidas->listaCardapiosBebidas($idCardapio);
        foreach ($CardapioBebidas as $BebidasTodas):
            extract($BebidasTodas);

            $retorno['data']['bebidasFornecedor'][$bebida_id]['id'] = $bebida_id;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['nome'] = $bebida_nome;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['img'] = $bebida_img;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['valor'] = $bebida_valor_dose;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['teor'] = $bebida_ml_alcool;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['qt'] = $dose_qt;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['categoria'] = $bebida_categoria;

        endforeach;

        echo json_encode($retorno);

    }


    /**
     * <b>Verificar Cadastro:</b> Retorna TRUE se o cadastro ou update for efetuado ou FALSE se não. Para verificar
     * erros execute um getError();
     * @return BOOL $Var = True or False
     */
    public function getResult()
    {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com a mensagem e o tipo de erro!
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError()
    {
        return $this->Error;
    }




    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    //Valida e cria os dados para realizar o cadastro
    private function setData()
    {
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
    }

    //Cadastra no banco!
    private function Create()
    {
        $Create = new Create;
        $Create->ExeCreate(self::Entity, $this->Data);
        if ($Create->getResult()):
            $this->Result['id'] = $Create->getResult();
            $this->Result['crypt'] = $this->Data['orcamento_crypt'];
        endif;
    }

    //Atualiza Orcamento no Banco
    private function UpdateOrcamento()
    {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE orcamento_id = :id", "id={$this->Id}");
        if ($Update->getResult()):
            $this->Result = true;
        endif;
    }

    //Atualiza Config do Orcamento no banco
    private function UpdateConfig()
    {
        $Update = new Update;
        $Update->ExeUpdate('app_orcamento_config', $this->Config, "WHERE orcamento_id = :id", "id={$this->Id}");
        if ($Update->getResult()):
            $this->Result = true;
        endif;
    }

}
