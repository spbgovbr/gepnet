<?php

class Projeto_Service_ParteInteressada extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_ParteInteressada
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

    public $auth = null;

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Projeto_Model_Mapper_Parteinteressada();
    }

    /**
     * @return Projeto_Form_ParteInteressada
     */
    public function getForm()
    {
        return $this->_getForm('Projeto_Form_Parteinteressada');
    }

    /**
     * @return Projeto_Form_ParteinteressadaExterno
     */
    public function getFormExterno()
    {
        return $this->_getForm('Projeto_Form_ParteinteressadaExterno');
    }

    /**
     * @return Projeto_Form_ParteinteressadaPesquisar
     */
    public function getFormPesquisar()
    {
        return $this->_getForm('Projeto_Form_ParteinteressadaPesquisar');
    }

    /**
     * @return Projeto_Form_Parteinteressada
     */
    public function getFormEditar()
    {
        $formEditar = $this->_getForm('Projeto_Form_Parteinteressada');
        return $formEditar;
    }

    public function insertExterno($dados)
    {
        $formExterno = $this->getFormExterno();
        if ($formExterno->isValid($dados)) {
            $model = new Projeto_Model_Parteinteressada();
            //tratamento para popular model pelo metodo construtor 
            $model->setParteInteressadaExterna($formExterno->getValues());
            $model->idcadastrador = $this->auth->idpessoa;
            $model->idprojeto = $dados['idprojeto'];
            $model->tppermissao = null;
            $model->idpessoainterna = null;

            $idparteinteressadafuncao = explode(',', $model->idparteinteressadafuncao);

            $model->idparteinteressada = $this->_mapper->insert($model);


            foreach ((array)$idparteinteressadafuncao as $item) {
                if (in_array($item, array(
                    Projeto_Model_Parteinteressada::PARTE_INTERESSADA,
                    Projeto_Model_Parteinteressada::EQUIPE_PROJETO
                ))) {
                    $params = array(
                        'idparteinteressadafuncao' => $item,
                        'idparteinteressada' => $model->idparteinteressada
                    );
                    $this->inserirParteInteressadaFuncoes($params);
                }
            }

            return $model;
        } else {
            $this->errors = $formExterno->getMessages();
            return false;
        }
    }

    public function parteInteressadaPorAtividade($idprojeto, $idatividade)
    {
        return $this->_mapper->parteInteressadaPorAtividade($idprojeto, $idatividade);
    }


    public function insertInterno($dados)
    {
        $dados['idpessoainterna'] = (int)$dados['idparteinteressada'];
        $dados['idparteinteressada'] = null;

        $form = $this->getForm();

        if ($form->isValid($dados)) {
            try {
                $db = $this->_mapper->getDbTable()->getAdapter();

                $db->beginTransaction();

                $servicePermissaoProj = new Projeto_Service_PermissaoProjeto();
                $serviceGerencia = new Projeto_Service_Gerencia();

                $model = new Projeto_Model_Parteinteressada($form->getValues());
                $servicePessoa = new Default_Service_Pessoa();

                /** @var Default_Model_Pessoa $pessoa */
                $pessoa = $servicePessoa->retornaPorId(array('idpessoa' => $model->idpessoainterna));

                if ($dados['destelefone'] == '(__) ____-____') {
                    $dados['destelefone'] = null;
                }


                $model->idcadastrador = $this->auth->idpessoa;
                $model->nomparteinteressada = $pessoa->nompessoa;
                $model->destelefone = (isset($dados['destelefone']) && (!empty($dados['destelefone']))) ? $dados['destelefone'] : $pessoa->numfone;
                $model->idprojeto = $dados['idprojeto'];
                $model->desemail = (isset($dados['desemail']) && (!empty($dados['desemail']))) ? $dados['desemail'] : $pessoa->desemail;

                $idparteinteressadafuncao = explode(',', $model->idparteinteressadafuncao);

                $model->idparteinteressada = $this->_mapper->insert($model);

                $projeto = array();

                $modelProjeto = $serviceGerencia->getById($dados);

                foreach ((array)$idparteinteressadafuncao as $item) {

                    if ($item == Projeto_Model_Parteinteressada::GERENTE_PROJETO) {
                        $projeto['idprojeto'] = $dados['idprojeto'];
                        $projeto['idgerenteprojeto'] = $model->idpessoainterna;

                        $serviceGerencia->atualizaParteInteressada($dados['idprojeto'], $modelProjeto->idgerenteprojeto,
                            $model->idpessoainterna, 1);
                    }

                    if ($item == Projeto_Model_Parteinteressada::GERENTE_ADJUNTO) {
                        $projeto['idprojeto'] = $dados['idprojeto'];
                        $projeto['idgerenteadjunto'] = $model->idpessoainterna;

                        $serviceGerencia->atualizaParteInteressada($dados['idprojeto'], $modelProjeto->idgerenteadjunto,
                            $model->idpessoainterna, 2);
                    }

                    if ($item == Projeto_Model_Parteinteressada::DEMANDANTE) {
                        $projeto['idprojeto'] = $dados['idprojeto'];
                        $projeto['iddemandante'] = $model->idpessoainterna;

                        $serviceGerencia->atualizaParteInteressada($dados['idprojeto'], $modelProjeto->iddemandante,
                            $model->idpessoainterna, 3);
                    }

                    if ($item == Projeto_Model_Parteinteressada::PATROCINADOR) {
                        $projeto['idprojeto'] = $dados['idprojeto'];
                        $projeto['idpatrocinador'] = $model->idpessoainterna;

                        $serviceGerencia->atualizaParteInteressada($dados['idprojeto'], $modelProjeto->idpatrocinador,
                            $model->idpessoainterna, 4);
                    }

                    if (in_array($item, array(
                        Projeto_Model_Parteinteressada::PARTE_INTERESSADA,
                        Projeto_Model_Parteinteressada::EQUIPE_PROJETO
                    ))) {
                        $params = array(
                            'idparteinteressadafuncao' => $item,
                            'idparteinteressada' => $model->idparteinteressada
                        );
                        $this->inserirParteInteressadaFuncoes($params);
                    }
                }

                if (!(count($projeto) > 0)) {
                    $servicePermissaoProj->permissaoNoProjeto($model);
                }

                $db->commit();

                return $model;
            } catch (Exception $exc) {
                $this->errors = $exc->getMessage();
                $db->rollBack();
                return false;
            }
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
    public function update($dados)
    {

        $form = $this->getFormEditar();
        if ($form->isValid($dados)) {
            $servicePermissaoProj = new Projeto_Service_PermissaoProjeto();

            $model = new Projeto_Model_Parteinteressada($form->getValues());
            $servicePermissaoProj->permissaoNoProjeto($model);

            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function verificaParteInteressadaInternaByProjeto($params)
    {
        return $this->_mapper->verificaParteInteressadaInternaByProjeto($params);
    }

    public function buscaParteInteressadaInterna($params)
    {
        return $this->_mapper->buscaParteInteressadaInterna($params);
    }

    public function verificaParteByProjeto($params)
    {
        return $this->_mapper->verificaParteByProjeto($params);
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function updateInterno($dados)
    {
        try {
            $db = $this->_mapper->getDbTable()->getAdapter();

            $db->beginTransaction();

            $servicePermissaoProj = new Projeto_Service_PermissaoProjeto();
            $serviceGerencia = new Projeto_Service_Gerencia();

            $model = new Projeto_Model_Parteinteressada($dados);

            $parteAntiga = $this->retornaPorId(array('idparteinteressada' => $model->idparteinteressada));

            $idparteinteressadafuncaoAntiga = explode(",", $parteAntiga['idparteinteressadafuncao']);

            $parte = $this->_mapper->updateParte($model);

            $idparteinteressadafuncao = $model->idparteinteressadafuncao;

            $projeto = array();

            $modelProjeto = $serviceGerencia->getById($dados);

            foreach ((array)$idparteinteressadafuncao as $item) {

                if ($item == Projeto_Model_Parteinteressada::GERENTE_PROJETO) {
                    $projeto['idprojeto'] = $dados['idprojeto'];
                    $projeto['idgerenteprojeto'] = $model->idpessoainterna;

                    $serviceGerencia->atualizaParteInteressada($dados['idprojeto'], $modelProjeto->idgerenteprojeto,
                        $model->idpessoainterna, 1);
                }

                if ($item == Projeto_Model_Parteinteressada::GERENTE_ADJUNTO) {
                    $projeto['idprojeto'] = $dados['idprojeto'];
                    $projeto['idgerenteadjunto'] = $model->idpessoainterna;
//
                    $serviceGerencia->atualizaParteInteressada($dados['idprojeto'], $modelProjeto->idgerenteadjunto,
                        $model->idpessoainterna, 2);
                }

                if ($item == Projeto_Model_Parteinteressada::DEMANDANTE) {
                    $projeto['idprojeto'] = $dados['idprojeto'];
                    $projeto['iddemandante'] = $model->idpessoainterna;

                    $serviceGerencia->atualizaParteInteressada($dados['idprojeto'], $modelProjeto->iddemandante,
                        $model->idpessoainterna, 3);
                }

                if ($item == Projeto_Model_Parteinteressada::PATROCINADOR) {
                    $projeto['idprojeto'] = $dados['idprojeto'];
                    $projeto['idpatrocinador'] = $model->idpessoainterna;

                    $serviceGerencia->atualizaParteInteressada($dados['idprojeto'], $modelProjeto->idpatrocinador,
                        $model->idpessoainterna, 4);
                }

                if (in_array($item, array(
                    Projeto_Model_Parteinteressada::PARTE_INTERESSADA,
                    Projeto_Model_Parteinteressada::EQUIPE_PROJETO
                ))) {

                    $params = array(
                        'idparteinteressadafuncao' => $item,
                        'idparteinteressada' => $model->idparteinteressada
                    );
                    $this->removerParteInteressadaFuncoes($params);

                    $this->inserirParteInteressadaFuncoes($params);
                }

            }

            foreach ($idparteinteressadafuncaoAntiga as $item) {
                if (!isset($projeto['idgerenteprojeto']) && $item == Projeto_Model_Parteinteressada::GERENTE_PROJETO) {
                    $serviceGerencia->atualizaParteInteressada($dados['idprojeto'],
                        $model->idpessoainterna, 0, 1);
                }

                if (!isset($projeto['idgerenteadjunto']) && $item == Projeto_Model_Parteinteressada::GERENTE_ADJUNTO) {
                    $serviceGerencia->atualizaParteInteressada($dados['idprojeto'],
                        $model->idpessoainterna, 0, 2);
                }

                if (!isset($projeto['iddemandante']) && $item == Projeto_Model_Parteinteressada::DEMANDANTE) {
                    $serviceGerencia->atualizaParteInteressada($dados['idprojeto'],
                        $model->idpessoainterna, 0, 3);
                }

                if (!isset($projeto['idpatrocinador']) && $item == Projeto_Model_Parteinteressada::PATROCINADOR) {
                    $serviceGerencia->atualizaParteInteressada($dados['idprojeto'],
                        $model->idpessoainterna, 0, 4);

                }
            }

            if (!count($projeto) > 0) {
                $servicePermissaoProj->permissaoNoProjeto($model);
            }

            $db->commit();

            return $parte;
        } catch (Exception $e) {
            $db->rollBack();
            echo 'Exceção capturada: ', $e->getLine(), "\n" . " - " . $e->getMessage(), "\n";
        }
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function updateExterno($dados)
    {
        $form = $this->getFormExterno();

        $idparteinteressadafuncao = $dados['idparteinteressadafuncaoexterno'];

        $dados['idparteinteressadafuncaoexterno'] = '5';

        unset($dados['idparteinteressadafuncao']);
        if ($form->isValid($dados)) {
            //tratamento de array para persistencia
            $dados['tppermissao'] = null;

            $dataForm = $this->trataToPersist($form->getValidValues($dados));

            $dataForm['idparteinteressada'] = $dados['idparteinteressadaexterno'];

            $model = $this->_mapper->updateParte($dataForm);

            $params = array(
                'idparteinteressada' => $dataForm['idparteinteressada']
            );

            $this->removerParteInteressadaFuncoes($params, true);

            foreach ((array)$idparteinteressadafuncao as $item) {
                if (in_array($item, array(
                    Projeto_Model_Parteinteressada::PARTE_INTERESSADA,
                    Projeto_Model_Parteinteressada::EQUIPE_PROJETO
                ))) {
                    $params = array(
                        'idparteinteressadafuncao' => $item,
                        'idparteinteressada' => $dados['idparteinteressadaexterno']
                    );

                    $this->inserirParteInteressadaFuncoes($params);
                }
            }


            return $model;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }


    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        $servicoGerencia = new Projeto_Service_Gerencia();
        try {
            //exclui a parte interessada
            $parteExcluir = $this->_mapper->getParteInteressada($dados);

            if (null != $parteExcluir['idpessoainterna']) {
                $servicoGerencia->removeParteInteressadaPorIdPessoaNoTAP($parteExcluir);
            }

            return $this->_mapper->excluir($dados);
        } catch (Zend_Db_Statement_Exception $exc) {
            if ($exc->getCode() == 23503) {
                $this->errors = App_Service_ServiceAbstract::ERRO_VIOLACAO_FK_CODE_23503;
            }
        } catch (Exception $exc) {
            $this->errors = $exc->getMessage();
            return false;
        }
    }

    /**
     * @param array $dados
     */
    public function updatePartes($dados)
    {
        return $this->_mapper->updatePartes($dados);
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function verificarPartesPorProjeto($dados)
    {
        return $this->_mapper->verificarPartesPorProjeto($dados);
    }

    /**
     *
     * @param array $params
     * @return boolean
     */
    public function verificaParteInteressadaByProjeto($dados)
    {
        $resultado = $this->_mapper->verificaParteInteressadaByProjeto($dados);
        if ($resultado[0]['total'] > 0) {
            return true;
        }
        return false;
    }

    public function getByProjeto($dados)
    {
        return $this->_mapper->getByProjeto($dados);
    }

    public function isParteInteressada($params)
    {
        return $this->_mapper->isParteInteressada($params);
    }


    public function retornaParteInteressadaByProjeto($dados, $model)
    {
        return $this->_mapper->retornaParteInteressadaByProjeto($dados, $model);
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

    public function buscarParteInteressadaInterna($params)
    {
        return $this->_mapper->buscaParteInteressadaInterna($params);
    }

    public function fetchPairsPorProjeto($params, $selecione = true)
    {
        $resultado = $this->_mapper->fetchPairsPorProjeto($params);
        $retorno = array();

        if ($selecione) {
            $retorno[''] = 'Selecione';
        }

        foreach ($resultado as $key => $value) {
            $retorno[$key] = $value;
        }
        return $retorno;
    }

    public function getParteInteressada($dados)
    {
        return $this->_mapper->getParteInteressada($dados);
    }

    public function retornaPorId($params, $model = false)
    {
        return $this->_mapper->retornaPorId($params, $model);
    }

    public function retornaPartes($params, $model = false)
    {
        return $this->_mapper->retornaPartes($params, $model);
    }

    public function retornaPartesInternas($params, $model)
    {
        return $this->_mapper->retornaPartesInternas($params, $model);
    }

    public function retornaPessoaPorIdPessoaInterna($params)
    {
        $servicePessoa = new Default_Service_Pessoa();
        return $servicePessoa->retornaPorId($params);
    }


    public function retornaPartesGrid($params)
    {
        $dados = $this->_mapper->retornaPartesGrid($params);
        $service = new App_Service_JqGrid();
        $service->setPaginator($dados);
        return $service;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function getParteInteressadaGrid($params, $paginator)
    {
        $dados = $this->_mapper->parteInteressadaGrid($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    /**
     * Altera o nome da chave do array para poder popular o form
     * @return array - $arrData
     */
    public function alteraKeyArray($data)
    {
        $arrData = array();
        foreach ($data as $key => $value) {
            $strElement = $key . 'externo';
            $arrData[$strElement] = $value;
        }
        return $arrData;
    }

    /**
     * Altera o nome da chave do array para persistir os dados
     * @return array - $arrData
     */
    public function trataToPersist($data)
    {
        $arrData = array();
        foreach ($data as $key => $value) {
            $strElement = str_replace('externo', '', $key);
            $arrData[$strElement] = $value;
        }
        return $arrData;
    }

    public function retornaIdPartaPorprojeto($params)
    {
        return $this->_mapper->retornaIdPartaPorprojeto($params);
    }

    public function getFuncaoProjeto($interno = true)
    {
        $mapper = new Projeto_Model_Mapper_ParteinteressadaFuncao();

        $array = array();

        foreach ($mapper->getAll($interno) as $item) {
            $array[$item['idparteinteressadafuncao']] = $item['nomfuncao'];
        }

        return $array;
    }

    public function getPermicoes()
    {
        return array(
            '' => 'Selecione',
            '1' => 'Editar',
            '2' => 'Visualizar',

        );
    }

    public function retornaCombofuncao($params)
    {
        return $this->_mapper->retornaFuncaoPorprojeto($params);
    }

    public function inserirParteInteressada($model)
    {
        return $this->_mapper->insert($model);
    }

    public function inserirParteInteressadaFuncoes($params)
    {
        $mapper = new Projeto_Model_Mapper_ParteinteressadaFuncoes();
        return $mapper->insert($params);
    }

    public function removerParteInteressadaFuncoes($params, $all = false)
    {
        $mapper = new Projeto_Model_Mapper_ParteinteressadaFuncoes();

        if ($all) {
            return $mapper->deleteAll($params);
        }

        return $mapper->delete($params);
    }

    public function atualizarFuncaoRhProjeto($params)
    {
        return $this->_mapper->atualizarFuncaoRhProjeto($params);
    }
}