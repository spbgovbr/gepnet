<?php

class Projeto_Service_Eap extends App_Service_ServiceAbstract
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

    public function editarEntrega($params)
    {
        $model = new Projeto_Model_Atividadecronograma($params);
        $atualiza = $this->_mapper->atualizarEntregaEap($model);

        if ($atualiza) {
            $this->_mapper->atualizaSucessoras($params);
            $this->_mapper->atualizarPercentualProjeto($params);
        }
        return $atualiza;

    }

    public function montaEAP($params)
    {

        $service = new Projeto_Service_AtividadeCronograma();
        $serviceGerencia = new Projeto_Service_Gerencia();
        $serviceParte = new Projeto_Service_Parteinteressada();

        try {
            $cronograma['cronograma'] = $service->retornaCronograma($params);
            /**@var Projeto_Model_Gerencia $projeto */
            $projeto = $serviceGerencia->retornaProjetoArrayPorId($params, false);
        } catch (Exception $exc) {
            var_dump($exc);
        }


        /************  Calcula os dias e percentuais de conclusão do Projeto   *************/

        $projetoArray = $projeto->toArray();

        $datas = $service->retornaDataPorProjeto($params);

        $projetoArray['nomprojeto'] = $projeto->nomprojeto;
        $projetoArray['datiniciobaseline'] = $datas['datiniciobaseline'];
        $projetoArray['datfimbaseline'] = $datas['datfimbaseline'];
        $projetoArray['datinicioReal'] = $datas['datinicio'];
        $projetoArray['datfimReal'] = $datas['datfim'];
        $projetoArray['vlratividadet'] = $datas['vlratividadet'];
        $projetoArray['numdiasbaseline'] = $datas['numdiasbaseline'];
        $projetoArray['numdiasrealizados'] = $datas['totaldiasrealizados'];


        $resultadoDiasProjeto = $projeto->retornarDiasEstimadosEReais();

        if ($resultadoDiasProjeto->estimativaTotalDias == 0) {
            $numpercentualprevisto = 0;
        } else {
            $numpercentualprevisto = round(100 * ($resultadoDiasProjeto->estimativaTotalDiasExecutados / $resultadoDiasProjeto->estimativaTotalDias));
        }
        if ($resultadoDiasProjeto->realTotalDias == 0) {
            $numpercentualconcluido = 0;
        } else {
            $numpercentualconcluido = round((100 * ($resultadoDiasProjeto->realTotalDiasExecutados / $resultadoDiasProjeto->realTotalDias)));
            $numpercentualconcluido = number_format($numpercentualconcluido, 0);
        }
        /*********************************************************************************/
        $serviceComentario = new Projeto_Service_Comentario();
        $datinicioprojeto = (@trim($projeto->datinicio) != "" ? $projeto->datinicio->toString('d/m/Y') : "");
        $datfimprojeto = (@trim($projeto->datfim) != "" ? $projeto->datfim->toString('d/m/Y') : "");
        $atrazoCabecalho = $projeto->retornaPrazoEmDiasCabecalho();
        $atrazoEmdias = $atrazoCabecalho->dias;
        $atrazodescricaoPrazo = $atrazoCabecalho->descricao;
        $projetoArray = $projeto->toArray();
        $projetoArray['datinicioprojeto'] = $datinicioprojeto;
        $projetoArray['datfimprojeto'] = $datfimprojeto;
        $projetoArray['nomeprojeto'] = mb_substr($projeto['nomcodigo'] . '--' . $projeto['nomprojeto'], 0, 40) . '...';
        $projetoArray['datiniciot'] = "";
        $projetoArray['datfimt'] = "";
        $projetoArray['diasbaselinet'] = $projeto['numdiasbaseline'];
        $projetoArray['numdiascompletost'] = $projeto['numdiascompletos'];
        $projetoArray['totaldiasbaselinet'] = $projeto['totaldiasbaseline'];
        $projetoArray['numpercentualprevistot'] = $projeto['numpercentualprevisto'];

        ### REMOVER PARA PUBLICACAO ###
        $projetoArray['estimativaTotalDias'] = $resultadoDiasProjeto->estimativaTotalDias;
        $projetoArray['estimativaTotalDiasExecutados'] = $resultadoDiasProjeto->estimativaTotalDiasExecutados;

        $projetoArray['realTotalDiasExecutados'] = $resultadoDiasProjeto->realTotalDiasExecutados;
        $projetoArray['realTotalDias'] = $resultadoDiasProjeto->realTotalDias;

        $projetoArray['vlratividadet'] = 0;
        $projetoArray['diasrealt'] = $projeto['numdiasrealizados'];
        $projetoArray['diasrealizadosreal'] = $projeto['numdiasrealizadosreal'];
        $projetoArray['numpercentualconcluidot'] = $numpercentualconcluido;
        $projetoArray['numpercentualprevistot'] = $numpercentualprevisto;
        $projetoArray['totalnumpercconcluidot'] = $numpercentualconcluido;

        if (count($cronograma['cronograma']) > 0) {
            $contEnt = 0;
            $contGr = 0;
            //$arrayCronograma['projeto']['grupos'] = array();
            $arrayGrupos = array();
            $arrayEntregas = array();
            $nivelAnt = null;
            foreach ($cronograma['cronograma'] as $key => $registro) {

                if ($nivelAnt == 1) {
                    $arrayEntregas = array();
                }

                switch ($registro['nivel']) {
                    case 1 :
                        $arrayGrupos[$registro['pai']]['idatividadecronograma'] = $registro['idatividadecronograma'];
                        $arrayGrupos[$registro['pai']]['nomatividadecronograma'] = $registro['nomatividadecronograma'];
                        $contGr++;
                        break;
                    case 2:
                        $arrayEntregas[$key]['idatividadecronograma'] = $registro['idatividadecronograma'];
                        $arrayEntregas[$key]['nomatividadecronograma'] = $registro['nomatividadecronograma'];
                        $arrayEntregas[$key]['numpercentualconcluido'] = $registro['numpercentualconcluido'];
                        $arrayEntregas[$key]['descriterioaceitacao'] = $registro['descriterioaceitacao'];
                        $arrayEntregas[$key]['numdiasrealizados'] = $registro['numdiasrealizados'];
                        $arrayEntregas[$key]['desobs'] = $registro['desobs'];
                        $arrayEntregas[$key]['domcoratraso'] = $registro['domcoratraso'];
                        $arrayEntregas[$key]['atraso'] = $registro['atraso'];
                        $arrayEntregas[$key]['numdiasrealizados'] = $registro['numdiasrealizados'];
                        $arrayEntregas[$key]['nomeresponsavelaceitacao'] = (!empty($registro['responsavelaceitacao'])) ? $registro['responsavelaceitacao'] : "";
                        $arrayEntregas[$key]['nomparteinteressada'] = (!empty($registro['nomparteinteressada'])) ? $registro['nomparteinteressada'] : "";
                        $contEnt++;
                        break;
                }

                $nivelAnt = $registro['nivel'];
                if ($nivelAnt == 2) {
                    $arrayGrupos[$registro['pai']]['entregas'] = $arrayEntregas;
                }
            }
            $arrayGrupos['contGrupo'] = $contGr;
            $arrayGrupos['contEntrega'] = $contEnt;
        }

        $datUltimaStatus = new Zend_Date($projetoArray['ultimoStatusReport']['datfimprojetotendencia'], 'd/m/Y');
        $projetoArray['ultimoStatusReport']['datfimprojetotendencia'] = $datUltimaStatus->toString('d/m/Y');
        $diasFarol = 0;
        $sinalFarol = "sucess";
        $totalDiasAtrasoFarol = 0;
        $descricaoAtrazoFarol = "default";
        $dadosAtraso = $service->calculaDiaAtrasoProjeto($projetoArray);

        if (is_object($dadosAtraso)) {
            $totalDiasAtrasoFarol = $dadosAtraso->totalDiasAtrasoFarol;
            $descricaoAtrazoFarol = $dadosAtraso->descricaoAtrazoFarol;
        }

        $projetoArray['numpercentualconcluido'] = $projeto->numpercentualconcluido;
        $projetoArray['numpercentualprevisto'] = $projeto->numpercentualprevisto;
        $projetoArray['prazoCabecalho'] = $totalDiasAtrasoFarol;
        $projetoArray['prazoEmDias'] = $atrazoEmdias;
        $projetoArray['descricaoPrazo'] = (int)$projeto->numpercentualconcluido == 100 ? "" : $projeto->retornaDescricaoPrazoCabecalho();
        $projetoArray['descricaoPrazoCabecalho'] = $projeto->retornaDescricaoPrazoCabecalho();
        $projetoArray['atrasoCabecalhoFarol'] = $totalDiasAtrasoFarol;
        $projetoArray['descricaoAtrasoFarol'] = $descricaoAtrazoFarol;
        if ((int)$projetoArray['numpercentualconcluido'] == 100) {
            $projetoArray['descricaoPrazo'] = "";
            $projetoArray['descricaoAtrasoFarol'] = "";
        } else {
            $projetoArray['descricaoAtrasoFarol'] = $descricaoAtrazoFarol;
        }
        $arrayCronograma['projeto'] = $projetoArray;
        $arrayCronograma['projeto']['grupos'] = $arrayGrupos;

        return $arrayCronograma;
    }


    public function getErrors()
    {
        return $this->errors;
    }

}


