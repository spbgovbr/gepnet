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
class App_View_Helper_DateBr extends Zend_View_Helper_Abstract
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
     * @param string | Zend_Date $data
     * @param string $default
     * @return string
     */
    public function dateBr($data, $default = null)
    {

        if ($data instanceof Zend_Date) {
            return $data->toString('d/m/Y');
        }

        if ($data instanceof DateTime) {
            return $data->format('d/m/Y');
        }

        if (empty($data) && $default !== null) {
            return $default;
        }

        return $data;
    }
}