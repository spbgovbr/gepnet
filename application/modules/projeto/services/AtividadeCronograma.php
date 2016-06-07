<?php

class Projeto_Service_AtividadeCronograma extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Atividadecronograma
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
        $this->_mapper = new Projeto_Model_Mapper_Atividadecronograma();
    }

    /**
     * @return Projeto_Form_AtividadeCronogramaGrupo
     */
    public function getFormGrupo($params = array())
    {
        $form = $this->_getForm('Projeto_Form_AtividadeCronogramaGrupo', array('submit', 'reset'));
        $form->populate($params);
        $form->populate(array('domtipoatividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_GRUPO));
        return $form;
    }

    /**
     * @param array $params
     * @return Projeto_Form_AtividadeCronogramaEntrega
     */
    public function getFormEntrega($params = array())
    {
        $parteInteressada = new Projeto_Service_ParteInteressada();

        $form = $this->_getForm('Projeto_Form_AtividadeCronogramaEntrega', array('submit', 'reset'));
        $form->getElement('idgrupo')->setMultiOptions($this->fetchPairsGrupo($params));
        $form->getElement('idparteinteressada')->setMultiOptions($parteInteressada->fetchPairsPorProjeto($params));
        $form->populate($params);
        $form->populate(array('domtipoatividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_ENTREGA));
        return $form;
    }

    /**
     * @return Projeto_Form_AtividadeCronograma
     */
    public function getFormAtividade($params)
    {
        //Zend_Debug::dump($params); exit;
        $parteInteressada = new Projeto_Service_ParteInteressada();
        $form             = $this->_getForm('Projeto_Form_AtividadeCronograma', array('submit', 'reset'));
        
        $elementoDespesa = new Default_Model_Mapper_Elementodespesa();

        $form->getElement('idgrupo')->setMultiOptions($this->fetchPairsEntrega($params));
        $form->getElement('idparteinteressada')->setMultiOptions($parteInteressada->fetchPairsPorProjeto($params));
        $form->getElement('predecessora')->setMultiOptions($this->fetchPairsAtividade($params));
        $form->getElement('idelementodespesa')->setMultiOptions($this->initCombo($elementoDespesa->fetchPairs(), "Selecione"));
        $form->populate($params);
        $form->populate(array(
            'domtipoatividade'     => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM,
            'numfolga'             => 0,
            'vlratividadebaseline' => 0,
            'vlratividade'         => 0,
        ));
        return $form;
    }
    
    /**
     * @return Projeto_Form_AtividadeCronogramaPesquisar
     */
    public function getFormAtividadePesquisar($params)
    {
        //Zend_Debug::dump($params); exit;
        $parteInteressada = new Projeto_Service_ParteInteressada();
        $form             = $this->_getForm('Projeto_Form_AtividadeCronogramaPesquisar');

        //$form->getElement('idgrupo')->setMultiOptions($this->fetchPairsEntrega($params));
        $form->getElement('idparteinteressada_pesq')->setMultiOptions($parteInteressada->fetchPairsPorProjeto($params));
        $form->populate(array('idprojeto_pesq' => $params['idprojeto']));
        return $form;
    }

    /**
     * @return Projeto_Form_AtividadeCronogramaMarco
     */
    public function getFormAtividadeMarco()
    {
        return $this->_getForm('Projeto_Form_AtividadeCronogramaMarco', array('submit', 'reset'));
    }
    
    public function getFormRelatorioCronograma()
    {
        return  $this->_getForm('Projeto_Form_RelatorioCronograma', array());
    }
    

    //put your code here
    public function inserir($dados)
    {
        $form = $this->getForm();

        if ( $form->isValid($dados) ) {
            ///  $model = new Projeto_Model_Gerencia($form->getValues());
            $model = new Projeto_Model_Gerencia($form->getValues());
            return $this->_mapper->insert($model);
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function inserirGrupo($dados)
    {
        $form = $this->getFormGrupo($dados);

        if ( $form->isValid($dados) ) {
            $model = new Projeto_Model_Grupocronograma($form->getValues());
            return $this->_mapper->inserirGrupo($model);
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function inserirEntrega($dados)
    {
        $form = $this->getFormEntrega($dados);

        if ( $form->isValid($dados) ) {
            $model = new Projeto_Model_Entregacronograma($form->getValues());
            return $this->_mapper->inserirEntrega($model);
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function inserirAtividade($dados)
    {
           if (isset($dados['flainformatica']) && $dados['flainformatica'] == 'N') {
            unset($dados['flainformatica']);
        }
        $form = $this->getFormAtividade($dados);

        if ( $form->isValid($dados) ) {
             $dados['numdiasrealizados'] = $dados['numdiasbaseline'];
            ///  $model = new Projeto_Model_Gerencia($form->getValues());
            $model = new Projeto_Model_Atividadecronograma($form->getValues());

            if(isset($dados['idpredecessora']) && count($dados['idpredecessora']) > 0){
                foreach ($dados['idpredecessora'] as $id)
                {
                    $p = new Projeto_Model_Atividadepredecessora();
                    $p->idatividadepredecessora = $id;
                    //Zend_Debug::dump($p);exit;

                    $model->adicionarPredecessora($p);
                }
            }

            $atividade = $this->_mapper->inserirAtividade($model);
            $this->atualizarDatasEntrega($atividade);
            $atividade->datfim            = $atividade->datfim->format('d/m/Y');
            $atividade->datinicio         = $atividade->datinicio->format('d/m/Y');
            $atividade->datfimbaseline    = $atividade->datfimbaseline->format('d/m/Y');
            $atividade->datiniciobaseline = $atividade->datiniciobaseline->format('d/m/Y');
            $this->atualizarPercentuaisGrupoEntrega(array('idprojeto' => $atividade->idprojeto, 'idatividadecronograma' => $atividade->idatividadecronograma));
            
            return $atividade;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }
    
    public function atualizarDatasEntrega(Projeto_Model_Atividadecronograma $model)
    {
        //Zend_Debug::dump($model); //exit;
        $entrega = $this->_mapper->retornaEntregaPorId(array('idprojeto' => $model->idprojeto, 'idatividadecronograma' => $model->idgrupo), false);
        //Zend_Debug::dump($entrega); exit;
        $datas = $this->_mapper->retornaDatasPorEntrega(array('idprojeto' => $model->idprojeto, 'idgrupo' => $model->idgrupo));

        $entrega->setFromArray($datas);
        $this->_mapper->atualizarDatasEntrega($entrega);
        $this->atualizarDatasGrupo($entrega);
        return true;
    }
    
    public function atualizarDatasGrupo(Projeto_Model_Entregacronograma $model)
    {
        $resultado = $this->_mapper->retornaGrupoPorId(array('idprojeto' => $model->idprojeto, 'idatividadecronograma' => $model->idgrupo));
        $datas = $this->_mapper->retornaDatasPorGrupo(array('idprojeto' => $model->idprojeto, 'idgrupo' => $model->idgrupo));
        $grupo = new Projeto_Model_Grupocronograma($resultado);
        $grupo->setFromArray($datas);
        return $this->_mapper->atualizarDatasGrupo($grupo);
    }
    

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function atualizarGrupo($dados)
    {

        $form = $this->getFormGrupo();
        if ( $form->isValidPartial($dados) ) {
            $model   = new Projeto_Model_Grupocronograma($form->getValues());
            $retorno = $this->_mapper->atualizarGrupo($model);
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
    public function atualizarEntrega($dados)
    {

        $form = $this->getFormEntrega($dados);
        if ( $form->isValidPartial($dados) ) {
            $model   = new Projeto_Model_Entregacronograma($form->getValues());
            $retorno = $this->_mapper->atualizarEntrega($model);
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
    public function atualizarAtividade($dados)
    {
        if(isset($dados['flainformatica']) && $dados['flainformatica'] == 'N')
                    {
                        $dados['flainformatica'] = null;
                    }
        $form = $this->getFormAtividade($dados);
        if ( $form->isValidPartial($dados) ) {
            $model   = new Projeto_Model_Atividadecronograma($form->getValues());
            $atividade = $this->_mapper->atualizarAtividade($model);
            $this->atualizarDatasEntrega($atividade);
            $this->atualizarPercentuaisGrupoEntrega(array('idprojeto' => $atividade->idprojeto, 'idatividadecronograma' => $atividade->idatividadecronograma));
            return $atividade;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    /**
     * Excluir Grupo
     * @param array $dados
     */
    public function excluir($dados)
    {
        return $this->_mapper->excluir($dados);
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function getErrors()
    {
        return $this->errors;
    }
    
    public function retornaGrupoPorId($params, $model = false, $collection = false)
    {
        return $this->_mapper->retornaGrupoPorId($params, $model, $collection);
    }

    public function fetchPairsGrupo($params)
    {
        return $this->_mapper->fetchPairsGrupo($params);
    }

    public function fetchPairsEntrega($params)
    {
        $resultado = $this->_mapper->fetchPairsEntrega($params);
        $retorno = array( '' => 'Selecione');
        return $retorno + $resultado;
    }

    public function fetchPairsAtividade($params)
    {
        $resultado = $this->_mapper->fetchPairsAtividade($params);
        $retorno = array(
            '' => 'selecione'
        );
        return $retorno + $resultado;
    }
     public function retornaAtividadePorProjeto($params)
    {
        $resultado = $this->_mapper->retornaAtividadePorProjeto($params);
        return $resultado;
    }
     public function retornaIdAtividadePorEntrega($idprojeto,$idgrupoEntrega)
    {
        $resultado = $this->_mapper->retornaIdAtividadePorEntrega($idprojeto, $idgrupoEntrega);
        return $resultado;
    }
     public function retornaIdEntregaPorGrupo($params)
    {
        $resultado = $this->_mapper->retornaIdEntregaPorGrupo($params);
        return $resultado;
    }
     public function retornaIdAtividadePorProjeto($params)
    {
        $resultado = $this->_mapper->retornaIdAtividadePorProjeto($params);
        return $resultado;
    }
    

    public function fetchPairsMarcosPorAceite($params)
    {
        return $this->_mapper->retornaMarcosPorAceite($params);
    }

    public function fetchPairsMarcosPorEntrega($params)
    {
        return $this->_mapper->fetchPairsMarcoPorEntrega($params);

    }

    public function retornaInicioBaseLinePorPredecessoras($params)
    {
        $predecessoras = array();
        if(isset($params['idpredecessora']) && count($params['idpredecessora']) > 0)
        {
            //Zend_Debug::dump($params['idpredecessora']);
            foreach ($params['idpredecessora'] as $id)
            {
                $predecessoras[] = $id;
            }
        }
        //$predecessoras[] = $params['predecessora'];
        $params['predecessora'] = $predecessoras;
        return $this->_mapper->retornaInicioBaseLinePorPredecessoras($params);
    }

    public function retornaInicioRealPorPredecessoras($params)
    {
        return $this->_mapper->retornaInicioRealPorPredecessoras($params);
    }
    
    public function retornaEntregasEMarcosPorProjeto($params,$parteinteressada = false)
    {
        $retorno = $this->_mapper->retornaEntregasEMarcosPorProjeto($params);
        if ($parteinteressada){
            if(count($retorno) > 0){
                for($i=0;$i<count($retorno);$i++){
                    $serviceParteInteressada = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
                    $parteInteressada = $serviceParteInteressada->getParteInteressada(array('idparteinteressada' => $retorno[$i]->idparteinteressada));
                    if(isset($parteInteressada['idpessoa'])){
                        $retorno[$i]->nomparteinteressada = $parteInteressada['nompessoa'];
                    } else {
                        $retorno[$i]->nomparteinteressada = $parteInteressada['nomparteinteressada'];
                    }
                }
            }
        }
        
//        Zend_Debug::dump($retorno); exit;
        return $retorno;
    }
    
    public function fetchPairsMarcosPorProjeto($params,$parteinteressada = false)
    {
        $retorno = $this->_mapper->fetchPairsMarcosPorProjeto($params);
        if ($parteinteressada){
            if(count($retorno) > 0){
                for($i=0;$i<count($retorno);$i++){
                    $serviceParteInteressada = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
                    $parteInteressada = $serviceParteInteressada->getParteInteressada(array('idparteinteressada' => $retorno[$i]['idparteinteressada']));
                    if(isset($parteInteressada['idpessoa'])){
                        $retorno[$i]['nomparteinteressada'] = $parteInteressada['nompessoa'];
                    } else {
                        $retorno[$i]['nomparteinteressada'] = $parteInteressada['nomparteinteressada'];
                    }
                }
            }
        }
        
//        Zend_Debug::dump($retorno); exit;
        return $retorno;
    }
    
    /**
     * 
     * @param array $params
     * @param boolean $array
     * @return Projeto_Model_AtividadeCronograma or array
     */
    public function retornaEntregaPorId($params, $array = false, $collection = false)
    {
        return $this->_mapper->retornaEntregaPorId($params, $array, $collection);
    }
    
    public function retornaProximoMarco($params, $array = false)
    {
        return $this->_mapper->retornaProximoMarco($params);
    }
    public function retornaUltimoMarco($params, $array = false)
    {
        return $this->_mapper->retornaUltimoMarco($params);
    }
    
    /**
     * 
     * @param array $params
     * @param boolean $array
     * @return Projeto_Model_AtividadeCronograma or array
     */
    public function retornaAtividadePorId($params, $predecessoras)
    {
        return $this->_mapper->retornaAtividadePorId($params, $predecessoras);
    }
    
    public function atividadeAtualizarPercentual($params)
    {
        $model = new Projeto_Model_Atividadecronograma($params);
        $atividade = $this->_mapper->atividadeAtualizarPercentual($model);
        $this->atualizarDatasEntrega($atividade);
        $this->atualizarPercentuaisGrupoEntrega(array('idprojeto' => $atividade->idprojeto, 'idatividadecronograma' => $atividade->idatividadecronograma));
        return $atividade;
    }
    
    
    public function atualizarPercentuaisGrupoEntrega($params, Projeto_Model_Grupocronograma $grupo = null){
        
        $obj = new Projeto_Model_Atividadecronograma();
        $db = $this->_db;
        $db->beginTransaction();
        try{
            if(is_null($grupo) ){
                $grupo = $this->_mapper->retornaGrupoPorAtividade(array('idprojeto' => $params['idprojeto'], 'idatividadecronograma' => $params['idatividadecronograma']));
            }
            
            foreach($grupo->entregas as $j=>$entrega)
            {
                /* @var $atividade Projeto_Model_Entregacronograma */
                $dados = $entrega->toArray();
                $percentuais = $entrega->retornaPercentuais();
                $dados['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                $obj->setFromArray($dados);
                $this->_mapper->atualizarPercentuaisGrupoEntrega($obj);
            }
            $percentuaisGrupo = $grupo->retornaPercentuais();
            $dadosGrupo['idprojeto'] = $grupo->idprojeto;
            $dadosGrupo['idatividadecronograma'] = $grupo->idgrupo;
            $dadosGrupo['numpercentualconcluido'] = $percentuaisGrupo->numpercentualconcluido;
            $obj->setFromArray($dadosGrupo);
            $this->_mapper->atualizarPercentuaisGrupoEntrega($obj);
            
            $db->commit();
        }  catch (Exception $exc){
            $db->rollBack();
            throw $exc;
        }
    }
    
    public function atualizarTipoAtividade($params)
    {
        $model = new Projeto_Model_Atividadecronograma($params);
        return $this->_mapper->atualizarTipoAtividade($model);
    }
    
    public function pesquisar($params) 
    {
        return $this->_mapper->pesquisar($params);
    }
    
    public function excluirAtividade($dados)
    {
        try {
            $grupo = $this->_mapper->retornaGrupoPorAtividade($dados);
            $retorno = $this->_mapper->excluir($dados);
            if($retorno){
                $this->atualizarPercentuaisGrupoEntrega($dados, $grupo);
            }
            return $retorno;
        } catch ( Exception $exc ) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }
    
     public function excluirComPredecessora($dados)
    {
        try {
            $grupo = $this->_mapper->retornaGrupoPorAtividade($dados);
            $retorno = $this->_mapper->excluirComPredecessora($dados);
            if($retorno){
                $this->atualizarPercentuaisGrupoEntrega($dados, $grupo);
            }
            return $retorno;
        } catch ( Exception $exc ) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }
    
    public function excluirEntrega($dados)
    {
        try {
            $grupo = $this->_mapper->retornaGrupoPorEntrega($dados);
            $retorno = $this->_mapper->excluir($dados);
            if($retorno){
                $this->atualizarPercentuaisGrupoEntrega($dados, $grupo);
            }
            return $retorno;
        } catch ( Exception $exc ) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }
    
    public function clonarGrupo($params){
        
        $grupo = $this->_mapper->retornaGrupoPorId($params, true);
        $entregas = $this->_mapper->retornaEntrega(array('idprojeto' => $params['idprojeto'], 'idgrupo' => $params['idatividadecronograma']));
        $db = $this->_db;
        $db->beginTransaction();
        try{
            $modelGrupo = $this->_mapper->inserirGrupo($grupo);
            if ( count($entregas) > 0 ) {
                foreach( $entregas as $ent) {
                    $ent->idgrupo = $modelGrupo->idatividadecronograma;
                    $modelEntrega = $this->_mapper->inserirEntrega($ent);
                    if ( count($ent->atividades) > 0 ) {
                        foreach($ent->atividades as $ativ) {
                            $ativ->idgrupo = $modelEntrega->idatividadecronograma;
                            $this->_mapper->inserirAtividade($ativ);
                            $this->atualizarDatasEntrega($ativ);
                        }
                    }
                }
            }
            $db->commit();
        } catch (Exception $exc) {
            $db->rollBack();
            throw $exc;
        }
        return true;
    }
    
    public function clonarEntrega($params){
        
        $entrega = $this->_mapper->retornaEntregaPorId($params);
        $atividades = $this->_mapper->retornaAtividade(array('idprojeto' => $params['idprojeto'], 'idgrupo' => $params['idatividadecronograma']));
        $db = $this->_db;
        $db->beginTransaction();
        try{
            $modelEntrega = $this->_mapper->inserirEntrega($entrega);
            if( count($atividades) > 0 ){
                foreach ( $atividades as $ativ){
                    $ativ->idgrupo = $modelEntrega->idatividadecronograma;
                    $this->_mapper->inserirAtividade($ativ);
                    $this->atualizarDatasEntrega($ativ);
                }
            }
            $db->commit();
        } catch (Exception $exc) {
            $db->rollBack();
            throw $exc;
        }
        return true;
    }
    
    public function pesquisarProjeto($params, $paginator){
        
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $dados = $mapperGerencia->pesquisarProjetoCronograma($params, $paginator);
        if ( $paginator ) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service->toJqgrid();
        }
        return $dados;
     }
        
    public function copiarCronograma($params){


        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();

        $mapperPredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
        $idsAtividades = array();
        $predecessoras = array();

        $db = $this->_db;
        $db->beginTransaction();
        try{

            $projeto = $mapperGerencia->retornaProjetoPorId($params);

            foreach($projeto->grupos as $gr){
                $gr['idprojeto'] = $params['idprojetoorigem'];
                $insereGrupo = $this->_mapper->inserirGrupo($gr);

                foreach($gr->entregas as $en){
                    $en['idprojeto'] = $params['idprojetoorigem'];
                    $en['idgrupo'] = $insereGrupo->idatividadecronograma;
                    $insereEntrega = $this->_mapper->inserirEntrega($en);

                    foreach($en->atividades as $at){
                        $idAntigo = $at['idatividadecronograma'];
                        $at['idprojeto'] = $params['idprojetoorigem'];
                        $at['idgrupo'] = $insereEntrega->idatividadecronograma;
                        $insereAtividade = $this->_mapper->inserirAtividade($at, false);
                        $idsAtividades[$insereAtividade->idatividadecronograma] = $idAntigo;

                        if( count($at->predecessoras) > 0){
                            foreach($at->predecessoras as $pr){
                                $predecessoras[] = $pr;
                            }
                        }
                    }
                    $datasEn = $this->_mapper->retornaDatasPorEntrega(array('idprojeto' => $params['idprojetoorigem'], 'idgrupo' => $insereEntrega->idatividadecronograma));
                    $en->setFromArray($datasEn);
                    $this->_mapper->atualizarDatasEntrega($en);
                }
                $datasGr = $this->_mapper->retornaDatasPorGrupo(array('idprojeto' => $params['idprojetoorigem'], 'idgrupo' => $insereGrupo->idatividadecronograma));
                $gr->setFromArray($datasGr);

                $this->_mapper->atualizarDatasGrupo($gr);
            }

            if( count($predecessoras) > 0){

                foreach($predecessoras as $pre){
                    $novoIdAtividadePredecessora = array_search($pre['idatividadepredecessora'], $idsAtividades);
                    $novoIdAtividade = array_search($pre['idatividade'], $idsAtividades);
                    if(!empty($novoIdAtividadePredecessora) && !empty($novoIdAtividade) ){
                        $pre['idatividadepredecessora'] = $novoIdAtividadePredecessora;
                        $pre['idatividade'] = $novoIdAtividade;
                        $pre['idprojeto'] = $params['idprojetoorigem'];
                        $inserePredecessora = $mapperPredecessora->insert($pre);
                    }
                }
            }
            $db->commit();
            return true;
        } catch (Exception $exc) {
            $db->rollBack();
            throw $exc;
        }
        
    }
    
    public function detalhar($params){
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $projeto = $mapperGerencia->retornaProjetoPorId($params);
    }
    
    public function atualizarBaselineAtividade($params){
        $atividade = new Projeto_Model_Atividadecronograma($params);
        $atividade->setDatiniciobaseline($params['datinicio']);
        $atividade->setDatfimbaseline($params['datfim']);
        return $this->_mapper->atualizarDatasAtividade($atividade);
    }
    
     public function atualizarBaseline($params){
         
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $db = $this->_db;
        $db->beginTransaction();
        try{
            $projeto = $mapperGerencia->retornaProjetoPorId($params);
            foreach($projeto->grupos as $gr){
                foreach($gr->entregas as $en){
                    foreach($en->atividades as $at){
                        $at->datiniciobaseline = $at->datinicio;
                        $at->datfimbaseline    = $at->datfim;
                        $this->_mapper->atualizarDatasAtividade($at);
                    }
                    $en->datiniciobaseline = $en->datinicio;
                    $en->datfimbaseline    = $en->datfim;
                    if(!empty($en->datiniciobaseline) && !empty($en->datfimbaseline)){
                        $this->_mapper->atualizarDatasEntrega($en);
                    }
                }
                $gr->datiniciobaseline  = $gr->datinicio;
                $gr->datfimbaseline     = $gr->datfim;
                $this->_mapper->atualizarDatasGrupo($gr);
            }
            $db->commit();
            return true;
        } catch (Exception $exc) {
            $db->rollBack();
            throw $exc;
        }
       
    }
    
    public function initCombo($objeto, $msg) {

        $listArray = array();
        $listArray = array('' => $msg);
        
        foreach ($objeto as $val => $desc) {
            if ($desc != $msg) {
                $listArray[$val] = $desc;
            }
        }
        return $listArray;
    }
    
    public function fetchPairsProjetos($params){
        
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $dados = $mapperGerencia->buscarProjetos($params);
        return $dados;
    }
    
    public function retornaCronogramaProjetos($params)
    {
        /*$resultAtividades = $this->_mapper->retornaRelatorioCronograma($params);

        //return $dados;
        //echo '<pre>'; //var_dump($resultAtividades); //exit;

        $itemNivel1 = '';
        $itemNivel2 = '';
        $itemNivel3 = '';
        $itemProjeto = '';


        $data = array();
        $arr = array();

        $i = 0;

        foreach ( $resultAtividades as $atividade) {

                if($itemProjeto != $atividade['idprojeto']){
                    $pos = $i;
                    $data[$pos] = array(
                            'idprojeto' => $atividade['idprojeto'],
                            'nomprojeto'=> $atividade['nomprojeto'],
                            'nomprograma' => $atividade['nomprograma'],
                            'idescritorio' => $atividade['idescritorio'],
                            'datinicio' => $atividade['datinicio'],
                            'datfim' => $atividade['datfim'],
                            'statusprojeto' => $atividade['statusprojeto'],
                            'nivel'   => 0,
                            'label' => $atividade['nv1_nomatividadecronograma'],
                            'start' => $atividade['nv1_datinicio'],
                            'end'   => $atividade['nv1_datfim'],
                            'node'  => 'nivel1',
                            'class' => 'success'
                        );
                    $itemProjeto = $atividade['idprojeto'];
                    $i++;
                }


                //monta atividades nivel 1
                if($itemNivel1 != $atividade['nv1_idatividadecronograma']) {
                    $data[$pos][] = array(
                        'idatividadecronograma'=>$atividade['nv1_idatividadecronograma'],
                        'label' => $atividade['nv1_nomatividadecronograma'],
                        'nv1_datinicio' => $atividade['nv1_datinicio'],
                        'nv1_datfim'   => $atividade['nv1_datfim'],
                        'nv1_datiniciobaseline' => $atividade['nv1_datiniciobaseline'],
                        'nv1_datfimbaseline' => $atividade['nv1_datfimbaseline'],
                        'node'  => 'nivel1',
                        'class' => 'success',
                        'nivel' => 1
                    );
                    $itemNivel1 = $atividade['nv1_idatividadecronograma'];

                }

                //monta atividades nivel 2
                if($atividade['nv2_idatividadecronograma'] != "" && $atividade['nv2_idatividadecronograma'] != $itemNivel2) {
                    $data[$pos][] = array(
                        'idatividade'=>$atividade['nv2_idatividadecronograma'],
                        'label' => $atividade['nv2_nomatividadecronograma'],
                        'nv1_datinicio' => $atividade['nv2_datinicio'],
                        'end'   => $atividade['nv2_datfim'],
                        'node'  => 'nivel2',
                        'class' => 'important',
                        'nivel' => 2
                    );
                    $itemNivel2 = $atividade['nv2_idatividadecronograma'];

                }

                //monta atividades nivel 3
                if($atividade['nv3_idatividadecronograma'] != "" && $atividade['nv3_idatividadecronograma'] != $itemNivel3) {
                    //if ($atividade['nv3_domtipoatividade'] == Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO) {
                        //$predecessoraMarco =  $atividadePredecessora->retornaPorAtividadeProjeto(array('idprojeto'=>$params['idprojeto'], 'idatividade'=>$atividade['nv3_idatividadecronograma']));
                        $data[$pos][] = array(
                            'idatividade'=>$atividade['nv3_idatividadecronograma'],
                            'label'     => $atividade['nv3_nomatividadecronograma'],
                            'start'     => $atividade['nv3_datinicio'],
                            'end'       => $atividade['nv3_datfim'],
                            'class'     => 'urgent',
                            'node'      => 'nivel3',
                            'nivel' => 3,
                            'tipoAtividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO,
                            //'idpredecessora' => @$predecessoraMarco,
                        );
                    } else {
                        /*$predecessora =  $atividadePredecessora->retornaPorAtividadeProjeto(array('idprojeto'=>$params['idprojeto'], 'idatividade'=>$atividade['nv3_idatividadecronograma']));
                        $data[] = array(
                            'idatividade'=>$atividade['nv3_idatividadecronograma'],
                            'label' => $atividade['nv3_nomatividadecronograma'],
                            'start' => $atividade['nv3_datinicio'],
                            'end'   => $atividade['nv3_datfim'],
                            'progress' => $atividade['nv3_numpercentualconcluido'],
                            'node'  => 'nivel3',
                            'idpredecessora' => @$predecessora,
                        );
                    }

                    $itemNivel3 = $atividade['nv3_idatividadecronograma'];

                }
               // $i++;
        }

        echo "<pre>";
        //var_dump($data); exit;

        //return $data;


        //echo "<pre>"; var_dump($retorno); exit;
*/
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        try {
            $projetos = $mapperGerencia->retornaCronogramaProjetos($params);
        } catch (Exception $exc) {
            throw $exc;
        }

       // echo '<pre>'; var_dump($projetos); exit;
        $cont = 0;
        $contAtiv = 0;
        $contEnt = 0;
        $contGrupo = 0;
        $custoTodosProjetos = 0;
        $params = array_filter($params);
        
        foreach($projetos as $projeto) {
            $custoProjeto = 0;
            $projetoArray[$cont] = $projeto->formPopulate();
            $projetoArray[$cont]['statusprojeto'] = $projeto->retornaDescricaoStatusProjeto();
            $projetoArray[$cont]['numpercentualprevisto'] = $projeto->ultimoStatusReport->numpercentualprevisto;
            $projetoArray[$cont]['numpercentualconcluido'] = $projeto->ultimoStatusReport->numpercentualconcluido;
            
            if(isset($projeto->grupos))
            {
                foreach($projeto->grupos as $i => $grupo)
                {
                    $gr = $grupo->toArray();

                    $gr['show'] = true;
                    if( !isset($params['tipogrupo'])){
                        $gr['show'] = false;
                    }else{
                        $contGrupo++;
                    }
                    
                    foreach ($grupo->entregas as $j => $entrega) {
                        $en = $entrega->toArray();

                        $en['show'] = true;
                        if(!isset($params['tipoentrega'])){
                            $en['show'] = false;
                        }else{
                            $contEnt++;
                        }
                        
                        foreach ($entrega->atividades as $k => $atividade) {
                            $at = $atividade->toArray();

                            $at['showAtividade'] = true;
                            $at['showMarco'] = true;
                            if(!isset($params['tipoatividade'])){ 
                                $at['showAtividade'] = false;
                            }else{
                                $contAtiv++;
                            }
                            if(!isset($params['tipomarco'])){ 
                                $at['showMarco'] = false;
                            }

                            $percentuais = $atividade->retornarDiasEstimadosEReais();
                            //Zend_Debug::dump($percentuais);exit;
                            $prazo = $atividade->retornaPrazo($projeto->numcriteriofarol);
                            $at['descricaoprazo'] = $prazo->descricao;
                            $at['prazo'] = $prazo->dias;
                            $en['atividades'][$k] = $at;
                            //$contAtiv++;
                        }
                        $en['prazo'] = 0;
                        if(!empty($en['datfim'])){
                            $prazoEn = $entrega->retornaPrazo($projeto->numcriteriofarol);
                            $en['descricaoprazo'] = $prazoEn->descricao;
                            $en['prazo'] = $prazoEn->dias;
                        }
                        $percentuais = $entrega->retornaPercentuais();
                        $en['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                        $en['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                        $gr['entregas'][$j] = $en;
                        //$contEnt++;
                    }
                    $percentuais = $grupo->retornaPercentuais();
                    $gr['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                    $gr['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                    $valor = str_replace(",","", str_replace(".","",$gr['vlratividade']));
                    $custoProjeto += $valor;
                    $valorfinal = substr($custoProjeto, 0, -2) . '.' . substr($custoProjeto, -2);
                    $projetoArray[$cont]['grupos'][$i] = $gr;
                    //$contGrupo++;
                }
                //$contGrupo += count($projetoArray[$cont]['grupos']);

            }
            $projetoArray[$cont]['custoProjeto'] = number_format($valorfinal, 2, ',', '.');
            $custoTodosProjetos += $custoProjeto;
            $cont++;
        }
        $custoTodosProjetos = substr($custoTodosProjetos, 0, -2) . '.' . substr($custoTodosProjetos, -2);
     //   exit;
        return array( 'projetos'            => $projetoArray,
                      'custoTodosProjetos'  => number_format($custoTodosProjetos, 2, ',', '.'),
                      'qtdeRegistros'       => $contGrupo + $contEnt + $contAtiv );


    }

    public function verificarAtividadesDesatualizadas($params){
        return $this->_mapper->verificarAtividadesDesatualizadas($params);
    }

    public function retornaTendenciaProjeto($params){
        return $this->_mapper->retornaTendenciaProjeto($params);
    }

    public function retornaIrregularidades($params){
        return $this->_mapper->retornaIrregularidades($params);
    }
}
?>

