<?php

class Diagnostico_Service_Estatistica extends App_Service_ServiceAbstract
{

    protected $_form;
    /**
     *
     * @var Diagnostico_Model_Mapper_Estatistica
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
        $this->_mapper = new Diagnostico_Model_Mapper_Estatistica();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Lista o resumo .
     * @param array $params
     * return array
     */
    public function resumo($params)
    {
        $arrayData = array(
            'unidadeDiagnosticadas' => $this->retornaQuantidadeUnidadeDiagnosticada($params),
            'pessoasEntrevistadas' => $this->retornaQuantidadePessoasEntrevistadas($params),
            'questionariosRespondidos' => $this->retornaQuantidadePessoasEntrevistadas($params),
            'stisfacaoServidor' => $this->retornaSomatorioEscalaLinkertTipoServidor($params),
            'satisfacaoCidadao' => $this->retornaSomatorioEscalaLinkertTipoCidadao($params)
        );
        $arrayData = $this->retornaQuantidadePessoaEntrevistadaPorCargo($params, $arrayData);
        $arrayData = $this->retornaMediaGeralSatisfacaoServidorPorSecao($params, $arrayData);
        $arrayData = $this->retornaMediaGeralSatisfacaoServidorPorMacroprocesso($params, $arrayData);
        $arrayData = $this->retornaMediaGeralSatisfacaoServidorPorCargo($params, $arrayData);

        return $arrayData;
    }

    public function listaDiagnosticoAll()
    {
        $arrayDiagnostico = new Diagnostico_Model_Mapper_Diagnostico();
        return $arrayDiagnostico->getAll();
    }

    /**
     * Funcão que retorna o quantitativo de unidade diagnosticada
     * @param $params
     * @return int
     */

    public function retornaQuantidadeUnidadeDiagnosticada($params)
    {
        $unidadesPrinciais = $this->_mapper->retornaQuantidadeUnidadePrincipalPorDiagnostico($params);
        $totalUnidadesSubordinadas = $this->_mapper->retornaQuantidadeUnidadeSubordinadaPorDiagnostico($params);
        $totalUnidadePrincial = $this->somatorioUnidadesPrincipais($unidadesPrinciais);
        $totalUnidadesSubordinadas = $this->somatorioUnidadesSubordinadas($totalUnidadesSubordinadas);
        $somatorio = $totalUnidadePrincial + $totalUnidadesSubordinadas;
        return $somatorio;
    }

    /**
     * Função que soma o quantitativo de unidades principais
     * @param array
     * @return int
     */
    private function somatorioUnidadesPrincipais($unidades)
    {
        $totalGeral = 0;
        if (is_array($unidades) && count($unidades) > 0) {
            foreach ($unidades as $unidade) {
                $totalGeral += $unidade['total_unidade_principal'];
            }
        }
        return $totalGeral;
    }

    /**
     * Função que soma o quantitativo de unidades subordinadas
     * @param array
     * @return int
     */
    private function somatorioUnidadesSubordinadas($unidadesSubordinadas)
    {
        $totalGeral = 0;

        if (is_array($unidadesSubordinadas) && count($unidadesSubordinadas) > 0) {
            foreach ($unidadesSubordinadas as $unidade) {
                $totalGeral += $unidade['total_unidade'];
            }
        }
        return $totalGeral;
    }

    /**
     * Função que retorna quantidade de pessoas entrevistadas
     * @param $params
     * @return int
     */
    public function retornaQuantidadePessoasEntrevistadas($params)
    {
        return $this->_mapper->retornaQuantidadeQuestionarioRespondido($params);
    }

    /**
     * Função que retorna quantidade de pessoas entrevistadas por cargo
     * @param $params
     * @param array $arr
     * @return array || int
     */
    public function retornaQuantidadePessoaEntrevistadaPorCargo($params, $arr)
    {

        $cargos = $this->_mapper->retornaQuantidadePessoaEntrevistadaPorCargo($params);

        $arr['entrevistadasCargo'] = array();
        if (is_array($cargos) && count($cargos) > 0) {
            foreach ($cargos as $cargo) {
                $arr['entrevistadasCargo'][] = $cargo;
            }
        }
        return $arr;
    }

    public function retornaSomatorioEscalaLinkertTipoServidor($params)
    {
        $params['tipo'] = '1';
        $params['tpquestionario'] = '1';

        $totalSomatorio = $this->_mapper->retornaSomatorioEscalaLinkertPorTipo($params);
        $qtdQuestRespondido = $this->_mapper->retornaQuantidadeQuestionarioRespondido($params);
        $total = 0;
        if ($totalSomatorio > 0) {
            $total = ($totalSomatorio / $qtdQuestRespondido);
        }
        return number_format($total, 1);
    }

    public function retornaSomatorioEscalaLinkertTipoCidadao($params)
    {
        $params['tipo'] = '2';
        $params['tpquestionario'] = '2';
        $totalSomatorio = $this->_mapper->retornaSomatorioEscalaLinkertPorTipo($params);
        $qtdQuestRespondido = $this->_mapper->retornaQuantidadeQuestionarioRespondido($params);
        $total = 0;
        if ($totalSomatorio > 0) {
            $total = ($totalSomatorio / $qtdQuestRespondido);
        }
        return number_format($total, 1);
    }

    public function retornaMediaGeralSatisfacaoServidorPorSecao($params, $arr)
    {
        $params['tipo'] = '1';
        $params['tpquestionario'] = '1';
        $secoes = $this->_mapper->retornaSomatorioEscalaLinkertPorTipoESecao($params);
        $qtdQuestRespondido = $this->_mapper->retornaQuantidadeQuestionarioRespondido($params);

        $arr['satisfacaoServidorSecao'] = array();
        if (is_array($secoes) && count($secoes) > 0) {
            foreach ($secoes as $secao) {
                if ($secao['valor'] > 0) {
                    $secao['valor'] = number_format(($secao['valor'] / $qtdQuestRespondido), 2);
                }
                $arr['satisfacaoServidorSecao'][] = $secao;
            }
        }
        return $arr;
    }

    public function retornaMediaGeralSatisfacaoServidorPorMacroprocesso($params, $arr)
    {
        $params['tipo'] = '1';
        $params['tpquestionario'] = '1';
        $macroprocessos = $this->_mapper->retornaSomatorioEscalaLinkertPorSecaoMacroprocesso($params);
        $qtdQuestRespondido = $this->_mapper->retornaQuantidadeQuestionarioRespondido($params);

        $arr['satisfacaoServidorMacroprocesso'] = array();
        if (is_array($macroprocessos) && count($macroprocessos) > 0) {
            foreach ($macroprocessos as $macroprocesso) {
                if ($macroprocesso['valor'] > 0) {
                    $macroprocesso['valor'] = number_format(($macroprocesso['valor'] / $qtdQuestRespondido), 2);
                }
                $arr['satisfacaoServidorMacroprocesso'][] = $macroprocesso;
            }
        }
        return $arr;
    }

    public function retornaMediaGeralSatisfacaoServidorPorCargo($params, $arr)
    {
        $params['tipo'] = '1';
        $params['tpquestionario'] = '1';
        $cargos = $this->_mapper->retornaSomatorioEscalaLinkertPorCargo($params);
        $qtdQuestRespondido = $this->_mapper->retornaQuantidadeQuestionarioRespondido($params);

        $arr['satisfacaoServidorCargo'] = array();
        if (is_array($cargos) && count($cargos) > 0) {
            foreach ($cargos as $cargo) {
                if ($cargo['valor'] > 0) {
                    $cargo['valor'] = number_format(($cargo['valor'] / $qtdQuestRespondido), 2);
                }
                $arr['satisfacaoServidorCargo'][] = $cargo;
            }
        }
        return $arr;
    }


}