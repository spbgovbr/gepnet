<?php

class Projeto_Service_AtividadePredecessora extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Atividadepredecessora
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
        $this->_mapper = new Projeto_Model_Mapper_Atividadepredecessora();
    }

    /**
     * @return Projeto_Form_AtividadeCronogramaMarco
     */
    public function getForm()
    {
        throw new Exception('metodo nao implementado.');
    }

    public function inserir($dados)
    {

        try {
            $model = new Projeto_Model_Atividadepredecessora($dados);
            $this->_mapper->insert($model);
            return $model;
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
        return false;
    }

    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            //$model = new Default_Model_Gerencia($dados);
            return $this->_mapper->excluir($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function excluirPorProjeto($dados)
    {
        try {
            //$model = new Default_Model_Gerencia($dados);
            return $this->_mapper->excluirPorProjeto($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function excluirPorAtividade($dados)
    {
        try {
            return $this->_mapper->excluirPorAtividade($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function retornaAtividadeSucessora($params, $array = true)
    {
        return $this->_mapper->retornaAtividadePorPredec($params, $array);
    }

    public function retornaAtividadeCountPredec($params)
    {
        return $this->_mapper->retornaAtividadeCountPredec($params);
    }

    public function retornaAtividadeCountPredecEntrega($params)
    {
        return $this->_mapper->retornaAtividadeCountPredecEntrega($params);
    }

    public function retornaAtividadeCountPredecGrupo($params)
    {
        return $this->_mapper->retornaAtividadeCountPredecGrupo($params);
    }

    public function retornaPorAtividade($params)
    {
        return $this->_mapper->retornaPorAtividade($params);
    }

    public function fetchPairsPorAtividade($params)
    {
        return $this->_mapper->fetchPairsPorAtividade($params);
    }

    public function listaPorAtividade($params)
    {
        return $this->_mapper->listaPorAtividade($params);
    }

    public function retornaTodasPredecessorasPorIdAtividade($projeto, $idatividade)
    {
        return $this->_mapper->retornaTodasPredecessorasPorIdAtividade($projeto, $idatividade);
    }

    public function retornaDataMaiorPredecessora($params)
    {
        return $this->_mapper->retornaDataMaiorPredecessora($params);
    }

    public function retornaMaiorDataPredecessoraByIdAtividade($params)
    {
        return $this->_mapper->retornaMaiorDataPredecessoraByIdAtividade($params);
    }

    public function retornaPredecePorIdAtividade($params)
    {
        return $this->_mapper->retornaPredecePorIdAtividade($params);
    }

    public function pesquisaPredecessoraAtividade($params)
    {
        return $this->_mapper->pesquisaPredecessoraAtividade($params);
    }

    public function retornaDataMaiorPredecessoras($params)
    {
        return $this->_mapper->retornaDataMaiorPredecessoras($params);
    }

    public function retoraAtividadePorId($idatividade, $idProjeto)
    {
        return $this->_mapper->retoraAtividadePorId($idatividade, $idProjeto);
    }

    public function update($params)
    {
        return $this->_mapper->update($params);
    }

    /*
     * Rotina de atualização de Atividde Predecessora
     * @param $idProjeto
     */
    public function atualizarAtividadePredecessoraByProjeto($idProjeto)
    {

        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();

        $mostrAtividad = $serviceAtividadeCronograma->retornaAtividadePorProjeto(['idprojeto' => $idProjeto]);

        $contarAtivida = count($mostrAtividad);

        if ($contarAtivida > 0) {

            $idAtivPred = '';
            for ($i = 0; $i < $contarAtivida; $i++) {

                $idAtivPred = $mostrAtividad[$i]['idatividadecronograma'];

                $retorgaProjeto = $this->retornaPredecePorIdAtividade([
                    'idatividadecronograma' => $idAtivPred,
                    'idprojeto' => $idProjeto
                ]);

                foreach ($retorgaProjeto as $retorgaProjetos) {
                    $idPredecessora = $retorgaProjetos['idatividadepredecessora'];
                    $retornaPredec = $this->retornaDataMaiorPredecessoras([
                        'idatividadecronograma' => $idPredecessora,
                        'idprojeto' => $idProjeto
                    ]);

                    foreach ($retornaPredec as $retornaPredecs) {
                        $idatividade = $retorgaProjetos['idatividade'];
                        $retornaAtividade = $this->retoraAtividadePorId($idatividade, $idProjeto);
                        foreach ($retornaAtividade as $retornaAtividades) {
                            $idAtividadeCrono = $retornaAtividades['idatividadecronograma'];
                            $numDiaRealizado = $retornaAtividades['numdiasrealizados'];
                            $dataInicioAtividade = $retornaAtividades['datinicio'];
                            $dataFimatividade = $retornaAtividades['datfim'];
                            $numerodeFolga = $retornaAtividades['numfolga'];
                            $precentualAtiv = $retornaAtividades['numpercentualconcluido'];
                            //calculando folga com data fim
                            $dtFim = $retornaPredecs['datfim'];
                            //separando a data para o calculo predecessora
                            $diaF = substr($dtFim, 0, 2);
                            $mesF = substr($dtFim, 3, 2);
                            $anoF = substr($dtFim, 6, 4);
                            //retornando a data fim com o numero de folga calculado
                            $datIniComFolga = date('Y-m-d', mktime(0, 0, 0, $mesF, $diaF + $numerodeFolga, $anoF));
                            // Calculando a nova data final para a sucessora
                            $diaA = substr($datIniComFolga, 8, 2);
                            $mesA = substr($datIniComFolga, 5, 2);
                            $anoA = substr($datIniComFolga, 0, 4);
                            // retornando a data fim com o numero calculado
                            $dtFimReal = date('Y-m-d', mktime(0, 0, 0, $mesA, $diaA + $numDiaRealizado, $anoA));
                            $dadosReal = array(
                                'datinicio' => $datIniComFolga,
                                'datfim' => $dtFimReal,
                                'numpercentualconcluido' => $precentualAtiv
                            );

                            $atualizarMultPrede = new Default_Model_DbTable_Atividadecronogramas();
                            $where = $atualizarMultPrede->getAdapter()->quoteInto('idatividadecronograma = ?',
                                $idAtividadeCrono);
                            if ($atualizarMultPrede) {
                                $update = $atualizarMultPrede->update($dadosReal, $where);
                            }
                        }
                    }
                }
            }
        }
    }


    /*
    * Rotina de atualização de Atividde Predecessora
    * @param $idProjeto
    * @array $atividadeSucessoras
    * @array $atividadeAtualizada
    */
    public function atualizarAtividadeSucessora(
        $idProjeto,
        $atividadeSucessoras,
        Projeto_Model_Atividadecronograma $atividadeAtualizada
    ) {

        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $cont = count($atividadeSucessoras);
        $i = 1;
        foreach ($atividadeSucessoras as $atividadeSucessora) {
            //busca atividade sucessora
            if ($i <= $cont) {
                //Zend_Debug::dump('##################### Inicio #########################');
                $arrayAtividade = null;
                $modelAtividadeSucessora = null;
                $entityAtividade = null;
                $novaDataInicio = null;
                $novaDataFim = null;
                $dados = array(
                    'idprojeto' => $idProjeto,
                    'idatividadecronograma' => $atividadeSucessora['idatividadecronograma']
                );

                $maiorDataPredecessora = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->retornaInicioBaseLinePorAtividade($dados));

                $arrayAtividade = $serviceAtividadeCronograma->getAtividadeById($atividadeSucessora['idatividadecronograma'],
                    $idProjeto);

                $modelAtividadeSucessora = new Projeto_Model_Atividadecronograma($arrayAtividade);

                if ($serviceAtividadeCronograma->compararDadas($atividadeAtualizada->datfim->format('d/m/Y'),
                    $maiorDataPredecessora->format('d/m/Y'))) {

                    //verificar se os dias reais da atividade é maior que zero
                    if ($modelAtividadeSucessora->numfolga >= 0) {
                        $modelAtividadeSucessora->datinicio = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->adicionarDias($atividadeAtualizada->datfim->format('d/m/Y'),
                            $modelAtividadeSucessora->numfolga));
                    } elseif ($modelAtividadeSucessora->numfolga < 0) {
                        $modelAtividadeSucessora->datinicio = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->subtrairDias($atividadeAtualizada->datfim->format('d/m/Y'),
                            $modelAtividadeSucessora->numfolga));
                    }

                    //verificar se os dias reais da atividade sucessora é maior que zero
                    if ($arrayAtividade['numdiasrealizados'] >= 0) {
                        //soma os dias reais a data inicio da atividade sucessora
                        $modelAtividadeSucessora->datfim = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->adicionarDias($modelAtividadeSucessora->datinicio->format('d/m/Y'),
                            $arrayAtividade['numdiasrealizados']));
                    } else {
                        $modelAtividadeSucessora->datfim = $novaDataFim;
                    }

                } else {

                    if ($modelAtividadeSucessora->numfolga >= 0) {
                        $modelAtividadeSucessora->datinicio = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->adicionarDias($maiorDataPredecessora->format('d/m/Y'),
                            $modelAtividadeSucessora->numfolga));
                    } elseif ($modelAtividadeSucessora->numfolga < 0) {
                        $modelAtividadeSucessora->datinicio = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->subtrairDias($maiorDataPredecessora->format('d/m/Y'),
                            $modelAtividadeSucessora->numfolga));
                    }

                    //verificar se os dias reais da atividade sucessora é maior que zero
                    if ($arrayAtividade['numdiasrealizados'] >= 0) {
                        //soma os dias reais a data inicio da atividade sucessora
                        $modelAtividadeSucessora->datfim = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->adicionarDias($modelAtividadeSucessora->datinicio->format('d/m/Y'),
                            $arrayAtividade['numdiasrealizados']));
                    } else {
                        $modelAtividadeSucessora->datfim = $novaDataFim;
                    }
                }

                $modelAtividadeSucessora->numdias = $modelAtividadeSucessora->retornaDiasReal();
                $modelAtividadeSucessora->numdiasrealizados = $arrayAtividade['numdiasrealizados'];
                $entityAtividade = $serviceAtividadeCronograma->atualizarMapperAtividade($modelAtividadeSucessora);
                $serviceAtividadeCronograma->atualizarDatasEntrega($modelAtividadeSucessora);
                $serviceAtividadeCronograma->atualizarPercentuaisGrupoEntrega(array(
                    'idprojeto' => $modelAtividadeSucessora->idprojeto,
                    'idatividadecronograma' => $modelAtividadeSucessora->idatividadecronograma
                ));
                //Fazer consulta na tabela atividadepredecessora para verificar se existe esta atividade sucessora
                if ($this->retornaAtividadeCountPredec($dados)) {
                    $entitySucessoras = $this->retornaAtividadeSucessora($dados);
                    $this->atualizarAtividadeSucessora($dados['idprojeto'], $entitySucessoras,
                        $modelAtividadeSucessora);
                }
                $i++;
            }
        }
    }

    /*
        * Rotina de atualização de Atividde Predecessora
        * @param $idProjeto
        * @array $atividadeSucessoras
        * @array $atividadeAtualizada
        */
    public function atualizarModelAtividadeSucessora(
        $idProjeto,
        $atividadeSucessoras,
        Projeto_Model_Atividadecronograma $atividadeAtualizada
    ) {
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $cont = count($atividadeSucessoras);
        $i = 1;
        foreach ($atividadeSucessoras as $atividadeSucessora) {
            //busca atividade sucessora
            if ($i <= $cont) {
                //Zend_Debug::dump('##################### Inicio #########################');
                $arrayAtividade = null;
                $modelAtividadeSucessora = null;
                $entityAtividade = null;
                $novaDataInicio = null;
                $novaDataFim = null;
                $dados = array(
                    'idprojeto' => $idProjeto,
                    'idatividadecronograma' => $atividadeSucessora->idatividadecronograma
                );
                $maiorDataPredecessora = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->retornaInicioBaseLinePorAtividade($dados));
                $arrayAtividade = $atividadeSucessora->toArray();
                $modelAtividadeSucessora = $atividadeSucessora;
                if ($serviceAtividadeCronograma->compararDadas($atividadeAtualizada->datfim->format('d/m/Y'),
                    $maiorDataPredecessora->format('d/m/Y'))) {
                    //verificar se os dias reais da atividade é maior que zero
                    if ($modelAtividadeSucessora->numfolga >= 0) {
                        $modelAtividadeSucessora->datinicio = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->adicionarDias($atividadeAtualizada->datfim->format('d/m/Y'),
                            $modelAtividadeSucessora->numfolga));
                    } elseif ($modelAtividadeSucessora->numfolga < 0) {
                        $modelAtividadeSucessora->datinicio = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->subtrairDias($atividadeAtualizada->datfim->format('d/m/Y'),
                            $modelAtividadeSucessora->numfolga));
                    }

                    //verificar se os dias reais da atividade sucessora é maior que zero
                    if ($arrayAtividade['numdiasrealizados'] >= 0) {
                        //soma os dias reais a data inicio da atividade sucessora
                        $modelAtividadeSucessora->datfim = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->adicionarDias($modelAtividadeSucessora->datinicio->format('d/m/Y'),
                            $arrayAtividade['numdiasrealizados']));
                    } else {
                        $modelAtividadeSucessora->datfim = $novaDataFim;
                    }

                } else {

                    if ($modelAtividadeSucessora->numfolga >= 0) {
                        $modelAtividadeSucessora->datinicio = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->adicionarDias($maiorDataPredecessora->format('d/m/Y'),
                            $modelAtividadeSucessora->numfolga));
                    } elseif ($modelAtividadeSucessora->numfolga < 0) {
                        $modelAtividadeSucessora->datinicio = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->subtrairDias($maiorDataPredecessora->format('d/m/Y'),
                            $modelAtividadeSucessora->numfolga));
                    }

                    //verificar se os dias reais da atividade sucessora é maior que zero
                    if ($arrayAtividade['numdiasrealizados'] >= 0) {
                        //soma os dias reais a data inicio da atividade sucessora
                        $modelAtividadeSucessora->datfim = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->adicionarDias($modelAtividadeSucessora->datinicio->format('d/m/Y'),
                            $arrayAtividade['numdiasrealizados']));
                    } else {
                        $modelAtividadeSucessora->datfim = $novaDataFim;
                    }
                }
                $modelAtividadeSucessora->numdias = $modelAtividadeSucessora->retornaDiasReal();
                $modelAtividadeSucessora->numdiasrealizados = $arrayAtividade['numdiasrealizados'];
                $entityAtividade = $serviceAtividadeCronograma->atualizarMapperAtividade($modelAtividadeSucessora);
                $serviceAtividadeCronograma->atualizarDatasEntrega($modelAtividadeSucessora);
                $serviceAtividadeCronograma->atualizarPercentuaisGrupoEntrega(array(
                    'idprojeto' => $modelAtividadeSucessora->idprojeto,
                    'idatividadecronograma' => $modelAtividadeSucessora->idatividadecronograma
                ));
                //Fazer consulta na tabela atividadepredecessora para verificar se existe esta atividade sucessora
                if ($this->retornaAtividadeCountPredec($dados)) {
                    $entitySucessoras = $this->retornaAtividadeSucessora($dados);
                    $this->atualizarAtividadeSucessora($dados['idprojeto'], $entitySucessoras,
                        $modelAtividadeSucessora);
                }
                $i++;
                //Zend_Debug::dump('##################### Fim #########################');
            }
        }
    }

    public function copiaPredecessorasByProjeto($dados)
    {
        return $this->_mapper->copiaPredecessorasByProjeto($dados);
    }

    public function addDias($data, $dias)
    {
        $dataRetorna = new Zend_Date($data);
        $dataRetorna->add('' . $dias . '', Zend_Date::DAY);
        return $dataRetorna->toString('d/m/Y');
    }

    private function preparaData($data)
    {
        $dt = explode("/", $data);
        $dataFormatada = $dt[2] . "-" . $dt[1] . "-" . $dt[0];
        $dataRetornada = DateTime::createFromFormat('Y-m-d', $dataFormatada);
        return $dataRetornada;
    }

}

?>

