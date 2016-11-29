<?php

/**
 * AdminCategoria.class [ MODEL ADMIN ]
 * Responável por gerenciar as categorias do sistema no manager!
 *
 * @copyright(c) 2016, Leonardo Zanela - ht2mlTech
 */
class AdminCategoria
{

    private $Data;
    private $CatId;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados!
    const Entity = 'app_categorias';

    /**
     * <b>Cadastrar Categoria:</b> Envelope titulo, descrição, data e sessão em um array atribuitivo e execute esse método
     * para cadastrar a categoria. Case seja uma sessão, envie o category_parent como STRING null.
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data)
    {
        $this->Data = $Data;

        $this->Data['categoria_img'] = str_replace("../../../", "", $this->Data['categoria_img']);

        if (in_array('', $this->Data)):
            if (empty($this->Data['tipoevento_img'])):
                $this->Result = false;
                $this->Error = ['<b>Erro ao cadastrar:</b> Para cadastrar a categoria, adicione uma imagem e preencha todos os campos!', WS_ALERT];
            else:
                $this->Result = false;
                $this->Error = ['<b>Erro ao cadastrar:</b> Para cadastrar a categoria, preencha todos os campos!', WS_ALERT];
            endif;
        else:
            $this->setData();
            $this->setName();
            $this->Create();
        endif;
    }

    /**
     * <b>Atualizar Categoria:</b> Envelope os dados em uma array atribuitivo e informe o id de uma
     * categoria para atualiza-la!
     * @param INT $CategoryId = Id da categoria
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($CategoryId, array $Data)
    {
        $this->CatId = (int)$CategoryId;
        $this->Data = $Data;

        if (in_array('', $this->Data)):
            $this->Result = false;
            $this->Error = ["<b>Erro ao atualizar:</b> Para atualizar a categoria {$this->Data['categoria_nome']}, preencha todos os campos!", WS_ALERT];
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
        $delete->ExeDelete(self::Entity, "WHERE categoria_id = :deletaid", "deletaid={$this->CatId}");
        $this->Result = true;

    }

    //Crop Image e Move Para Pasta
    public function CropImg($Image, $Name = null, $Width = null, $X, $Y, $W, $H)
    {
        if (empty($X) || empty($Y) || empty($W) || empty($H)):
            $this->Error = ['<b>Erro ao cadastrar:</b> Para cadastrar a categoria, selecione o campo da imagem a ser cortada!', WS_ALERT];
            $this->Result = false;
            return $this->Result;
        endif;
        $Upload = new Upload();
        $Upload->CropImagem($Image, $Name, $Width, $X, $Y, $W, $H);
        if ($Upload->getResult()):
            return $this->Result = $Upload->getResult();
        else:
            return false;
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

    }

    //Cadastra a categoria no banco!
    private function Create()
    {
        $Create = new Create;
        $Create->ExeCreate(self::Entity, $this->Data);
        if ($Create->getResult()):
            $this->Result = $Create->getResult();
            $this->Error = ["<b>Sucesso:</b> A categoria {$this->Data['categoria_nome']} foi cadastrada no sistema!", WS_ACCEPT];
        endif;
    }

    //Atualiza Categoria
    private function Update()
    {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE categoria_id = :catid", "catid={$this->CatId}");
        if ($Update->getResult()):
            $this->Result = true;
        endif;
    }


}
