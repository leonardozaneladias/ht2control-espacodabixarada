<?php

/**
 * AdminCategoria.class [ MODEL ADMIN ]
 * Responável por gerenciar as categorias do sistema no manager!
 *
 * @copyright(c) 2016, Leonardo Zanela - ht2mlTech
 */
class AdminComissao
{

    private $Data;
    private $Id;
    private $Error;
    private $Result;
    private $ArrayCursos;
    private $ArrayIntegrantes;

    //Nome da tabela no banco de dados!
    const Entity = 'app_comissoes';

    /**
     * <b>Cadastrar Comissão:</b> Envelope os dados e envie para cadastrar
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data)
    {
        $this->Data = $Data;

        $this->Data['comissao_img'] = str_replace("../../../", "", $this->Data['comissao_img']);

        foreach ($this->Data as $dados):
            if ($dados == ''): $Error = true; endif;
        endforeach;

        foreach ($this->Data['comissao_cursos'] as $dados):
            if ($dados == ''): $Error = true; endif;
        endforeach;

        foreach ($this->Data['comissao_campus'] as $dados):
            if ($dados == ''): $Error = true; endif;
        endforeach;

        foreach ($this->Data['comissao_periodo'] as $dados):
            if ($dados == ''): $Error = true; endif;
        endforeach;

        foreach ($this->Data['integrante_nome'] as $dados):
            if ($dados == ''): $Error = true; endif;
        endforeach;

        foreach ($this->Data['integrante_email'] as $dados):
            if ($dados == ''): $Error = true; endif;
            if (!Check::Email($dados)): $Error = true; endif;
        endforeach;

        if (isset($Error) && $Error == true):
            if (empty($this->Data['comissao_img'])):
                $this->Result = false;
                $this->Error = ['<b>Erro ao cadastrar:</b> Para cadastrar a comissão, adicione uma imagem e preencha todos os campos!', WS_ALERT];
            else:
                $this->Result = false;
                $this->Error = ['<b>Erro ao cadastrar:</b> Para cadastrar a comissão, preencha todos os campos!', WS_ALERT];
            endif;
            return false;
        else:
            $this->SetCursos();
            $this->SetIntegrantes();
            $this->Create();

            if ($this->Result):
                unset($this->Data);
                $this->Data['comissao_id'] = $this->Result;
                $this->CadastraCursos();
                $this->CadastraIntegrantes();
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
     * <b>Dados comissão:</b> Retorna um array associativo com dados da comissão!
     * @return ARRAY $Data = Array associatico com dados
     */
    public function getComissao($id)
    {
        $this->Id = $id;
        $Read = new Read;
        $Read->ExeRead("app_comissoes", "WHERE comissao_id = :id", "id={$this->Id}");
        if ($Read->getResult()):
            $Retorno['ComissaoData'] = $Read->getResult();
            $Read->FullRead("SELECT app_comissoes_cursos.curso_id, 
                    app_cursos.curso_nome, 
                    app_comissoes_cursos.campus_id, 
                    app_campus.campus_nome, 
                    app_comissoes_cursos.periodo_id
                FROM app_cursos INNER JOIN app_comissoes_cursos ON app_cursos.curso_id = app_comissoes_cursos.curso_id
                INNER JOIN app_campus ON app_campus.campus_id = app_comissoes_cursos.campus_id
                WHERE app_comissoes_cursos.comissao_id = :id ", "id={$this->Id}");

            $Retorno['ComissaoCursos'] = $Read->getResult();

            $Read->ExeRead("app_comissoes_integrantes", "WHERE comissao_cod = :id", "id={$this->Id}");
            $Retorno['ComissaoIntegrantes'] = $Read->getResult();

            return $Retorno;

        else:
            return false;
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
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE categoria_id = :catid", "catid={$this->CatId}");
        if ($Update->getResult()):
            $this->Result = true;
        endif;
    }

    //Organiza os dados de Cursos
    private function SetCursos()
    {
        $this->ArrayCursos['cursos'] = $this->Data['comissao_cursos'];
        $this->ArrayCursos['campus'] = $this->Data['comissao_campus'];
        $this->ArrayCursos['periodo'] = $this->Data['comissao_periodo'];
        unset($this->Data['comissao_cursos']);
        unset($this->Data['comissao_campus']);
        unset($this->Data['comissao_periodo']);
    }

    //Cadastra Cursos na Tabela "app_comissoes_cursos
    private function CadastraCursos()
    {
        $Create = new Create();
        for ($i = 0; $i <= count($this->ArrayCursos['cursos']) - 1; $i++):
            $this->Data['curso_id'] = $this->ArrayCursos['cursos'][$i];
            $this->Data['campus_id'] = $this->ArrayCursos['campus'][$i];
            $this->Data['periodo_id'] = $this->ArrayCursos['periodo'][$i];
            $Create->ExeCreate("app_comissoes_cursos", $this->Data);
        endfor;
        unset($this->Data);

    }

    private function SetIntegrantes()
    {
        $this->ArrayIntegrantes['nome'] = $this->Data['integrante_nome'];
        $this->ArrayIntegrantes['email'] = $this->Data['integrante_email'];
        $this->ArrayIntegrantes['telefone'] = $this->Data['integrante_telefone'];
        unset($this->Data['integrante_nome']);
        unset($this->Data['integrante_email']);
        unset($this->Data['integrante_telefone']);
    }

    //Cadastra Integrantes na Tabela "app_comissoes_cursos"
    private function CadastraIntegrantes()
    {
        $this->Data['comissao_cod'] = $this->Result;
        $Create = new Create();
        for ($i = 0; $i <= count($this->ArrayIntegrantes['nome']) - 1; $i++):
            $this->Data['integrante_nome'] = $this->ArrayIntegrantes['nome'][$i];
            $this->Data['integrante_email'] = $this->ArrayIntegrantes['email'][$i];
            $this->Data['integrante_telefone'] = $this->ArrayIntegrantes['telefone'][$i];
            $Create->ExeCreate("app_comissoes_integrantes", $this->Data);
        endfor;

    }

}