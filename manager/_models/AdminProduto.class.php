<?php

/**
 * AdminProduto.class [ MODEL ADMIN ]
 * Responável por gerenciar produto do orçamento do sistema no admin!
 *
 * @copyright(c) 2016, Leonardo Zanela - ht2mlTech
 */
class AdminProduto
{

    private $Data;
    private $ArrayCatecorias;
    private $Id;
    private $Error;
    private $Result;


    //Nome da tabela no banco de dados!
    const Entity = 'app_produtos';
    //Nome do Model
    const NameModel = "Produto";
    //Nome do campo chave
    const PKey = "produto_id";
    //Nome do campo chave
    const CampoName = "produto_name";

    /**
     * <b>Cadastrar Produto:</b> Método para cadastro Produto no banco.
     *
     * @param ARRAY $Data = Atribuitivo Forms
     */
    public function ExeCreate(array $Data)
    {
        $this->Data = $Data;

        
        if (in_array('', $this->Data)):
            $this->Result = false;
            $this->Error = ['<b>Erro ao cadastrar:</b> Para cadastrar o '.self::NameModel.', preencha todos os campos!', WS_ALERT];
        else:
            $this->Data['produto_valor'] = Check::Decimal($this->Data['produto_valor']);
            $this->Data['produto_valor_minimo'] = Check::Decimal($this->Data['produto_valor_minimo']);
            
            $this->setData();
            $this->Create();
        endif;
    }

    /**
     * <b>Atualizar Tipo de Evento:</b> Método para atualzar dados do Tipo de Evento
     *
     * @param INT $TipoEventoId = Id do tipo do evento
     * @param ARRAY $Data = Atribuitivo Forms
     */
    public function ExeUpdate($Id, array $Data)
    {
        $this->Id = (int) $Id;
        $this->Data = $Data;

        if (in_array('', $this->Data)):
            $this->Result = false;
            $this->Error = ["<b>Erro ao atualizar:</b> Para atualizar ".self::NameModel." {$this->Data[self::CampoName]}, preencha todos os campos!", WS_ALERT];
        else:
            $this->setData();
            $this->Update();
        endif;
    }

    /**
     * <b>Deleta Produto:</b> Informe o ID para remove-lo do sistema. Esse método verifica
     * o produto e se é permitido excluir de acordo com os registros do sistema!
     * @param INT $ProdutoId = Id do produto
     */
    public function ExeDelete($ProdutoId)
    {
        $this->Id = (int) $ProdutoId;

        $delete = new Delete;
        $delete->ExeDelete(self::Entity, "WHERE ".self::PKey." = :deletaid", "deletaid={$this->Id}");
        $this->Result = true;
        $this->Error = ["A <b>".self::NameModel."</b> foi removido com sucesso do sistema!", WS_ACCEPT];
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

    /**
     * <b>Seleciona Categorias:</b> Seleciona categorias e tipo de produto
     */
    public function ListCategorias()
    {

        $this->ArrayCatecorias = new Read();
        $this->ArrayCatecorias->ExeRead("app_tipoevento");
        if ($this->ArrayCatecorias->getResult()):
            foreach ($this->ArrayCatecorias->getResult() as $TipoEvento):
                extract($TipoEvento);
                $TIPO_EVETO[$tipoevento_id] = $tipoevento_nome;
            endforeach;
            $this->ArrayCatecorias->ExeRead("app_categorias");
            if ($this->ArrayCatecorias->getResult()):
                foreach ($this->ArrayCatecorias->getResult() as $Categoria):
                    extract($Categoria);
                    $CATEGORIA[$categoria_id] = $TIPO_EVETO[$tipoevento_cod] . " | " . $categoria_nome;
                    asort($CATEGORIA);
                endforeach;
                return $CATEGORIA;
            else:
                return null;
            endif;
        else:
            return null;
        endif;
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

    //Cadastra a categoria no banco!
    private function Create()
    {
        $Create = new Create;
        $Create->ExeCreate(self::Entity, $this->Data);
        if ($Create->getResult()):
            $this->Result = $Create->getResult();
            $this->Error = ["<b>Sucesso:</b> O Produto {$this->Data['produto_nome']} foi cadastrada no sistema!", WS_ACCEPT];
        endif;
    }

    //Atualiza Categoria
    private function Update()
    {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE ".self::PKey." = :tpid", "tpid={$this->$Id}");
        if ($Update->getResult()):

            $this->Result = true;
            $this->Error = ["<b>Sucesso:</b> ".self::NameModel." {$this->Data['tipoevento_nome']} foi atualizada no sistema!", WS_ACCEPT];
        endif;
    }

}
