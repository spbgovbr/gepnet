<?php

class App_Service_JqGrid extends App_Service_ServiceAbstract
{

    protected $_form;
    protected $_mapper;

    public function init()
    {
        //$this->_mapper = new Default_Model_Mapper_Documento();
    }

    public function toJqgrid()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();

        // Pass the current page number to paginator
        $this->_paginator->setCurrentPageNumber($request->getParam('page', 1));
        //Zend_Debug::dump($this->_paginator);exit;
        // Fetch a row of items from the adapter
        $rows = $this->_paginator->getCurrentItems();

        $grid = new stdClass();
        $grid->page = $this->_paginator->getCurrentPageNumber();
        //$grid->total = $this->_paginator->getItemCountPerPage();
        $grid->total = $this->_paginator->count();
        //$grid->records = $this->_paginator->getPageCount();
        $grid->records = $this->_paginator->getTotalItemCount();
        $grid->rows = array();

        foreach ($rows as $k => $row) {
            if (isset($row['id'])) {
                $grid->rows[$k]['id'] = $row['id'];
            }

            $grid->rows[$k]['cell'] = array_values($row);
            /*
              $grid->rows[$k]['cell'] = array();
              array_push($grid->rows[$k]['cell'], $row);
             */
        }
        return $grid;
    }
}

?>
