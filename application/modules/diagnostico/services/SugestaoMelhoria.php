<?php

class Diagnostico_Service_SugestaoMelhoria extends App_Service_ServiceAbstract
{
    protected $_form;
    /**
     *
     * @var Diagnostico_Model_Mapper_SugestaoMelhoria
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Diagnostico_Model_Mapper_SugestaoMelhoria();
    }

    /**
     * @return Diagnostico_Form_SugestaoMelhoria
     */
    public function getFormSugestaoMelhoria($request)
    {
        $this->_form = new Diagnostico_Form_SugestaoMelhoria();
        $objetivo = new Default_Model_Mapper_Objetivo();
        $acaoEstrategica = new Default_Model_Mapper_Acao();
        $this->_form->getElement('idmacroprocessotrabalho')->addMultiOptions(
            array('' => 'Selecione') + $this->getAllMacroprocesso()
        );
        $this->_form->getElement('idmacroprocessomelhorar')->addMultiOptions(
            array('' => 'Selecione') + $this->getAllMacroprocesso()
        );
        $this->_form->getElement('idareamelhoria')->addMultiOptions(
            array('' => 'Selecione') + $this->getAreaMelhoria()
        );
        $this->_form->getElement('idsituacao')->addMultiOptions(
            array('' => 'Selecione') + $this->getSituacao()
        );
        $this->_form->getElement('flaabrangencia')->addMultiOptions(
            array('' => 'Selecione') + $this->getAbrangencia()
        );
        $this->_form->getElement('idunidaderesponsavelproposta')->addMultiOptions(
            array('' => 'Selecione') + $this->getUnidadesVinculadas(array('iddiagnostico' => $request['iddiagnostico']))
        );
        if ($request["action"] == "cadastrar") {
            $this->_form->getElement('idmelhoria')->setValue($this->_mapper->getIdMelhoria()["nextval"]);
        }
        $this->_form->getElement('idunidaderesponsavelimplantacao')->addMultiOptions(
            array('' => 'Selecione')
        );
        $this->_form->getElement('idobjetivoinstitucional')->addMultiOptions(
            array('' => 'Selecione') + $objetivo->fetchPairs()
        );
        if (isset($request['idunidadeprincipal'])) {
            $this->_form->getElement('unidadegestora')->setValue($this->getNomeUnidade($request['idunidadeprincipal'])['sigla']);
        }
        if (isset($request['iddiagnostico'])) {
            $this->_form->getElement('idunidadeprincipal')->setValue(
                $this->getUnidadePrincipal($request['iddiagnostico'])['idunidadeprincipal']
            );
            $this->_form->getElement('unidadegestora')->setValue(
                $this->getNomeUnidade($this->getUnidadePrincipal($request['iddiagnostico'])['idunidadeprincipal'])['sigla']
            );
        }
        return $this->_form;
    }

    public function getFormPadronizacaoMelhoria($request)
    {
        $this->_form = new Diagnostico_Form_PadronizacaoMelhoria();
        $this->_form->getElement('desrevisada')->setValue($request->desmelhoria);
        $this->_form->getElement('idmelhoria')->setValue($request->id);
        $this->_form->getElement('idpadronizacaomelhoria')->setValue($request->idpadronizacaomelhoria);
        $this->_form->getElement('idprazo')->addMultiOptions(
            array('' => 'Selecione') + $this->getPrazo()
        );
        $this->_form->getElement('idimpacto')->addMultiOptions(
            array('' => 'Selecione') + $this->getImpacto()
        );
        $this->_form->getElement('idesforco')->addMultiOptions(
            array('' => 'Selecione') + $this->getEsforco()
        );
        $this->_form->getElement('flaagrupadora')->addMultiOptions(
            array('' => 'Selecione') + $this->getAgrupadora()
        );
        $this->_form->getElement('desmelhoriaagrupadora')->addMultiOptions(
            array('' => 'Selecione') + $this->_mapper->getMelhoriaAgrupadora()
        );
        if (isset($request->idmelhoria)) {
            $pontuacao = $this->retornaPontuacao($request->idmelhoria);
        }
        return $this->_form;
    }

    public function getFormPesquisar()
    {
        $this->_form = new Diagnostico_Form_SugestaoMelhoriaPesquisar();
        $this->_form->getElement('idmacroprocessotrabalho')->addMultiOptions(
            array('' => 'Selecione') + $this->getAllMacroprocesso()
        );
        $this->_form->getElement('idmacroprocessomelhorar')->addMultiOptions(
            array('' => 'Selecione') + $this->getAllMacroprocesso()
        );
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function retornaPontuacao($params)
    {
        try {
            $id = array(
                'idmelhoria' => $params,
            );

            $padronizacao = new Diagnostico_Model_Mapper_PadronizacaoMelhoria();
            $result = $padronizacao->getByPontuacao($id);

            if ($result > 0) {
                $resultPont = (($result["idprazo"] * $result["idimpacto"] * $result["idesforco"]) / 48);
                return round($resultPont, 2);
            } else {
                $resultPad = null;
                return $resultPad;
            }
        } catch (Exception $e) {
            return 0;
        }
    }

    public function fetchPairs($params = array())
    {
        if (count($params) <= 0) {
            $retorno = array(
                '' => 'Todas'
            );
            $options = $this->_mapper->fetchPairs();
            foreach ($options as $i => $val) {
                $retorno[$i] = $val;
            }
            return $retorno;
        }
        return $this->_mapper->fetchPairs($params);
    }

    /**
     *
     * @param array $params - parametros do request
     * @return boolean|\App_Service_JqGrid
     */
    public function retornaSugestaoMelhoriaByDiagnostico($params = null)
    {
        $this->permissaoPerfil($params);

        try {
            $dados = $this->_mapper->retornaPorDiagnosticoToGrid($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function getByIdDetalhar($params)
    {
        $sugestaoMelhoria = $this->_mapper->getByIdDetalhar($params);
        return $sugestaoMelhoria;
    }

    public function insert($dados)
    {
        $model = new stdClass();
        $modelSugestaoMelhoria = new Diagnostico_Model_SugestaoMelhoria();
        $modelSugestaoMelhoria->setFromArray($dados);
        $modelSugestaoMelhoria->idcadastrador = $this->auth->idpessoa;
        $model->idmelhoria = $this->_mapper->insert($modelSugestaoMelhoria);
        return $model;
    }

    public function insertParonizacaoMelhoria($dados)
    {
        $model = new stdClass();
        $mapperPadronizacao = new Diagnostico_Model_Mapper_PadronizacaoMelhoria();
        $modelPadronizacaoMelhoria = new Diagnostico_Model_PadronizacaoMelhoria();
        $modelPadronizacaoMelhoria->setFromArray($dados);
        $modelPadronizacaoMelhoria->idcadastrador = $this->auth->idpessoa;
        $model->idmelhoria = $mapperPadronizacao->insert($modelPadronizacaoMelhoria);
        return $model;
    }

    public function update($dados)
    {
        $model = new Diagnostico_Model_SugestaoMelhoria($dados);
        $retorno = $this->_mapper->update($model);
        return $retorno;
    }

    public function updatePadronizacaoMelhoria($dados)
    {
        $model = new Diagnostico_Model_PadronizacaoMelhoria($dados);
        $mapperPadronizacao = new Diagnostico_Model_Mapper_PadronizacaoMelhoria();
        $retorno = $mapperPadronizacao->update($model);
        return $retorno;
    }

    public function excluir($params)
    {
        $padronizacao = new Diagnostico_Model_Mapper_PadronizacaoMelhoria();
        $result = $padronizacao->getByIdPadronizacao($params);

        if ($result > 0) {
            $resultPad = $padronizacao->deletePadronizacao($params);

            $this->_mapper->delete($params);
            return true;
        } else {
            $this->_mapper->delete($params);
            return true;
        }
    }

    /**
     * Relacao de perfis que podem ver Sugestão de Melhorias
     *
     * @param array $params - ponteiro para o array de parametros
     * @return void
     */
    public function permissaoPerfil(&$params)
    {
        $perfisPermissao = array(
            Default_Model_Perfil::ADMINISTRADOR_GEPNET,
            Default_Model_Perfil::GERENTE_DE_PROJETOS,
        );

        if (in_array($this->auth->perfilAtivo->idperfil, $perfisPermissao)) {
            $params['ver_nao_aprovados'] = true;
        }
    }

    public function getAllMacroprocesso()
    {
        $macroprocesso = new Processo_Model_Mapper_Processo();
        return $macroprocesso->fetchPairs(array());
    }

    public function getAreaMelhoria()
    {
        return $this->_mapper->getAreaMelhoria();
    }

    public function getSituacao()
    {
        return $this->_mapper->getSituacao();
    }

    public function getAbrangencia()
    {
        return $this->_mapper->getAbrangencia();
    }

    public function getPrazo()
    {
        return $this->_mapper->getPrazo();
    }

    public function getImpacto()
    {
        return $this->_mapper->getImpacto();
    }

    public function getEsforco()
    {
        return $this->_mapper->getEsforco();
    }

    public function getAgrupadora()
    {
        return $this->_mapper->getAgrupadora();
    }

    public function retornaPorIdMelhoria($params)
    {
        $sugestaoMelhoria = $this->_mapper->retornaPorIdMelhoria($params);
        return $sugestaoMelhoria;
    }

    public function getUnidadesFilhas($idDiagnostico)
    {
        $unidade = new Diagnostico_Model_Mapper_Diagnostico();
        /** Busca o id da unidade principal do diagnóstico */
        $idPai = $unidade->getById(array('iddiagnostico' => $idDiagnostico))->idunidadeprincipal;
        /** Lista as unidades filhas do diagnóstico */
        $arrayFilha = [];
        foreach ($unidade->getUnidadesFilhas($idPai) as $p) {
            $arrayFilha[$p['id_unidade']] = $p['sigla'];
        }
        return $arrayFilha;
    }

    public function getAllDelegacias()
    {
        return $this->_mapper->getAllDelegacia();
    }

    public function getUnidadesVinculadas($getAllParams)
    {
        $service = new Diagnostico_Model_Mapper_Diagnostico();
        return $service->getUnidadesVinculadas($getAllParams['iddiagnostico']);
    }

    public function quantidadeMelhoriaAgrupadora($desmelhoriaagrupadora)
    {
        $padronizacao = new Diagnostico_Model_Mapper_PadronizacaoMelhoria();
        return $padronizacao->quantidadeMelhoriaAgrupadora($desmelhoriaagrupadora);
    }

    public function getNomeUnidade($idUnidade)
    {
        $service = new Diagnostico_Model_Mapper_Diagnostico();
        return $service->getNomeUnidade($idUnidade);
    }

    public function getUnidadePrincipal($idDiagnostico)
    {
        return $this->_mapper->getUnidadePrincipal($idDiagnostico);
    }

    public function getMelhoriaToDiagnostico($idDiagnostico)
    {
        return $this->_mapper->getMelhoriaToDiagnostico($idDiagnostico);
    }

}

