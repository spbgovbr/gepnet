<?php

class Planejamento_Service_Portfolio extends App_Service_ServiceAbstract
{
    protected $_form;

    /**
     *
     * @var Planejamento_Model_Mapper_Portfolio
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Planejamento_Model_Mapper_Portfolio();
    }

    /**
     * @return Planejamento_Form_PortfolioEstrategico
     */
    public function getFormPortfolioEstrategico($params = null)
    {
        $form = $this->_getForm('Planejamento_Form_PortfolioEstrategico');
        if ($params && isset($params['idescritorio'])) {
            $form->idescritorio->setValue($params['idescritorio']);
        }
        return $form;
    }

    public function getFormPesquisarPortfolio()
    {
        $form = $this->_getForm('Planejamento_Form_Portfolio');
        $form->idescritorio->setRequired(false)->setAttrib('data-rule-required', false);
        $form->noportfolio->setRequired(false)->setAttribs(array(
            'data-rule-required' => false,
            'class' => 'span2_5'
        ));
        $form->tipo->setRequired(false)->setAttrib('data-rule-required', false);
        $form->ativo->setRequired(false)->setAttrib('data-rule-required', false);

        return $form;
    }

    public function pesquisaPortfolio()
    {
        $form = $this->_getForm('Planejamento_Form_Portfolio');
        $form->idescritorio->setRequired(false)->setAttrib('data-rule-required', false);
        $form->noportfolio->setRequired(false)->setAttribs(array(
            'data-rule-required' => false,
            'class' => 'span2_5'
        ));
        $form->tipo->setRequired(false)->setAttrib('data-rule-required', false);
        $form->ativo->setRequired(false)->setAttrib('data-rule-required', false);

        return $form;
    }

    public function getForm()
    {
        $form = $this->_getForm('Planejamento_Form_Portfolio');
        $mapperPrograma = new Default_Model_Mapper_Programa();
        $fetchPairs = $mapperPrograma->fetchPairs();
        $form->idprograma->setMultiOptions($fetchPairs);
        return $form;
    }

    public function getFormEditar()
    {
        $form = $this->_getForm('Planejamento_Form_Portfolio');
        $mapperPrograma = new Default_Model_Mapper_Programa();
        $fetchPairs = $mapperPrograma->fetchPairs();
        $form->idprograma->setMultiOptions($fetchPairs);
        return $form;

    }

    /**
     * @return Planejamento_Form_PortfolioEstrategico
     */
    public function getFormPesquisar($params)
    {
        $form = $this->_getForm('Planejamento_Form_PortfolioEstrategico');
        $form->idobjetivo->setValue(isset($params['idobjetivo']) ? $params['idobjetivo'] : null);
        $form->idescritorio->setValue(isset($params['idescritorio']) ? $params['idobjetivo'] : null);
        if (isset($params['idacao'])) {
            $form->idacao->setValue($params['idacao']);
        }
        return $form;
    }

    public function inserir($dados)
    {
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Planejamento_Model_Portfolio($form->getValues());
            $retorno = $this->_mapper->insert($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function editar($dados)
    {
        $form = $this->getFormEditar();
        if ($form->isValid($dados)) {
            $model = new Planejamento_Model_Portfolio($form->getValues());
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    /* public function editarPortfolioPrograma($dados)
     {
         if(isset($dados['idprograma']) && count($dados['idprograma']) > 0){
             $mapperPrograma = new Default_Model_Mapper_Programa();
             foreach($dados['idprograma'] as $idprograma){
                 $dadosPrograma = array('idprograma' => $idprograma,
                                         'idportfolio' => $dados['idportfolio']);
                 $modelPrograma = new Default_Model_Programa($dadosPrograma);
                 $inserePrograma = $mapperPrograma->update($modelPrograma);
             }
             return true;
         }
         return false;
     }*/

    public function getPortfolioById($params)
    {
        $portfolio = $this->_mapper->getPortfolioById($params);
        $mapperPortProg = new Planejamento_Model_Mapper_Portfolioprograma();
        $programas = $mapperPortProg->getProgramaByPortfolio(array('idportfolio' => $portfolio->idportfolio));
        $portfolio->idprograma = $programas;
        return $portfolio;
    }

    public function getByIdDetalhar($params)
    {
        $portfolio = $this->_mapper->getPortfolioById($params);
        $mapperPortProg = new Planejamento_Model_Mapper_Portfolioprograma();
        $programas = $mapperPortProg->fecthAllProgramasByPortfolio(array('idportfolio' => $portfolio->idportfolio));
        $portfolio->idprograma = $programas;
        return $portfolio;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function pesquisarPortfolio($params, $paginator)
    {
        $dados = $this->_mapper->pesquisarPortfolio($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        }
        return $dados;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Processo_Service_JqGrid | array
     */
    public function pesquisarProjeto($params, $paginator)
    {
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $serviceGerencia = new Projeto_Service_Gerencia();
        $dados = $mapperGerencia->pesquisarProjetoPortfolio($params, $paginator);
        if ($paginator) {
            $response = array();
            $response['page'] = $dados->getPages()->current;
            $response['total'] = $dados->getPages()->pageCount;
            $response['records'] = $dados->getPages()->totalItemCount;

            foreach ($dados as $d) {
                $array = array();
                $statusprojeto = $serviceGerencia->retornaDescricaoStatusProjeto($d['domstatusprojeto']);
                $previsto = "-";
                $concluido = "-";
                $atraso = "-";
                $prazo = "-";
                $risco = "-";
                $ultimoacompanhamento = "-";


                if (!empty ($d['idstatusreport'])) {
                    if ($d['datfimprojetotendencia']) {
                        $datfimprojetotendencia = new Zend_Date($d['datfimplano'], 'dd/MM/YYYY');
                        $datfim = new Zend_Date($d['datfim'], 'dd/MM/YYYY');
                        $diff = $datfimprojetotendencia->sub($datfim)->toValue();
                        $dias = floor($diff / 60 / 60 / 24);
                    }
                    $previsto = $d['numpercentualprevisto'] . "%";
                    $concluido = $d['numpercentualconcluido'] . "%";
                    $atraso = $prazo = $dias;
                    $risco = $d['domcorrisco'];
                    $ultimoacompanhamento = $d['datacompanhamento'];
                }
                $array['cell'] = array(
                    $d['nomprograma'],
                    $d['nomprojeto'],
                    $statusprojeto,
                    $d['idgerenteprojeto'],
                    $d['datinicio'],
                    $d['datfimplano'],
                    $d['datfim'],
                    $previsto,
                    $concluido,
                    $prazo,
                    $risco,
                    $atraso,
                    $ultimoacompanhamento,
                    $d['datfim'],
                    $d['idprojeto'],
                    $d['numcriteriofarol'],
                );
                $response["rows"][] = $array;
            }
            return $response;
        }
        return $dados;
    }

    public function fetchPairs()
    {
        return $this->_mapper->fetchPairs();
    }

    public function pesquisarIdPrograma($params)
    {
        return $this->_mapper->pesquisarIdPrograma($params);
    }

    public function getPortfolioEstrategico($params)
    {

        $mapperObjetivo = new Planejamento_Model_Mapper_Objetivo();
        $objetivos = $mapperObjetivo->getTodosObjetivosEAcoes($params);
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $objetivosArray = array();
        $contAcao = 0;

        foreach ($objetivos as $i => $objetivo) {
            $objetivosArray[$i] = $objetivo->toArray();
            $objetivosArray[$i]['totalProjetoProposta'] = $mapperGerencia->getQtdeProjetosPorStatus(array(
                    'idobjetivo' => $objetivo->idobjetivo,
                    'domstatusprojeto' => Projeto_Model_Gerencia::STATUS_PROPOSTA
                ) + $params);
            $objetivosArray[$i]['totalProjetos'] = $mapperGerencia->getTotalProjetosPorObjetivo($objetivo->idobjetivo,
                $params);
            foreach ($objetivo->acoes as $j => $acao) {
                $contAcao++;
                $ac[$j] = $acao->toArray();
                $ac[$j]['totalProjetoProposta'] = $mapperGerencia->getQtdeProjetosPorStatus(array(
                        'idacao' => $acao->idacao,
                        'domstatusprojeto' => Projeto_Model_Gerencia::STATUS_PROPOSTA,
                    ) + $params);
                $ac[$j]['totalProjetos'] = $mapperGerencia->getQtdeProjetosPorStatusAcao(array(
                        'idacao' => $acao->idacao,
                        'domstatusprojeto' => Projeto_Model_Gerencia::STATUS_ANDAMENTO . ',' .
                            Projeto_Model_Gerencia::STATUS_CONCLUIDO . ',' .
                            Projeto_Model_Gerencia::STATUS_PARALISADO . ',' .
                            Projeto_Model_Gerencia::STATUS_CANCELADO . ',' .
                            Projeto_Model_Gerencia::STATUS_PROPOSTA,
                        //Projeto_Model_Gerencia::STATUS_BLOQUEADO.','.
                        ////Projeto_Model_Gerencia::STATUS_ALTERACAO,
                    ) + $params);
            }
            $objetivosArray[$i]['acoes'] = $ac;
            $ac = array();
        }
        return $objetivosArray;
    }

    public function getBuscaPortfolioEstrategico($params)
    {

        $mapperObjetivo = new Planejamento_Model_Mapper_Objetivo();
        $objetivos = $mapperObjetivo->getTodosObjetivosEAcoes($params);
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $objetivosArray = array();
        $contAcao = 0;
        if (isset($params['idescritorio']) && $params['idescritorio'] != '') {
            $parametro = 'idescritorio';
            $parametros = $params['idescritorio'];
        }
        if (isset($params['idacao']) && $params['idacao'] != '') {
            $parametro = 'idacao';
            $parametros = $params['idacao'];
        }
        if (isset($params['idobjetivo']) && $params['idobjetivo'] != '') {
            $parametro = 'idobjetivo';
            $parametros = $params['idobjetivo'];
        }
        if (isset($params['idsetor']) && $params['idsetor'] != '') {
            $parametro = 'idsetor';
            $parametros = $params['idsetor'];
        }
        if (isset($params['nomprojeto']) && $params['nomprojeto'] != '') {
            $parametro = 'nomprojeto';
            $parametros = $params['nomprojeto'];
        }
        if (isset($params['idprograma']) && $params['idprograma'] != '') {
            $parametro = 'idprograma';
            $parametros = $params['idprograma'];
        }
        if (isset($params['idnatureza']) && $params['idnatureza'] != '') {
            $parametro = 'idnatureza';
            $parametros = $params['idnatureza'];
        }
        if (isset($params['domstatusprojeto']) && $params['domstatusprojeto'] != '') {
            $parametro = 'domstatusprojeto';
            $parametros = $params['domstatusprojeto'];
        }
        foreach ($objetivos as $i => $objetivo) {

            $objetivosArray[$i] = $objetivo->toArray();
            $objetivosArray[$i]['totalProjetoProposta'] = $mapperGerencia->getPesquisaQtdeProjetosPorStatus(array(
                $parametro => $parametros,
                'domstatusprojeto' => Projeto_Model_Gerencia::STATUS_PROPOSTA
            ));
            $objetivosArray[$i]['totalProjetos'] = $mapperGerencia->getTotalProjetosPorObjetivo($objetivo->idobjetivo,
                array($parametro => $parametros));
            foreach ($objetivo->acoes as $j => $acao) {
                $contAcao++;
                $ac[$j] = $acao->toArray();
                $ac[$j]['totalProjetoProposta'] = $mapperGerencia->getQtdeProjetosPorStatus(array(
                    $parametro => $parametros,
                    'domstatusprojeto' => Projeto_Model_Gerencia::STATUS_PROPOSTA
                ));
                $ac[$j]['totalProjetos'] = $mapperGerencia->getQtdeProjetosPorStatusAcao(array(
                    $parametro => $parametros,
                    'domstatusprojeto' => Projeto_Model_Gerencia::STATUS_ANDAMENTO . ',' .
                        Projeto_Model_Gerencia::STATUS_CONCLUIDO . ',' .
                        Projeto_Model_Gerencia::STATUS_PARALISADO . ',' .
                        Projeto_Model_Gerencia::STATUS_CANCELADO . ',' .
                        Projeto_Model_Gerencia::STATUS_PROPOSTA,
                    //Projeto_Model_Gerencia::STATUS_BLOQUEADO.','.
                    //Projeto_Model_Gerencia::STATUS_ALTERACAO,

                ));
            }
            $objetivosArray[$i]['acoes'] = $ac;
            $ac = array();
        }
        return $objetivosArray;
    }


    public function getTotalOrcamentarioProjetosPrograma($params)
    {
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $dadosChart = $mapperGerencia->getTotalOrcamentarioProjetosPorPrograma($params);
        $retorno = array();
        foreach ($dadosChart as $d) {
            $r = new stdClass();
            $r->programa = $d['nomprograma'] . " (" . $d['total'] . ")";
            if (!empty($d["soma"])) {
                //$r->datay = $d["soma"]/1000000;
                $r->milhoes = $d["soma"];
            }
            $r->totalProjetos = (int)$d['total'];
            $retorno[] = $r;
        }
        return $retorno;
    }

    public function getTotalProjetosPorNatureza($params)
    {
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $dadosChart = $mapperGerencia->getTotalProjetosNatureza($params);
        $retorno = array();
        foreach ($dadosChart as $d) {
            $r = new stdClass();
            $r->natureza = $d['nomnatureza'] . " (" . $d['total'] . ")";
            $r->totalProjetos = (int)$d['total'];
            $retorno[] = $r;
        }
        return $retorno;
    }

    public function initCombo($objeto, $msg)
    {

        $listArray = array();
        $listArray = array('' => $msg);

        foreach ($objeto as $val => $desc) {
            if ($desc != $msg) {
                $listArray[$val] = $desc;
            }
        }
        return $listArray;
    }
}
