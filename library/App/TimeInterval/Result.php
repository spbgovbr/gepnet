<?php

/**
 * Description of TimeInterval
 *
 * @author Wilton Barbosa da Silva Júnior
 */

/**
 * Include needed Date classes
 */
//require_once 'Zend/Date/DateObject.php';

class App_TimeInterval_Result
{
    /*
    const PARTE_DIA     = 1;
    const PARTE_HORA    = 2;
    const PARTE_MINUTO  = 3;
    const PARTE_SEGUNDO = 4;
    */
    public $dias = 0;
    public $horas = 0;
    public $minutos = 0;
    public $segundos = 0;

    public function __construct($dias = 0, $horas = 0, $minutos = 0, $segundos = 0)
    {
        $this->dias = $dias;
        $this->horas = $horas;
        $this->minutos = $minutos;
        $this->segundos = $segundos;
    }

    public function __toString()
    {
        $format = '%s dia(s) %shora(s) %sminuto(s) %ssegundo(s)';
        return sprintf($format, $this->dias, $this->horas, $this->minutos, $this->segundos);
    }
}