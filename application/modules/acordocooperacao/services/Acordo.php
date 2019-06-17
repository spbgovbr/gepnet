<?php

class Acordocooperacao_Service_Acordo extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Acordocooperacao_Model_Mapper_Acordo
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Acordocooperacao_Model_Mapper_Acordo();
    }

    public function getForm()
    {
        return $this->_getForm('Acordocooperacao_Form_Acordo');
    }

    public function getFormEditar()
    {
        return $this->_getForm('Acordocooperacao_Form_Acordoeditar');
    }

    /**
     * @return Evento_Form_Evento
     */
    public function getFormPesquisar()
    {
//        $form = $this->_getForm('Acordocooperacao_Form_Acordo');
        $formAcordo = $this->_getForm('Acordocooperacao_Form_Acordopesquisar');
        $formAcordo->getElement('nomacordo')
            ->setAttribs(array('class' => 'span4', 'data-rule-required' => false))
            ->setRequired(false)
            ->removeValidator('NotEmpty');
        $formAcordo->getElement('idsetor')
            ->setAttribs(array('class' => 'span4', 'data-rule-required' => false))
            ->setRequired(false)
            ->removeValidator('NotEmpty');
        $formAcordo->getElement('flasituacaoatual')
            ->setAttribs(array('class' => 'span4', 'data-rule-required' => false))
            ->setRequired(false)
            ->removeValidator('NotEmpty');
        return $formAcordo;
    }

    public function inserir($dados)
    {
        $entidades = Array();
        foreach ($dados as $index => $d) {
            if (strstr($index, 'entidade_') !== false) {
                $entidades[] = $d;
            }
        }

        $dados = array_filter($dados);

        $form = $this->getForm();

        $descaminho = $form->getElement('descaminho');
        $descaminho->setValueDisabled(true);

        if ($descaminho->receive() != false) {
            $filename = $this->renomearArquivo($descaminho);
        }

        if ($form->isValidPartial($dados)) {
            $model = new Acordocooperacao_Model_Acordo($form->getValues());

            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                $model->idcadastrador = $auth->getIdentity()->idpessoa;
            }

            $model->descaminho = isset($filename) ? $filename : "";

            //Salva o acordo
            $retorno = $this->_mapper->insert($model);
            if ($retorno) {
                if (empty($entidades) == false) {
                    //salva as entidades para o acordo
                    $serviceAcordoEntidade = App_Service_ServiceAbstract::getService('Acordocooperacao_Service_Acordoentidadeexterna');
                    $serviceAcordoEntidade->inserir($retorno, $entidades);
                }
            }
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    private function renomearArquivo(Zend_Form_Element_File $file)
    {
        $extension = pathinfo($file->getFileName('descaminho'), PATHINFO_EXTENSION);
        $uniqueToken = md5(uniqid(mt_rand(), true));
        $format = 'ins_%s.%s';
        //$newFileName    = $id . '.' . $extn;
        $newFileName = sprintf($format, $uniqueToken, $extension);
//        Zend_Debug::dump($newFileName); exit;
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
        //ENTIDADES-----------
        $entidades = Array();
        foreach ($dados as $index => $d) {
            if (strstr($index, 'entidade_') !== false) {
                $entidades[] = $d;
            }
        }
        //--------------------

        $form = $this->getFormEditar();

        $dados = array_filter($dados);

//        try{
        if ($form->isValidPartial($dados)) {
            $descaminho = $form->getElement('descaminho');
            $descaminho->setValueDisabled(true);

            if ($descaminho->receive() != false) {
                $filename = $this->renomearArquivo($descaminho);
            }

            $model = new Acordocooperacao_Model_Acordo($form->getValues());

            $model->descaminho = isset($filename) ? $filename : "";

            $retorno = $this->_mapper->update($model);
            if ($retorno) {
                if (empty($entidades) == false) {
                    //salva as entidades para o acordo
                    $serviceAcordoEntidade = App_Service_ServiceAbstract::getService('Acordocooperacao_Service_Acordoentidadeexterna');
                    $serviceAcordoEntidade->excluirEntidades($retorno);
                    $serviceAcordoEntidade->inserir($retorno, $entidades);
                }
            }

            return $retorno;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
        /*} catch(Exception $e) {
            $this->errors = $form->getMessages();
//            throw($e);
        }*/
    }


    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }

    public function getByIdDetalhar($params)
    {
        return $this->_mapper->getByIdDetalhar($params);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Atividade_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params, $paginator);
//        if ( $paginator ) {
//            $service = new App_Service_JqGrid();
//            $service->setPaginator($dados);
//            return $service;
//        }
        if ($paginator) {
            $response = array();
            $response['page'] = $dados->getPages()->current;
            $response['total'] = $dados->getPages()->pageCount;
            $response['records'] = $dados->getPages()->totalItemCount;

            foreach ($dados as $d) {
//                Zend_Debug::dump($d);
                $array = array();
                $array['cell'] = array(
                    $d['idacordo'],
                    $d['nomsetor'],
                    $d['numsiapro'],
                    $d['nomacordo'],
                    $d['responsavelinterno'],
                    $d['nomfiscal'],
                    $d['datiniciovigencia'],
                    $d['datfimvigencia'],
                    $d['flasituacaoatual'],
                    $d['pdf'],
                    $d['idacordo'],
                    $d['descaminho']
                );

                $response["rows"][] = $array;
            }
//            exit;
        }
        return $response;
    }


    public function retornaSituacao()
    {
        $array = array(
            '0' => 'Todas',
            '1' => 'Vigente',
            '2' => 'Proposta',
            '3' => 'Vencido',
            '4' => 'Rescindido',
        );

        return $array;
    }

    public function retornaRescindido()
    {
        $array = array(
            '' => 'Selecione',
            'S' => 'SIM',
            'N' => 'NÃO',
        );

        return $array;
    }

    public function getFiles($acordos)
    {
//        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Acordo');
        $config = Zend_Registry::get('config');
        $uploadDir = $config->resources->cachemanager->default->backend->options->upload_dir;
        if (isset($acordos["rows"])) {
            foreach ($acordos["rows"] as &$a) {
//                var_dump($a);
                $path = $uploadDir . 'acordo' . DIRECTORY_SEPARATOR . $a['cell'][11];

//                var_dump($path);
//                var_dump(is_file($path));
                if (is_file($path)) {
                    $url = $this->retornaAnexo(array('descaminho' => $a['cell'][11]), false, true);
                    $a['cell'][9] = '<a href="' . $url . '" title="Anexo" target="_blank"><i class="icon-download-alt"></i></a>';
                }
            }
        }
//        Zend_Debug::dump($acordos); exit;
        return $acordos;
    }

//    public function retornaAcordoPai(){
//        $dados = $this->_mapper->fetchPairs();
//        Zend_Debug::dump($dados); exit;
//    }

    /**
     * @param $params
     * @param bool $retornaPath
     * @param bool $retornaRouteDownload
     * @return bool|string
     * @throws Zend_Exception
     */
    public function retornaAnexo($params, $retornaPath = false, $retornaRouteDownload = false)
    {
        $config = Zend_Registry::get('config');
        $arquivosDir = $config->resources->cachemanager->default->backend->options->upload_dir;
        $filename = $params['descaminho'];
        $path = $arquivosDir . 'acordo/' . $filename;
        if (file_exists($path)) {
            $view = new Zend_View_Helper_Url();
            if ($retornaRouteDownload) {
                return $view->url(
                    array(
                        'arquivo' => base64_encode($path)
                    ),
                    'download'
                );
            }
            if ($retornaPath) {
                return $path;
            }
            return $view->url(
                array(
                    'module' => 'acordocooperacao',
                    'controller' => 'instrumentocooperacao',
                    'action' => 'download',
                    'file' => base64_encode($filename)
                ),
                'download'
            );
        }
        return false;
    }

    /**
     * @param $params
     * @return stdClass
     * @throws Zend_Exception
     */
    public function getDownloadConfig($params)
    {
        $config = Zend_Registry::get('config');
        $arquivosDir = $config->resources->cachemanager->default->backend->options->upload_dir;
        $fileLocation = $arquivosDir . 'acordo/' . $params['file'];
        $size = filesize($fileLocation);
        $extension = pathinfo($fileLocation, PATHINFO_EXTENSION);
        $fileName = pathinfo($fileLocation, PATHINFO_FILENAME) . '.' . $extension;
        $download = new App_Download();
        $headers = $download->getHeaders($fileName, $size);
        $response = new stdClass();
        $response->headers = $headers;
        $response->path = $fileLocation;
        $response->filename = $fileName;
        return $response;
    }
}


