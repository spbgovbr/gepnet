<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of App_Mask_Cpf
 *
 * @author Administrador
 */
class App_Mask_TelefoneCelular
{
    /**
     * DDD.DDD.DDD-DD
     */

    const PATTERN = '/([\d]{2})([\d]{4})([\d]{4})/';
    const PATTERN_SP = '/([\d]{2})([\d]{4})([\d]{5})/';
    const REPLACEMENT = '($1) $2-$3';

    public $valor;

    public function __construct($valor)
    {
        $this->valor = $valor;
    }

    public function _()
    {
        return (string)$this;
    }

    public function __toString()
    {
        $valor = $this->valor;
        $filtros = new Zend_Filter();
        $filtros
            ->addFilter(new Zend_Filter_Digits());
        $valorFiltrado = $filtros->filter($valor);
        if (strlen($valorFiltrado) == 10) {
            return preg_replace(self::PATTERN, self::REPLACEMENT, $valorFiltrado);
        }
        return preg_replace(self::PATTERN_SP, self::REPLACEMENT, $valorFiltrado);
    }

}

?>
