<?php

class Projeto_Service_Relatorio extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Relatorio
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Statusreport();
    }

    /**
     * @return Projeto_Form_Relatorio
     */
    public function getForm()
    {
        return $this->_getForm('Projeto_Form_StatusReport');
    }

    public function getFiles($acompanhamentos, $params)
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        if (isset($acompanhamentos["rows"])) {
            foreach ($acompanhamentos["rows"] as &$a) {
                $arquivo = $service->retornaAnexo(array(
                    'idprojeto' => $params['idprojeto'],
                    'idstatusreport' => $a['cell'][8]
                ), false, true);
                if ($arquivo) {
                    $a['cell'][4] = '<a href="' . $arquivo . '" title="Cronograma" target="_blank"><i class="icon-download-alt"></i></a>';
                }
            }
        }
        return $acompanhamentos;
    }
}