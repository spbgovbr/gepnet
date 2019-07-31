<?php

use Default_Service_Log as Log;

class Projeto_Service_StatusReport extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Statusreport
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
     * @return Projeto_Form_StatusReportinserir
     */
    //public function getForm($params = null,$statusReport = null ,Projeto_Model_Gerencia $projeto = null) {
    public function getForm($params = null)
    {
        $desandamentoprojeto = null;
        $form = $this->_getForm('Projeto_Form_StatusReportinserir');
        if (!empty($params)) {
            $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
            $serviceGerencia = new Projeto_Service_Gerencia();
            $desatividadeconcluida = "";
            $desatividadeandamento = "";
            $periodos = $serviceAtividadeCronograma->retornaDataPeriodo(array(
                'idprojeto' => $params['idprojeto']
            ));

            if ($periodos) {
                $dtInicio = new Zend_Date($periodos['datainiperiodo'], 'dd/MM/YYYY');
                $dtFim = new Zend_Date($periodos['datafinperiodo'], 'dd/MM/YYYY');

                $dadosAtvConcluidas = array(
                    'idprojeto' => $params['idprojeto'],
                    'dtInicio' => $dtInicio->toString('d-m-Y'),
                    'dtFim' => $dtFim->toString('d-m-Y')
                );

                $dadosAtvEmAndamento = array(
                    'idprojeto' => $params['idprojeto'],
                    'dtInicio' => $dtInicio->toString('d-m-Y'),
                    'dtFim' => date('d-m-Y')
                );
                $desatividadeconcluida = $this->getAtividadesConcluidas($dadosAtvConcluidas);
                $desatividadeandamento = $this->getAtividadesEmAndamento($dadosAtvEmAndamento);

                $desandamentoprojeto = $desandamentoprojeto ? $desandamentoprojeto : "Não existem considerações sobre o andamento do projeto.";
            }
            $array = $serviceAtividadeCronograma->fetchPairsMarcosPorProjeto($params);
            $arr = array('' => 'Selecione');
            $array = $arr + $array;

            $params['desandamentoprojeto'] = $desandamentoprojeto;
            $params['desatividadeconcluida'] = $desatividadeconcluida;
            $params['desatividadeandamento'] = $desatividadeandamento;
            $params['datcadastro'] = date('Y-m-d');
            $params['idprojeto'] = $params['idprojeto'];

            $form->getElement('idmarco')->setMultiOptions($array);
            $form->populate($params);
        }
        return $form;
    }

    /**
     * @return Projeto_Form_Gerencia
     */
    public function getFormPesquisar()
    {

        return $this->_getForm('Projeto_Form_Statusreportpesquisar');
    }

    public function getFormEditar($params = null)
    {
        $form = $this->_getForm('Projeto_Form_StatusReporteditar');
        if (empty($params) == false) {
            $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
            /** @var Projeto_Model_Statusreport $acompanhamentoAtual */
            $acompanhamentoAtual = $this->getById($params);
            $array = $serviceAtividadeCronograma->fetchPairsMarcosPorProjeto($params);
            $arr = array('' => 'Selecione');
            $array = $arr + $array;
            $dadosDataAtv = $this->retornaPeriodoAcompanhamento($params);
            $desatividadeconcluida = $this->getAtividadesConcluidas($dadosDataAtv);
            $desatividadeandamento = $this->getAtividadesEmAndamento($dadosDataAtv);

            $params = $acompanhamentoAtual->formPopulate();

            $params['desatividadeconcluida'] = $desatividadeconcluida;
            $params['desatividadeandamento'] = $desatividadeandamento;

            $form->getElement('idmarco')->setMultiOptions($array);
            $form->populate($params);
        }

        return $form;
    }

    public function getAtividadesConcluidas($dadosDataAtv)
    {
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $desatividadeconcluida = "";
        $atvConcluidas = $serviceAtividadeCronograma->retornaAtividadesConcluidas($dadosDataAtv);
        if (count($atvConcluidas) > 0) {
            foreach ($atvConcluidas as $registro) {
                $desatividadeconcluida .= $registro['registro'];
            }
        }
        $desatividadeconcluida = (!empty($desatividadeconcluida)) ? trim($desatividadeconcluida) : "Não existem atividades concluídas neste período.";

        return $desatividadeconcluida;
    }

    public function getAtividadesEmAndamento($dadosDataAtv)
    {
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $desatividadeandamento = "";
        $atvAndamento = $serviceAtividadeCronograma->retornaAtividadesEmAndamento($dadosDataAtv);

        if (count($atvAndamento) > 0) {
            foreach ($atvAndamento as $registro) {
                $desatividadeandamento .= $registro['registro'];
            }
        }
        $desatividadeandamento = (!empty($desatividadeandamento)) ? trim($desatividadeandamento) : "Não existem atividades em andamento neste período.";

        return $desatividadeandamento;
    }


    public function retornaPeriodoAcompanhamento($params)
    {

        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $serviceGerencia = new Projeto_Service_Gerencia();
        if (!empty($params['idstatusreport'])) {
            /** @var Projeto_Model_Statusreport $acompanhamentoAtual */
            $acompanhamentoAtual = $this->getById($params);
        } else {
            $acompanhamentoAtual = $this->retornaUltimoPorProjeto($params);
        }

        $dados = $acompanhamentoAtual->toArray();
        $dados['datacompanhamento'] = $acompanhamentoAtual->datacompanhamento->toString('Y-m-d');
        /** @var Projeto_Model_Statusreport $acompanhamentoAnterior */
        $acompanhamentoAnterior = $this->isAcompanhamentoAnterior($dados);

        if (is_object($acompanhamentoAnterior) && (!empty($acompanhamentoAnterior->idstatusreport))) {
            $arr = array(
                'datainicio' => $acompanhamentoAnterior->datacompanhamento->toString('d/m/Y'),
                'numdias' => (int)2
            );
            $dtInicio = new Zend_Date($serviceAtividadeCronograma->retornaDataFimValidaPorDias($arr));
            $dtFim = $acompanhamentoAtual->datacompanhamento->toString('d/m/Y');
        } else {

            /** @var Projeto_Model_Gerencia $projeto */
            $projeto = $serviceGerencia->retornaProjetoPorId($params);
            $dtInicio = $projeto->datinicio;
            $dtFim = $acompanhamentoAtual->datacompanhamento->toString('d/m/Y');
        }

        $periodo = array(
            'idprojeto' => $acompanhamentoAtual->idprojeto,
            'dtInicio' => $dtInicio->toString('d-m-Y'),
            'dtFim' => $dtFim
        );

        return $periodo;
    }


    public function getFormExcluir($params = null)
    {
        $form = $this->_getForm('Projeto_Form_StatusReportexcluir');
        if (empty($params) == false) {
            $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
            $array = $serviceAtividadeCronograma->fetchPairsMarcosPorProjeto($params);
            $arr = array('' => 'Selecione');
            $array = $arr + $array;

            $form->getElement('idmarco')->setMultiOptions($array);
            $form->populate($params);
        }

        return $form;
    }

    //put your code here
    /*public function inserir($dados) {
       $form = $this->getForm($dados);
        
//        Zend_Debug::dump($form->getValues());
//        exit;

        if ($form->isValidPartial($dados)) {
            ///  $model = new Projeto_Model_Gerencia($form->getValues());
            $model = new Projeto_Model_Statusreport($form->getValues());
            return $this->_mapper->insert($model);
        } else {
            $this->errors = $form->getMessages();
        }
        return false;


    }*/

    /**
     * Função de cadastro de relatorio de acompanhamento de projeto
     * @param array $params
     * @return Projeto_Model_Statusreport
     */
    public function inserirStatusProjeto($params)
    {

        $atraso = 0;
        $domcoratraso = "success";
        $acompanhamentos = $this->retornaUltimoAcompanhamento(array('idprojeto' => $params['idprojeto']), false);
        $auth = Zend_Auth::getInstance();
        $idntiti = $auth->getIdentity();
        $idperfil = $idntiti->perfilAtivo->idperfil;
        $statusReport = array();
        /*******************DADOS DO ACOMPANHAMENTO*****************************/
        $statusReport['domstatusprojeto'] = 1;
        $statusReport['datacompanhamento'] = date("Y-m-d");
        $statusReport['datcadastro'] = date("Y-m-d");
        $statusReport['dataaprovacao'] = date("Y-m-d");
        $statusReport['numpercentualprevisto'] = 0.00;
        $statusReport['numpercentualconcluido'] = 0.00;
        $statusReport['idmarco'] = 1;
        $statusReport['idcadastrador'] = $idperfil;
        $statusReport['domcorrisco'] = 1;
        $statusReport['desatividadeconcluida'] = "Projeto sem acompanhamento cadastrado.";
        $statusReport['desatividadeandamento'] = "Projeto sem acompanhamento cadastrado.";
        $statusReport['desmotivoatraso'] = "Projeto sem acompanhamento cadastrado.";
        $statusReport['descontramedida'] = "Projeto sem acompanhamento cadastrado.";
        $statusReport['desirregularidade'] = "Projeto sem acompanhamento cadastrado.";
        $statusReport['desrisco'] = "Projeto sem acompanhamento cadastrado.";
        $statusReport['descaminho'] = "Projeto sem acompanhamento cadastrado.";
        $statusReport['flaaprovado'] = 2;
        $statusReport['idprojeto'] = $params['idprojeto'];

        if (null == $acompanhamentos->idstatusreport) {
            if (
            (Zend_Date::isDate($params['datfim']))
            ) {
                $dataFimProjeto = new Zend_Date($params['datfim'], 'dd/mm/yyyy');
                $statusReport['datmarcotendencia'] = $dataFimProjeto->toString('Y-m-d');
                $statusReport['datfimprojetotendencia'] = $dataFimProjeto->toString('Y-m-d');
                $statusReport['datfimprojeto'] = $dataFimProjeto->toString('Y-m-d');
            }
        } else {
            $statusReport['datmarcotendencia'] = date("d-m-Y");
            $statusReport['datfimprojetotendencia'] = date("d-m-Y");
        }

        if (is_array($statusReport) && count($statusReport) > 0) {
            $model = new Projeto_Model_Statusreport($statusReport);
            $model->setAtrasoProjeto($atraso);
            $model->setDomCorAtraso($domcoratraso);
            $model->setPercentualConcluidoMarco(0.00);
            $model->setNumeroCriterioFarol($params['numcriteriofarol']);
            $insert = $this->_mapper->insert($model);
            return $insert;
        } else {
            return false;
        }
    }

    /**
     * Retorna o acompanhamento anterior ao passado por parametros
     * @param $params
     * @return Projeto_Model_
     *
     */
    public function isAcompanhamentoAnterior($params)
    {
        return $this->_mapper->isAcompanhamentoAnterior($params);
    }


    public function alterarStatusProjeto($statusReport)
    {
        try {
            //inserindo os dados no primeiro momento do projeto
            $model = new Projeto_Model_Statusreport($statusReport);
            $insert = $this->_mapper->insert($model);
            return true;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    public function ultimoId()
    {
        $id = $this->_mapper->ultimoId();
        return $id['idstatusreport'];
    }

    public function inserir($dados)
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $serviceSituacaoProjeto = new Projeto_Service_SituacaoProjeto();
        $serviceGerencia = new Projeto_Service_Gerencia();
        $dados['desatividadeconcluida'] = (trim($dados['desatividadeconcluida']) ? mb_substr($dados['desatividadeconcluida'],
            0, 4000) : $dados['desatividadeconcluida']);
        $dados['desatividadeandamento'] = (trim($dados['desatividadeandamento']) ? mb_substr($dados['desatividadeandamento'],
            0, 4000) : $dados['desatividadeandamento']);
        $dados['desmotivoatraso'] = (trim($dados['desmotivoatraso']) ? mb_substr($dados['desmotivoatraso'], 0,
            4000) : $dados['desmotivoatraso']);
        $dados['descontramedida'] = (trim($dados['descontramedida']) ? mb_substr($dados['descontramedida'], 0,
            4000) : $dados['descontramedida']);
        $dados['desirregularidade'] = (trim($dados['desirregularidade']) ? mb_substr($dados['desirregularidade'], 0,
            4000) : $dados['desirregularidade']);
        $dados['desrisco'] = (trim($dados['desrisco']) ? mb_substr($dados['desrisco'], 0, 4000) : $dados['desrisco']);

        if (!empty($dados['numprocessosei'])) {
            $dados['numprocessosei'] = preg_replace('/[^0-9]/i', '', $dados['numprocessosei']);
        }
        $form = $this->getForm($dados);
        $arquivo = isset($dados['descaminho']) == false;
        if (empty($dados['descaminho'])) {
            $dados['descaminho'] = null;
        }

        if ($dados['idmarco'] == '1' && $dados['flaaprovado'] == '1') {
            $dados['idmarco'] = null;
            $dados['datfimprojeto'] = $dados['datfimprojetotendencia'];
            $dados['dataprovacao'] = date('Y-m-d');
            $dados['flaaprovado'] = 1;
            $dados['datcadastro'] = null;
        }

        if ($form->isValidPartial($dados)) {
            $objStatusRerport = new Projeto_Model_Statusreport($form->getValues());
            $objProjeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $dados['idprojeto']));
            $objStatusRerport->setDatfimprojeto($objProjeto->datfim->toString('d/m/Y'));
            $objStatusRerport->setAtrasoProjeto($dados['diaatraso']);
            $objStatusRerport->setDomCorAtraso($dados['domcoratraso']);
            $objStatusRerport->setPercentualConcluidoMarco($objProjeto->percentualConcluidoMarco);
            $objStatusRerport->setNumeroCriterioFarol($objProjeto->numcriteriofarol);

            /** @var Projeto_Model_Statusreport $model */
            $model = $this->_mapper->insert($objStatusRerport);

            if (is_object($model) && (!empty($model->idstatusreport))) {
                $retorno = $serviceGerencia->atualizaStatusProjeto($model);

                if ($retorno) {
                    $model->nomdomstatusprojeto = trim($serviceSituacaoProjeto->getById($model->domstatusprojeto));
                }

                if (isset($dados['numprocessosei']) && (!empty($dados['numprocessosei']))) {
                    $data = array(
                        "numprocessosei" => $dados['numprocessosei'],
                        "idprojeto" => (int)$model->idprojeto,
                        "idstatusreport" => $model->idstatusreport
                    );
                    $serviceGerencia->atualizaNumeroSEIProjeto($data);
                }

                $serviceGerencia->updateTapAssinado($dados);

                return $model;
            } else {
                return false;
            }
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    private function renomearArquivo(Zend_Form_Element_File $file, $dados, $insert = false)
    {
        $idstatusreport = isset($dados['idstatusreport']) ? $dados['idstatusreport'] : $this->ultimoId();
        $extension = pathinfo($file->getFileName('descaminho'), PATHINFO_EXTENSION);
//        $uniqueToken    = md5(uniqid(mt_rand(), true));
        $format = 'pdf_%s_%s.%s';
        //$newFileName    = $id . '.' . $extn;
        $newFileName = sprintf($format, $dados['idprojeto'], $idstatusreport, $extension);
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
     * converte Model report para array
     *
     * @param Projeto_Model_Statusreport
     * @return array
     */
    public function toArrayAcompanhmento(Projeto_Model_Statusreport $acompanhamento)
    {

        $servicoCron = new Projeto_Service_AtividadeCronograma();

        $params = array(
            'idprojeto' => $acompanhamento->idprojeto,
            'idatividadecronograma' => $acompanhamento->idmarco
        );

        $marco = $servicoCron->retornaMarcoById($params);

        if ($acompanhamento->idmarco == '1') {
            $acompanhamento->datcadastro = null;
        }

        return array(
            'idstatusreport' => $acompanhamento->idstatusreport,
            'idprojeto' => $acompanhamento->idprojeto,
            'idprograma' => $acompanhamento->idprograma,
            'datacompanhamento' => $acompanhamento->datacompanhamento != null ? $acompanhamento->datacompanhamento->toString('d/m/Y') : "",
            'nomdomstatusprojeto' => $acompanhamento->nomdomstatusprojeto,
            'numpercentualconcluido' => $acompanhamento->numpercentualconcluido,
            'numpercentualprevisto' => $acompanhamento->numpercentualprevisto,
            'desatividadeconcluida' => $acompanhamento->desatividadeconcluida,
            'desatividadeandamento' => $acompanhamento->desatividadeandamento,
            'desmotivoatraso' => $acompanhamento->desmotivoatraso,
            'desirregularidade' => $acompanhamento->desirregularidade,
            'idmarco' => $acompanhamento->idmarco,
            'datfimbaseline' => $marco['datfimbaseline'],
            'datfimMarco' => $marco['datfim'],
            'nomatividadecronograma' => $marco['nomatividadecronograma'],
            'datmarcotendencia' => $acompanhamento->datmarcotendencia != null ? $acompanhamento->datmarcotendencia->toString('d/m/Y') : "",
            'datfimprojetotendencia' => $acompanhamento->datfimprojetotendencia != null ? $acompanhamento->datfimprojetotendencia->toString('d/m/Y') : "",
            'idcadastrador' => $acompanhamento->idcadastrador,
            'datcadastro' => $acompanhamento->datcadastro != null ? $acompanhamento->datcadastro->toString('d/m/Y') : "",
            'domstatusprojeto' => $acompanhamento->domstatusprojeto,
            'flaaprovado' => $acompanhamento->flaaprovado,
            'domcorrisco' => $acompanhamento->domcorrisco,
            'descontramedida' => $acompanhamento->descontramedida,
            'desrisco' => $acompanhamento->desrisco,
            'dataprovacao' => $acompanhamento->dataprovacao,
            'descaminho' => $acompanhamento->descaminho,
            'pgpassinado' => $acompanhamento->pgpassinado,
            'tepassinado' => $acompanhamento->tepassinado,
            'desandamentoprojeto' => $acompanhamento->desandamentoprojeto,
            'diaatraso' => $acompanhamento->diaatraso,
            'numpercentualconcluidomarco' => $acompanhamento->numpercentualconcluidomarco,
            'domcoratraso' => $acompanhamento->domcoratraso,
            'datfimprojeto' => $acompanhamento->datfimprojeto != null ? $acompanhamento->datfimprojeto->toString('d/m/Y') : "",
            'numcriteriofarol' => $acompanhamento->numcriteriofarol,
        );
    }


    public function retornaAnexo($params, $retornaPath = false, $retornaRouteDownload = false)
    {

        $filename = 'pdf_' . $params['idprojeto'] . "_" . $params['idstatusreport'] . ".pdf";
//        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

        $config = Zend_Registry::get('config');
        $arquivosDir = $config->resources->cachemanager->default->backend->options->arquivos_dir;

        $path = $arquivosDir . $filename;
        $url = $arquivosDir . $filename;
//        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . $filename;
//        $url = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "arquivos" . DIRECTORY_SEPARATOR . $filename;

//        echo $url;
//        Zend_Debug::dump($path);exit;
        //Zend_Debug::dump($retornaPath);
        //    Zend_Debug::dump($url); exit;
        //Zend_Debug::dump(file_exists($path)); exit;
        if (file_exists($path)) {
            if ($retornaRouteDownload) {
                $view = new Zend_View_Helper_Url();
                return $view->url(
                    array(
                        'arquivo' => base64_encode($path)
                    ),
                    'download'
                );
            }

            if (is_bool($retornaPath) && $retornaPath == true) {
                return $path;
            }

            return $url;
        }
        return false;
    }

    public function update($dados)
    {
        $dados['desatividadeconcluida'] = (trim($dados['desatividadeconcluida']) ? mb_substr($dados['desatividadeconcluida'],
            0, 4000) : $dados['desatividadeconcluida']);
        $dados['desatividadeandamento'] = (trim($dados['desatividadeandamento']) ? mb_substr($dados['desatividadeandamento'],
            0, 4000) : $dados['desatividadeandamento']);
        $dados['desmotivoatraso'] = (trim($dados['desmotivoatraso']) ? mb_substr($dados['desmotivoatraso'], 0,
            4000) : $dados['desmotivoatraso']);
        $dados['descontramedida'] = (trim($dados['descontramedida']) ? mb_substr($dados['descontramedida'], 0,
            4000) : $dados['descontramedida']);
        $dados['desirregularidade'] = (trim($dados['desirregularidade']) ? mb_substr($dados['desirregularidade'], 0,
            4000) : $dados['desirregularidade']);
        $dados['desrisco'] = (trim($dados['desrisco']) ? mb_substr($dados['desrisco'], 0, 4000) : $dados['desrisco']);
        $form = $this->getFormEditar($dados);
        $arquivo = isset($dados['descaminho']) == false;
        if (isset($dados['descaminho'])) {
            $dados['descaminho'] = null;
        }

        if ($dados['idmarco'] == '1') {
            $dados['idmarco'] = null;
        }

        if ($form->isValidPartial($dados)) {
            $updateModel = new Projeto_Model_Statusreport($form->getValues());
            if ($arquivo) {
                $this->renomearArquivo($form->getElement('descaminho'), $dados);
            }
            $model = $this->_mapper->update($updateModel);
            $serviceGerencia = new Projeto_Service_Gerencia();
            if (!empty($dados['numprocessosei'])) {
                $dados['numprocessosei'] = preg_replace('/[^0-9]/i', '', $dados['numprocessosei']);
                $params['idprojeto'] = $dados['idprojeto'];
                $params['numprocessosei'] = $dados['numprocessosei'];
                $serviceGerencia->atualizaNumeroSEIProjeto($params);
            }
            $data['idprojeto'] = $dados['idprojeto'];
            $data['idstatusreport'] = $dados['idstatusreport'];

            //$model = $this->_mapper->retornaAcompanhamentoPorId($data);
            $model->descaminho = $this->retornaAnexo($data, true);
            return $model;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function getProximaChave()
    {

    }

    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            //$model = new Default_Model_Documento($dados);
            $model = $this->_mapper->excluir($dados);

            if ($pathArquivo = $this->retornaAnexo($dados, true)) {
                unlink($pathArquivo);
            }

            return $model;
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function getById($dados)
    {
        $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
        $retorno = $this->_mapper->getById($dados);
        $retorno->nomstatusprojeto = $this->getNomeStatusById($retorno->domstatusprojeto);
        $marco = $serviceAtividadeCronograma->getAtividadeByProjetoId(array(
            'idatividadecronograma' => $retorno->idmarco,
            'idprojeto' => $retorno->idprojeto
        ));
        if ($marco->datiniciobaseline && $marco->datfim) {
            $retorno->nomproximomarco = $marco->nomatividadecronograma . " - " . $marco->datiniciobaseline->format('d/m/Y') . " - " . $marco->datfim->format('d/m/Y');
        }
        $retorno->nomrisco = $this->getNomeRiscoById($retorno->domcorrisco);
        return $retorno;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function retornaUltimoPorProjeto($dados)
    {
        return $this->_mapper->retornaUltimoPorProjeto($dados);
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

    public function getStatus()
    {
        //'Proposta'      => 'Proposta',
        //'Em Alteracao'  => 'Em Alteração',
        $retorno = array(
            '' => 'Todos',
            '2' => 'Em Andamento',
            '3' => 'Concluído',
            '4' => 'Paralisado',
            '5' => 'Cancelado',
            '8' => 'Excluído',
        );

        return $retorno;
    }

    public function getNomeStatusById($id)
    {
        $nome = '';
        Switch ($id) {
            case '1':
                $nome = 'Proposta';
                break;
            case '2':
                $nome = 'Em andamento';
                break;
            case '3':
                $nome = 'Concluído';
                break;
            case '4':
                $nome = 'Paralisado';
                break;
            case '5':
                $nome = 'Cancelado';
                break;
            case '6':
                $nome = 'Bloqueado';
                break;
            case '7':
                $nome = 'Em Alteração';
                break;
            case '8':
                $nome = 'Excluído';
                break;
            Default:
                $nome = 'Todos';
                break;
        }

        return $nome;
    }

    public function getNomeRiscoById($id)
    {
        $nome = '';
        Switch ($id) {
            case '1':
                $nome = 'Baixo';
                break;
            case '2':
                $nome = 'Médio';
                break;
            case '3':
                $nome = 'Alto';
                break;
            Default:
                $nome = 'Baixo';
                break;
        }

        return $nome;
    }

    public function getOptionsObejetivo()
    {
        $serviceObjetivo = new Default_Service_Objetivo();
        $objetivos = $serviceObjetivo->fetchPairs();
        //array_unshift($objetivos, "Selecione");
        return $objetivos;
    }


    public function getStatusProjeto()
    {
        $serviceSituacao = new Projeto_Service_SituacaoProjeto();
        $retorno = $serviceSituacao->retornaNomeSituacaoAtivo();
        return $retorno;
//        $retorno = array(
//            //'' => 'Todos',
//            '' => 'Proposta',
//            '2' => 'Em andamento',
//            '3' => 'Concluído',
//            '4' => 'Paralisado',
//            '5' => 'Cancelado',
//            '7' => 'Em Alteração'
//        );

        return $retorno;
    }

    public function getFlaCopa()
    {
        $retorno = array(
            '' => 'Todos',
            'S' => 'SIM',
            'N' => 'NÃO',
        );

        return $retorno;
    }

    public function getTapAssinado()
    {
        $retorno = array(
            '2' => 'NÃO',
            '1' => 'SIM',
        );
        return $retorno;
    }

    public function getPgpAssinado()
    {
        $retorno = array(
            'N' => 'NÃO',
            'S' => 'SIM',
        );
        return $retorno;
    }

    public function getTepAssinado()
    {
        $retorno = array(
            'N' => 'NÃO',
            'S' => 'SIM',
        );
        return $retorno;
    }

    public function retornarTodosAcompanhamento($params)
    {
        return $this->_mapper->retornarTodosAcompanhamento($params);
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function retornaAcompanhamentosPorProjeto($params, $paginator, $array = false)
    {
        $dados = $this->_mapper->retornaAcompanhamentosPorProjeto($params, $paginator, $array);
        $serviceGerencia = new Projeto_Service_Gerencia();
        $projeto = $serviceGerencia->getById($params);

        if ($paginator) {
            $response = array();
            $response['page'] = $dados->getPages()->current;
            $response['total'] = $dados->getPages()->pageCount;
            $response['records'] = $dados->getPages()->totalItemCount;
            foreach ($dados as $d) {
                $array = array();
                $previsto = "-";
                $concluido = "-";
                $prazo = "-";
                $risco = "-";
                $numEmDias = 0;
                $datfimprojetotendencia = new Zend_Date($d['datfimprojetotendencia'], 'dd/MM/YYYY');
                $dataFimProjeto = $projeto->datfim;
                $numcriteriofarol = $projeto->numcriteriofarol;

                if (isset($d['diaatraso']) && (!empty($d['diaatraso']))) {
                    $prazo = "<span class='badge badge-" . $d['domcoratraso'] . "' title='" . $d['diaatraso'] . " dias'>" . $d['diaatraso'] . " dias</span>";
                } else {
                    if ((Zend_Date::isDate($dataFimProjeto)) && (Zend_Date::isDate($datfimprojetotendencia))) {
                        $dtArray['datainicio'] = $datfimprojetotendencia->toString('d/m/Y');
                        $dtArray['datafim'] = $dataFimProjeto->toString('d/m/Y');
                        $service = new Projeto_Service_AtividadeCronograma();
                        /* retira um dia do cálculo para atender a regra definida */
                        if (($datfimprojetotendencia->equals($dataFimProjeto)) == false) {
                            $numEmDias = $service->retornaQtdeDiasUteisEntreDatas($dtArray);
                            $numEmDias = $numEmDias * (-1);
                            $numEmDias = ($numEmDias > 0 ? $numEmDias - 1 : $numEmDias + 1) . ' dias';
                        }
                    }
                    $prazo = $this->getSemaforoPrazo($numEmDias, $numcriteriofarol);
                }

                $array = array();
                $previsto = $d['numpercentualprevisto'] . "%";
                $concluido = $d['numpercentualconcluido'] . "%";
                $risco = $this->getSemaforoRisco($d['domcorrisco']);
                $d['numIdIstatusReport'] = $d['idstatusreport'];
                $array['cell'] = array(
                    $d['datacompanhamento'],
                    $previsto,
                    $concluido,
                    $d['datfimprojetotendencia'],
                    '',
                    $d['nomcadastrador'],
                    $prazo,
                    $risco,
                    $d['idstatusreport'],
                    $d['numIdIstatusReport']
                );
                $response["rows"][] = $array;
            }
            return $response;
        }
        return $dados;
    }

    public function getSemaforo($dias, $numcriteriofarol)
    {

        if ($dias <= 0) {
            $sinal = "<span class='badge badge-success' title='" . $dias . "'>P</span>";
        } elseif ($dias > 0 && $dias <= $numcriteriofarol) {
            $sinal = "<span class='badge badge-warning' title='" . $dias . "'>P</span>";
        } elseif ($dias > $numcriteriofarol) {
            $sinal = "<span class='badge badge-important' title='" . $dias . "'>P</span>";
        }

        return $sinal;
    }

    public function retornaDiferencaDias($dataFimProjeto, $datfimprojetotendencia)
    {
        $numEmDias = 0;

        if ((Zend_Date::isDate($dataFimProjeto)) &&
            (Zend_Date::isDate($datfimprojetotendencia))
        ) {
            $dtArray['datainicio'] = $datfimprojetotendencia->toString('d/m/Y');
            $dtArray['datafim'] = $dataFimProjeto->toString('d/m/Y');
            $service = new Projeto_Service_AtividadeCronograma();
            /* retira um dia do cálculo para atender a regra definida */
            if (($datfimprojetotendencia->equals($dataFimProjeto)) == false) {
                $numEmDias = $service->retornaQtdeDiasUteisEntreDatas($dtArray);
                $numEmDias = $numEmDias * (-1);
                $numEmDias = ($numEmDias > 0 ? $numEmDias - 1 : $numEmDias + 1);
            }
        }
        return $numEmDias;
    }

    public function getSemaforoPrazoEmDias($dias, $numcriteriofarol)
    {
        if ($dias <= 0) {
            $sinal = "<span class='badge badge-success' title='" . $dias . "'>$dias</span>";
        } elseif ($dias > 0 && $dias <= $numcriteriofarol) {
            $sinal = "<span class='badge badge-warning' title='" . $dias . "'>$dias</span>";
        } elseif ($dias > $numcriteriofarol) {
            $sinal = "<span class='badge badge-important' title='" . $dias . "'>$dias</span>";
        }

        return $sinal;
    }

    private function getSemaforoPrazo($dias, $numcriteriofarol)
    {
        if ($dias <= 0) {
            $sinal = "<span class='badge badge-success' title='" . $dias . "'>$dias</span>";
        } elseif ($dias > 0 && $dias <= $numcriteriofarol) {
            $sinal = "<span class='badge badge-warning' title='" . $dias . "'>$dias</span>";
        } elseif ($dias > $numcriteriofarol) {
            $sinal = "<span class='badge badge-important' title='" . $dias . "'>$dias</span>";
        }

        return $sinal;
    }

    private function getSemaforoRisco($risco)
    {
        $retorno = '-';

        if ($risco == '1') {
            $retorno = '<span class="badge badge-success">Baixo</span>';
        } elseif ($risco == '2') {
            $retorno = '<span class="badge badge-warning">Medio</span>';
        } elseif ($risco == '3') {
            $retorno = '<span class="badge badge-important">Alto</span>';
        }

        return $retorno;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function retornaAcompanhamentoPorId($params, $paginator)
    {
        $dados = $this->_mapper->retornaAcompanhamentoPorId($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return Projeto_Model_Statusreport | \Default_Service_JqGrid
     */
    public function retornaUltimoAcompanhamento($params, $paginator)
    {
        $dados = $this->_mapper->retornaUltimoAcompanhamento($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return  array
     */
    public function getChartPlanejadoRealizado($params, $paginator)
    {
        $dados = $this->_mapper->getChartPlanejadoRealizado($params, $paginator);
        $retorno = array();
        $ordem = 1;
        foreach ($dados as $d) {
            $r = new stdClass();
            $r->data = $ordem;
            $r->Planejado = (float)$d["numpercentualprevisto"];
            $r->Realizado = (float)$d["numpercentualconcluido"];
            $retorno[] = $r;
            $ordem++;
        }
        return $retorno;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return  array
     */
    public function getChartEvolucaoAtraso($params, $paginator)
    {
        $dados = $this->_mapper->getChartEvolucaoAtraso($params, $paginator);
        $retorno = array();
        $ordem = 1;
        foreach ($dados as $d) {
            $r = new stdClass();
            $r->data = $ordem;
            $r->Atraso = (float)$d["atraso"];
            $retorno[] = $r;
            $ordem++;
        }
        return $retorno;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return  array
     */
    public function getChartPrazo($params, $paginator)
    {
        //Zend_Debug::dump($params);exit;

        $dados = $this->_mapper->getChartPrazo($params, $paginator);
        $atraso = 0;
        $param = array();

        if (count($dados) > 0) {
            $numcriteriofarol = $dados["numcriteriofarol"];
            $dataFim = $dados['datfim'];

            if (null == $dados['datfimprojeto_report'] && (empty($dados['datfimprojeto_report']))) {

                $dataFimRealizada = new Zend_Date($dados['datfimprojetotendencia'], 'dd/MM/YYYY');
                $dataFimPlanejada = new Zend_Date($dataFim, 'dd/MM/YYYY');
                if (
                    (Zend_Date::isDate($dataFimRealizada)) &&
                    (Zend_Date::isDate($dataFimPlanejada))
                ) {
                    if (($dataFimRealizada->equals($dataFimPlanejada)) == false) {
                        $d['datainicio'] = $dataFimRealizada;
                        $d['datafim'] = $dataFimPlanejada;
                        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
                        $atraso = $serviceAtividadeCronograma->retornaQtdeDiasUteisEntreDatas($d);
                        $atraso = $atraso * (-1);
                        $atraso = ($atraso > 0 ? $atraso - 1 : $atraso + 1);
                    }
                }
            } else {
                $numcriteriofarol = $dados['numcriteriofarol_report'];
                if (null != $dados['diaatraso']) {
                    $atraso = $dados['diaatraso'];
                }
            }

        }

        $r = new stdClass();
        $r->numcriteriofarol = $numcriteriofarol;
        $r->prazo = $atraso;
        return $r;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return  array
     */
    public function getUltimoPrazo($params, $paginator)
    {
        $dados = $this->_mapper->getUltimoPrazo($params, $paginator);
        $atraso = 0;
        if (count($dados) > 0) {
            $dataFimRealizada = new Zend_Date($dados['datfimprojetotendencia'], 'dd/MM/YYYY');
            $dataFimPlanejada = new Zend_Date($dados['datfim'], 'dd/MM/YYYY');
            if (
                (Zend_Date::isDate($dataFimRealizada)) &&
                (Zend_Date::isDate($dataFimPlanejada))
            ) {
                if (($dataFimRealizada->equals($dataFimPlanejada)) == false) {
                    $d['datainicio'] = $dataFimRealizada;
                    $d['datafim'] = $dataFimPlanejada;
                    //Zend_Debug::dump($d);exit;
                    $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
                    $atraso = $serviceAtividadeCronograma->retornaQtdeDiasUteisEntreDatas($d);
                    $atraso = $atraso * (-1);
                    $atraso = ($atraso > 0 ? $atraso - 1 : $atraso + 1);
                }
            }
        }

        $r = new stdClass();
        $r->numcriteriofarol = $dados["numcriteriofarol"];
        $r->prazo = $atraso;

        return $r;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return  array
     */
    public function getChartMarco($params, $paginator)
    {
        $service = new Projeto_Service_AtividadeCronograma();

        $percentual = $service->retornaPercentualConcluidoMarcoByProjeto($params);

        $r = new stdClass();
        $r->numcriteriofarol = 0;
        $r->prazo = $percentual;
        //var_dump($r);exit;
        return $r;
    }

    public function getChartMarcoByRelatorio($params, $paginator)
    {
        $r = new stdClass();
        $r->numcriteriofarol = 0;

        $retorno = $this->_mapper->retornaMarcoConcluidoProjetoByStatusReport($params);

        if($retorno['numpercentualconcluidomarco'] > 0) {
            $r->prazo = $retorno['numpercentualconcluidomarco'];
        }else{
            $service = new Projeto_Service_AtividadeCronograma();
            $percentual = $service->getPercentualConcluidoMarcoByRelatorio($params);
            $r->prazo = $percentual;
        }

        return $r;
    }

    /**
     *
     * @param array $params
     * @param Projeto_Model_Mapper_Statusreport
     * @return  array
     */
    public function getImagemPlanejadoRealizado($params)
    {
        $dados = null;
        $dados = $this->_mapper->getChartPlanejadoRealizado($params, null);
        //Zend_Debug::dump($dados);exit;
        $Planejado = null;
        $Realizado = null;
        $ordemItem = array(0);
        $ordem = 0;
        foreach ($dados as $d) {
            $ordemItem[$ordem] = $ordem;
            $Planejado[$ordem] = (float)$d["numpercentualprevisto"];
            $Realizado[$ordem] = (float)$d["numpercentualconcluido"];
            $ordem++;
        }
        $servicePchartPlRe = App_Service_ServiceAbstract::getService('Default_Service_PChart2');
        $servicePchartPlRe->addChart();
        $Pontos = $Planejado;
        $servicePchartPlRe->addItens($Pontos, $SerieName = "Planejado", $SerieDesc = "Planejado", $SerieWeight = 1,
            $SerieTicks = 0);
        $Pontos = $Realizado;
        $servicePchartPlRe->addItens($Pontos, $SerieName = "Realizado", $SerieDesc = "Realizado", $SerieWeight = 1,
            $SerieTicks = 4);
        /* Draw serie 1 in red with a 80% opacity */
        $servicePchartPlRe->setPaletteSerie($SerieName = "Planejado", $valueR = 128, $valueG = 128, $valueB = 0,
            $valueAlpha = 80);
        /* Affect the same palette on different series */
        $servicePchartPlRe->setPaletteSerie($SerieName = "Realizado", $valueR = 0, $valueG = 0, $valueB = 0,
            $valueAlpha = 100);
        /* Set Absissa serie */
        $Pontos = $ordemItem;
        $servicePchartPlRe->setAbsissa($Pontos, "% Concluido", "% Concluido");
        /* Create the pChart object */
        $servicePchartPlRe->setRectangle($servicePchartPlRe->SizeImageX, $servicePchartPlRe->SizeImageY,
            $servicePchartPlRe->Chart, true, false);
        /* Draw the background */
        $servicePchartPlRe->setRGBSeries($servicePchartPlRe->BGRValueV, $servicePchartPlRe->BGGValueV,
            $servicePchartPlRe->BGBValueV,
            $servicePchartPlRe->DashRValueV, $servicePchartPlRe->DashGValueV, $servicePchartPlRe->DashBValueV);
        /* Draw the background */
        $servicePchartPlRe->setBackGroundRectangle($servicePchartPlRe->SizeImageX, $servicePchartPlRe->SizeImageY);
        /* Overlay with a gradient */
        $servicePchartPlRe->setRGBGradiente($servicePchartPlRe->StartRValue, $servicePchartPlRe->StartGValue,
            $servicePchartPlRe->StartGValue,
            $servicePchartPlRe->EndRValue, $servicePchartPlRe->EndGValue, $servicePchartPlRe->EndBValue,
            $servicePchartPlRe->AlphaGValue);
        //$servicePchartPlRe->setBackGradientRectangle($servicePchartPlRe->SizeImageX, $servicePchartPlRe->SizeImageY, $servicePchartPlRe->SizeT);
        $servicePchartPlRe->setBorderPicture($servicePchartPlRe->SizeImageX, $servicePchartPlRe->SizeImageY);

        $servicePchartPlRe->setBorderPicture($servicePchartPlRe->SizeImageX, $servicePchartPlRe->SizeImageY);
        $servicePchartPlRe->setChartTitle("% Concluído ( Planejado x Realizado)", TEXT_ALIGN_TOPMIDDLE, "Forgotte.ttf",
            12, 120, 5);
        $servicePchartPlRe->setShadow(false);
        /* Set the default font */
        $servicePchartPlRe->setFontDefault("pf_arma_five.ttf", 6, 0, 0, 0);
        /* Define the chart area */
        $servicePchartPlRe->setChartArea(30, 20, 310, 165);
        /* Draw the scale - LINHAS DE GRADE */
        $servicePchartPlRe->setDrawScale(10, 10, true, 0, 0, 0, true, true);
        /* Turn on Antialiasing */
        $servicePchartPlRe->setAntialias(true);
        /* Enable shadow computing */
        $servicePchartPlRe->setShadow(true);
        /* Set the default font */
        $servicePchartPlRe->setFontDefault("arial.ttf", 8, 0, 0, 0);
        /* Draw the line chart */
        $servicePchartPlRe->setDrawLineChar(true, true, 2, -60, 80);
        /* Enable shadow computing */
        $servicePchartPlRe->setShadow(true);
        /* Draw the line chart */
        $servicePchartPlRe->setDrawLineChar(true, true, 2, -60, 80);
        /* Write the chart legend */
        $servicePchartPlRe->setWriteCharLegend($PosX = 315, $PosY = 35, $vFontR = 0, $vFontG = 0, $vFontB = 0,
            $vFontName = "GeosansLight.ttf", $vFontSize = 10, $vMargin = 166, $vAlpha = 130,
            $vBoxSize = 0, $vStyle = LEGEND_NOBORDER, $vMode = LEGEND_VERTICAL);
        $servicePchartPlRe->setFontDefault("arial.ttf", 9, 0, 0, 0);
        $servicePchartPlRe->myPicture->drawText(150, 187, "Relatório", array("R" => 0, "G" => 0, "B" => 0));
        ob_start();
        imagepng($servicePchartPlRe->criaImagem());
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    /**
     *
     * @param array $params
     * @param Projeto_Model_Mapper_Statusreport
     * @return  array
     */
    public function getImagemEvolucaoAtraso($params)
    {
        $dados = null;
        $dados = $this->_mapper->getChartEvolucaoAtraso($params, null);
        $EvolucaoAtraso = null;
        $ordemItem = array(0);
        $ordemO = 0;
        foreach ($dados as $d) {
            $ordemItem[$ordemO] = $ordemO;
            $EvolucaoAtraso[$ordemO] = (float)$d["atraso"];
            $ordemO++;
        }
        $servicePchart2 = App_Service_ServiceAbstract::getService('Default_Service_PChart2');
        $servicePchart2->addChart();
        $Pontos = $EvolucaoAtraso;
        $servicePchart2->addItens($Pontos, $SerieName = "Atraso", $SerieDesc = "Atraso", $SerieWeight = 1,
            $SerieTicks = 0);
        /* Draw serie 1 in red with a 80% opacity */
        $servicePchart2->setPaletteSerie($SerieName = "Atraso", $valueR = 128, $valueG = 128, $valueB = 0,
            $valueAlpha = 80);
        /* Set Absissa serie */
        $Pontos = $ordemItem;
        $servicePchart2->setAbsissa($Pontos, "Atraso (%)", "Atraso (%)");
        /* Create the pChart object */
        $servicePchart2->setRectangle($servicePchart2->SizeImageX, $servicePchart2->SizeImageY, $servicePchart2->Chart,
            true, false);
        /* Draw the background */
        $servicePchart2->setRGBSeries($servicePchart2->BGRValueV, $servicePchart2->BGGValueV,
            $servicePchart2->BGBValueV,
            $servicePchart2->DashRValueV, $servicePchart2->DashGValueV, $servicePchart2->DashBValueV);
        /* Draw the background */
        $servicePchart2->setBackGroundRectangle($servicePchart2->SizeImageX, $servicePchart2->SizeImageY);
        /* Overlay with a gradient */
        $servicePchart2->setRGBGradiente($servicePchart2->StartRValue, $servicePchart2->StartGValue,
            $servicePchart2->StartGValue,
            $servicePchart2->EndRValue, $servicePchart2->EndGValue, $servicePchart2->EndBValue,
            $servicePchart2->AlphaGValue);
        //$servicePchart2->setBackGradientRectangle($servicePchart2->SizeImageX, $servicePchart2->SizeImageY, $servicePchart2->SizeT);
        $servicePchart2->setBorderPicture($servicePchart2->SizeImageX, $servicePchart2->SizeImageY);

        $servicePchart2->setBorderPicture($servicePchart2->SizeImageX, $servicePchart2->SizeImageY);
        $servicePchart2->setChartTitle("Percentual  do  Atraso  do  Projeto  ( % )", TEXT_ALIGN_TOPMIDDLE,
            "Forgotte.ttf", 12, 110, 5);
        $servicePchart2->setShadow(false);
        /* Set the default font */
        $servicePchart2->setFontDefault("pf_arma_five.ttf", 6, 0, 0, 0);
        /* Define the chart area */
        $servicePchart2->setChartArea(35, 20, 313, 165);
        /* Draw the scale - LINHAS DE GRADE */
        $servicePchart2->setDrawScale(10, 10, true, 0, 0, 0, true, true);
        /* Turn on Antialiasing */
        $servicePchart2->setAntialias(true);
        /* Enable shadow computing */
        $servicePchart2->setShadow(true);
        /* Set the default font */
        $servicePchart2->setFontDefault("arial.ttf", 8, 0, 0, 0);
        /* Draw the line chart */
        $servicePchart2->setDrawLineChar(true, true, 2, -60, 80);
        // $this->_helper->getHelper("layout")->disableLayout();
        /* Enable shadow computing */
        $servicePchart2->setShadow(true);
        /* Draw the line chart */
        $servicePchart2->setDrawLineChar(true, true, 2, -60, 80);
        /* Write the chart legend */
        $servicePchart2->setWriteCharLegend($PosX = 315, $PosY = 35, $vFontR = 0, $vFontG = 0, $vFontB = 0,
            $vFontName = "GeosansLight.ttf", $vFontSize = 10, $vMargin = 166, $vAlpha = 130,
            $vBoxSize = 0, $vStyle = LEGEND_NOBORDER, $vMode = LEGEND_VERTICAL);/**/
        $servicePchart2->setFontDefault("arial.ttf", 9, 0, 0, 0);
        $servicePchart2->myPicture->drawText(150, 187, "Relatório", array("R" => 0, "G" => 0, "B" => 0));
        ob_start();
        imagepng($servicePchart2->criaImagem());
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    /**
     *
     * @param array $params
     * @param Projeto_Model_Mapper_Statusreport
     * @return  array
     */
    public function getImagemChartGauge($pValor = 0, $pCriterioFarol = 0, $pGrafico = 1)
    {
        $nValor = null;
        if ($pGrafico == 1) {
            $nValor = $pValor;
        } else {
            if ($pGrafico == 2) {
                if ($pValor == 1) {
                    $nValor = 17; //Semaforo verde
                } else {
                    if ($pValor == 2) {
                        $nValor = 50; //Semaforo amarelo
                    } else {
                        $nValor = 80; //Semaforo vermelho
                    }
                }
            } else {
                if ($pValor >= $pCriterioFarol) {
                    $nValor = 80; //Semaforo vermelho
                } else {
                    if ($pValor > 0) {
                        $nValor = 50; //Semaforo amarelo
                    } else {
                        $nValor = 17; //Semaforo verde
                    }
                }
            }
        }
        if (!(isset($nValor))) {
            $nValor = 0;
        }

        $WidHeigImg = 230;
        $servicePchart2 = App_Service_ServiceAbstract::getService('Default_Service_PChart2');
        $servicePchart2->addChart();
        $servicePchart2->SizeImageX = $WidHeigImg;
        $servicePchart2->SizeImageY = $WidHeigImg;
        $servicePchart2->addItens(array($nValor), $SerieName = "Prazo", $SerieDesc = "Prazo", $SerieWeight = 1,
            $SerieTicks = 0);
        /* Create the pChart object */
        $servicePchart2->setRectangle($servicePchart2->SizeImageX, $servicePchart2->SizeImageY, $servicePchart2->Chart,
            true, false);

        $servicePchart2->myPicture->drawGradientArea(0, 0, $WidHeigImg, $WidHeigImg, DIRECTION_VERTICAL,
            array(
                "StartR" => 255,
                "StartG" => 255,
                "StartB" => 255,
                "EndR" => 255,
                "EndG" => 255,
                "EndB" => 255,
                "Alpha" => 100
            ));
        $servicePchart2->myPicture->drawLine(0, 20, $WidHeigImg, 20, array("R" => 255, "G" => 255, "B" => 255));
        /* Add a border to the picture */
        $servicePchart2->myPicture->drawRectangle(0, 0, $WidHeigImg, $WidHeigImg,
            array("R" => 255, "G" => 255, "B" => 255));
        /* Set the default font properties */
        $servicePchart2->setFontDefault("Forgotte.ttf", 12, 80, 80, 80);
        /* Enable shadow computing */
        $servicePchart2->myPicture->setShadow(false,
            array("X" => 2, "Y" => 2, "R" => 255, "G" => 255, "B" => 255, "Alpha" => 10));
        /* Create the pGauge object */
        $servicePchart2->addGauge();
        /* Draw a Gauge chart */
        $servicePchart2->setChartArea(10, 25, $WidHeigImg, $WidHeigImg);
        $Options = array(
            "DrawPoly" => true,
            "WriteValues" => true,
            "ValueFontSize" => 8,
            "Layout" => GAUGE_LAYOUT_CIRCLE,
            "Segments" => 1,
            "FixedMax" => 180,
            "MinAxis" => ($nValor < 0 ? $nValor - 15 : ($nValor < 170 ? 0 : ($nValor - 10))),
            "BackgroundGradient" => array(
                "StartR" => 255,
                "StartG" => 255,
                "StartB" => 255,
                "StartAlpha" => 400,
                "EndR" => 255,
                "EndG" => 255,
                "EndB" => 255,
                "EndAlpha" => 0
            )
        );
        $servicePchart2->SplitChart->drawGauge($servicePchart2->myPicture, $servicePchart2->Chart, $Options);
        ob_start();
        imagepng($servicePchart2->criaImagem());
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    /**
     *
     * @param array $params
     * @param Projeto_Model_Mapper_Statusreport
     * @return  array
     */
    public function getImagemChartDrawRiscoMarco($pValor = 0, $pCriterioFarol = 0, $pGrafico = 1)
    {
        $nValor = null;
        if ($pGrafico == 1) {
            $nValor = $pValor;
        } else {
            if ($pGrafico == 2) {
                if ($pValor == 1) {
                    $nValor = 17; //Semaforo verde
                } else {
                    if ($pValor == 2) {
                        $nValor = 50; //Semaforo amarelo
                    } else {
                        $nValor = 80; //Semaforo vermelho
                    }
                }
            } else {
                if ($pValor >= $pCriterioFarol) {
                    $nValor = 80; //Semaforo vermelho
                } else {
                    if ($pValor > 0) {
                        $nValor = 50; //Semaforo amarelo
                    } else {
                        $nValor = 17; //Semaforo verde
                    }
                }
            }
        }
        if (!(isset($nValor))) {
            $nValor = 0;
        }

        $WidHeigImg = 230;
        $servicePchart2 = App_Service_ServiceAbstract::getService('Default_Service_PChart2');
        $servicePchart2->addChart();
        $servicePchart2->SizeImageX = $WidHeigImg;
        $servicePchart2->SizeImageY = $WidHeigImg;
        if ($pGrafico >= 2) {
            $servicePchart2->addItens(90, $SerieName = "Pontos", $SerieDesc = "Pontos", $SerieWeight = 1,
                $SerieTicks = 0);
        } else {
            $servicePchart2->addItens($nValor + 100, $SerieName = "Pontos", $SerieDesc = "Pontos", $SerieWeight = 1,
                $SerieTicks = 0);
        }

        $servicePchart2->addItens($nValor, $SerieName = "Porcentagens", $SerieDesc = "Porcentagens", $SerieWeight = 1,
            $SerieTicks = 0);
        $servicePchart2->setAbsissa(array(" ", " "), "", "Valores");

        /* Create the pChart object */
        $servicePchart2->setRectangle($servicePchart2->SizeImageX, $servicePchart2->SizeImageY, $servicePchart2->Chart,
            true, false);

        /* Write the chart title */
        $servicePchart2->setFontDefault($vFontName = "Forgotte.ttf", 15);
        //$servicePchart2->myPicture->drawText(20,25,"Risco",array("FontSize"=>12));
        /* Write the chart title */
        //$servicePchart2->setFontDefault("Forgotte.ttf", 15);
        //$servicePchart2->myPicture->drawText(20,25,"Risco",array("FontSize"=>12));
        $servicePchart2->setFontDefault("pf_arma_five.ttf", 6);
        /* Set the graph area */
        $servicePchart2->setChartArea(5, 40, 210, 210);
        $servicePchart2->myPicture->drawGradientArea(70, 60, $servicePchart2->SizeImageX, $servicePchart2->SizeImageY,
            DIRECTION_HORIZONTAL, array(
                "StartR" => 200,
                "StartG" => 200,
                "StartB" => 200,
                "EndR" => 255,
                "EndG" => 255,
                "EndB" => 255,
                "Alpha" => 30
            ));

        $servicePchart2->setBackGroundRectangle($servicePchart2->SizeImageX, $servicePchart2->SizeImageY);
        /* Overlay with a gradient */
        $servicePchart2->setRGBGradiente($servicePchart2->StartRValue, $servicePchart2->StartGValue,
            $servicePchart2->StartGValue,
            $servicePchart2->EndRValue, $servicePchart2->EndGValue, $servicePchart2->EndBValue,
            $servicePchart2->AlphaGValue);

        //$servicePchart2->setBackGradientRectangle($servicePchart2->SizeImageX, $servicePchart2->SizeImageY, $servicePchart2->SizeT);
        $servicePchart2->setBorderPicture($servicePchart2->SizeImageX, $servicePchart2->SizeImageY);

        $servicePchart2->setBorderPicture($servicePchart2->SizeImageX, $servicePchart2->SizeImageY);
        $TituloChar = ($pGrafico == 1 ? "Prazo (Dias)" : ($pGrafico == 2 ? "Risco do Projeto" : "% de Marcos Concluídos do Projeto"));
        $servicePchart2->setChartTitle($TituloChar, TEXT_ALIGN_TOPMIDDLE, "Forgotte.ttf", 13, 110, 5);
        $servicePchart2->setShadow(false);

        /* Set the default font */
        $servicePchart2->setFontDefault("pf_arma_five.ttf", 6, 0, 0, 0);
        /* Draw the chart scale */
        $scaleSettings = array(
            "AxisAlpha" => 10,
            "TickAlpha" => 10,
            "DrawXLines" => false,
            "Mode" => SCALE_MODE_START0,
            "GridR" => 0,
            "GridG" => 0,
            "GridB" => 0,
            "GridAlpha" => 10,
            "Pos" => SCALE_POS_TOPBOTTOM
        );
        $servicePchart2->myPicture->drawScale($scaleSettings);

        /* Write a label over the chart */
        $LabelSettings = array(
            "DrawVerticalLine" => true,
            "TitleMode" => LABEL_TITLE_BACKGROUND,
            "TitleR" => 255,
            "TitleG" => 255,
            "TitleB" => 255
        );
        $servicePchart2->myPicture->writeLabel("Risco do Projeto", $nValor, $LabelSettings);

        /* Turn on shadow computing */
        $servicePchart2->myPicture->setShadow(true,
            array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
        /* Create the per bar palette */
        $Palette = array(
            "0" => array("R" => 128, "G" => 128, "B" => 128, "Alpha" => 100)//,
            //"1"=>array("R"=>200,"G"=>200,"B"=>200,"Alpha"=>30)
        );
        $servicePchart2->Chart->setSerieDrawable("Pontos", false);
        $servicePchart2->myPicture->drawBarChart(array(
            "DisplayValues" => true,
            "DisplayShadow" => true,
            "DisplayPos" => LABEL_POS_INSIDE,
            "Rounded" => true,
            "Surrounding" => 30,
            "OverrideColors" => $Palette,
            "DisplayR" => 255,
            "DisplayG" => 255,
            "DisplayB" => 255
        ));

        /* Create the pIndicator object */
        $servicePchart2->addIndicator();

        /* Define the indicator sections */
        $IndicatorSections = "";
        $IndicatorSections[] = array("Start" => 0, "End" => 33, "Caption" => "Baixo", "R" => 0, "G" => 153, "B" => 0);
        $IndicatorSections[] = array(
            "Start" => 34,
            "End" => 66,
            "Caption" => "Médio",
            "R" => 236,
            "G" => 243,
            "B" => 24
        );
        $IndicatorSections[] = array("Start" => 67, "End" => 100, "Caption" => "Alto", "R" => 153, "G" => 0, "B" => 0);

        /* Draw the 2nd indicator */
        $IndicatorSettings = array(
            "Values" => $nValor,
            "Unit" => ($pGrafico >= 2 ? "%" : ""),
            "CaptionPosition" => INDICATOR_CAPTION_BOTTOM,
            "CaptionR" => 0,
            "CaptionG" => 0,
            "CaptionB" => 0,
            "DrawLeftHead" => true,
            "ValueDisplay" => INDICATOR_VALUE_LABEL,
            "ValueFontName" => "../fonts/Forgotte.ttf",
            "ValueFontSize" => 15,
            "IndicatorSections" => $IndicatorSections
        );
        $servicePchart2->setDrawIndicator(8, 191, 220, 5, $IndicatorSettings);

        ob_start();
        imagepng($servicePchart2->criaImagem());
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    /**
     *
     * @param array $params
     * @param Projeto_Model_Mapper_Statusreport
     * @return  array
     */
    public function getImagemChartDrawPrazo($pValor = null, $pCriterioFarol = 0, $pGrafico = 1)
    {
        if (!(isset($pValor))) {
            $pValor = 0;
        }
        $WidHeigImg = 230;
        $servicePchartPrazo = new Default_Service_PChart2();
        $servicePchartPrazo->addChart();
        $servicePchartPrazo->SizeImageX = $WidHeigImg;
        $servicePchartPrazo->SizeImageY = $WidHeigImg;
        $pCriterioFarol = ($pCriterioFarol < 50 ? 50 : $pCriterioFarol);
        $LblSkip = ($pValor <= 100 ? 10 : ($pValor <= 250 ? 45 : 85));
        $CountItem = 0;
        $IniI = ($pValor > 0 ? -15 : $pValor - 10);
        $FimI = $pValor + $pCriterioFarol;
        for ($i = $IniI; $i <= $FimI; $i++) {
            $pointsAbs[] = $i;
            $pointsAbsY[] = 1;
            if ($pValor == $i) {
                $PosItem = $CountItem;
            }
            $CountItem++;
        }

        /* Create and populate the pData object */
        $servicePchartPrazo->Chart->addPoints($pointsAbs, "Prazo 1");
        $servicePchartPrazo->Chart->addPoints($pointsAbs, "Prazo 2");
        $servicePchartPrazo->Chart->setAxisName(0, "");
        $servicePchartPrazo->Chart->setAxisUnit(0, "");
        $servicePchartPrazo->Chart->addPoints($pointsAbs, "Labels");
        $servicePchartPrazo->Chart->setAbscissa("Labels");
        $servicePchartPrazo->Chart->setSerieDrawable("Prazo 1", false);
        $servicePchartPrazo->Chart->setSerieDrawable("Prazo 2", false);

        /* Create the pChart object */
        $servicePchartPrazo->setRectangle($servicePchartPrazo->SizeImageX, $servicePchartPrazo->SizeImageY,
            $servicePchartPrazo->Chart, true, false);

        $servicePchartPrazo->setFontDefault("pf_arma_five.ttf", 6);
        /* Set the graph area */
        $servicePchartPrazo->setChartArea(-8, 40, 240, 191);
        $servicePchartPrazo->myPicture->drawGradientArea(70, 60, $servicePchartPrazo->SizeImageX,
            $servicePchartPrazo->SizeImageY, DIRECTION_HORIZONTAL, array(
                "StartR" => 200,
                "StartG" => 200,
                "StartB" => 200,
                "EndR" => 255,
                "EndG" => 255,
                "EndB" => 255,
                "Alpha" => 30
            ));
        /* Draw the background */
        $servicePchartPrazo->setBackGroundRectangle($servicePchartPrazo->SizeImageX, $servicePchartPrazo->SizeImageY);
        /* Overlay with a gradient */
        $servicePchartPrazo->setRGBGradiente($servicePchartPrazo->StartRValue, $servicePchartPrazo->StartGValue,
            $servicePchartPrazo->StartGValue,
            $servicePchartPrazo->EndRValue, $servicePchartPrazo->EndGValue, $servicePchartPrazo->EndBValue,
            $servicePchartPrazo->AlphaGValue);

        //$servicePchartPrazo->setBackGradientRectangle($servicePchartPrazo->SizeImageX, $servicePchartPrazo->SizeImageY, $servicePchartPrazo->SizeT);
        $servicePchartPrazo->setBorderPicture($servicePchartPrazo->SizeImageX, $servicePchartPrazo->SizeImageY);
        /* Overlay with a gradient  * /
        $serieSettingsBc = array("StartR" => $servicePchartPrazo->StartRValue, "StartG" => $servicePchartPrazo->StartGValue, "StartB" => $servicePchartPrazo->StartBValue,
            "EndR" => $servicePchartPrazo->EndRValue, "EndG" => $servicePchartPrazo->EndGValue, "EndB" => $servicePchartPrazo->EndBValue, "Alpha" => $servicePchartPrazo->AlphaGValue);
        $servicePchartPrazo->myPicture->drawGradientArea(0, 0, $servicePchartPrazo->SizeImageX, $servicePchartPrazo->SizeImageY, $servicePchartPrazo->dVertical, $serieSettingsBc);
        $servicePchartPrazo->setBGTitle($servicePchartPrazo->SizeImageX, $servicePchartPrazo->SizeT, $servicePchartPrazo->dVertical);
        /**/
        /* Add a border to the picture * /
        $servicePchartPrazo->myPicture->drawRectangle(1, 1, 228, 228, array("R" => 0, "G" => 0, "B" => 0));
        $servicePchartPrazo->setShadow(FALSE);

        /* Add a border to the picture */
        $servicePchartPrazo->setBorderPicture($servicePchartPrazo->SizeImageX, $servicePchartPrazo->SizeImageY);
        /* Write the picture title */
        $TituloChar = "Atraso do Projeto (Dias)";
        $servicePchartPrazo->setChartTitle($TituloChar, TEXT_ALIGN_TOPMIDDLE, "Forgotte.ttf", 13, 120, 5);
        $servicePchartPrazo->setShadow(false);

        $servicePchartPrazo->setFontDefault("pf_arma_five.ttf", 6);
        /* Draw the scale and the 1st chart */
        $ScaleSettings = array(
            "LabelSkip" => $LblSkip,
            "DrawYLines" => array(0),
            "Pos" => SCALE_POS_LEFTRIGHT,
            "XMargin" => 20,
            "YMargin" => 10,
            "Floating" => true,
            "DrawSubTicks" => true,
            "DrawArrows" => false,
            "ArrowSize" => 2,
            "AutoAxisLabels" => false
        );
        $servicePchartPrazo->myPicture->drawScale($ScaleSettings);

        $servicePchartPrazo->setShadow(true);
        $servicePchartPrazo->myPicture->drawSplineChart();
        /* Write the chart legend */
        $servicePchartPrazo->myPicture->drawLegend(50, 50,
            array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));
        $servicePchartPrazo->setShadow(true);
        $servicePchartPrazo->setFontDefault("pf_arma_five.ttf", 6);

        /* Write a label over the chart */
        $LabelSettings = array(
            "DrawVerticalLine" => true,
            "TitleR" => 0,
            "TitleG" => 0,
            "TitleB" => 0,
            "DrawSerieColor" => true,
            "TitleMode" => LABEL_TITLE_BACKGROUND,
            "OverrideTitle" => "Atraso",
            "ForceLabels" => array($pValor),
            "GradientEndR" => 220,
            "GradientEndG" => 255,
            "GradientEndB" => 220,
            "TitleBackgroundG" => 155
        );
        $servicePchartPrazo->myPicture->writeLabel(array("Prazo 1"), $PosItem, $LabelSettings);

        /* Render the picture (choose the best way) */
        ob_start();
        imagepng($servicePchartPrazo->criaImagem());
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

}