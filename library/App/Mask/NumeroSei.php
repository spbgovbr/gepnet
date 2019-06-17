<?php

/**
 * Class App_Mask_NumeroSei
 */
class App_Mask_NumeroSei
{
    const PATTERN = '#####.######/####-##';
    public $numero;

    /**
     * App_Mask_NumeroSei constructor.
     * @param $numero
     */
    public function __construct($numero)
    {
        $this->numero = $numero;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $maskared = '';
        $k = 0;
        $mask = self::PATTERN;

        if (!empty($this->numero)) {
            for ($i = 0; $i <= strlen($mask) - 1; $i++) {
                if ($mask[$i] == '#') {
                    if (isset($this->numero[$k])) {
                        $maskared .= $this->numero[$k++];
                    }
                } else {
                    if (isset($mask[$i])) {
                        $maskared .= $mask[$i];
                    }
                }
            }
            return (string)$maskared;
        } else {
            return (string)$this->numero;
        }
    }
}
