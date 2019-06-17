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
     * @var Projeto_Service_AtividadeCronograma
     */
    protected $serviceAtividadeCronograma = null;


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
        $this->serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
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
            //Zend_Debug::dump($model);exit;
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

    public function getErrors()
    {
        return $this->errors;
    }

    public function retornaAtividadeSucessora($params)
    {
        return $this->_mapper->retornaAtividadePorPredec($params);
    }


    public function retornaPorAtividade($params)
    {
        return $this->_mapper->retornaPorAtividade($params);
    }

    public function retornaTodasPredecessorasPorIdAtividade($projeto, $idatividade)
    {
        return $this->_mapper->retornaTodasPredecessorasPorIdAtividade($projeto, $idatividade);
    }

    public function retornaDataMaiorPredecessora($params)
    {
        return $this->_mapper->retornaDataMaiorPredecessora($params);
    }

    /* public function retornaMaiorDataPredecessoraByIdAtividade($params){
         return $this->_mapper->retornaMaiorDataPredecessoraByIdAtividade($params);
     }*/

    public function retornaPredecePorIdAtividade($params)
    {
        return $this->_mapper->retornaPredecePorIdAtividade($params);
    }

    public function retornaDataMaiorPredecessoras($params)
    {
        return $this->_mapper->retornaDataMaiorPredecessoras($params);
    }

    public function retoraAtividadePorId($idatividade, $idProjeto)
    {
        return $this->_mapper->retoraAtividadePorId($idatividade, $idProjeto);
    }


    /*
     * Rotina de atualização de Atividde Predecessora
     * @param $idProjeto
     */
    public function atualizarAtividadePredecessoraByProjeto($idProjeto)
    {

        $mostrAtividad = $this->serviceAtividadeCronograma->retornaAtividadePorProjeto(['idprojeto' => $idProjeto]);

        //Zend_Debug::dump($mostrAtividad);exit();

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
                            //Zend_Debug::dump('Id atividade '. $idAtividadeCrono.' Data fim predec '.$dtFim. ' Data inicio atividades '.$retornaAtivi['datinicio'] .' Numero de folga '. $numerodeFolga. ' Data inicio calculada com a folga '.$datIniComFolga. ' Numero de dias '.$numDiaRealizado. ' Data final calculada '.$dtFimReal );
                            if ($atualizarMultPrede) {
                                $update = $atualizarMultPrede->update($dadosReal, $where);
                                //Zend_Debug::dump($update);exit;
                            }
                        }
                    }
                }
            }
        }
    }

}

?>

