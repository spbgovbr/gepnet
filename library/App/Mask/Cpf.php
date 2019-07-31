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
class App_Mask_Cpf
{
    /**
     * DDD.DDD.DDD-DD
     */

    const PATTERN = '/([\d]{3})([\d]{3})([\d]{3})([\d]{2})/';
    const REPLACEMENT = '$1.$2.$3-$4';

    public $cpf;

    public function __construct($cpf)
    {
        $this->cpf = $cpf;
    }

    public function _()
    {
        return (string)$this;
    }

    public function __toString()
    {
        $cpf = str_pad($this->cpf, 11, "0", STR_PAD_LEFT);
        $filtros = new Zend_Filter();
        $filtros
            ->addFilter(new Zend_Filter_Digits());
        $cpfFiltrado = $filtros->filter($cpf);
        return preg_replace(self::PATTERN, self::REPLACEMENT, $cpfFiltrado);
    }

}

?>
