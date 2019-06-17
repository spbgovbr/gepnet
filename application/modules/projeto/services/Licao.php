<?php

class Projeto_Service_Licao extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Licao
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Licao();
    }

    /**
     * @return Projeto_Form_Licao
     */
    public function getForm($params)
    {
        $serviceAtivCronograma = new Projeto_Service_AtividadeCronograma();
        $fetchPairEntrega = $serviceAtivCronograma->fetchPairsEntrega($params);
        $serviceLicao = new Projeto_Service_Licao();
        $getAssociada = $serviceLicao->getAssociada();
        $form = $this->_getForm('Projeto_Form_Licao');
        $form->getElement('identrega')->setMultiOptions($fetchPairEntrega);
        $form->getElement('idassociada')->setMultiOptions($getAssociada);
        return $form;
    }

    public function getFormPesquisar($params)
    {
        $serviceAtivCronograma = new Projeto_Service_AtividadeCronograma();
        $fetchPairEntrega = $serviceAtivCronograma->fetchPairsEntrega($params);
        $form = $this->_getForm('Projeto_Form_Licao');
        $form->getElement('identrega')->setMultiOptions($fetchPairEntrega)
            ->setAttribs(array('class' => 'span3', 'data-rule-required' => false))
            ->setRequired(false);
        $form->getElement('submit')->setLabel('Pesquisar');
        return $form;
    }

    public function inserir($dados)
    {
        if (@trim($dados['desresultadosobtidos']) != "") {
            $dados['desresultadosobtidos'] = @mb_substr(@trim($dados['desresultadosobtidos']), 0, 1000);
        }
        if (@trim($dados['despontosfortes']) != "") {
            $dados['despontosfortes'] = @mb_substr(@trim($dados['despontosfortes']), 0, 1000);
        }
        if (@trim($dados['despontosfracos']) != "") {
            $dados['despontosfracos'] = @mb_substr(@trim($dados['despontosfracos']), 0, 1000);
        }
        if (@trim($dados['dessugestoes']) != "") {
            $dados['dessugestoes'] = @mb_substr(@trim($dados['dessugestoes']), 0, 1000);
        }
        $form = $this->getForm($dados);
        if ($form->isValid($dados)) {
            $model = new Projeto_Model_Licao($dados);
            $retorno = $this->_mapper->insert($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function update($dados)
    {
        if (@trim($dados['desresultadosobtidos']) != "") {
            $dados['desresultadosobtidos'] = @mb_substr(@trim($dados['desresultadosobtidos']), 0, 1000);
        }
        if (@trim($dados['despontosfortes']) != "") {
            $dados['despontosfortes'] = @mb_substr(@trim($dados['despontosfortes']), 0, 1000);
        }
        if (@trim($dados['despontosfracos']) != "") {
            $dados['despontosfracos'] = @mb_substr(@trim($dados['despontosfracos']), 0, 1000);
        }
        if (@trim($dados['dessugestoes']) != "") {
            $dados['dessugestoes'] = @mb_substr(@trim($dados['dessugestoes']), 0, 1000);
        }
        $form = $this->getForm($dados);
        if ($form->isValid($dados)) {
            $model = new Projeto_Model_Licao($form->getValues());
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
    public function excluir($dados)
    {
        return $this->_mapper->delete($dados);
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \App_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        }
        return $dados;
    }

    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }

    public function detalhar($params)
    {
        return $this->_mapper->getById($params);
    }

    public function retornaLicaoPorProjeto($idprojeto)
    {
        return $this->_mapper->retornaLicaoPorProjeto($idprojeto);
    }

    public function retornaLicoes($params, $paginator)
    {
        $dados = $this->_mapper->retornaPorProjeto($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        }
        return $dados;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getAssociada()
    {
        $retorno = array(
            '' => 'Selecione',
            '1' => 'Entrega',
            '2' => 'Projeto',
        );
        return $retorno;
    }

    public function retornaTipoassociada($params)
    {
        return $this->_mapper->retornTipoassociada($params);
    }


}

