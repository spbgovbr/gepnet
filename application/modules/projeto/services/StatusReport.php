<?php

class Projeto_Service_StatusReport extends App_Service_ServiceAbstract {

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Gerencia
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

    public function init() {
        $this->_mapper = new Projeto_Model_Mapper_Statusreport();
    }

    /**
     * @return Projeto_Form_Gerencia
     */
    //public function getForm($params = null,$statusReport = null ,Projeto_Model_Gerencia $projeto = null) {
    public function getForm($params = null) {
//        print "<PRE>";
//        print_r($params);
//        exit;
        $form = $this->_getForm('Projeto_Form_StatusReportinserir');
        //return $form;
        if (empty($params) == false) {
            $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
            $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
            $statusReport = $serviceGerencia->generateStatusReport(array('idprojeto' => $params['idprojeto']));
            //        $statusReport               = $serviceGerencia->generateStatusReport($params);
            //        $projeto                    = $serviceGerencia->retornaProjetoPorId($params);
            $desatividadeconcluida = "Não existem atividades.";

            if(empty($statusReport['desatividadeconcluida']) == false){
                $txtAc = "";
                foreach($statusReport['desatividadeconcluida'] as $ac){
                    $txtAc .= $ac['datinicio'] . " - " . $ac['datfim'] . " - " . $ac['nomatividadecronograma'] . "<br>";
                }
                $desatividadeconcluida = mb_substr($txtAc, 0, 252)."...";
            }

            $desatividadeandamento = "Não existem atividades.";

            if(empty($statusReport['desatividadeandamento']) == false){
                $txtAa = "";
                foreach($statusReport['desatividadeandamento'] as $aa){
                    $txtAa .= $aa['datinicio'] . " - " . $aa['datfim'] . " - " . $aa['nomatividadecronograma'] . "<br>\n";
                }
                $desatividadeandamento = mb_substr($txtAa, 0, 252)."...";
            }


            $array = $serviceAtividadeCronograma->fetchPairsMarcosPorProjeto($params);
            $arr = array('' => 'Selecione');
            $array = $arr + $array;

            $params['desatividadeconcluida'] = $desatividadeconcluida;
            $params['desatividadeandamento'] = $desatividadeandamento;
//            $params['datfimprojetotendencia'] = date('Y-m-d');
//            $params['idmarco'] = date('Y-m-d');
            $params['datcadastro'] = date('Y-m-d');
            //$params['datfimprojetotendencia']   = $projeto->ultimoStatusReport->datfimprojetotendencia->toString('d/m/Y');
            $params['idprojeto'] = $params['idprojeto'];
            
            $form->getElement('idmarco')->setMultiOptions($array);
            $form->populate($params);
            //exit;
        }
        return $form;
    }

    /**
     * @return Projeto_Form_Gerencia
     */
    public function getFormPesquisar() {
        return $this->_getForm('Projeto_Form_Statusreportpesquisar');
    }

    public function getFormEditar($params = null) {
        $form = $this->_getForm('Projeto_Form_StatusReporteditar');
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
    
    public function getFormExcluir($params = null) {
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
    public function inserirStatusProjeto($statusReport){
        //inserindo os dados no primeiro momento do projeto
        if($statusReport != ''){
            $model = new Projeto_Model_Statusreport($statusReport);
            $insert = $this->_mapper->insert($model);
            return $insert;
        }
        else{
             $this->errors = $form->getMessages();
             return false;
        }
    }

    public function ultimoId(){
        $id = $this->_mapper->ultimoId();
        return $id['idstatusreport'];
    }

    public function inserir($dados) {

        $form = $this->getFormEditar($dados);

        $arquivo = isset($dados['descaminho']) == false;
        if(empty($dados['descaminho'])){
            $dados['descaminho'] = null;
        }
//        print "service - update";
//        Zend_Debug::dump($dados);
//        Zend_Debug::dump($form);
//        $dados = array_filter($dados);
//        var_dump($form->isValidPartial($dados));
//        exit;

        if ($form->isValidPartial($dados)) {
            ///  $model = new Projeto_Model_Gerencia($form->getValues());
            $model = new Projeto_Model_Statusreport($form->getValues());
            $insert = $this->_mapper->insert($model);

//            print_r($insert); exit;

            if($arquivo){
                $this->renomearArquivo($form->getElement('descaminho'),$dados);
            }
            return $insert;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }


        /*$service = App_Service_ServiceAbstract::getService('Default_Service_Upload');
        $form = $this->getForm($dados);
        $this->_db->beginTransaction();
//        Zend_Debug::dump($this->proximoId());exit;
//        Zend_Debug::dump('testes');
//        Zend_Debug::dump($dados['descaminho']);
//        Zend_Debug::dump($dados);exit;
//        $descaminho = $form->getElement('descaminho');
//        Zend_Debug::dump($descaminho);
//        var_dump($form->isValidPartial($dados));
//        exit;
        if(empty($dados['descaminho'])){
            $dados['descaminho'] = null;
        }
        $dados = array_filter($dados);
        if ($form->isValidPartial($dados)) {
            $upload = $service->getUploadConfig($form,$dados);
            if($upload){
//                Zend_Debug::dump($dados['descaminho']);exit;
                if(isset($dados['descaminho'])){
                    $descaminho = $form->getElement('descaminho');
                    if ( !$descaminho->receive() ) {
                        $this->_db->rollBack();
                        return false;
                    }
                    $fileName = $this->renomearArquivo($form->getElement('descaminho'),$dados);
                }

                $model = new Projeto_Model_Statusreport($form->getValues());
//                $model->descaminho = $fileName;
                $id                = $this->_mapper->insert($model);

                $this->_db->commit();

                return $id;
            }
//            return false;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }*/
    }

    private function renomearArquivo(Zend_Form_Element_File $file,$dados,$insert = false)
    {
        $idstatusreport = isset($dados['idstatusreport']) ? $dados['idstatusreport'] : $this->ultimoId();
        $extension      = pathinfo($file->getFileName('descaminho'), PATHINFO_EXTENSION);
//        $uniqueToken    = md5(uniqid(mt_rand(), true));
        $format         = 'pdf_%s_%s.%s';
        //$newFileName    = $id . '.' . $extn;
        $newFileName    = sprintf($format, $dados['idprojeto'], $idstatusreport, $extension);
        $uploadfilepath = $file->getDestination() . DIRECTORY_SEPARATOR . $newFileName;
        //Zend_Debug::dump($uploadfilepath);
        //exit;

        $filterRename = new Zend_Filter_File_Rename(array(
            'target'    => $uploadfilepath,
            'overwrite' => true
        ));
        $filterRename->filter($file->getFileName('descaminho'));

        return $newFileName;
    }

    public function retornaAnexo($params,$retornaPath = false){
        $filename = 'pdf_' . $params['idprojeto'] . "_" . $params['idstatusreport'] . ".pdf";
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR .'arquivos' . DIRECTORY_SEPARATOR . $filename;
        $url = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "arquivos" . DIRECTORY_SEPARATOR . $filename;

//        echo $url;
//        Zend_Debug::dump($path);
//        Zend_Debug::dump($url); exit;

        if(file_exists( $path )){
            if($retornaPath){
                return $path;
            }
            return $url;
        }
        return false;
    }

    public function update($dados) {
        $form = $this->getFormEditar($dados);

//        Zend_Debug::dump($dados['descaminho']);
//        Zend_Debug::dump(isset($dados['descaminho']));
//        Zend_Debug::dump(isset($dados['descaminho']) == false);
//        exit;
        $arquivo = isset($dados['descaminho']) == false;
        if(isset($dados['descaminho'])){
            $dados['descaminho'] = null;
        }

//        if($dados['descaminho']){
//
//            $this->renomearArquivo($form->getElement('descaminho'),$dados);
//        }

//        print "service - update";
//        Zend_Debug::dump($dados);
//        $dados = array_filter($dados);
//        var_dump($form->isValidPartial($dados));
//        exit;

        if ($form->isValidPartial($dados)) {
            ///  $model = new Projeto_Model_Gerencia($form->getValues());
            $model = new Projeto_Model_Statusreport($form->getValues());

            if($arquivo){
                $this->renomearArquivo($form->getElement('descaminho'),$dados);
            }

            return $this->_mapper->update($model);
        } else {
            $this->errors = $form->getMessages();
            return false;
        }

        /* $form = $this->getFormResumoDoProjeto();
          if ($form->isValidPartial($dados)) {
          $values = array_filter($form->getValues());
          $model = new Projeto_Model_Gerencia($values);
          $retorno = $this->_mapper->update($model);
          return $retorno;
          } else {
          $this->errors = $form->getMessages();
          return false;
          } */
    }

    public function getProximaChave(){

    }

    /**
     * 
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            //$model = new Default_Model_Documento($dados);
            $excluir =  $this->_mapper->excluir($dados);

            if($pathArquivo = $this->retornaAnexo($dados,true)){
                unlink($pathArquivo);
            }

            return $excluir;
        } catch ( Exception $exc ) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }
    
    public function getById($dados) {
        $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
        $retorno = $this->_mapper->getById($dados);
        $retorno->nomstatusprojeto = $this->getNomeStatusById($retorno->domstatusprojeto);
        
        $marco = $serviceAtividadeCronograma->getById(array('idatividadecronograma' => $retorno->idmarco));
//        Zend_Debug::dump($marco->datiniciobaseline); exit;
        if($marco->datiniciobaseline && $marco->datfim){
            $retorno->nomproximomarco   = $marco->nomatividadecronograma . " - " .$marco->datiniciobaseline->format('d/m/Y') . " - ". $marco->datfim->format('d/m/Y');
        }
            $retorno->nomrisco          = $this->getNomeRiscoById($retorno->domcorrisco);
//        Zend_Debug::dump($retorno->idmarco); exit;
//        Zend_Debug::dump($marco); exit;
        return $retorno;
    }

    public function getErrors() {
        return $this->errors;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator) {
        if($this->getRequest()){
        $dados = $this->_mapper->pesquisar($params, $paginator);
        }
        $dados = $this->_mapper->pesquisar($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    public function getStatus() {
            //'Proposta'      => 'Proposta',
            //'Em Alteracao'  => 'Em Alteração',
        $retorno = array(
            ''  => 'Todos',
            '2' => 'Em Andamento',
            '3' => 'Concluído',
            '4' => 'Paralisado',
            '5' => 'Cancelado',
        );

        return $retorno;
    }
    
    public function getNomeStatusById($id) {
        $nome = '';
        Switch($id){
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
            Default:
                $nome = 'Todos';
                break;
        }
        
        return $nome;
    }
    
    public function getNomeRiscoById($id) {
        $nome = '';
        Switch($id){
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

    public function getOptionsObejetivo(){
        $serviceObjetivo   = new Default_Service_Objetivo();
        $objetivos = $serviceObjetivo->fetchPairs();
        array_unshift($objetivos,"Selecione");
        return $objetivos;
    }


    public function getStatusProjeto() {
         $serviceSituacao =  new Projeto_Service_SituacaoProjeto();
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

    public function getFlaCopa() {
        $retorno = array(
            '' => 'Todos',
            'S' => 'SIM',
            'N' => 'NÃO',
        );

        return $retorno;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function retornaAcompanhamentosPorProjeto($params, $paginator, $array = false) {
        $dados = $this->_mapper->retornaAcompanhamentosPorProjeto($params, $paginator, $array);
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $projeto = $serviceGerencia->getById($params);

//        $datfimprojetotendencia = new Zend_Date($acompanhamentos->datfimprojetotendencia , 'dd/MM/YYYY');
//        $datfim = new Zend_Date( $d['datfim'], 'dd/MM/YYYY');
//        $diff =$datfimprojetotendencia->sub($datfim)->toValue();
//        $dias = floor($diff/60/60/24);

        $dataplanejamento = new Zend_Date($projeto->datfim, 'dd/MM/YYYY');


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

                $datfimprojetotendencia = new Zend_Date($d['datfimprojetotendencia'], 'dd/MM/YYYY');
                $diff = $datfimprojetotendencia->sub($projeto->datfim)->toValue();
                $dias = floor($diff / 60 / 60 / 24);
                $array = array();
                $previsto = $d['numpercentualprevisto'] . "%";
                $concluido = $d['numpercentualconcluido'] . "%";
                $prazo = $this->getSemaforoPrazo($dias, $projeto->numcriteriofarol);
                $risco = $this->getSemaforoRisco($d['domcorrisco']);

                $array['cell'] = array(
                    $d['datacompanhamento'],
                    $previsto,
                    $concluido,
                    $d['datfimprojetotendencia'],
                    '',
                    $d['nomcadastrador'],
                    $prazo,
                    $risco,
                    $d['idstatusreport']
                );

                $response["rows"][] = $array;
            }

            return $response;

            //$service = new App_Service_JqGrid();
            //$service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            //return $service;
        }
        return $dados;
    }

    public function getSemaforo($dias, $numcriteriofarol) {
//        return $dias;
        if ($dias >= $numcriteriofarol)
            $sinal = "<span class='badge badge-important' title='" . $dias . "'>P</span>";
        elseif ($dias > 0)
            $sinal = "<span class='badge badge-warning' title='" . $dias . "'>P</span>";
        else
            $sinal = "<span class='badge badge-success' title='" . $dias . "'>P</span>";

        return $sinal;
    }

    public function retornaDiferencaDias($datfim, $datfimprojetotendencia) {
        $datfimprojetotendencia = new Zend_Date($datfimprojetotendencia, 'dd/MM/YYYY');
        $diff = $datfimprojetotendencia->sub($datfim)->toValue();
        $dias = floor($diff / 60 / 60 / 24);

        return $dias;
    }

    private function getSemaforoPrazo($dias, $numcriteriofarol) {
//        return $dias;
        if ($dias >= $numcriteriofarol)
            $sinal = "<span class='badge badge-important' title='" . $dias . "'>P</span>";
        elseif ($dias > 0)
            $sinal = "<span class='badge badge-warning' title='" . $dias . "'>P</span>";
        else
            $sinal = "<span class='badge badge-success' title='" . $dias . "'>P</span>";

        return $sinal;
    }

    private function getSemaforoRisco($risco) {
        $retorno = '-';

        if ($risco == '1') {
            $retorno = '<span class="badge badge-success">R</span>';
        } elseif ($risco == '2') {
            $retorno = '<span class="badge badge-warning">R</span>';
        } elseif ($risco == '3') {
            $retorno = '<span class="badge badge-important">R</span>';
        }

        return $retorno;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function retornaAcompanhamentoPorId($params, $paginator) {
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
     * @return \Default_Service_JqGrid | array
     */
    public function retornaUltimoAcompanhamento($params, $paginator) {
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
    public function getChartPlanejadoRealizado($params, $paginator) {
        $dados = $this->_mapper->getChartPlanejadoRealizado($params, $paginator);
        $retorno = array();
        $ordem = 0;
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
    public function getChartEvolucaoAtraso($params, $paginator) {
        $dados = $this->_mapper->getChartEvolucaoAtraso($params, $paginator);
        $retorno = array();
        $ordem = 0;
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
    public function getChartPrazo($params, $paginator) {
        $dados = $this->_mapper->getChartPrazo($params, $paginator);

        $r = new stdClass();
        $r->numcriteriofarol = $dados["numcriteriofarol"];
        $r->prazo = $dados["prazo"];

        return $r;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return  array
     */
    public function getChartMarco($params, $paginator) {
        $dados = $this->_mapper->getChartMarco($params, $paginator);

        $r = new stdClass();
        $r->numcriteriofarol = $dados["numcriteriofarol"];
        $r->prazo = $dados["prazo"];

        return $r;
    }

}
