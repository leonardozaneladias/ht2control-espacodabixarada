<?php

/**
 * Check.class [ HELPER ]
 * Classe responável por manipular e validade dados do sistema!
 *
 * @copyright(c) 2016, Leonardo Zanela - ht2mlTech
 */
class Message
{

    private static $Msg;
    private static $Name;
    private static $TipoMsg;
    private static $Fade;

    /**
     *
     *
     *
     */
    public static function FlashMsg($Name, $TipoMsg = null, $Msg = null, $Fade = null)
    {
        self::$Msg = (string) $Msg;
        self::$Name = (string) $Name;
        self::$TipoMsg = (string) $TipoMsg;
        self::$Fade = (boolean) $Fade;

        if(!$_SESSION):
            session_start();
        endif;

        if(self::$Msg):
            $_SESSION[self::$Name]['msg'] = self::$Msg;
            $_SESSION[self::$Name]['type'] = self::$TipoMsg;
            $_SESSION[self::$Name]['fade'] = self::$Fade;
        else:
            if(isset($_SESSION[self::$Name]['msg']) && !empty($_SESSION[self::$Name]['msg'])):
                WSErro($_SESSION[self::$Name]['msg'], $_SESSION[self::$Name]['type'], null, $_SESSION[self::$Name]['fade']);
                unset($_SESSION[self::$Name]);
            endif;
        endif;
    }


}
