<?php

/**
 * AdminCategory.class [ MODEL ADMIN ]
 * Responável por gerenciar as categorias do sistema no admin!
 *
 * @copyright(c) 2016, Leonardo Zanela - ht2mlTech
 */
class AdminServico
{

    private $Data;
    private $TPId;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados!
    const Entity = 'app_tipoevento';

    /**
     * <b>Cadastrar Tipo de Evento:</b> Método para cadastro de Tipo de Evento
     *
     * @param ARRAY $Data = Atribuitivo Forms
     */
    public function ExeCreate(array $Data)
    {
        $this->Data = $Data;

        if (in_array('', $this->Data)):
            $this->Result = false;
            $this->Error = ['<b>Erro ao cadastrar:</b> Para cadastrar o tipo de evento, preencha todos os campos!', WS_ALERT];
        else:
            $this->setData();
            $this->setName();
            $this->Create();
        endif;
    }

    /**
     * <b>Atualizar Tipo de Evento:</b> Método para atualzar dados do Tipo de Evento
     *
     * @param INT $TipoEventoId = Id do tipo do evento
     * @param ARRAY $Data = Atribuitivo Forms
     */
    public function ExeUpdate($TipoEventoId, array $Data)
    {
        $this->TPId = (int)$TipoEventoId;
        $this->Data = $Data;

        if (in_array('', $this->Data)):
            $this->Result = false;
            $this->Error = ["<b>Erro ao atualizar:</b> Para atualizar o Tipo de Evento {$this->Data['tipoevento_nome']}, preencha todos os campos!", WS_ALERT];
        else:
            $this->setData();
            $this->setName();
            $this->Update();
        endif;
    }

    /**
     * <b>Deleta Tipo de Evento:</b> Informe o ID para remove-lo do sistema. Esse método verifica
     * o tipo de evento e se é permitido excluir de acordo com os registros do sistema!
     * @param INT $TipoEventoId = Id do tipo do evento
     */
    public function ExeDelete($TipoEventoId)
    {
        $this->TPId = (int)$TipoEventoId;

        $delete = new Delete;
        $delete->ExeDelete(self::Entity, "WHERE tipoevento_id = :deletaid", "deletaid={$this->TPId}");
        if($delete->getResult()):
            $this->Result = true;
            $this->Error = ["A <b>O Tipo de Evento</b> foi removida com sucesso do sistema!", WS_ACCEPT];
        endif;
        
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

    //Verifica o NAME da categoria. Se existir adiciona um pós-fix +1
    private function setName()
    {

        /*
        $Where = (!empty($this->CatId) ? "category_id != {$this->CatId} AND" : '' );

        $readName = new Read;
        $readName->ExeRead(self::Entity, "WHERE {$Where} category_title = :t", "t={$this->Data['category_title']}");
        if ($readName->getResult()):
            $this->Data['category_name'] = $this->Data['category_name'] . '-' . $readName->getRowCount();
        endif;
        */
    }

    //Cadastra a categoria no banco!
    private function Create()
    {
        $Create = new Create;
        $Create->ExeCreate(self::Entity, $this->Data);
        if ($Create->getResult()):
            $this->Result = $Create->getResult();
            $this->Error = ["<b>Sucesso:</b> A Tipo de Evento {$this->Data['tipoevento_nome']} foi cadastrada no sistema!", WS_ACCEPT];
        endif;
    }

    //Atualiza Categoria
    private function Update()
    {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE tipoevento_id = :tpid", "tpid={$this->TPId}");
        if ($Update->getResult()):

            $this->Result = true;
            $this->Error = ["<b>Sucesso:</b> O Tipo de Evento {$this->Data['tipoevento_nome']} foi atualizada no sistema!", WS_ACCEPT];
        endif;
    }

}
