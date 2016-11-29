<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
session_start();
require('../../../_app/Config.inc.php');

$retorno = ['error' => 1, 'error_msg' => 'Acesso negado!'];

if (!isset($_SESSION['userlogin']['user_id']) or empty($_SESSION['userlogin']['user_id'])):
    echo json_encode($retorno);
    die();
else:
    $Id = $_SESSION['userlogin']['user_id'];
    $Password = $_SESSION['userlogin']['user_password'];
    $Read = new Read();
    $Read->ExeRead("ws_users", "WHERE user_id = :id AND user_password = :password", "id={$Id}&password={$Password}");
    if (!$Read->getResult()):
        $retorno = ['error' => 2, 'error_msg' => 'Sessão não encontrada!'];
        echo json_encode($retorno);
        die();
    endif;
endif;


$dataGet = filter_input(INPUT_GET, 'fnc', FILTER_DEFAULT);
$dataPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$dataGet = strip_tags(trim($dataGet));


if ($dataGet == 'getInstiuicoesDados'):
    $Id = (int)$dataPost['id'];
    $Read->ExeRead("app_campus", "WHERE instituicao_cod = :id", "id={$Id}");
    if ($Read->getResult()):
        foreach ($Read->getResult() as $campus):
            extract($campus);
            $retorno['data']['campus'][] = ["id" => $campus_id, "nome" => $campus_nome];
        endforeach;
        $Read->ExeRead("app_instituicoes_cursos", "WHERE instituicao_cod = :id", "id={$Id}");
        if ($Read->getResult()):
            $Cursos = new Read;
            $Cursos->ExeRead("app_cursos", "WHERE curso_status = :status", "status=1");
            foreach ($Cursos->getResult() as $cursos_ids):
                extract($cursos_ids);
                $cursos_id[$curso_id] = $curso_nome;
            endforeach;
            foreach ($Read->getResult() as $cursos_inst):
                extract($cursos_inst);
                $retorno['data']['cursos'][] = ["id" => $curso_cod, "nome" => $cursos_id[$curso_cod]];
            endforeach;
            $retorno['error'] = 0;
            $retorno['error_msg'] = 'Ok';
        endif;
    else:
        $retorno = ['error' => 3, 'error_msg' => 'Dados não encontrados!'];
    endif;
    echo json_encode($retorno);
endif;


/*
 **********************************************************
 * Orçamentos Comissão
 ***********************************************************
 */

if ($dataGet == 'getOrcamentoComissoes'):
    $nome = trim(strip_tags($dataPost['nome']));

    $IsNot = "";
    $Read->ExeRead("app_orcamento_comissoes", "WHERE orcamento_id = :idOrcamento", "idOrcamento=" . $_SESSION['orcamento']['id']);
    if ($Read->getResult()):
        $IsNot = "AND app_comissoes.comissao_id NOT IN (";
        $i = 1;
        $linhas = count($Read->getResult());
        foreach ($Read->getResult() as $Result):
            if ($linhas == $i):
                $IsNot .= $Result['comissao_id'];
            else:
                $IsNot .= $Result['comissao_id'] . ",";
            endif;

            $i++;
        endforeach;
        $IsNot .= ")";
    endif;

    $Read->ExeRead("app_comissoes", "LEFT OUTER JOIN app_instituicoes ON app_comissoes.instituicao_cod = app_instituicoes.instituicao_id WHERE app_comissoes.comissoes_nome LIKE :nome AND app_comissoes.comissao_status = :status AND app_comissoes.representante_cod = :repre {$IsNot}", "nome=%{$nome}%&status=1&repre={$_SESSION['userlogin']['user_id']}");
    if ($Read->getResult()):
        $ReadCursos = new Read;
        foreach ($Read->getResult() as $dados):
            extract($dados);
            $cursos = "";
            $ReadCursos->FullRead("SELECT app_cursos.curso_nome FROM app_comissoes_cursos LEFT OUTER JOIN app_cursos ON app_comissoes_cursos.curso_id = app_cursos.curso_id WHERE app_comissoes_cursos.comissao_id = :comissao_id", "comissao_id={$comissao_id}");
            if ($ReadCursos->getResult()):
                $cursos = "";
                foreach ($ReadCursos->getResult() as $cursos_dados):
                    extract($cursos_dados);
                    $cursos .= "{$curso_nome}, ";
                endforeach;
            endif;
            $retorno['data']['comissoes'][] = ["id" => $comissao_id, "nome" => $comissoes_nome, "instituicao" => $instituicao_apelido, "conclusao_ano" => $comissao_conclusao_ano, "conclusao_mes" => $comissao_conclusao_mes, "cursos" => $cursos];

        endforeach;
        $retorno['error'] = 0;
        $retorno['error_msg'] = 'Ok';
    else:
        $retorno = ['error' => 2, 'error_msg' => 'Nenhuma comissão encontrada'];
    endif;
    echo json_encode($retorno);
endif;


if ($dataGet == 'addOrcamentoComissao'):

    $idComissao = (int)$dataPost['id'];
    $idOrcamento = (int)$_SESSION['orcamento']['id'];

    $Data = [
        'orcamento_id' => $idOrcamento,
        'comissao_id' => $idComissao
    ];

    $Read = new Read;
    $Read->ExeRead("app_orcamento_comissoes", "WHERE orcamento_id = :idOrcamento AND comissao_id = :idComissao", "idOrcamento={$idOrcamento}&idComissao={$idComissao}");
    if ($Read->getResult()):
        $retorno = ['error' => 3, 'error_msg' => 'Comissão já adicionada!'];
    else:
        $Create = new Create;
        $Create->ExeCreate('app_orcamento_comissoes', $Data);
        if ($Create->getResult() == 0):
            $retorno['error'] = 0;
            $retorno['error_msg'] = 'Ok';
        else:
            $retorno = ['error' => 2, 'error_msg' => 'Erro ao tentar adicionar comissão!'];
        endif;

        echo json_encode($retorno);
    endif;
endif;

if ($dataGet == 'listarOrcamentoComissao'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];

    $ReadComissao = new Read();
    $ReadComissao->FullRead("SELECT app_comissoes.comissao_id, app_comissoes.comissoes_nome, app_instituicoes.instituicao_nome, app_instituicoes.instituicao_id, app_comissoes.comissao_conclusao_ano, app_comissoes.comissao_conclusao_mes FROM app_orcamento_comissoes INNER JOIN app_comissoes ON app_orcamento_comissoes.comissao_id = app_comissoes.comissao_id INNER JOIN app_instituicoes ON app_comissoes.instituicao_cod = app_instituicoes.instituicao_id WHERE app_orcamento_comissoes.orcamento_id = :id", "id={$idOrcamento}");
    if ($ReadComissao->getResult()):
        //var_dump($ReadComissao->getResult());
        $DataComissoes = $ReadComissao->getResult();
        foreach ($DataComissoes as $Comissoes):
            extract($Comissoes);

            $retorno['data'][$comissao_id]['comissoes_nome'] = $comissoes_nome;
            $retorno['data'][$comissao_id]['instituicao_nome'] = $instituicao_nome;
            $retorno['data'][$comissao_id]['instituicao_id'] = $instituicao_id;
            $Mes = ($comissao_conclusao_mes == 12) ? "DEZEMBRO" : "JULHO";
            $retorno['data'][$comissao_id]['conclusao'] = $Mes . "/" . $comissao_conclusao_ano;

            $ReadComissao->FullRead("SELECT app_cursos.curso_nome FROM app_instituicoes_cursos INNER JOIN app_instituicoes ON app_instituicoes_cursos.instituicao_cod = app_instituicoes.instituicao_id INNER JOIN app_cursos ON app_cursos.curso_id = app_instituicoes_cursos.curso_cod WHERE app_instituicoes.instituicao_id = :id", "id={$instituicao_id}");
            foreach ($ReadComissao->getResult() as $Cursos):
                $retorno['data'][$comissao_id]['cursos'][] = $Cursos['curso_nome'];
            endforeach;
        endforeach;
        $retorno['error'] = 0;
        $retorno['error_msg'] = 'Ok';
    else:
        $retorno = ['error' => 3, 'error_msg' => 'Nenhuma comissão cadastrada'];
    endif;
    echo json_encode($retorno);
endif;


if ($dataGet == 'delOrcamentoComissao'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $idComissao = (int)$dataPost['id'];
    $Delete = new Delete();
    $Delete->ExeDelete("app_orcamento_comissoes", "WHERE orcamento_id = :oid AND comissao_id = :cid", "oid={$idOrcamento}&cid={$idComissao}");
    if ($Delete->getResult()):
        $retorno = ['error' => 0, 'error_msg' => 'OK'];
    else:
        $retorno = ['error' => 1, 'error_msg' => 'Erro ao deletar!'];
    endif;
    echo json_encode($retorno);
endif;


/*
 **********************************************************
 * Orçamentos TipoEvento
 ***********************************************************
 */

if ($dataGet == 'OnOffOrcamentoTipoEvento'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $idTipoevento = (int)$dataPost['id'];
    $state = $dataPost['state'];
    $Data = [
        'orcamento_id' => $idOrcamento,
        'tipoevento_id' => $idTipoevento
    ];

    if ($state == 'true'):
        $Create = new Create;
        $Create->ExeCreate('app_orcamento_tipoeventos', $Data);
        if ($Create->getResult()):
            $retorno = ['error' => 0, 'error_msg' => 'OK1'];
        else:
            $retorno = ['error' => 1, 'error_msg' => 'Erro ao criar tipo de evento!'];
        endif;
    else:
        $Delete = new Delete();
        $Delete->ExeDelete("app_orcamento_tipoeventos", "WHERE orcamento_id = :oid AND tipoevento_id = :tid", "oid={$idOrcamento}&tid={$idTipoevento}");
        if ($Delete->getResult()):
            $retorno = ['error' => 0, 'error_msg' => 'OK2'];
        else:
            $retorno = ['error' => 1, 'error_msg' => 'Erro ao deletar tipo evento!'];
        endif;
    endif;
    echo json_encode($retorno);

endif;


if ($dataGet == 'LiveUpdateOrcamentoTipoEvento'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $idTipoevento = (int)$dataPost['id'];
    $campoName = $dataPost['campoName'];
    $campoValor = $dataPost['campoValor'];

    $Data = [
        $campoName => $campoValor
    ];

    $Update = new Update;
    $Update->ExeUpdate('app_orcamento_tipoeventos', $Data, 'WHERE orcamento_id = :oid AND tipoevento_id = :tid', "oid={$idOrcamento}&tid={$idTipoevento}");

endif;


/*
 **********************************************************
 * Orçamentos Álbum
 ***********************************************************
 */

if ($dataGet == 'getOrcamentoAlbuns'):
    $nome = $dataPost['nome'];

    $IsNot = "";
    $Read->ExeRead("app_orcamento_albuns", "WHERE orcamento_id = :idOrcamento", "idOrcamento=" . $_SESSION['orcamento']['id']);
    if ($Read->getResult()):
        $IsNot = "AND album_id NOT IN (";
        $i = 1;
        $linhas = count($Read->getResult());
        foreach ($Read->getResult() as $Result):
            if ($linhas == $i):
                $IsNot .= (int)$Result['album_id'];
            else:
                $IsNot .= (int)$Result['album_id'] . ",";
            endif;

            $i++;
        endforeach;
        $IsNot .= ")";
    endif;

    $Read->ExeRead("app_albuns", "WHERE album_nome LIKE :nome AND album_status = :status $IsNot", "nome=%{$nome}%&status=1");
    if ($Read->getResult()):
        $ReadCursos = new Read;
        foreach ($Read->getResult() as $dados):
            extract($dados);
            $retorno['data']['albuns'][] = ["id" => $album_id, "nome" => $album_nome, "descricao" => $album_descricao, "valor" => $album_valor, "repasse" => $album_repasse];
        endforeach;
        $retorno['error'] = 0;
        $retorno['error_msg'] = 'Ok';
    else:
        $retorno = ['error' => 2, 'error_msg' => 'Nenhum álbum encontrado!'];
    endif;
    echo json_encode($retorno);
endif;


if ($dataGet == 'addOrcamentoAlbum'):

    $idAlbum = (int)$dataPost['id'];

    if (isset($dataPost['qt'])):
        $idAlbumQt = (int)$dataPost['qt'];
    else:
        $idAlbumQt = 1;
    endif;

    $idOrcamento = (int)$_SESSION['orcamento']['id'];

    $Read = new Read;
    $Read->ExeRead("app_albuns", "WHERE album_id = :id", "id={$idAlbum}");

    if ($Read->getResult()):
        $Album = $Read->getResult()[0];

        $Data = [
            'orcamento_id' => $idOrcamento,
            'album_id' => $idAlbum,
            'album_nome' => $Album['album_nome'],
            'album_descricao' => $Album['album_descricao'],
            'album_valor' => $Album['album_valor'],
            'album_repasse' => $Album['album_repasse'],
            'album_qt' => $idAlbumQt

        ];

        $Create = new Create;
        $Create->ExeCreate('app_orcamento_albuns', $Data);
        if ($Create->getResult() == 0):
            $retorno['error'] = 0;
            $retorno['error_msg'] = 'Ok';
        else:
            $retorno = ['error' => 2, 'error_msg' => 'Erro ao tentar adicionar álbum! (COD00299)'];
        endif;
    else:
        $retorno = ['error' => 1, 'error_msg' => 'Erro ao tentar adicionar álbum! (COD00199)'];
    endif;

    echo json_encode($retorno);

endif;


if ($dataGet == 'listarOrcamentoAlbuns'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];

    $ReadAlbuns = new Read();
    $ReadAlbuns->ExeRead("app_orcamento_albuns", "WHERE orcamento_id = :id", "id={$idOrcamento}");
    if ($ReadAlbuns->getResult()):

        $DataAlbuns = $ReadAlbuns->getResult();
        foreach ($DataAlbuns as $Albuns):
            extract($Albuns);

            $retorno['data'][$album_id]['album_id'] = $album_id;
            $retorno['data'][$album_id]['album_nome'] = $album_nome;
            $retorno['data'][$album_id]['album_descricao'] = $album_descricao;
            $retorno['data'][$album_id]['album_valor'] = $album_valor;
            $retorno['data'][$album_id]['album_repasse'] = $album_repasse;
            $retorno['data'][$album_id]['album_qt'] = $album_qt;

        endforeach;
        $retorno['error'] = 0;
        $retorno['error_msg'] = 'Ok';
    else:
        $retorno = ['error' => 3, 'error_msg' => 'Nenhuma comissão cadastrada'];
    endif;
    echo json_encode($retorno);
endif;


if ($dataGet == 'LiveUpdateOrcamentoAlbumQt'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $idAlbum = (int)$dataPost['id'];
    $album_qt = (int)$dataPost['album_qt'];

    $Data = [
        'album_qt' => $album_qt
    ];

    $Update = new Update;
    $Update->ExeUpdate('app_orcamento_albuns', $Data, 'WHERE orcamento_id = :oid AND album_id = :aid', "oid={$idOrcamento}&aid={$idAlbum}");

endif;


if ($dataGet == 'LiveUpdateOrcamentoAlbumConfig'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $selectCache = $dataPost['selectCache'];
    $campoValor = str_replace(",",".",str_replace(".","",$dataPost['campoValor']));

    $campoValor = (empty($campoValor)) ? 0 : $campoValor;

    $Data = [
        'orcamento_config_album_cache' => $selectCache,
        'orcamento_config_album_cache_valor' => $campoValor
    ];

    if ($selectCache == 2) {
        $Data = [
            'orcamento_config_album_cache' => $selectCache,
            'orcamento_config_album_cache_valor' => 0
        ];
    }

    $Update = new Update;
    $Update->ExeUpdate('app_orcamento_config', $Data, 'WHERE orcamento_id = :oid', "oid={$idOrcamento}");

endif;


if ($dataGet == 'delOrcamentoAlbum'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $idAlbum = (int)$dataPost['id'];
    $Delete = new Delete();
    $Delete->ExeDelete("app_orcamento_albuns", "WHERE orcamento_id = :oid AND album_id = :aid", "oid={$idOrcamento}&aid={$idAlbum}");
    if ($Delete->getResult()):
        $retorno = ['error' => 0, 'error_msg' => 'OK'];
    else:
        $retorno = ['error' => 1, 'error_msg' => 'Erro ao deletar!'];
    endif;
    echo json_encode($retorno);
endif;


/*
 **********************************************************
 * Orçamentos Detalhes
 ***********************************************************
 */


if ($dataGet == 'LiveUpdateOrcamentoDetalhes'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $campoName = $dataPost['campoName'];
    $campoValor = $dataPost['campoValor'];

    if ($campoName == 'orcamento_detalhes_img') {
        $campoValor = str_replace('../../../', '', $campoValor);
    }

    $Data = [
        $campoName => $campoValor
    ];

    $Update = new Update;
    $Update->ExeUpdate('app_orcamento_detalhes', $Data, 'WHERE orcamento_id = :oid', "oid={$idOrcamento}");

endif;


/*
 **********************************************************
 * Orçamentos Local
 ***********************************************************
 */


if ($dataGet == 'ListarOrcamentoLocais'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $tipoEventoId = (int)$dataPost['id'];

    if ((isset($tipoEventoId) and !empty($tipoEventoId)) or (isset($idOrcamento) and !empty($idOrcamento))):
        $Read = new Read;
        $ReadImg = new Read;
        $Read->ExeRead("app_locais", "WHERE tipoevento_cod = :id", "id={$tipoEventoId}");
        if ($Read->getResult()):
            $retorno = ['error' => 0, 'error_msg' => 'OK'];
            foreach ($Read->getResult() as $Value):
                extract($Value);
                $ReadImg->ExeRead("app_locais_imgs", "WHERE local_id = :id AND local_img_capa = 1", "id={$local_id}");
                //var_dump($ReadImg);
                if ($ReadImg->getResult()[0]['local_img']):
                    $LocalImg = $ReadImg->getResult()[0]['local_img'];
                else:
                    $LocalImg = "";
                endif;

                $retorno['data'][$local_id]['local_img'] = HOME . "/" . $LocalImg;
                $retorno['data'][$local_id]['local_nome'] = $local_nome;
                $retorno['data'][$local_id]['local_mesas'] = $local_mesas;
                $retorno['data'][$local_id]['local_valor'] = $local_valor;
                $retorno['data'][$local_id]['local_capacidade'] = $local_capacidade;
                $retorno['data'][$local_id]['local_max_formandos'] = $local_max_formandos;
            endforeach;
            echo json_encode($retorno);
        else:
            $retorno = ['error' => 2, 'error_msg' => 'Erro genérico'];
            echo json_encode($retorno);
        endif;
    else:
        $retorno = ['error' => 2, 'error_msg' => 'Erro genérico'];
        echo json_encode($retorno);
    endif;

endif;


if ($dataGet == 'AddOrcamentoLocais'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $idLocal = (int)$dataPost['id'];
    $tipoeventoId = (int)$dataPost['tipoeventoId'];

    include_once("../../_models/AdminOrcamento.class.php");
    $Admin = new AdminOrcamento();
    $Admin->saveLocal($idOrcamento, $tipoeventoId, $idLocal);


endif;


/*
 **********************************************************
 * Orçamentos Brindes
 ***********************************************************
 */


if ($dataGet == 'getOrcamentoBrindes'):
    $nome = $dataPost['nome'];

    $IsNot = "";

    $Read->ExeRead("app_orcamento_brinde", "WHERE orcamento_id = :idOrcamento", "idOrcamento=" . $_SESSION['orcamento']['id']);
    if ($Read->getResult()):
        $IsNot = "AND brinde_id NOT IN (";
        $i = 1;
        $linhas = count($Read->getResult());
        foreach ($Read->getResult() as $Result):
            if ($linhas == $i):
                $IsNot .= $Result['brinde_id'];
            else:
                $IsNot .= $Result['brinde_id'] . ",";
            endif;

            $i++;
        endforeach;
        $IsNot .= ")";
    endif;


    $Read->ExeRead("app_brindes", "WHERE brinde_nome LIKE :nome AND brinde_status = :status $IsNot", "nome=%{$nome}%&status=1");
    if ($Read->getResult()):
        $ReadCursos = new Read;
        foreach ($Read->getResult() as $dados):
            extract($dados);
            $retorno['data']['brindes'][] = ["id" => $brinde_id, "nome" => $brinde_nome, "descricao" => $brinde_descricao, "valor" => $brinde_valor];
        endforeach;
        $retorno['error'] = 0;
        $retorno['error_msg'] = 'Ok';
    else:
        $retorno = ['error' => 2, 'error_msg' => 'Nenhum brinde encontrado!'];
    endif;
    echo json_encode($retorno);
endif;


if ($dataGet == 'addOrcamentoBrinde'):

    $id = (int)$dataPost['id'];

    if (isset($dataPost['qt'])):
        $Qt = (int)$dataPost['qt'];
    else:
        $Qt = 1;
    endif;

    $idOrcamento = (int)$_SESSION['orcamento']['id'];

    $Read = new Read;
    $Read->ExeRead("app_brindes", "WHERE brinde_id = :id", "id={$id}");

    if ($Read->getResult()):
        $Res = $Read->getResult()[0];

        $Data = [
            'orcamento_id' => $idOrcamento,
            'brinde_id' => $id,
            'orcamento_brinde_valor' => $Res['brinde_valor'],
            'orcamento_brinde_qt' => 1

        ];

        $Create = new Create;
        $Create->ExeCreate('app_orcamento_brinde', $Data);
        if ($Create->getResult() == 0):
            $retorno['error'] = 0;
            $retorno['error_msg'] = 'Ok';
        else:
            $retorno = ['error' => 2, 'error_msg' => 'Erro ao tentar adicionar Brinde! (COD00299)'];
        endif;
    else:
        $retorno = ['error' => 1, 'error_msg' => 'Erro ao tentar adicionar Brinde! (COD00199)'];
    endif;

    echo json_encode($retorno);

endif;


if ($dataGet == 'listarOrcamentoBrindes'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];

    $Read = new Read();
    $Read->ExeRead("app_orcamento_brinde", "INNER JOIN app_brindes ON app_orcamento_brinde.brinde_id = app_brindes.brinde_id WHERE app_orcamento_brinde.orcamento_id = :id", "id={$idOrcamento}");
    if ($Read->getResult()):

        $DataRes = $Read->getResult();
        foreach ($DataRes as $Res):
            extract($Res);

            $retorno['data'][$brinde_id]['brinde_id'] = $brinde_id;
            $retorno['data'][$brinde_id]['brinde_nome'] = $brinde_nome;
            $retorno['data'][$brinde_id]['brinde_descricao'] = $brinde_descricao;
            $retorno['data'][$brinde_id]['orcamento_brinde_valor'] = $orcamento_brinde_valor;
            $retorno['data'][$brinde_id]['orcamento_brinde_qt'] = $orcamento_brinde_qt;

        endforeach;
        $retorno['error'] = 0;
        $retorno['error_msg'] = 'Ok';
    else:
        $retorno = ['error' => 3, 'error_msg' => 'Nenhuma brinde cadastrado'];
    endif;
    echo json_encode($retorno);
endif;


if ($dataGet == 'LiveUpdateOrcamentoBrindeQt'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $id = (int)$dataPost['id'];
    $qt = (int)$dataPost['qt'];

    $Data = [
        'orcamento_brinde_qt' => $qt
    ];

    $Update = new Update;
    $Update->ExeUpdate('app_orcamento_brinde', $Data, 'WHERE orcamento_id = :oid AND brinde_id = :aid', "oid={$idOrcamento}&aid={$id}");

endif;


if ($dataGet == 'delOrcamentoBrinde'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $id = (int)$dataPost['id'];
    $Delete = new Delete();
    $Delete->ExeDelete("app_orcamento_brinde", "WHERE orcamento_id = :oid AND brinde_id = :aid", "oid={$idOrcamento}&aid={$id}");
    if ($Delete->getResult()):
        $retorno = ['error' => 0, 'error_msg' => 'OK'];
    else:
        $retorno = ['error' => 1, 'error_msg' => 'Erro ao deletar!'];
    endif;
    echo json_encode($retorno);
endif;


/*
 **********************************************************
 * Orçamentos Bar
 ***********************************************************
 */

if ($dataGet == 'getOrcamentoBarCardapios'):

    $id = (int)$dataPost['id'];
    $IsNot = "";

    require('../../_models/AdminBar.class.php');
    $Bar = new AdminBar();
    $Res = $Bar->listaCardapios($id);
    if ($Res):
        $retorno['error'] = 0;
        $retorno['error_msg'] = 'OK';
        foreach ($Res as $Cardapio):
            extract($Cardapio);
            $retorno['data'][$bar_cardapio_id]['bar_cardapio_nome'] = $bar_cardapio_nome;
        endforeach;
    else:
        $retorno = ['error' => 1, 'error_msg' => 'Nenhum cardapio cadastrado para este Fornecedor'];
    endif;
    echo json_encode($retorno);

endif;


if ($dataGet == 'getOrcamentoBarCardapiosBebidas'):

    $id = (int)$dataPost['id'];

    require('../../_models/AdminBar.class.php');
    $Bar = new AdminBar();
    $Res = $Bar->listaCardapiosBebidas($id);
    if ($Res):
        foreach ($Res as $BebidasTodas):
            extract($BebidasTodas);
            $retorno['data']['bebidasFornecedor'][$bebida_id]['id'] = $bebida_id;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['nome'] = $bebida_nome;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['img'] = $bebida_img;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['valor'] = $bebida_valor_dose;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['teor'] = $bebida_ml_alcool;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['qt'] = $dose_qt;
            $retorno['data']['bebidasFornecedor'][$bebida_id]['categoria'] = $bebida_categoria;
        endforeach;
    else:
        $retorno['data']['bebidasFornecedor'] = 0;
    endif;
    echo json_encode($retorno);

endif;


if ($dataGet == 'saveOrcamentoBar'):

    //var_dump($dataPost);
    //die();

    $idOrcamento = (int)$_SESSION['orcamento']['id'];

    require('../../_models/AdminOrcamento.class.php');
    $Orcamento = new AdminOrcamento();
    $Orcamento->saveBar($dataPost, $_SESSION['orcamento']['id']);


endif;


if ($dataGet == 'getOrcamentoBarBebidasEscolhidas'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $idTipoEvento = (int)$dataPost['tid'];
    $idCardapio = (int)$dataPost['cid'];

    require('../../_models/AdminOrcamento.class.php');
    $Orcamento = new AdminOrcamento();
    $Orcamento->SelectBebidas($idOrcamento, $idTipoEvento, $idCardapio);

endif;


/*
 **********************************************************
 * Orçamentos Produtos
 ***********************************************************
 */


if ($dataGet == 'getOrcamentoProdutos'):
    $nome = trim(strip_tags($dataPost['nome']));
    $idOrcamento = (int)$_SESSION['orcamento']['id'];

    $In = "";
    $NotIn = "";

    require('../../_models/AdminOrcamento.class.php');
    $AdminOrcamento = new AdminOrcamento;

    $TiposEventos = $AdminOrcamento->getTiposEventos();

    foreach ($TiposEventos as $Result):
        $In .= ",";
        $In .= $Result['tipoevento_id'];
    endforeach;
    $In = substr($In, 1);
    $In = "AND app_tipoevento.tipoevento_id IN ({$In})";

    $Read = new Read;
    $Read->ExeRead('app_orcamento_produtos', 'WHERE orcamento_cod = :oid', "oid={$idOrcamento}");
    if ($Read->getResult()):
        foreach ($Read->getResult() as $Result):
            if ($Result['produto_cod'] > 0):
                $NotIn .= ",";
                $NotIn .= $Result['produto_cod'];
            endif;
        endforeach;
        $NotIn = substr($NotIn, 1);
        $NotIn = "AND app_produtos.produto_id NOT IN ({$NotIn})";
    endif;

    $Read->FullRead("
        SELECT
        app_produtos.produto_id,
        app_produtos.produto_nome,
        app_produtos.produto_descricao,
        app_produtos.produto_valor,
        app_categorias.categoria_nome,
        app_tipoevento.tipoevento_nome
        FROM
        app_produtos
        INNER JOIN app_categorias ON app_produtos.categoria_cod = app_categorias.categoria_id
        INNER JOIN app_tipoevento ON app_categorias.tipoevento_cod = app_tipoevento.tipoevento_id
        WHERE  app_produtos.produto_status = 1
        AND app_produtos.produto_nome LIKE :nome
        {$In}
        {$NotIn}
        ORDER BY app_produtos.produto_nome
    ",
        "nome=%{$nome}%");
    if ($Read->getResult()):
        $retorno = ['error' => 0, 'error_msg' => 'OK'];
        foreach ($Read->getResult() as $Produto):
            extract($Produto);
            $retorno['data'][$produto_id]['nome'] = $produto_nome;
            $retorno['data'][$produto_id]['produto_descricao'] = $produto_descricao;
            $retorno['data'][$produto_id]['produto_valor'] = $produto_valor;
            $retorno['data'][$produto_id]['categoria_nome'] = $categoria_nome;
            $retorno['data'][$produto_id]['tipoevento_nome'] = $tipoevento_nome;
        endforeach;
    else:
        $retorno = ['error' => 2, 'error_msg' => 'Nenhum produto encontrado com este nome!'];
    endif;

    echo json_encode($retorno);
endif;


if ($dataGet == 'getOrcamentoProduto'):

    $id = (int)$dataPost['id'];
    $idOrcamento = (int)$_SESSION['orcamento']['id'];

    $Read = new Read;
    $Read->ExeRead('app_orcamento_produtos', 'WHERE orcamento_cod = :oid AND produto_id = :pid', "oid={$idOrcamento}&pid={$id}");
    if ($Read->getResult()):
        $retorno = ['error' => 0, 'error_msg' => 'OK'];
        $retorno['data'] = $Read->getResult()[0];
    else:
        $retorno = ['error' => 2, 'error_msg' => 'Nenhum produto encontrado com este nome!'];
    endif;
    echo json_encode($retorno);
endif;


if ($dataGet == 'addOrcamentoProduto'):

    $id = (int)$dataPost['id'];

    if (isset($dataPost['qt'])):
        $Qt = (int)$dataPost['qt'];
    else:
        $Qt = 1;
    endif;

    $idOrcamento = (int)$_SESSION['orcamento']['id'];

    $Read = new Read;
    $Read->FullRead('
        SELECT
        app_produtos.produto_id,
        app_produtos.produto_nome,
        app_produtos.categoria_cod,
        app_produtos.produto_valor,
        app_produtos.produto_valor_minimo,
        app_produtos.produto_posicao,
        app_produtos.produto_mult_formando,
        app_produtos.produto_mult_convites,
        app_produtos.produto_mult_mesas,
        app_produtos.produto_extra_mult_mesa,
        app_produtos.produto_extra_mult_convite,
        app_produtos.produto_alt_cortesia,
        app_produtos.produto_descricao,
        app_produtos.produto_obs,
        app_produtos.produto_status,
        app_produtos.produto_alias,
        app_categorias.categoria_nome,
        app_categorias.categoria_img,
        app_categorias.categoria_descricao,
        app_categorias.categoria_posicao,
        app_categorias.tipoevento_cod,
        app_categorias.categoria_status,
        app_categorias.categoria_alias,
        app_tipoevento.tipoevento_nome,
        app_tipoevento.tipoevento_descricao,
        app_tipoevento.tipoevento_posicao,
        app_tipoevento.tipoevento_status
        FROM
        app_produtos
        INNER JOIN app_categorias ON app_produtos.categoria_cod = app_categorias.categoria_id
        INNER JOIN app_tipoevento ON app_categorias.tipoevento_cod = app_tipoevento.tipoevento_id
        WHERE
        app_produtos.produto_id = :id
    ', "id={$id}");

    if ($Read->getResult()):

        $produtoRes = $Read->getResult()[0];
        require('../../_models/AdminOrcamento.class.php');
        $AdminOrcamento = new AdminOrcamento;

        $TiposEventos = $AdminOrcamento->getTiposEventosProduto($Read->getResult()[0]['tipoevento_cod']);
        $TiposEventos = $TiposEventos[0];

        $produtoInsert['produto_qt'] = 1;

        if (
            $produtoRes['produto_mult_formando'] == 1
            || $produtoRes['produto_mult_convites'] == 1
            || $produtoRes['produto_mult_mesas'] == 1
            || $produtoRes['produto_extra_mult_mesa'] == 1
            || $produtoRes['produto_extra_mult_convite'] == 1
        ):
            if (
                // formando apenas
                $produtoRes['produto_mult_formando'] == 1
                && $produtoRes['produto_mult_convites'] == 0
                && $produtoRes['produto_mult_mesas'] == 0
                && $produtoRes['produto_extra_mult_mesa'] == 0
                && $produtoRes['produto_extra_mult_convite'] == 0
            ):
                $produtoInsert['produto_qt'] = $TiposEventos['orcamento_tipoevento_qt_formandos'];

            elseif (
                // formando * convites
                $produtoRes['produto_mult_formando'] == 1
                && $produtoRes['produto_mult_convites'] == 1
                && $produtoRes['produto_mult_mesas'] == 0
                && $produtoRes['produto_extra_mult_mesa'] == 0
                && $produtoRes['produto_extra_mult_convite'] == 0
            ):
                $produtoInsert['produto_qt'] = $TiposEventos['orcamento_tipoevento_qt_formandos'] * $TiposEventos['orcamento_tipoevento_qt_convites'];

            elseif (
                //formandos * mesas
                $produtoRes['produto_mult_formando'] == 1
                && $produtoRes['produto_mult_convites'] == 0
                && $produtoRes['produto_mult_mesas'] == 1
                && $produtoRes['produto_extra_mult_mesa'] == 0
                && $produtoRes['produto_extra_mult_convite'] == 0
            ):
                $produtoInsert['produto_qt'] = $TiposEventos['orcamento_tipoevento_qt_formandos'] * $TiposEventos['orcamento_tipoevento_qt_mesas'];

            elseif (
                // (formando * convites) + convites extras
                $produtoRes['produto_mult_formando'] == 1
                && $produtoRes['produto_mult_convites'] == 1
                && $produtoRes['produto_mult_mesas'] == 0
                && $produtoRes['produto_extra_mult_mesa'] == 0
                && $produtoRes['produto_extra_mult_convite'] == 1
            ):
                $produtoInsert['produto_qt'] = ($TiposEventos['orcamento_tipoevento_qt_formandos'] * $TiposEventos['orcamento_tipoevento_qt_convites']) + $TiposEventos['orcamento_tipoevento_extra_qt_convites'];

            elseif (
                $produtoRes['produto_mult_formando'] == 1
                && $produtoRes['produto_mult_convites'] == 0
                && $produtoRes['produto_mult_mesas'] == 1
                && $produtoRes['produto_extra_mult_mesa'] == 1
                && $produtoRes['produto_extra_mult_convite'] == 0
            ):
                $produtoInsert['produto_qt'] = ($TiposEventos['orcamento_tipoevento_qt_formandos'] * $TiposEventos['orcamento_tipoevento_qt_mesas']) + $TiposEventos['orcamento_tipoevento_extra_qt_mesas'];

            endif;
        endif;

        $produtoInsert['orcamento_cod'] = $idOrcamento;
        $produtoInsert['produto_cod'] = $produtoRes['produto_id'];
        $produtoInsert['produto_nome'] = $produtoRes['produto_nome'];

        $produtoInsert['categoria_cod'] = $produtoRes['categoria_cod'];
        $produtoInsert['produto_valor'] = $produtoRes['produto_valor'];
        $produtoInsert['produto_valor_minimo'] = $produtoRes['produto_valor_minimo'];
        $produtoInsert['produto_posicao'] = $produtoRes['produto_posicao'];
        $produtoInsert['produto_mult_formando'] = $produtoRes['produto_mult_formando'];
        $produtoInsert['produto_mult_convites'] = $produtoRes['produto_mult_convites'];
        $produtoInsert['produto_extra_mult_mesa'] = $produtoRes['produto_extra_mult_mesa'];
        $produtoInsert['produto_extra_mult_convite'] = $produtoRes['produto_extra_mult_convite'];
        $produtoInsert['produto_alt_cortesia'] = $produtoRes['produto_alt_cortesia'];
        $produtoInsert['produto_descricao'] = $produtoRes['produto_descricao'];
        $produtoInsert['produto_obs'] = $produtoRes['produto_obs'];
        $produtoInsert['produto_alias'] = $produtoRes['produto_alias'];

        $Read->ExeRead('app_orcamento_produtos', 'WHERE orcamento_cod = :oid AND produto_cod = :pid', "oid={$produtoInsert['orcamento_cod']}&pid={$produtoInsert['produto_cod']}");
        if (!$Read->getResult()):
            $Create = new Create();
            $Create->ExeCreate('app_orcamento_produtos', $produtoInsert);
            $retorno = ['error' => 0, 'error_msg' => 'OK'];
        else:
            $retorno = ['error' => 2, 'error_msg' => 'Produto já adicionado a este orcamento'];
        endif;
    else:
        $retorno = ['error' => 1, 'error_msg' => 'Erro ao tentar adicionar Brinde! (COD00199)'];
    endif;

    echo json_encode($retorno);

endif;


if ($dataGet == 'getOrcamentoProdutoCadastrados'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];

    $Read = new Read;
    $Read->FullRead('
        SELECT
        app_orcamento_produtos.produto_id,
        app_orcamento_produtos.produto_nome,
        app_orcamento_produtos.produto_valor,
        app_orcamento_produtos.produto_qt,
        app_orcamento_produtos.produto_mult_formando,
        app_orcamento_produtos.produto_mult_convites,
        app_orcamento_produtos.produto_extra_mult_mesa,
        app_orcamento_produtos.produto_extra_mult_convite,
        app_categorias.categoria_id,
        app_categorias.categoria_nome,
        app_tipoevento.tipoevento_id,
        app_tipoevento.tipoevento_nome,
        app_categorias.categoria_posicao,
        app_tipoevento.tipoevento_posicao
        FROM
        app_orcamento_produtos
        INNER JOIN app_categorias ON app_orcamento_produtos.categoria_cod = app_categorias.categoria_id
        INNER JOIN app_tipoevento ON app_categorias.tipoevento_cod = app_tipoevento.tipoevento_id
        WHERE
        app_orcamento_produtos.orcamento_cod = :id
        ORDER BY
        app_tipoevento.tipoevento_posicao ASC,
        app_categorias.categoria_descricao ASC,
        app_orcamento_produtos.produto_posicao ASC
    ', "id={$idOrcamento}");

    if ($Read->getResult()):

        $retorno = ['error' => 0, 'error_msg' => 'OK'];
        foreach ($Read->getResult() as $k => $v):
            $retorno['data'][$v['produto_id']] = $v;
        endforeach;

    else:

        $retorno = ['error' => 2, 'error_msg' => 'Nenhuma Produto cadastrado!'];

    endif;

    echo json_encode($retorno);

endif;


if ($dataGet == 'delOrcamentoProdutoCadastrado'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $idProd = (int)$dataPost['id'];
    $Delete = new Delete();
    $Delete->ExeDelete("app_orcamento_produtos", "WHERE orcamento_cod = :oid AND produto_id = :pid", "oid={$idOrcamento}&pid={$idProd}");
    if ($Delete->getResult()):
        $retorno = ['error' => 0, 'error_msg' => 'OK'];
    else:
        $retorno = ['error' => 1, 'error_msg' => 'Erro ao deletar!'];
    endif;
    echo json_encode($retorno);
endif;


if ($dataGet == 'editOrcamentoProduto'):

    $idOrcamento = (int)$_SESSION['orcamento']['id'];
    $Data = $dataPost['data'];
    parse_str($Data, $Data);
    $Id = $Data['produto_id'];
    unset($Data['produto_id']);

    $Read = new Read;
    $Read->ExeRead('app_orcamento_produtos', 'WHERE orcamento_cod = :oid AND produto_id = :id', "oid={$idOrcamento}&id={$Id}");
    if ($Read->getResult()):

        $Data['produto_valor'] = str_replace(",", ".", str_replace(".", "", $Data['produto_valor']));
        $Data['produto_valor'] = number_format($Data['produto_valor'],2,".","");
        $valorMin = number_format($Read->getResult()[0]['produto_valor_minimo'],2,".","");

        if ($Data['produto_valor'] < $Read->getResult()[0]['produto_valor_minimo']):

            $retorno = ['error' => 2, 'error_msg' => 'Valor menor do que o minimo estipulado'];

        else:

            $Update = new Update;
            $Update->ExeUpdate('app_orcamento_produtos', $Data, 'WHERE orcamento_cod= :oid AND produto_id = :id', "oid={$idOrcamento}&id={$Id}");
            if ($Update->getResult()):
                $retorno = ['error' => 0, 'error_msg' => 'OK'];
            else:
                $retorno = ['error' => 1, 'error_msg' => 'Erro ao salvar!'];
            endif;

        endif;



    endif;


    echo json_encode($retorno);

endif;


ob_end_flush();