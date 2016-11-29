<?php

/**
 * AdminCategoria.class [ MODEL ADMIN ]
 * Responável por gerenciar as categorias do sistema no manager!
 *
 * @copyright(c) 2016, Leonardo Zanela - ht2mlTech
 */
class AdminAnimal
{

    private $Data;
    private $Id;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados!
    const Entity = 'app_animal';

    /**
     * <b>Cadastrar Cliente:</b> Envelope os dados e envie para cadastrar
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data)
    {
        $this->Data = $Data;

        if(isset($this->Data['animal_img']) && !empty($this->Data['animal_img'])){
            $this->Data['animal_img'] = str_replace("../../../", "", $this->Data['animal_img']);
        }


        foreach ($this->Data as $key => $dados):
            if($key == 'animal_img'): continue; endif;
            if ($dados == ''): $Error = true; endif;
        endforeach;


        if (isset($Error) && $Error == true):

            $this->Result = false;
            $this->Error = ['<b>Erro ao cadastrar:</b> Para cadastrar, preencha todos os campos!', WS_ALERT];

            return false;
        else:
            $this->Create();

            if ($this->Result):
                unset($this->Data);
                return true;
            else:
                $this->Result = false;
                $this->Error = ['<b>Erro ao cadastrar:</b> Erro! (COD:3454)', WS_ALERT];
            endif;
        endif;

    }

    /**
     * <b>Atualizar Comissão:</b> Envelope os dados em uma array atribuitivo e informe o id de uma
     * comissão para atualiza-la!
     * @param INT id  = Id da comissão
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($CategoryId, array $Data)
    {
        $this->CatId = (int)$CategoryId;
        $this->Data = $Data;



        if(isset($this->Data['animal_img']) && !empty($this->Data['animal_img'])){
            $this->Data['animal_img'] = str_replace("../../../", "", $this->Data['animal_img']);
        }


        foreach ($this->Data as $key => $dados):
            if($key == 'animal_img'): continue; endif;
            if ($dados == ''): $Error = true; endif;
        endforeach;




        if (isset($Error) && $Error == true):
            $this->Result = false;
            $this->Error = ["<b>Erro ao atualizar:</b> Para atualizar o animal {$this->Data['nome']}, preencha todos os campos!", WS_ALERT];
        else:
            $this->setData();
            $this->setName();
            $this->Update();
        endif;
    }

    /**
     * <b>Deleta categoria:</b> Informe o ID de uma categoria para remove-la do sistema. Esse método verifica
     * o tipo de categoria e se é permitido excluir de acordo com os registros do sistema!
     * @param INT $CategoryId = Id da categoria
     */
    public function ExeDelete($CategoriaId)
    {
        $this->CatId = (int)$CategoriaId;
        $delete = new Delete;
        $delete->ExeDelete(self::Entity, "WHERE id = :deletaid", "deletaid={$this->CatId}");
        $this->Result = true;

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

    }

    //Cadastra a categoria no banco!
    private function Create()
    {
        $Create = new Create;
        $Create->ExeCreate(self::Entity, $this->Data);
        if ($Create->getResult()):
            $this->Result = $Create->getResult();
            //Message::FlashMsg("AlertMsg", WS_ACCEPT, "<b>Sucesso:</b> A Comissão {$this->Data['comissao_nome']} foi cadastrada no sistema!");
        endif;
    }

    //Atualiza Categoria
    private function Update()
    {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :catid", "catid={$this->CatId}");
        if ($Update->getResult()):
            $this->Result = true;
        endif;
    }

}