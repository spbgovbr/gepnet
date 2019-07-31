<?php

class Default_Service_Documento extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Documento
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
        $this->_mapper = new Default_Model_Mapper_Documento();
    }

    /**
     * @return Default_Form_Documento
     */
    public function getForm()
    {
        return $this->_getForm('Default_Form_Documento');
    }

    /**
     * @return Default_Form_Documento
     */
    public function getFormPesquisar()
    {
        return $this->_getForm('Default_Form_DocumentoPesquisar');
    }

    /**
     * @return Default_Form_DocumentoEditar
     */
    public function getFormEditar()
    {
        $form = $this->_getForm('Default_Form_Documento', array('submit', 'reset', 'descaminho'));
        return $form;
    }

    public function getFormEditarArquivo()
    {
        $form = $this->_getForm('Default_Form_Documento', array(
            'submit',
            'reset',
            'flaativo',
            'desobs',
            'datdocumento',
            'idtipodocumento',
            'nomdocumento'
        ));
        $form->setAttrib('id', 'form-documento-arquivo');
        return $form;
    }

    //put your code here
    public function inserir($dados)
    {
        $form = $this->getForm();
        $this->_db->beginTransaction();

        if ($form->isValidPartial($dados)) {
            $descaminho = $form->getElement('descaminho');
            $descaminho->setValueDisabled(true);

            if (!$descaminho->receive()) {
                $this->_db->rollBack();
//                $this->errors = $form->getErrors();
                $this->errors = $form->getMessages();
                return false;
            }

            $fileName = $this->renomearArquivo($descaminho);
            $model = new Default_Model_Documento($form->getValues());
            $model->descaminho = $fileName;
            $id = $this->_mapper->insert($model);
            $this->_db->commit();

            return $id;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    private function renomearArquivo(Zend_Form_Element_File $file)
    {
        $extension = pathinfo($file->getFileName('descaminho'), PATHINFO_EXTENSION);
        $uniqueToken = md5(uniqid(mt_rand(), true));
        $format = 'file_%s_%s.%s';
        $newFileName = sprintf($format, $extension, $uniqueToken, $extension);
        $uploadfilepath = $file->getDestination() . DIRECTORY_SEPARATOR . $newFileName;
        //Zend_Debug::dump($uploadfilepath);
        //exit;

        $filterRename = new Zend_Filter_File_Rename(array(
            'target' => $uploadfilepath,
            'overwrite' => true
        ));
        $filterRename->filter($file->getFileName('descaminho'));
        return $newFileName;
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function update($dados)
    {
        $form = $this->getFormEditar();
        if ($form->isValid($dados)) {
            $model = new Default_Model_Documento($form->getValues());
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function editarArquivo($dados)
    {

        $form = $this->getFormEditarArquivo();
        $retorno = false;
        if ($form->isValid($dados)) {

//            var_dump($dados);exit;
            $descaminho = $form->getElement('descaminho');
            $model = new Default_Model_Documento($form->getValues());
            $fileName = $this->renomearArquivo($descaminho);
            //Envia para o servidor
            //Zend_Debug::dump($uploadfilepath);
            //exit;
            $model->descaminho = $fileName;

            $retorno = $this->_mapper->update($model);
        } else {
            $this->errors = $form->getMessages();
//            var_dump($this->errors);exit;
        }

        return $retorno;
    }

    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            //$model = new Default_Model_Documento($dados);
            return $this->_mapper->delete($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }
}

?>
