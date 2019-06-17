<?php

use Default_Service_Log as Log;

class Diagnostico_Service_Sumario extends App_Service_ServiceAbstract
{
    /**
     *
     * @var Diagnostico_Model_Mapper_Sumario
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Diagnostico_Model_Mapper_Sumario();
    }

    /**
     * Funcão que retorna o quantitativo de questionario respondido
     * @param array $params
     * @return int
     */
    public function retornaQuantitativoQuestionarioRespondido($params)
    {
        return $this->_mapper->retornaQuantitativoQuestionarioRespondido($params);
    }

    /**
     * Funcão que retorna o somario do valor da escala de linkert das respotas por diagnostico
     * @param array $params
     * @return float
     */
    public function retornaSomatorioEscalaLinkertPorDiagnostico($params)
    {
        $somatorio = $this->_mapper->retornaSomatorioEscalaLinkertPorDiagnostico($params);
        return $somatorio;
    }

    /**
     * Funcão que retorna média geral de respostas numéricas  por diagnostico
     * @param array $params
     * @param int $qtdQuestResp
     * @return float
     */
    public function retornaMediaGeralRespNumericaPorDiagnostico($params, $qtdQuestResp)
    {
        $resultado = 0;
        $somatorio = $this->retornaSomatorioEscalaLinkertPorDiagnostico($params);

        if ($qtdQuestResp > 0) {
            $resultado = (float)($somatorio / $qtdQuestResp);
        }
        return $resultado;
    }

    public function retornaSomatorioEscalaLinkertPorResposta($params)
    {
        $resultado = $this->_mapper->retornaSomatorioEscalaLinkertPorResposta($params);
        return $resultado;
    }

    public function retornaMediaGeralRespNumericaPorResposta($params)
    {
        return $this->retornaSomatorioEscalaLinkertPorResposta($params);
    }


}