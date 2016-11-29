<?php
// DADOS DE DESENVOLVIMENTO DO SISTEMA ####################
define('APP_VERSION', '1.0.0');
define('APP_NAME', 'SiForme');

// CONFIGRAÇÕES DO BANCO ####################
define('HOST', '127.0.0.1');
define('USER', 'root');
define('PASS', 'oowMTqYskdw7');
define('DBSA', 'espacodabixarada');

// DEFINE SERVIDOR DE E-MAIL ################
define('MAILUSER', 'email@dominio.com.br');
define('MAILPASS', 'senhadoemail');
define('MAILPORT', 'postadeenvio');
define('MAILHOST', 'servidordeenvio');

// DEFINE IDENTIDADE DO SITE ################
define('SITENAME', 'Espaço da Bixarada');
define('SITEDESC', 'Este site foi desenvolvido gerenciamento de Pet Shops');

// DEFINE A BASE DO SITE ####################
define('HOME', 'http://espacodabixarada.com.br');
define('THEME', 'site');

define('INCLUDE_PATH', HOME . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . THEME);
define('REQUIRE_PATH', 'themes' . DIRECTORY_SEPARATOR . THEME);

// AUTO LOAD DE CLASSES ####################
function __autoload($Class) {

    $cDir = ['Conn', 'Helpers', 'Models'];
    $iDir = null;

    foreach ($cDir as $dirName):
        if (!$iDir && file_exists(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php') && !is_dir(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php')):
            include_once (__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $Class . '.class.php');
            $iDir = true;
        endif;
    endforeach;

    if (!$iDir):
        trigger_error("Não foi possível incluir {$Class}.class.php", E_USER_ERROR);
        die;
    endif;
}

// TRATAMENTO DE ERROS #####################
//CSS constantes :: Mensagens de Erro
define('WS_ACCEPT', 'success');
define('WS_INFOR', 'info');
define('WS_ALERT', 'warning');
define('WS_ERROR', 'danger');

//WSErro :: Exibe erros lançados :: Front
function WSErro($ErrMsg, $ErrNo, $ErrDie = null, $fade = null) {
    $Fade = ($fade) ? "alert-fadeout" : "" ;
    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
    echo "<div class=\"alert alert-{$CssClass} {$Fade}\" role='alert'>{$ErrMsg}</div>";

    if ($ErrDie):
        die;
    endif;
}

//PHPErro :: personaliza o gatilho do PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
    echo "<p class=\"alert alert-{$CssClass}\">";
    echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class=\"ajax_close\"></span></p>";

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

set_error_handler('PHPErro');


$STATUS = [
    0 => 'Inativo',
    1 => 'Ativo'
];

$LEVEL = [
    1 => 'Cadastrado',
    3 => 'Funcionario',
    8 => 'Administrador'
];

$CATEGORIA_SERVICO = [
    1 => 'Banho',
    2 => 'Tosa',
    3 => 'Medicamentos',
    4 => 'Veterinaria'
];

$ALBUM_CACHE = [
    1 => 'Cachê',
    2 => 'Repasse'
];

