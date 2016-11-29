<?php

/**
 * Form.class [ HELPER ]
 * Classe responável por manipular e validade dados do sistema!
 *
 * @copyright(c) 2016, Leonardo Zanela - ht2mlTech
 */
class Form
{

    private static $Data;
    private static $InputValue;
    private static $ListValue;


    /**
     * <b>Campo Value</b> Ao executar este HELPER, ele automaticamente verifica a existencia de valor para o atributo value
     *
     * @return $Data
     */
    public static function Value($Data)
    {

        self::$Data = $Data;

        if (!empty(self::$Data)):
            return self::$Data;
        else:
            return false;
        endif;
    }


    /**
     * <b>Campo Select</b> Ao executar este HELPER, ele automaticamente verifica se o options é o escolhido e cria a tag selected
     *
     * @return $Data
     */
    public static function SelectOption($Data = null, $ValorOption = null)
    {
        if ($Data):

            self::$Data = $Data;
            self::$InputValue = $ValorOption;

            if (self::$Data == self::$InputValue):
                return "selected";
            else:
                return false;
            endif;

        else:

            return false;

        endif;

    }

}
