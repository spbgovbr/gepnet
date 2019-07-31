<?php

/**
 * FlashMessages view helper
 * application/modules/admin/views/helpers/FlashMessages.php
 *
 * This helper creates an easy method to return groupings of
 * flash messages by status.
 *
 * @author Aaron Bach <bachya1208[at]googlemail.com
 * @license Free to use - no strings.
 */
class App_View_Helper_Esc extends Zend_View_Helper_Abstract
{

    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
    }

    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    /**
     *
     * @param string $data
     * @param string $default
     * @return string
     */
    public function esc($valor, $default = null)
    {
        if (empty($valor) && $default !== null) {
            return $default;
        }

        return empty($valor) ? '' : $valor;
    }
}