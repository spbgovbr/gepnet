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
class App_Mask_TelefoneFixo
{
    /**
     * DDD.DDD.DDD-DD
     */
    #(08)3224-5783
    const PATTERN = '/([\d]{2})([\d]{4})([\d]{4})/';
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
        $valor = str_pad($this->valor, 10, "0", STR_PAD_LEFT);
        $filtros = new Zend_Filter();
        $filtros
            ->addFilter(new Zend_Filter_Digits());
        $valorFiltrado = $filtros->filter($valor);
        return preg_replace(self::PATTERN, self::REPLACEMENT, $valorFiltrado);
    }

}

?>
