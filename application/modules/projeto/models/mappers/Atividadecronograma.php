<?php

use Default_Service_Log as Log;

/**
 * Automatically generated data model
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Atividadecronograma extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     * @param string $value
     * @return Projeto_Model_Atividadecronograma
     */
    public function inserirAtividade(Projeto_Model_Atividadecronograma $model, $inserePredecessora = true)
    {
        try {

            //$model->idatividadecronograma = $this->maxVal("idatividadecronograma");
            $maxAtividade = $this->maxVal("idatividadecronograma", "idprojeto = :idprojeto",
                array('idprojeto' => $model->idprojeto));
            $model->idatividadecronograma = $maxAtividade;
            $data = array(
                "idatividadecronograma" => $model->idatividadecronograma,
                "idprojeto" => $model->idprojeto,
                "idgrupo" => $model->idgrupo,
                "numpercentualconcluido" => $model->numpercentualconcluido,
                "nomatividadecronograma" => $model->nomatividadecronograma,
                "datiniciobaseline" => new Zend_Db_Expr("to_date('" . $model->datiniciobaseline->format('Y-m-d') . "','YYYY-MM-DD')"),
                "datfimbaseline" => new Zend_Db_Expr("to_date('" . $model->datfimbaseline->format('Y-m-d') . "','YYYY-MM-DD')"),
                "datinicio" => new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')"),
                "datfim" => new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')"),
                "datcadastro" => new Zend_Db_Expr("now()"),
                "domtipoatividade" => $model->domtipoatividade,
                "idresponsavel" => $model->idresponsavel,
                "idparteinteressada" => $model->idparteinteressada,
                "flacancelada" => $model->flacancelada,
                "flaaquisicao" => $model->flaaquisicao,
                "flainformatica" => $model->flainformatica,
                "flaordenacao" => $model->flaordenacao,
                "desobs" => mb_substr($model->desobs, 0, 4000),
                "idcadastrador" => $model->idcadastrador,
                "idmarcoanterior" => $model->idmarcoanterior,
                "numdias" => $model->numdias,
                "numdiasrealizados" => $model->numdiasrealizados,
                "numdiasbaseline" => $model->numdiasbaseline,
                "vlratividadebaseline" => $model->vlratividadebaseline,
                "vlratividade" => $model->vlratividade,
                "numfolga" => (int)$model->numfolga,
                "idelementodespesa" => $model->idelementodespesa,
            );
            $data = array_filter($data);
            if (!isset($data['numpercentualconcluido'])) {
                $data['numpercentualconcluido'] = 0;
            }
            if (!isset($data['numfolga'])) {
                $data['numfolga'] = (int)0;
            }

            $retorno = $this->getDbTable()->insert($data);

            return $model;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     * @param $params
     * @return int
     * @throws Exception
     */
    public function excluir($params)
    {
        try {
            $pks = array();
            if (isset($params['idprojeto']) && !empty($params['idprojeto'])) {
                $pks['idprojeto'] = $params['idprojeto'];
            }
            if (isset($params['idatividadecronograma']) && !empty($params['idatividadecronograma'])) {
                $pks['idatividadecronograma'] = $params['idatividadecronograma'];
            }
            if (empty($pks)) {
                throw new Exception('Informe ao menos 1 parametro para exclusao.');
            }
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            return $this->getDbTable()->delete($where);
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    public function excluirComPredecessora($params)
    {
        $where = $this->_db->quoteInto('idatividadecronograma = ?', $params['idatividadecronograma'],
            'and idprojeto = ?', $params['idprojeto']);
        $retorno = $this->getDbTable()->delete($where);
        return $retorno;
    }

    /**
     * @param Projeto_Model_Grupocronograma $model
     * @return Projeto_Model_Grupocronograma
     */
    public function inserirGrupo(Projeto_Model_Grupocronograma $model)
    {
        $model->idatividadecronograma = $this->maxVal("idatividadecronograma", "idprojeto = :idprojeto",
            array('idprojeto' => $model->idprojeto));

        $data = array(
            "idatividadecronograma" => $model->idatividadecronograma,
            "idprojeto" => $model->idprojeto,
            "nomatividadecronograma" => $model->nomatividadecronograma,
            "datcadastro" => new Zend_Db_Expr("now()"),
            "domtipoatividade" => $model->domtipoatividade,
            //"flacancelada"         => $model->flacancelada,
            "idcadastrador" => $model->idcadastrador,
        );

        $data = array_filter($data);
        $retorno = $this->getDbTable()->insert($data);

        return $model;
    }

    /**
     * @param Projeto_Model_Grupocronograma $model
     * @return Projeto_Model_Grupocronograma
     */
    public function atualizarGrupo(Projeto_Model_Grupocronograma $model)
    {
        $data = array(
            "nomatividadecronograma" => $model->nomatividadecronograma,
            "flacancelada" => $model->flacancelada,
        );
        if ((!empty($model->datinicio)) && ((!empty($model->datfim)))) {
            $numdiasrealizados = $this->retornaQtdeDiasUteisEntreDatas(
                array(
                    'datainicio' => $model->datinicio->format('d/m/Y'),
                    'datafim' => $model->datfim->format('d/m/Y')
                )
            );
            $data["numdiasrealizados"] = $numdiasrealizados;
            $data["numdias"] = $numdiasrealizados;
        } else {
            $data["numdiasrealizados"] = $model->numdiasrealizados;
        }

        $data['idparteinteressada'] = (!empty($model->idparteinteressada)) ? (int)$model->idparteinteressada : null;

        if ((!empty($model->datiniciobaseline)) && ((!empty($model->datfimbaseline)))) {
            $numdiasbaseline = $this->retornaQtdeDiasUteisEntreDatas(
                array(
                    'datainicio' => $model->datiniciobaseline->format('d/m/Y'),
                    'datafim' => $model->datfimbaseline->format('d/m/Y')
                )
            );
            $data["numdiasbaseline"] = $numdiasbaseline;
        } else {
            $data["numdiasbaseline"] = $model->numdiasrealizados;
        }
        $data = array_filter($data);

        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );

        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @param Projeto_Model_Entregacronograma $model
     * @return Projeto_Model_Entregacronograma
     */
    public function inserirEntrega(Projeto_Model_Entregacronograma $model)
    {
        $model->idatividadecronograma = $this->maxVal("idatividadecronograma", "idprojeto = :idprojeto",
            array('idprojeto' => $model->idprojeto));

        $data = array(
            "idatividadecronograma" => $model->idatividadecronograma,
            "idprojeto" => $model->idprojeto,
            "idgrupo" => $model->idgrupo,
            "nomatividadecronograma" => $model->nomatividadecronograma,
            "domtipoatividade" => $model->domtipoatividade,
            "numpercentualconcluido" => $model->numpercentualconcluido,
            "datcadastro" => new Zend_Db_Expr("now()"),
            "domtipoatividade" => $model->domtipoatividade,
            "flacancelada" => $model->flacancelada,
            "idcadastrador" => $model->idcadastrador,
            "desobs" => mb_substr($model->desobs, 0, 4000),
            "descriterioaceitacao" => mb_substr($model->descriterioaceitacao, 0, 4000),
        );
        $data = array_filter($data);
        $data['idparteinteressada'] = (!empty($model->idparteinteressada)) ? $model->idparteinteressada : null;
        $data['idresponsavel'] = (!empty($model->idresponsavel)) ? $model->idresponsavel : null;

        $this->getDbTable()->insert($data);
        return $model;
    }

    /**
     * @param Projeto_Model_Entregacronograma $model
     * @return Projeto_Model_Entregacronograma
     */
    public function atualizarEntrega(Projeto_Model_Entregacronograma $model)
    {
        $data = array(
            "idgrupo" => $model->idgrupo,
            "nomatividadecronograma" => $model->nomatividadecronograma,
            "domtipoatividade" => $model->domtipoatividade,
            "flacancelada" => $model->flacancelada,
            "desobs" => mb_substr($model->desobs, 0, 4000),
            "descriterioaceitacao" => mb_substr($model->descriterioaceitacao, 0, 4000),
        );
        if ((!empty($model->datinicio)) && ((!empty($model->datfim)))) {
            $numdiasrealizados = $this->retornaQtdeDiasUteisEntreDatas(
                array(
                    'datainicio' => $model->datinicio->format('d/m/Y'),
                    'datafim' => $model->datfim->format('d/m/Y')
                )
            );
            $data["numdiasrealizados"] = $numdiasrealizados;
            $data["numdias"] = $numdiasrealizados;
        } else {
            $data["numdiasrealizados"] = $model->numdiasrealizados;
        }
        if ((!empty($model->datiniciobaseline)) && ((!empty($model->datfimbaseline)))) {
            $numdiasbaseline = $this->retornaQtdeDiasUteisEntreDatas(
                array(
                    'datainicio' => $model->datiniciobaseline->format('d/m/Y'),
                    'datafim' => $model->datfimbaseline->format('d/m/Y')
                )
            );
            $data["numdiasbaseline"] = $numdiasbaseline;
        } else {
            $data["numdiasbaseline"] = $model->numdiasrealizados;
        }
        $dados = array_filter($data);
        $dados['idparteinteressada'] = !empty($model->idparteinteressada) ? $model->idparteinteressada : null;
        $dados['idresponsavel'] = !empty($model->idresponsavel) ? $model->idresponsavel : null;

        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );

        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);

            $this->getDbTable()->update($dados, $where);
            return $model;
        } catch (Exception $exc) {
            print_r($exc);
            throw $exc;
        }
    }

    /**
     * @param Projeto_Model_Atividadecronograma $model
     * @return Projeto_Model_Atividadecronograma
     */
    public function atualizarDatasAtividade(Projeto_Model_Atividadecronograma $model)
    {
        $data = array(
            "datiniciobaseline" => new Zend_Db_Expr("to_date('" . $model->datiniciobaseline->format('Y-m-d') . "','YYYY-MM-DD')"),
            "datfimbaseline" => new Zend_Db_Expr("to_date('" . $model->datfimbaseline->format('Y-m-d') . "','YYYY-MM-DD')"),
            "datinicio" => new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')"),
            "datfim" => new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')"),
        );
        $data = array_filter($data);
        if ($model->domtipoatividade == "4") {
            $data["numdiasbaseline"] = 0;
            $data["numdiasrealizados"] = 0;
        } else {
            if ((!empty($model->datinicio)) && ((!empty($model->datfim)))) {
                $numdiasrealizados = $this->retornaQtdeDiasUteisEntreDatas(
                    array(
                        'datainicio' => $model->datinicio->format('d/m/Y'),
                        'datafim' => $model->datfim->format('d/m/Y')
                    )
                );
                $data["numdiasrealizados"] = $numdiasrealizados;
                $data["numdias"] = $numdiasrealizados;
            } else {
                $data["numdiasrealizados"] = $model->numdiasrealizados;
            }
            if ((!empty($model->datiniciobaseline)) && ((!empty($model->datfimbaseline)))) {
                $numdiasbaseline = $this->retornaQtdeDiasUteisEntreDatas(
                    array(
                        'datainicio' => $model->datiniciobaseline->format('d/m/Y'),
                        'datafim' => $model->datfimbaseline->format('d/m/Y')
                    )
                );
                $data["numdiasbaseline"] = $numdiasbaseline;
            } else {
                $data["numdiasbaseline"] = $model->numdiasrealizados;
            }
            if (!empty($data["numdiasbaseline"])) {
                if ($data["numdiasbaseline"] == 0) {
                    $data["numdiasbaseline"] = (int)1;
                }
            }
            if (!empty($data["numdiasrealizados"])) {
                if ($data["numdiasrealizados"] == 0) {
                    $data["numdiasrealizados"] = (int)1;
                }
            }
        }
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @param Projeto_Model_Entregacronograma $model
     * @return Projeto_Model_Entregacronograma
     */
    public function atualizarDatasEntrega(Projeto_Model_Entregacronograma $model)
    {
        $data = array(
            "datiniciobaseline" => null,
            "datfimbaseline" => null,
            "datinicio" => null,
            "datfim" => null,
        );

        if ((!empty($model->datiniciobaseline)) && ((!empty($model->datfimbaseline)))) {
//            $data["datiniciobaseline"] = $model->datiniciobaseline->format('Y-m-d');
            $data["datiniciobaseline"] = new Zend_Db_Expr("to_date('" . $model->datiniciobaseline->format('Y-m-d') . "','YYYY-MM-DD')");
//            $data["datfimbaseline"] = $model->datfimbaseline->format('Y-m-d');
            $data["datfimbaseline"] = new Zend_Db_Expr("to_date('" . $model->datfimbaseline->format('Y-m-d') . "','YYYY-MM-DD')");
        }
        if ((!empty($model->datinicio)) && ((!empty($model->datfim)))) {
//            $data["datinicio"] = $model->datinicio->format('Y-m-d');
            $data["datinicio"] = new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')");
//            $data["datfim"] = $model->datfim->format('Y-m-d');
            $data["datfim"] = new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')");
        }
        $data["numdiasbaseline"] = $model->numdiasbaseline;
        $data["numdiasrealizados"] = $model->numdiasrealizados;
        $data = array_filter($data);
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma,
            "idgrupo" => $model->idgrupo,
        );

        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }

    }

    public function isExisteCrongrama($idProjeto)
    {
        $sql = "SELECT count(idatividadecronograma) as total
                 FROM agepnet200.tb_atividadecronograma
                 WHERE idprojeto = :idprojeto";

        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $idProjeto));

        return ($resultado['total'] == 0) ? true : false;

    }

    public function retornaMenorDataFimBaseLineAndMaiorRealizadaCronogramaByProjeto($params)
    {
        $sql = "select to_char((SELECT max(atv1.datfimbaseline) FROM agepnet200.tb_atividadecronograma atv1 WHERE atv1.idprojeto=atv.idprojeto),'DD/MM/YYYY') as datfimbaseline,
                to_char((SELECT max(atv2.datfim) FROM agepnet200.tb_atividadecronograma atv2 WHERE atv2.idprojeto=atv.idprojeto),'DD/MM/YYYY') as datfim
                from agepnet200.tb_atividadecronograma atv
                where atv.idprojeto = :idprojeto and atv.domtipoatividade in(3,4) LIMIT 1";

        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));

        return $resultado;
    }


    /**
     * @param Projeto_Model_Grupocronograma $model
     * @return Projeto_Model_Grupocronograma || boolean
     */
    public function atualizarDatasGrupo(Projeto_Model_Grupocronograma $model)
    {
        $data = array(
            "datiniciobaseline" => null,
            "datfimbaseline" => null,
            "datinicio" => null,
            "datfim" => null,
            "numdiasbaseline" => 0,
            "numdias" => 0,
            "numdiasrealizados" => 0
        );

        if (!empty($model->datinicio) && (!empty($model->datfim))) {
            $data = array(
                "datiniciobaseline" => (!empty($model->datiniciobaseline)) ? new Zend_Db_Expr("to_date('" . $model->datiniciobaseline->format('Y-m-d') . "','YYYY-MM-DD')") : "",
                "datfimbaseline" => (!empty($model->datfimbaseline)) ? new Zend_Db_Expr("to_date('" . $model->datfimbaseline->format('Y-m-d') . "','YYYY-MM-DD')") : "",
                "datinicio" => (!empty($model->datinicio)) ? new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')") : "",
                "datfim" => (!empty($model->datfim)) ? new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')") : "",
                "numdiasbaseline" => $model->numdiasbaseline,
                "numdias" => $model->numdiasrealizados,
                "numdiasrealizados" => $model->numdiasrealizados,
            );
        }

        $data = array_filter($data);

        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
        return true;

    }

    public function atualizarTodasDatasAtividade($params)
    {
        $query = "UPDATE agepnet200.tb_atividadecronograma 
                   SET datiniciobaseline = datasAtividades.datinicio, 
                       datfimbaseline    = datasAtividades.datfim, 
                       numdias           = datasAtividades.numdiasrealizados, 
                       numdiasbaseline   = datasAtividades.numdiasrealizados, 
                       numdiasrealizados = datasAtividades.numdiasrealizados
                
                  FROM (SELECT cron.idatividadecronograma,
                           cron.datinicio, 
                           cron.datfim, 
                           (CASE WHEN domtipoatividade = 4 THEN 0  
                             ELSE (SELECT COUNT(*) AS diasuteis
                                     FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                                          TO_TIMESTAMP(TO_CHAR(cron.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                                    WHERE EXTRACT('ISODOW' FROM the_day) < 6 
                                      AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' || 
                                                                                   LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0')
                                                                                  FROM agepnet200.tb_feriado tf 
                                                                                 WHERE tf.flaativo = 'S' 
                                                                                   AND tf.tipoferiado = '1') 
                                                                                   AND TO_CHAR(the_day, 'dd/mm/yyyy') 
                                                                                   NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
                                                                                                  LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' || 
                                                                                                  LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa 
                                                                                             FROM agepnet200.tb_feriado tf
                                                                                            WHERE tf.flaativo = 'S' 
                                                                                              AND tf.tipoferiado = '2')) 
                                END) AS numdiasrealizados
                              FROM agepnet200.tb_atividadecronograma cron
                             WHERE cron.idprojeto = :idprojeto) AS datasAtividades
                 WHERE tb_atividadecronograma.idprojeto = :idprojeto 
                   AND tb_atividadecronograma.idatividadecronograma = datasAtividades.idatividadecronograma";

        $resultado = $this->_db->query($query, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));
        return $resultado;
    }

    /**
     * @param Projeto_Model_Atividadecronograma $model
     * @return Projeto_Model_Atividadecronograma
     */
    public function atualizarAtividade(Projeto_Model_Atividadecronograma $model)
    {
        try {
            if ($model->numfolga == 0) {
                $numFolga = (int)0;
            }
            if ($model->numdiasrealizados == 0) {
                $model->numdiasrealizados = (int)0;
            }
            if ($model->domtipoatividade == "3") {
                if (!empty($model->numdiasrealizados)) {
                    if ($model->numdiasrealizados == "0") {
                        $model->numdiasrealizados = (int)1;
                    }
                }
                if (!empty($model->numdiasrealizados)) {
                    if ($model->numdiasrealizados == 0) {
                        $model->numdiasrealizados = (int)1;
                    }
                }
            }
            if ($model->domtipoatividade == "4") {
                $model->numdiasrealizados = (int)0;
            }

            $data = array(
                "idatividadecronograma" => (int)$model->idatividadecronograma,
                "idprojeto" => (int)$model->idprojeto,
                "idgrupo" => (int)trim($model->idgrupo),
                "numpercentualconcluido" => $model->numpercentualconcluido,
                "nomatividadecronograma" => $model->nomatividadecronograma,
                "datinicio" => (!empty($model->datinicio)) ? $model->datinicio->format('Y-m-d') : null,
                //"datinicio" => (!empty($model->datinicio)) ? new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')") : null,
                "datfim" => (!empty($model->datfim)) ? $model->datfim->format('Y-m-d') : null,
                //"datfim" => (!empty($model->datfim)) ? new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')") : null,
                //"datcadastro"            => new Zend_Db_Expr("now()"),
                "domtipoatividade" => $model->domtipoatividade,
                "idparteinteressada" => (!empty($model->idparteinteressada)) ? (int)$model->idparteinteressada : null,
                "idresponsavel" => (!empty($model->idresponsavel)) ? (int)$model->idresponsavel : null,
                "flacancelada" => $model->flacancelada,
                "flaaquisicao" => $model->flaaquisicao,
                "flainformatica" => $model->flainformatica,
                "flaordenacao" => $model->flaordenacao,
                "desobs" => $model->desobs,
                "idcadastrador" => (!empty($model->idcadastrador)) ? (int)$model->idcadastrador : null,
                "idmarcoanterior" => (!empty($model->idmarcoanterior)) ? (int)$model->idmarcoanterior : null,
                "numdias" => (!empty($model->numdias)) ? (int)$model->numdias : $model->numdias,
                "numdiasrealizados" => (!empty($model->numdiasrealizados)) ? (int)$model->numdiasrealizados : $model->numdiasrealizados,
                "numdiasbaseline" => $model->numdiasbaseline,
                "vlratividadebaseline" => $model->vlratividadebaseline,
                "vlratividade" => (!empty($model->vlratividade)) ? (int)$model->vlratividade : 0,
                "numfolga" => (!empty($model->numfolga)) ? (int)$model->numfolga : 0,
                "idelementodespesa" => (!empty($model->idelementodespesa)) ? $model->idelementodespesa : null,
            );
            $data = array_filter($data);

            if (isset($numFolga) && $numFolga == 0) {
                $data['numfolga'] = (int)0;
            }
            if (isset($numdiasrealizados)) {
                if ($numdiasrealizados == 0) {
                    $data['numdiasrealizados'] = (int)0;
                }
            }

            $data['datatividadeconcluida'] = $this->retornaDataAtividadeconcluida($data);
            if (!isset($data['numpercentualconcluido'])) {
                $data['numpercentualconcluido'] = 0;
                $data['datatividadeconcluida'] = null;
            } else if (100 == $data['numpercentualconcluido'] && empty($data['datatividadeconcluida'])) {
                $data['datatividadeconcluida'] = date('d/m/Y');
            }

            if (!isset($data['idelementodespesa'])) {
                $data['idelementodespesa'] = null;
            }
            if (!isset($data['desobs'])) {
                $data['desobs'] = "";
            }

            if ($model->domtipoatividade == "4") {
                $data["numdiasbaseline"] = (int)0;
                $data["numdiasrealizados"] = (int)0;
                $data["numdias"] = (int)0;
            } else {
                if ((!empty($model->datinicio)) && ((!empty($model->datfim)))) {
                    $numdiasrealizados = $this->retornaQtdeDiasUteisEntreDatas(
                        array(
                            'datainicio' => $model->datinicio->format('d/m/Y'),
                            'datafim' => $model->datfim->format('d/m/Y')
                        )
                    );

                    $data["numdiasrealizados"] = $numdiasrealizados;
                    $data["numdias"] = $numdiasrealizados;
                }
                if ((!empty($model->datiniciobaseline)) && ((!empty($model->datfimbaseline)))) {
                    $numdiasbaseline = $this->retornaQtdeDiasUteisEntreDatas(
                        array(
                            'datainicio' => $model->datiniciobaseline->format('d/m/Y'),
                            'datafim' => $model->datfimbaseline->format('d/m/Y')
                        )
                    );

                    $data["numdiasbaseline"] = $numdiasbaseline;
                }
                if (!empty($data["numdiasbaseline"])) {
                    if ($data["numdiasbaseline"] == 0) {
                        $data["numdiasbaseline"] = (int)1;
                    }
                }
                if (!empty($data["numdiasrealizados"])) {
                    if ($data["numdiasrealizados"] == 0) {
                        $data["numdiasrealizados"] = (int)1;
                    }
                }
            }


            $pks = array(
                "idprojeto" => (int)$model->idprojeto,
                "idatividadecronograma" => (int)$model->idatividadecronograma
            );

            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);

            return $model;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    public function retornaGrupoPorProjeto($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    atc.datinicio as datainicio,
                    atc.idatividadecronograma,
                    atc.numseq,
                    atc.idprojeto,
                    atc.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=atc.idprojeto and vs.idatividadecronograma=atc.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    atc.datfim as datafinal,
                    atc.nomatividadecronograma,
                    to_char(atc.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline_a,
                    to_char((
                       SELECT min(atv.datiniciobaseline)
                       FROM agepnet200.tb_atividadecronograma atv WHERE
                       atv.idgrupo in (
                          select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
                          where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
                              select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
                              where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
                              gr.idatividadecronograma=atc.idatividadecronograma
                           )
                       ) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
                    ), 'DD/MM/YYYY')  as datiniciobaseline,
                    to_char(atc.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline_a,
                    to_char((
                       SELECT max(atv.datfimbaseline)
                       FROM agepnet200.tb_atividadecronograma atv WHERE
                       atv.idgrupo in (
                          select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
                          where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
                              select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
                              where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
                              gr.idatividadecronograma=atc.idatividadecronograma
                           )
                       ) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
                    ), 'DD/MM/YYYY') as datfimbaseline,
                    case when (
                       SELECT min(atv.datiniciobaseline)
                       FROM agepnet200.tb_atividadecronograma atv WHERE
                       atv.idgrupo in (
					select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
					where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
						select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
						where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
						gr.idatividadecronograma=atc.idatividadecronograma
					)
				) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
			) is null or (
				SELECT max(atv.datfimbaseline)
				FROM agepnet200.tb_atividadecronograma atv WHERE
				atv.idgrupo in (
					select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
					where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
						select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
						where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
						gr.idatividadecronograma=atc.idatividadecronograma
					)
				) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
			) is null then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char((
                              SELECT min(atv.datiniciobaseline)
                               FROM agepnet200.tb_atividadecronograma atv WHERE
                               atv.idgrupo in (
                                  select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
                                  where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
                                      select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
                                      where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
                                      gr.idatividadecronograma=atc.idatividadecronograma
                                   )
                               ) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
                            ), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char((
                               SELECT max(atv.datfimbaseline)
                               FROM agepnet200.tb_atividadecronograma atv WHERE
                               atv.idgrupo in (
                                  select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
                                  where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
                                      select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
                                      where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
                                      gr.idatividadecronograma=atc.idatividadecronograma
                                   )
                               ) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
                            ), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
		            end as numdiasbaseline,
                    to_char(atc.datinicio, 'DD/MM/YYYY') as datinicio_a,
                    to_char(atc.datfim, 'DD/MM/YYYY') as datfim_a,
		            to_char((
                        SELECT min(atv.datinicio)
                        FROM agepnet200.tb_atividadecronograma atv WHERE
                        atv.idgrupo in (
                          select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
                          where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
                              select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
                              where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
                              gr.idatividadecronograma=atc.idatividadecronograma
                           )
                        ) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
                    ), 'DD/MM/YYYY')  as datinicio,
		            to_char((
                        SELECT max(atv.datfim)
                        FROM agepnet200.tb_atividadecronograma atv WHERE
                        atv.idgrupo in (
                          select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
                          where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
                              select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
                              where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
                              gr.idatividadecronograma=atc.idatividadecronograma
                           )
                        ) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
                    ), 'DD/MM/YYYY')  as datfim,
		            case
		            when (
                    SELECT min(atv.datinicio)
                    FROM agepnet200.tb_atividadecronograma atv WHERE
                    atv.idgrupo in (
                      select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
                      where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
                          select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
                          where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
                          gr.idatividadecronograma=atc.idatividadecronograma
                       )
                    ) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
                    ) is null or (
                    SELECT max(atv.datfim)
                    FROM agepnet200.tb_atividadecronograma atv WHERE
                    atv.idgrupo in (
                          select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
                          where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
                              select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
                              where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
                              gr.idatividadecronograma=atc.idatividadecronograma
                           )
                        ) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
			        ) is null then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char((
                                SELECT min(atv.datinicio)
                                FROM agepnet200.tb_atividadecronograma atv WHERE
                                atv.idgrupo in (
                                  select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
                                  where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
                                      select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
                                      where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
                                      gr.idatividadecronograma=atc.idatividadecronograma
                                   )
                                ) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
                            ), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char((
                                SELECT max(atv.datfim)
                                FROM agepnet200.tb_atividadecronograma atv WHERE
                                atv.idgrupo in (
                                   select et.idatividadecronograma from agepnet200.tb_atividadecronograma et
                                   where et.idprojeto = atv.idprojeto and et.domtipoatividade=2 and et.idgrupo in(
                                       select gr.idatividadecronograma from agepnet200.tb_atividadecronograma gr
                                       where gr.idprojeto = atv.idprojeto and gr.domtipoatividade=1 AND
                                       gr.idatividadecronograma=atc.idatividadecronograma
                                    )
                                ) and atv.domtipoatividade in (3,4) and atv.flacancelada='N' and atv.idprojeto=atc.idprojeto
                            ), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
                    )
		            end as numdiasrealizados,
                    (
                       SELECT SUM(ROUND(coalesce(
                       case
                       when (
                           SELECT min(atc1.datinicio)
                           FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = atc.idprojeto
                           and atc1.domtipoatividade in (3,4) and atc1.flacancelada='N' and atc1.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.idgrupo = atc.idatividadecronograma and entrega.domtipoatividade=2
                                and entrega.idprojeto = atc.idprojeto
                            )
                       ) is null or (
                           SELECT max(atc2.datfim)
                           FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = atc.idprojeto
                           and atc2.domtipoatividade in (3,4) and atc2.flacancelada='N' and atc2.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.idgrupo = atc.idatividadecronograma and entrega.domtipoatividade=2
                                and entrega.idprojeto = atc.idprojeto
                           )
                       ) is null then '0'
                       else(((((
                            SELECT count(*) AS diasuteis
                            FROM generate_series(
                                to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                            ) the_day
                            WHERE  extract('ISODOW' FROM the_day) < 6
                            and to_char(the_day,'dd/mm') not in(
                               SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                            )
                            and to_char(the_day,'dd/mm/yyyy') not in(
                               SELECT
                                 lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                            )))*coalesce(atividade.numpercentualconcluido,0))/100))
                            end ,1),2)) as numdiasrealizadosreal
                            FROM agepnet200.tb_atividadecronograma atividade
                            WHERE
                            atividade.idprojeto = atc.idprojeto and atividade.domtipoatividade=3 and atividade.flacancelada='N'
                            and atividade.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.idgrupo = atc.idatividadecronograma and entrega.domtipoatividade=2
                                and entrega.idprojeto = atc.idprojeto
                            )
                    ) as numdiasrealizadosreal,
                    (
                       SELECT SUM(ROUND(coalesce(
                       case
                       when (
                           SELECT min(atc1.datinicio)
                           FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = atc.idprojeto
                           and atc1.domtipoatividade in (3,4) and atc1.flacancelada='N' and atc1.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.idgrupo = atc.idatividadecronograma and entrega.domtipoatividade=2
                                and entrega.idprojeto = atc.idprojeto
                            )
                       ) is null or (
                           SELECT max(atc2.datfim)
                           FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = atc.idprojeto
                           and atc2.domtipoatividade in (3,4) and atc2.flacancelada='N' and atc2.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.idgrupo = atc.idatividadecronograma and entrega.domtipoatividade=2
                                and entrega.idprojeto = atc.idprojeto
                           )
                       ) is null then '0'
                       else(
                            SELECT count(*) AS diasuteis
                            FROM generate_series(
                                to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                            ) the_day
                            WHERE  extract('ISODOW' FROM the_day) < 6
                            and to_char(the_day,'dd/mm') not in(
                               SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                            )
                            and to_char(the_day,'dd/mm/yyyy') not in(
                               SELECT
                                 lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                            ))
                            end ,1),2)) as numdiasrealatividades
                            FROM agepnet200.tb_atividadecronograma atividade
                            WHERE
                            atividade.idprojeto = atc.idprojeto and atividade.domtipoatividade=3 and atc.flacancelada='N'
                            and atividade.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.idgrupo = atc.idatividadecronograma and entrega.domtipoatividade=2
                                and entrega.idprojeto = atc.idprojeto
                            )
                    ) as numdiasrealatividades,
                    to_char(atc.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    atc.numdiasrealizados numdiasreal,
                    atc.numdiasbaseline numdiasbase,
                    atc.domtipoatividade,
                    atc.datiniciobaseline as dtib,
                    atc.datfimbaseline as dtfb,
                    atc.datinicio as inicio,
                    atc.datfim as fim,
                    atc.numpercentualconcluido as numpercentualconcluido,
                    tbp.numcriteriofarol
                FROM agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    atc.domtipoatividade = 1
                    and atc.idprojeto = :idprojeto
                    " . (!empty($params['idgrupo']) ? " and atc.idatividadecronograma=:idgrupo " : "")
            . " ORDER BY atc.numseq, fim asc, inicio asc ";

        if (!empty($params['idgrupo'])) {
            $resultado = $this->_db->fetchAll($sql, array(
                'idprojeto' => $params['idprojeto'],
                'idgrupo' => $params['idgrupo'],
            ));
        } else {
            $resultado = $this->_db->fetchAll($sql, array(
                'idprojeto' => $params['idprojeto'],
            ));
        }
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Grupocronograma');
        foreach ($resultado as $r) {
            $o = new Projeto_Model_Grupocronograma($r);
            $o->entregas = new App_Model_Relation(
                $this, 'retornaEntrega', array(
                    array(
                        'idprojeto' => $o->idprojeto,
                        'idgrupo' => $o->idatividadecronograma,
                        'pesquisa' => $params
                    )
                )
            );
            $collection[] = $o;
        }
        return $collection;
    }

    public function retornaEntrega($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        if (isset($params['pesquisa'])) {
            $parametros = $params['pesquisa'];
        } else {
            $parametros = $params;
        }
        $sql = "SELECT
                    ent.idatividadecronograma,
                    ent.numseq,
                    ent.idprojeto,
                    ent.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=ent.idprojeto and vs.idatividadecronograma=ent.idatividadecronograma
					    and vs.idpessoa=" . $idpessoa . "
					) >0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    ent.nomatividadecronograma,
                    to_char(ent.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline_a,
                    to_char((
                       SELECT min(atc.datiniciobaseline)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                    ), 'DD/MM/YYYY')  as datiniciobaseline,
                    to_char(ent.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline_a,
		            to_char((
                       SELECT max(atc.datfimbaseline)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                    ), 'DD/MM/YYYY')  as datfimbaseline,
                    numdiasbaseline as numdiasbaseline_a,
		            case when (
                       SELECT min(atc.datiniciobaseline)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                    ) is null or (
                       SELECT max(atc.datfimbaseline)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                    ) is null then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char((
			       SELECT min(atc.datiniciobaseline)
			       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
			       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
			    ), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char((
			       SELECT max(atc.datfimbaseline)
			       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
			       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
			    ), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                    end as numdiasbaseline,
                    to_char(ent.datinicio, 'DD/MM/YYYY') as datinicio_a,
                    to_char(ent.datfim, 'DD/MM/YYYY') as datfim_a,
		            to_char((
                       SELECT min(atc.datinicio)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                    ), 'DD/MM/YYYY')  as datinicio,
                    to_char((
                       SELECT max(atc.datfim)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                    ), 'DD/MM/YYYY')  as datfim,
	            case
                    when (
                       SELECT min(atc.datinicio)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                    ) is null or (
                       SELECT max(atc.datfim)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                    ) is null then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char((
                               SELECT min(atc.datinicio)
                               FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                               and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                            ), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char((
                               SELECT max(atc.datfim)
                               FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                               and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                            ), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                    end as numdiasrealizados,
                    (
                    SELECT sum(ROUND(coalesce(
                    case
                    when (
                       SELECT min(atc.datinicio)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                    ) is null or (
                       SELECT max(atc.datfim)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                       and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                    ) is null then '0'
                        else(((((
                            SELECT count(*) AS diasuteis
                            FROM generate_series(
                                to_timestamp(to_char(entrega.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                to_timestamp(to_char(entrega.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                            ) the_day
                            WHERE  extract('ISODOW' FROM the_day) < 6
                            and to_char(the_day,'dd/mm') not in(
                               SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                            )
                            and to_char(the_day,'dd/mm/yyyy') not in(
                               SELECT
                                 lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                            )))*coalesce(entrega.numpercentualconcluido,0))/100))
                            end ,1),2)) as numdiasrealizadosreal
                        FROM
                            agepnet200.tb_atividadecronograma entrega
                        WHERE
                            entrega.idprojeto = ent.idprojeto and entrega.domtipoatividade=3 and entrega.flacancelada='N'
                            and entrega.idgrupo=ent.idatividadecronograma
                    ) as numdiasrealizadosreal,
		            (
                    SELECT sum(ROUND(coalesce(
                        case
                        when (
                           SELECT min(atc.datinicio)
                           FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                           and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                        ) is null or (
                           SELECT max(atc.datfim)
                           FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = ent.idprojeto
                           and atc.idgrupo = ent.idatividadecronograma and atc.domtipoatividade in (3,4) and atc.flacancelada='N'
                        ) is null then '0'
                        else(
                            SELECT count(*) AS diasuteis
                            FROM generate_series(
                                to_timestamp(to_char(entrega.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                to_timestamp(to_char(entrega.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                            ) the_day
                            WHERE  extract('ISODOW' FROM the_day) < 6
                            and to_char(the_day,'dd/mm') not in(
                               SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                            )
                            and to_char(the_day,'dd/mm/yyyy') not in(
                               SELECT
                                 lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                            ))
                            end ,1),2)) as numdiasrealizadosreal
                        FROM
                            agepnet200.tb_atividadecronograma entrega
                        WHERE
                            entrega.idprojeto = ent.idprojeto and entrega.domtipoatividade=3 and entrega.flacancelada='N'
                            and entrega.idgrupo=ent.idatividadecronograma
                     ) as numdiasrealatividades,
                    to_char(ent.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    (ent.datfim - ent.datinicio) as qtdreal,
                    ent.numdiasrealizados numdiasreal,
                    ent.numdiasbaseline numdiasbase,
                    ent.domtipoatividade,
                    ent.idparteinteressada,
                    ent.idresponsavel,
                    ent.desobs,
                    ent.idcadastrador,
                    ent.descriterioaceitacao,
                    ent.datiniciobaseline as dtib,
                    ent.datfimbaseline as dtfb,
                    ent.numpercentualconcluido as numpercentualconcluido,
                    tbp.numcriteriofarol
                FROM agepnet200.tb_atividadecronograma ent
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = ent.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    ent.idprojeto = :idprojeto
                    and ent.idgrupo = :idgrupo
                    " . (!empty($parametros['identrega']) ? " and ent.idatividadecronograma=:identrega " : "")
            . " and ent.domtipoatividade = 2 ";

        if (isset($parametros['idresponsavel'])) {
            $idparteinteressada = $parametros['idresponsavel'];
            $sql .= " and ent.idparteinteressada = (select par.idparteinteressada
                                                from agepnet200.tb_parteinteressada par
                                                where par.idpessoainterna = {$idparteinteressada}
                                                and par.idprojeto = :idprojeto)";
        }

        //$sql .= " ORDER BY dtib asc, dtfb asc, idatividadecronograma asc";
        //$sql .= " ORDER BY ent.datfim asc";
        $sql .= " ORDER BY ent.numseq, ent.datfim asc, ent.datinicio asc ";

        if (!empty($parametros['identrega'])) {
            $resultado = $this->_db->fetchAll($sql, array(
                'idprojeto' => $params['idprojeto'],
                'idgrupo' => $params['idgrupo'],
                'identrega' => $parametros['identrega'],
            ));
        } else {
            $resultado = $this->_db->fetchAll($sql, array(
                'idprojeto' => $params['idprojeto'],
                'idgrupo' => $params['idgrupo'],
            ));
        }

        $mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();
        $mapperPredecessora = new Projeto_Model_Mapper_AtividadeCronoPredecessora();

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Entregacronograma');

        foreach ($resultado as $r) {
            $o = new Projeto_Model_Entregacronograma($r);

            $o->atividades = new App_Model_Relation(
                $this, 'retornaAtividade', array(
                    array(
                        'idprojeto' => $o->idprojeto,
                        'idgrupo' => $o->idatividadecronograma,
                        'pesquisa' => $parametros
                    )
                )
            );
            $o->parteinteressada = $mapperParteInteressada->retornaPorId(array(
                'idparteinteressada' => $o->idresponsavel
            ), true);
            $o->responsavelAceitacao = $mapperParteInteressada->retornaPorId(array(
                'idparteinteressada' => $o->idparteinteressada
            ), true);
            $o->predecessoras = $mapperPredecessora->retornaPorAtividade(array(
                'idatividadecronograma' => $o->idatividadecronograma,
                'idprojeto' => $o->idprojeto
            ), false);

            $collection[] = $o;
        }

        return $collection;
    }

    public function retornaAtividadeCronograma($params)
    {

        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;

        $sql = "SELECT
                    atc.idatividadecronograma,
                    atc.numseq,
                    atc.idprojeto,
                    atc.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=atc.idprojeto and vs.idatividadecronograma=atc.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,

                    atc.numpercentualconcluido,
                    atc.datinicio as datainicio,
                    atc.nomatividadecronograma,
                    to_char(atc.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(atc.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
		            case
                    when atc.datfimbaseline is null or atc.datiniciobaseline is null  or atc.domtipoatividade = 4 then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(atc.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                    end as numdiasbaseline,
                    case
                    when atc.datfimbaseline is null or atc.datiniciobaseline is null  or atc.domtipoatividade = 4 then '0'
                    when to_date(to_char(atc.datiniciobaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then '0'
                    when to_char(atc.datiniciobaseline, 'DD/MM/YYYY') = to_char(now(), 'DD/MM/YYYY') then '0'
                    when to_date(to_char(atc.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                    (
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(now(),    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
                    )
                    when to_date(to_char(atc.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                    (
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(atc.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
		            )
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(atc.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                             FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                       )
                    )
                    end as numdiascompletos,
                    to_char(atc.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(atc.datfim, 'DD/MM/YYYY') as datfim,
		            case
                    when atc.datfim is null or atc.datinicio is null  or atc.domtipoatividade = 4 then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(atc.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                    end as numdiasrealizados,
                    coalesce(ROUND(
                    case
                    when atc.datfim is null or atc.datinicio is null  or atc.domtipoatividade = 4 then '0'
                    else(
                    (((
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(atc.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        ))*coalesce(atc.numpercentualconcluido,0))/100)
                    end ,2),1) as numdiasrealizadosreal,
                    to_char(atc.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    atc.numdiasrealizados numdiasreal,
                    atc.numdiasbaseline numdiasbase,
                    atc.domtipoatividade,
                    atc.idparteinteressada,
                    atc.flacancelada,
                    atc.flaaquisicao,
                    atc.flainformatica,
                    atc.flaordenacao,
                    atc.desobs,
                    atc.idcadastrador,
                    atc.idmarcoanterior,
                    atc.numdias,
                    atc.vlratividadebaseline,
                    atc.vlratividade,
                    atc.numfolga,
                    atc.idelementodespesa,
                    atc.datiniciobaseline as dtib,
                    atc.datfimbaseline as dtfb,
                    atc.datinicio as inicio,
                    atc.datfim as fim,
                    atc.numpercentualconcluido as numpercentualconcluido,
                    tbp.numcriteriofarol
                FROM agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    atc.idprojeto = :idprojeto
                    and atc.idatividadecronograma = :idatividade
                    and atc.domtipoatividade in (3,4)";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividade' => $params['idatividadecronograma'],
        ));

        $modelAtividade = new Projeto_Model_Atividadecronograma($resultado);
        $mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();
        $mapperPredecessora = new Projeto_Model_Mapper_AtividadeCronoPredecessora();
        if (!empty($modelAtividade->idparteinteressada)) {
            $modelAtividade->parteinteressada = $mapperParteInteressada->retornaPorId(array(
                'idparteinteressada' => $modelAtividade->idparteinteressada
            ), true);
        }
        if (!empty($modelAtividade->idresponsavel)) {
            $modelAtividade->responsavelAceitacao = $mapperParteInteressada->retornaPorId(array(
                'idparteinteressada' => $modelAtividade->idresponsavel
            ), true);
        }
        $modelAtividade->predecessoras = $mapperPredecessora->retornaPorAtividade(array(
            'idatividadecronograma' => $modelAtividade->idatividadecronograma,
            'idprojeto' => $modelAtividade->idprojeto
        ), false);

        return $modelAtividade;
    }


    public function retornaAtividade($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    atc.idatividadecronograma,
                    atc.numseq,
                    atc.idprojeto,
                    atc.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=atc.idprojeto and vs.idatividadecronograma=atc.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    atc.numpercentualconcluido,
                    atc.datinicio as datainicio,
                    atc.nomatividadecronograma,
                    to_char(atc.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(atc.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
		            case
                    when atc.datfimbaseline is null or atc.datiniciobaseline is null  or atc.domtipoatividade = 4 then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(atc.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                    end as numdiasbaseline,
                    case
                    when atc.datfimbaseline is null or atc.datiniciobaseline is null  or atc.domtipoatividade = 4 then '0'
                    when to_date(to_char(atc.datiniciobaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then '0'
                    when to_char(atc.datiniciobaseline, 'DD/MM/YYYY') = to_char(now(), 'DD/MM/YYYY') then '0'
                    when to_date(to_char(atc.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                    (
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(now(),    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
                    )
                    when to_date(to_char(atc.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                    (
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(atc.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
		            )
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(atc.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                             FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
                    )
                    end as numdiascompletos,
                    to_char(atc.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(atc.datfim, 'DD/MM/YYYY') as datfim,
		            case
                    when atc.datfim is null or atc.datinicio is null  or atc.domtipoatividade = 4 then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(atc.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                    end as numdiasrealizados,
                    coalesce(ROUND(
                    case
                    when atc.datfim is null or atc.datinicio is null  or atc.domtipoatividade = 4 then '0'
                    else(
                    (((
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atc.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(atc.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        ))*coalesce(atc.numpercentualconcluido,0))/100)
                    end ,2),1) as numdiasrealizadosreal,
                    to_char(atc.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    atc.numdiasrealizados numdiasreal,
                    atc.numdiasbaseline numdiasbase,
                    atc.domtipoatividade,
                    atc.idparteinteressada,
                    atc.flacancelada,
                    atc.flaaquisicao,
                    atc.flainformatica,
                    atc.flaordenacao,
                    atc.desobs,
                    atc.idcadastrador,
                    atc.idmarcoanterior,
                    atc.numdias,
                    atc.vlratividadebaseline,
                    atc.vlratividade,
                    atc.numfolga,
                    atc.idelementodespesa,
                    atc.datiniciobaseline as dtib,
                    atc.datfimbaseline as dtfb,
                    atc.datinicio as inicio,
                    atc.datfim as fim,
                    atc.numpercentualconcluido as numpercentualconcluido,
                    tbp.numcriteriofarol
                FROM agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    atc.idprojeto = :idprojeto
                    and atc.idgrupo = :idgrupo
                    and atc.domtipoatividade in (3,4)
                ";

        if (isset($params['pesquisa'])) {
            $parametros = array_filter($params['pesquisa']);
        } else {
            $parametros = array_filter($params);
        }
        if (isset($parametros['idresponsavel'])) {
            $idparteinteressada = $parametros['idresponsavel'];
            $sql .= " and atc.idparteinteressada = (select par.idparteinteressada
                                                from agepnet200.tb_parteinteressada par
                                                where par.idpessoainterna = {$idparteinteressada}
                                                and par.idprojeto = :idprojeto)";
        }

        if (isset($parametros['idelementodespesa'])) {
            $idelementodespesa = $parametros['idelementodespesa'];
            $sql .= " and atc.idelementodespesa = {$idelementodespesa}";
        }
        if (isset($parametros['statusatividade'])) {
            $status = $parametros['statusatividade'];
            switch ($status):
                case 'C':
                    $sql .= " and atc.flacancelada = 'S' ";
                    break;
                case 100:
                    $sql .= " and atc.numpercentualconcluido = 100 ";
                    break;
                case 50:
                    $sql .= " and atc.numpercentualconcluido < 100 ";
                    break;
                case 'A':
                    $sql .= " and (atc.numpercentualconcluido = 0 and atc.datinicio < CURRENT_DATE ) ";
                    break;
            endswitch;
        }
        if (isset($parametros['inicial_dti'])) {
            $sql .= " and atc.datinicio >= to_date('{$parametros['inicial_dti']}','DD/MM/YYYY')";
        }
        if (isset($parametros['inicial_dtf'])) {
            $sql .= " and atc.datinicio <= to_date('{$parametros['inicial_dtf']}','DD/MM/YYYY')";
        }
        if (isset($parametros['final_dti'])) {
            $sql .= " and atc.datfim >= to_date('{$parametros['final_dti']}','DD/MM/YYYY')";
        }
        if (isset($parametros['final_dtf'])) {
            $sql .= " and atc.datfim <= to_date('{$parametros['final_dtf']}','DD/MM/YYYY')";
        }
        $sql .= " ORDER BY atc.numseq, atc.datfim asc,  atc.datinicio asc ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo' => $params['idgrupo'],
        ));
        $mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();
        $mapperPredecessora = new Projeto_Model_Mapper_AtividadeCronoPredecessora();

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Atividadecronograma');

        foreach ($resultado as $r) {
            $o = new Projeto_Model_Atividadecronograma($r);
            $o->parteinteressada = $mapperParteInteressada->retornaPorId(array(
                'idparteinteressada' => $o->idparteinteressada
            ), true);
            $o->responsavelAceitacao = $mapperParteInteressada->retornaPorId(array(
                'idparteinteressada' => $o->idresponsavel
            ), true);
            $o->predecessoras = $mapperPredecessora->retornaPorAtividade(array(
                'idatividadecronograma' => $o->idatividadecronograma,
                'idprojeto' => $o->idprojeto
            ), false);

            $collection[] = $o;
        }
        return $collection;
    }

    public function retornaMarco($params)
    {
        /**
         * @todo criar o model para os marcos
         */
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    atc.idatividadecronograma,
                    atc.numseq,
                    atc.idprojeto,
                    atc.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=atc.idprojeto and vs.idatividadecronograma=atc.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    atc.numpercentualconcluido,
                    atc.nomatividadecronograma,
                    to_char(atc.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(atc.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(atc.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(atc.datfim, 'DD/MM/YYYY') as datfim,
                    to_char(atc.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    atc.domtipoatividade,
                    atc.idparteinteressada,
                    atc.flacancelada,
                    atc.flaaquisicao,
                    atc.flainformatica,
                    atc.flaordenacao,
                    atc.desobs,
                    atc.idcadastrador,
                    atc.idmarcoanterior,
                    atc.numdias,
                    atc.vlratividadebaseline,
                    atc.vlratividade,
                    atc.numfolga,
                    atc.idelementodespesa
                FROM agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    atc.idprojeto = :idprojeto
                    and atc.idgrupo = :idgrupo
                    and atc.domtipoatividade = 4
                    order by atc.numseq, atc.datfim asc, atc.datinicio asc ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo' => $params['idgrupo'],
        ));
        //return $resultado;

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Atividadecronograma');

        foreach ($resultado as $r) {
            $o = new Projeto_Model_Atividadecronograma($r);
            $collection[] = $o;
        }

        return $collection;
    }

    public function retornaMarcosPorEntrega($params, $array = false, $collection = false)
    {
        /**
         * @todo criar o model para os marcos
         */
        $idprojeto = $params['idprojeto'];
        $identrega = $params['identrega'];
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    atc.idatividadecronograma,
                    atc.numseq,
                    atc.idprojeto,
                    atc.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=atc.idprojeto and vs.idatividadecronograma=atc.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    atc.numpercentualconcluido,
                    atc.nomatividadecronograma,
                    to_char(atc.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(atc.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(atc.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(atc.datfim, 'DD/MM/YYYY') as datfim,
                    to_char(atc.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    atc.domtipoatividade,
                    atc.idparteinteressada,
                    atc.flacancelada,
                    atc.flaaquisicao,
                    atc.flainformatica,
                    atc.flaordenacao,
                    atc.desobs,
                    atc.idcadastrador,
                    atc.idmarcoanterior,
                    atc.numdias,
                    atc.vlratividadebaseline,
                    atc.vlratividade,
                    atc.numfolga,
                    atc.idelementodespesa
                FROM agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                atc.idprojeto        = :idprojeto and
                atc.idgrupo          = :identrega and
                atc.domtipoatividade = 4
                order by atc.numseq, atc.datfim asc, atc.datinicio asc ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $idprojeto,
            'identrega' => $identrega,
        ));
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Atividadecronograma');

        foreach ($resultado as $r) {
            $o = new Projeto_Model_Atividadecronograma($r);
            $collection[] = $o;
        }

        return $collection;
    }

    public function retornaValidaPredecessoraAtividade($params)
    {
        $sql = "SELECT COUNT(asr.idatividadepredecessora) = 0 AS verdade
                  FROM agepnet200.\"AtividadeSucessorasRecursivo\"(:idprojeto) asr
                  LEFT JOIN agepnet200.tb_atividadecronopredecessora acp 
                    ON acp.idprojetocronograma = asr.idprojeto 
                   AND acp.idatividadecronograma = asr.idatividadepredecessora 
                 WHERE (asr.pai = :idatividadecronograma OR acp.idatividadecronograma = :idatividadecronograma) 
                   AND asr.nivel > 1
                   AND (asr.idatividadepredecessora = :idatividadepredecessora 
                        OR asr.idatividadepredecessora IN (SELECT idatividadepredecessora 
					                                         FROM agepnet200.\"AtividadeSucessorasRecursivo\"(:idprojeto) 
				                                            WHERE pai = :idatividadepredecessora 
				                                              AND nivel > 1));";

        $resultado = $this->_db->fetchOne($sql, array(
            'idatividadecronograma' => $params['idatividadecronograma'],
            'idprojeto' => $params['idprojeto'],
            'idatividadepredecessora' => $params['idatividadepredecessora']
        ));

        return $resultado;
    }

    public function fetchPairsGrupo($params)
    {
        $sql = "SELECT
                    atc.idatividadecronograma, atc.nomatividadecronograma
                FROM agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 
                WHERE
                    atc.idprojeto = :idprojeto
                    and atc.domtipoatividade = 1 
                    order by atc.numseq, atc.datfim asc, atc.datinicio asc ";

        return $this->_db->fetchPairs($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
    }

    public function fetchPairsEntrega($params)
    {
        $params = array_filter($params);
        $sql = "SELECT
                    atc.idatividadecronograma, atc.nomatividadecronograma
                FROM agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    atc.idprojeto = :idprojeto
                    and atc.domtipoatividade = 2 ";
        if (!empty($params['idgrupo'])) {
            $sql .= " and atc.idgrupo = :idgrupo "
                . " order by atc.numseq, atc.datfim asc, atc.datinicio asc ";
            $retorno = $this->_db->fetchPairs($sql, array(
                'idprojeto' => $params['idprojeto'],
                'idgrupo' => $params['idgrupo'],
            ));
        } else {
            $sql .= " order by atc.numseq, atc.datfim asc, atc.datinicio asc  ";
            $retorno = $this->_db->fetchPairs($sql, array(
                'idprojeto' => $params['idprojeto'],
            ));
        }

        return $retorno;
    }

    public function fetchPairsMarcoPorEntrega($params)
    {
        $sql = "SELECT
                    atc.idatividadecronograma, atc.nomatividadecronograma
                FROM agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 
                WHERE
                    atc.idprojeto = :idprojeto
                    and atc.idgrupo = :idgrupo
                    and atc.domtipoatividade = 4
                order by atc.numseq, atc.datfim asc, atc.datinicio asc
                    ";
        return $this->_db->fetchPairs($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo' => $params['identrega']
        ));
    }

    public function fetchPairsAtividade($params)
    {
        $params = array_filter($params);
        $sql = "SELECT
                    atc.idatividadecronograma,
                    atc.numseq || ' - ' || to_char(atc.datinicio, 'DD/MM/YYYY') || ' a ' || to_char(atc.datfim, 'DD/MM/YYYY') || ' -- ' || nomatividadecronograma
                FROM agepnet200.tb_atividadecronograma atc                
                WHERE
                    atc.idprojeto = :idprojeto
                AND atc.datinicio IS NOT NULL AND atc.datfim IS NOT NULL    
                AND atc.domtipoatividade in (3,4) ";

        if (!empty($params['domtipoatividade'])) {
            $sql .= " and atc.domtipoatividade = :domtipoatividade ";
        }
        /* Nao mostra na lista de predecessoras a atividade que está sendo editada */
        if (!empty($params['idatividadeAt'])) {
            $sql .= " and atc.idatividadecronograma <> " . $params['idatividadeAt'];
        }
        if (!empty($params['identrega'])) {
            $sql .= " and atc.idgrupo = " . $params['identrega'];
        }
        $sql .= " order by  atc.numseq asc, atc.datfim asc, atc.datinicio asc, atc.domtipoatividade asc ";

        if (!empty($params['domtipoatividade'])) {
            return $this->_db->fetchPairs($sql, array(
                'idprojeto' => $params['idprojeto'],
                'domtipoatividade' => $params['domtipoatividade']
            ));
        } else {
            return $this->_db->fetchPairs($sql, array(
                'idprojeto' => $params['idprojeto']
            ));
        }

    }

    public function fetchPairsAtividadePredecessora($params)
    {
        $sql = "SELECT
                    atc.idatividadecronograma
                    ,atc.numseq || ' - ' || to_char(atc.datinicio, 'DD/MM/YYYY') || ' a ' || to_char(atc.datfim, 'DD/MM/YYYY') || ' -- '  || nomatividadecronograma
                FROM agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    atc.idprojeto = :idprojeto
                    and atc.idatividadecronograma != :idatividadecronograma
                    and atc.domtipoatividade in(3,4)
                    order by atc.numseq, atc.datfim asc, atc.datinicio asc ";

        return $this->_db->fetchPairs($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma'],
        ));
    }

    public function retornaAtividadePorProjeto($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    atc.idatividadecronograma,
                    atc.numseq,
                    atc.idprojeto,
                    atc.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=atc.idprojeto and vs.idatividadecronograma=atc.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    atc.numpercentualconcluido,
                    atc.nomatividadecronograma,
                    to_char(atc.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(atc.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(atc.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(atc.datfim, 'DD/MM/YYYY') as datfim,
                    to_char(atc.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    atc.domtipoatividade,
                    atc.idparteinteressada,
                    atc.flacancelada,
                    atc.flaaquisicao,
                    atc.flainformatica,
                    atc.flaordenacao,
                    atc.desobs,
                    atc.idcadastrador,
                    atc.idmarcoanterior,
                    atc.numdias,
                    atc.vlratividadebaseline,
                    atc.vlratividade,
                    atc.numfolga,
                    atc.idelementodespesa
                FROM agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    atc.idprojeto = :idprojeto
                order by atc.numseq, atc.datfim asc, atc.datinicio asc ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto']
        ));
        return $resultado;
    }

    protected function fetchPairs($params, $tipo)
    {
        $sql = "SELECT
                    atc.idatividadecronograma, atc.nomatividadecronograma
                FROM agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    atc.idprojeto = :idprojeto ";
        $binds = array(
            'idprojeto' => $params['idprojeto'],
        );

        switch ($tipo) {
            case Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_GRUPO :
                $sql .= " and atc.domtipoatividade = 1";
                break;
            case Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_ENTREGA :
                //$sql .= " and idgrupo = :idgrupo and domtipoatividade = 2";
                $sql .= " and atc.domtipoatividade = 2";
                //$binds['idgrupo'] = $params['idgrupo'];
                break;
            case Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM :
                //$sql .= " and idgrupo = :idgrupo and domtipoatividade = 3";
                $sql .= " and atc.domtipoatividade = 3";
                //$binds['idgrupo'] = $params['idgrupo'];
                break;
            case Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO :
                $sql .= " and atc.idgrupo = :idgrupo and atc.domtipoatividade = 4";
                $binds['idgrupo'] = $params['idgrupo'];
                break;
            default:
                $sql .= " and atc.idgrupo = :idgrupo and atc.domtipoatividade = 3";
                $binds['idgrupo'] = $params['idgrupo'];
                break;
        }
        $sql .= " order by atc.numseq, atc.datfim asc, atc.datinicio asc ";

        return $this->_db->fetchPairs($sql, $binds);
    }

    public function retornaInicioBaseLineComFolgaPorAtividade($params)
    {
        $sql = "SELECT to_char(max(ac.datfim), 'DD/MM/YYYY') datfim,
                (SELECT coalesce(a1.numfolga,0) from agepnet200.tb_atividadecronograma a1
                WHERE a1.idatividadecronograma=:idatividadecronograma and a1.idprojeto = :idprojeto) numfolga
                FROM agepnet200.tb_atividadecronopredecessora ap
                INNER JOIN agepnet200.tb_atividadecronograma ac 
                  ON ac.idatividadecronograma=ap.idatividadepredecessora 
                  AND ac.idprojeto=ap.idprojetocronograma
                WHERE ap.idatividadecronograma=:idatividadecronograma 
                 AND ap.idprojetocronograma = :idprojeto";
        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma'],
        ));
        return $resultado;
    }

    public function retornaInicioBaseLinePorAtividade($params)
    {
        $sql = "select to_char(max(ac.datfim), 'DD/MM/YYYY')
                FROM agepnet200.tb_atividadecronopredecessora ap
                INNER JOIN agepnet200.tb_atividadecronograma ac 
                  ON ac.idatividadecronograma=ap.idatividadepredecessora 
                  AND ac.idprojeto=ap.idprojetocronograma
                WHERE ap.idatividadecronograma=:idatividadecronograma 
                AND ap.idprojetocronograma = :idprojeto";

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma'],
        ));
        return $resultado;
    }

    public function retornaInicioBaseLinePorPredecessoras($params)
    {
        $sql = "select
                    to_char(max(atc.datfim), 'DD/MM/YYYY')
                from agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                where
                    atc.idprojeto = :idprojeto
                    and atc.idatividadecronograma in (" . implode(',', $params['predecessora']) . ")";

        return $this->_db->fetchOne($sql, array(
            'idprojeto' => $params['idprojeto'],
            //'predecessora' => implode(',', $params['predecessora']),
        ));

    }

    public function retornaInicioRealPorPredecessoras($params)
    {
        $sql = "select
                    to_char(max(atc.datfim), 'DD/MM/YYYY')
                from agepnet200.tb_atividadecronograma atc
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                where
                    atc.idprojeto = :idprojeto
                    and atc.idatividadecronograma in (" . $params['predecessora'] . ")";
        return $this->_db->fetchOne($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
    }

    public function retornaEntregasPorProjeto($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.numseq,
                    cron.idprojeto,
                    cron.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    cron.domtipoatividade,
                    cron.desobs,
                    to_char(cron.datcadastro,'DD/MM/YYYY') as datcadastro,
                    cron.idmarcoanterior,
                    cron.numdias,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.descriterioaceitacao,
                    cron.idelementodespesa,
                    cron.idcadastrador,
                    --p1.nomparteinteressada as idparteinteressada,
                    p1.idparteinteressada,
                    to_char(cron.datiniciobaseline,'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline,'DD/MM/YYYY') as datfimbaseline,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flaordenacao,
                    cron.flacancelada,
                    to_char(cron.datinicio,'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim,'DD/MM/YYYY') as datfim,
                    cron.datinicio as inicio,
                    cron.datfim as fim
                FROM
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                LEFT OUTER JOIN
                    agepnet200.tb_parteinteressada p1 ON cron.idparteinteressada = p1.idparteinteressada
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.domtipoatividade=2
                    --and cron.datfim is not null
                    --and cron.datfimbaseline is not null
                order by cron.numseq asc, cron.datfim asc, cron.datinicio asc ";

        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Atividadecronograma');

        foreach ($resultado as $r) {
            $o = new Projeto_Model_Atividadecronograma($r);
            /*$o->predecessoras = new App_Model_Relation(
                $this, 'retornaMarcosPorEntregaEProjeto', array(
                    array(
                        'idprojeto' => $o->idprojeto,
                        'idgrupo'     => $o->idatividadecronograma
                    )
                )
            );*/
            $collection[] = $o;
        }
        return $collection;
    }

    /**
     * Retorna um rang de data inicio e fim de um relatorio
     * @params arrary
     * @return array
     */
    public function retornaMarcoPorStatusReport($params)
    {
        $sql = "SELECT
                   CASE 
                        WHEN str.numpercentualconcluidomarco IS NOT NULL THEN ROUND(str.numpercentualconcluidomarco,0)
                        ELSE 	
                            (SELECT COUNT(ac.idatividadecronograma) 
                               FROM agepnet200.tb_atividadecronograma ac
                              WHERE ac.idprojeto = str.idprojeto AND ac.domtipoatividade IN(4)
                                AND ac.datfim <= str.datacompanhamento AND ac.numpercentualconcluido=100)			
                   END	as concluido,
                   (SELECT COUNT(cr.idatividadecronograma) 
                      FROM agepnet200.tb_atividadecronograma cr 
                     WHERE cr.idprojeto=str.idprojeto AND cr.domtipoatividade IN(4)) AS total
                  FROM agepnet200.tb_statusreport str
                 WHERE str.idprojeto = :idprojeto 
                   AND str.idstatusreport = :idstatusreport";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idstatusreport' => $params['idstatusreport'],
        ));
        return $resultado;
    }

    public function retornaUltmaDataFimCronograma($params)
    {
        $sql = "select
                  to_char(max(datfim),'dd/mm/YYYY')  as datfim
                from agepnet200.tb_atividadecronograma
                where idprojeto = :idprojeto";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
        return $resultado;
    }

    /**
     * Retorna um rang de data inicio e fim de um relatorio
     * @params arrary
     * @return array
     */
    public function retornaPercentualMarcoPorDataEProjeto($params)
    {

        $sql = "SELECT
                    (SELECT COUNT(ac.idatividadecronograma) from agepnet200.tb_atividadecronograma ac
                    where ac.idprojeto = at.idprojeto and ac.domtipoatividade = 4
                    and ac.datfim <= :datacompanhamento and ac.numpercentualconcluido=100) as concluido,
                    (select count(cr.idatividadecronograma) from agepnet200.tb_atividadecronograma cr
                    where cr.idprojeto=at.idprojeto and cr.domtipoatividade = 4) as total,
                    at.idprojeto
                FROM
                    agepnet200.tb_atividadecronograma at
                WHERE
                    at.idprojeto = :idprojeto
                group by at.idprojeto";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'datacompanhamento' => $params['datacompanhamento'],
        ));

        return $resultado;
    }

    /**
     * Retorna um rang de data inicio e fim de um projeto
     * @params arrary
     * @return array
     */
    public function retornaMarcosPorProjeto($params)
    {
        $sql = "SELECT
                  (select count(cr.idatividadecronograma) from agepnet200.tb_atividadecronograma cr
                   where cr.idprojeto=cron.idprojeto and cr.domtipoatividade = 4
                   and cr.numpercentualconcluido in(100)) as concluidos,
                  (select count(cr.idatividadecronograma) from agepnet200.tb_atividadecronograma cr
                  where cr.idprojeto=cron.idprojeto and cr.domtipoatividade = 4) as total,
                  cron.idprojeto
                FROM
                   agepnet200.tb_atividadecronograma cron
                WHERE
                   cron.idprojeto = :idprojeto and cron.domtipoatividade in(4)  group by cron.idprojeto";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
        return $resultado;
    }

    public function retornaNumDiasProjeto($params)
    {

        $sql = $this->_getSqlRecursive();

        $sql .= "SELECT 
                   x.datiniciobaseline,  
                   x.datfimbaseline, 
                   x.datinicio, 
                   x.datfim,
                   x.numdiasbaseline,
                   x.totaldiasbaseline,
                   x.numdiascompletos,
                   x.numdiasrealizados,
                   x.numdiasrealizadosreal,
                   CASE WHEN COALESCE(x.totaldiasbaseline, 0) > 0 THEN ROUND((x.numdiascompletos / x.totaldiasbaseline) * 100, 2) ELSE 0::NUMERIC END AS numpercentualprevisto,
                   CASE WHEN COALESCE(x.numdiasrealizados, 0) > 0 THEN ROUND((x.numdiasrealizadosreal / x.numdiasrealizados) * 100, 2)  ELSE 0::NUMERIC END AS numpercentualconcluido,
                   x.totaldiasrealizados
                FROM 
                 (SELECT TO_CHAR(MIN(TO_DATE(a.datiniciobaseline, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datiniciobaseline, 
                    TO_CHAR(MAX(TO_DATE(a.datfimbaseline, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datfimbaseline,
                    TO_CHAR(MIN(TO_DATE(a.datinicio, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datinicio, 
                    TO_CHAR(MAX(TO_DATE(a.datfim, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datfim,
                    (SELECT COUNT(*) AS diasuteis
                       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(MIN(TO_DATE(a.datiniciobaseline, 'DD/MM/YYYY')), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                TO_TIMESTAMP(TO_CHAR(MAX(TO_DATE(a.datfimbaseline, 'DD/MM/YYYY')), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                      WHERE EXTRACT('ISODOW' FROM the_day) < 6
                        AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                             LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0')
                                            FROM agepnet200.tb_feriado tf 
                                           WHERE tf.flaativo = 'S'
                                             AND tf.tipoferiado = '1')
                                             AND TO_CHAR(the_day, 'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
                                                                       LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
                                                                       LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
                                                                      FROM agepnet200.tb_feriado tf
                                                                 WHERE tf.flaativo = 'S' AND tf.tipoferiado = '2')
                    ) AS numdiasbaseline,
                    SUM(CASE WHEN a.nivel = 3 THEN a.numdiasbaseline ELSE 0 END) AS totaldiasbaseline,
                    SUM(CASE WHEN a.nivel = 3 THEN a.numdiascompletos ELSE 0 END) AS numdiascompletos,
                    SUM(CASE WHEN a.nivel = 3 THEN a.numdiasrealizados ELSE 0 END) AS numdiasrealizados,
                    SUM(CASE WHEN a.nivel = 3 THEN a.numdiasrealizadosreal ELSE 0 END) AS numdiasrealizadosreal,
                    (SELECT COUNT(*) AS diasuteis
                       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(MIN(TO_DATE(a.datinicio, 'DD/MM/YYYY')), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                TO_TIMESTAMP(TO_CHAR(MAX(TO_DATE(a.datfim, 'DD/MM/YYYY')), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                      WHERE EXTRACT('ISODOW' FROM the_day) < 6
                        AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                             LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0')
                                            FROM agepnet200.tb_feriado tf 
                                           WHERE tf.flaativo = 'S'
                                             AND tf.tipoferiado = '1')
                                             AND TO_CHAR(the_day, 'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
                                                                       LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
                                                                       LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
                                                                      FROM agepnet200.tb_feriado tf
                                                                 WHERE tf.flaativo = 'S' AND tf.tipoferiado = '2')
                    ) AS totaldiasrealizados
                   FROM atividade a ) x";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
        return $resultado;

    }


    public function retornaQtdePercentualPorProjeto($params)
    {
        $sql = "SELECT
                (SELECT count(cron1.idatividadecronograma)
                FROM agepnet200.tb_atividadecronograma cron1
                WHERE cron1.idprojeto = cron.idprojeto
                AND cron1.domtipoatividade IN(3) ) AS totalatividadeporprojeto,
                (SELECT count(cron2.idatividadecronograma)
                FROM agepnet200.tb_atividadecronograma cron2
                WHERE cron2.idprojeto = cron.idprojeto
                AND cron2.domtipoatividade IN(3)
                AND cron2.numpercentualconcluido > 0
                AND cron2.numpercentualconcluido < 100) AS qtdeatividadeiniciada,
                (SELECT count(cron3.idatividadecronograma)
                FROM agepnet200.tb_atividadecronograma cron3
                WHERE cron3.idprojeto = cron.idprojeto
                AND cron3.domtipoatividade IN(3)
                AND cron3.numpercentualconcluido = 0) AS qtdeatividadenaoiniciada,
                (SELECT count(cron4.idatividadecronograma)
                FROM agepnet200.tb_atividadecronograma cron4
                WHERE cron4.idprojeto = cron.idprojeto
                AND cron4.domtipoatividade IN(3)
                AND cron4.numpercentualconcluido = 100) AS qtdeatividadeconcluida
            FROM agepnet200.tb_atividadecronograma cron
            WHERE cron.idprojeto = :idprojeto 
            GROUP BY cron.idprojeto";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
        return $resultado;
    }

    public function retornaMarcosPorEntregaEProjeto($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                cron.idatividadecronograma,
                cron.numseq,
                cron.idprojeto,
                cron.idgrupo,
                " . (empty($idpessoa) ? " 'S' "
                : " case
                when (
                    select count(*) FROM agepnet200.tb_atividadeocultar vs
                    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
                    and vs.idpessoa =" . $idpessoa . "
                )>0 THEN 'N' ELSE 'S'
                end ") . " as flashowhide,
                cron.numpercentualconcluido,
                cron.nomatividadecronograma,
                cron.domtipoatividade,
                cron.desobs,
                cron.datcadastro,
                cron.idmarcoanterior,
                cron.numdias,
                cron.vlratividadebaseline,
                cron.vlratividade,
                cron.numfolga,
                cron.descriterioaceitacao,
                cron.idelementodespesa,
                cron.idcadastrador,
                p1.nomparteinteressada as nomparteinteressada,
                cron.idparteinteressada,
                to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                cron.flaaquisicao,
                cron.flainformatica,
                cron.flaordenacao,
                cron.flacancelada,
                to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                cron.datinicio as inicio,
                cron.datfim as fim
                FROM
                agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                LEFT OUTER JOIN
                agepnet200.tb_parteinteressada p1 ON cron.idparteinteressada = p1.idparteinteressada
                WHERE
                cron.idprojeto = :idprojeto and
                cron.domtipoatividade = 4
                and cron.idgrupo = :idgrupo
                order by cron.numseq asc, cron.idprojeto, cron.datfim asc, cron.datinicio asc ";

        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo' => $params['idgrupo'],
        ));
        return $resultado;
    }

    public function retornaEntregasEMarcosPorProjeto($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.numseq,
                    cron.idprojeto,
                    cron.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
                        select count(*) FROM agepnet200.tb_atividadeocultar vs
                        where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
                        and vs.idpessoa =" . $idpessoa . "
                    )>0 THEN 'N' ELSE 'S'
                    end ") . " as flashowhide,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    cron.domtipoatividade,
                    cron.desobs,
                    to_char(cron.datcadastro,'DD/MM/YYYY') as datcadastro,
                    cron.idmarcoanterior,
                    cron.numdias,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.descriterioaceitacao,
                    cron.idelementodespesa,
                    cron.idcadastrador,
                    --p1.nomparteinteressada as idparteinteressada,
                    p1.idparteinteressada,
                    to_char(cron.datiniciobaseline,'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline,'DD/MM/YYYY') as datfimbaseline,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flaordenacao,
                    cron.flacancelada,
                    to_char(cron.datinicio,'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim,'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                LEFT OUTER JOIN
                    agepnet200.tb_parteinteressada p1 ON cron.idparteinteressada = p1.idparteinteressada
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.domtipoatividade IN (" . $params['domtipoatividade'] . ")
                    --and cron.datinicio is not null
                    --and cron.datfim is not null
                    ";
        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */
        $sql .= " order by cron.numseq asc, cron.datfim asc, cron.datinicio asc ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Atividadecronograma');

        foreach ($resultado as $r) {
            $o = new Projeto_Model_Atividadecronograma($r);
            $collection[] = $o;
        }

        return $collection;
    }

    public function retornaGrupoPorId($params, $model = false, $collection = false)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                cron.idatividadecronograma,
                cron.numseq,
                cron.idprojeto,
                cron.idgrupo,
                " . (empty($idpessoa) ? " 'S' "
                : " case
                when (
                    select count(*) FROM agepnet200.tb_atividadeocultar vs
                    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
                    and vs.idpessoa =" . $idpessoa . "
                )>0 THEN 'N' ELSE 'S'
                end ") . " as flashowhide,
                cron.numpercentualconcluido,
                cron.nomatividadecronograma,
                cron.domtipoatividade,
                cron.desobs,
                cron.datcadastro,
                cron.idmarcoanterior,
                cron.numdias,
                cron.vlratividadebaseline,
                cron.vlratividade,
                cron.numfolga,
                cron.descriterioaceitacao,
                cron.idelementodespesa,
                cron.idcadastrador,
                to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                cron.flaaquisicao,
                cron.flainformatica,
                cron.flaordenacao,
                cron.flacancelada,
                to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                tbp.numcriteriofarol
            FROM
                agepnet200.tb_atividadecronograma cron
            INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                  and tbp.idtipoiniciativa = 1 /* PROJETO */
            WHERE
                cron.idprojeto = :idprojeto
                and cron.idatividadecronograma = :idatividadecronograma
                and cron.domtipoatividade = 1 ";
        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));

        if ($model) {
            $grupo = new Projeto_Model_Grupocronograma($resultado);
            return $grupo;
        }

        if ($collection) {
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Grupocronograma');

            $o = new Projeto_Model_Grupocronograma($resultado);
            $o->entregas = new App_Model_Relation(
                $this, 'retornaEntrega', array(
                    array(
                        'idprojeto' => $o->idprojeto,
                        'idgrupo' => $o->idatividadecronograma
                    )
                )
            );
            $collection = $o;
            return $collection;
        }

        return $resultado;
    }

    public function retornaEntregaPorAtividade($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    cr.idatividadecronograma,
                    cr.numseq,
                    cr.idprojeto,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
                        select count(*) FROM agepnet200.tb_atividadeocultar vs
                        where vs.idprojeto=cr.idprojeto and vs.idatividadecronograma=cr.idatividadecronograma
                        and vs.idpessoa =" . $idpessoa . "
                    )>0 THEN 'N' ELSE 'S'
                    end ") . " as flashowhide,
                    cr.numpercentualconcluido,
                    cr.nomatividadecronograma,
                    cr.domtipoatividade,
                    cr.desobs,
                    cr.datcadastro,
                    cr.idmarcoanterior,
                    cr.numdias,
                    cr.vlratividadebaseline,
                    cr.vlratividade,
                    cr.numfolga,
                    cr.descriterioaceitacao,
                    cr.idelementodespesa,
                    cr.idcadastrador,
                    p1.nomparteinteressada as nomparteinteressada,
                    cr.idparteinteressada,
                    to_char(cr.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cr.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flaordenacao,
                    cron.flacancelada,
                    to_char(cr.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cr.datfim, 'DD/MM/YYYY') as datfim,
                    cr.datinicio as inicio,
                    cr.datfim as fim
                FROM
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                    inner join  agepnet200.tb_atividadecronograma cr ON cr.idatividadecronograma=cron.idgrupo
                    and cr.idprojeto=cron.idprojeto and cr.domtipoatividade = 2
                    left join agepnet200.tb_parteinteressada p1 ON cr.idparteinteressada = p1.idparteinteressada
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.idatividadecronograma = :idatividadecronograma
                    ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));
        return $resultado;

    }


    public function retornaEntregaPorId($params, $array = false, $collection = false)
    {

        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                cron.idatividadecronograma,
                cron.numseq,
                cron.idprojeto,
                cron.idgrupo,
                " . (empty($idpessoa) ? " 'S' "
                : " case
                when (
                    select count(*) FROM agepnet200.tb_atividadeocultar vs
                    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
                    and vs.idpessoa =" . $idpessoa . "
                )>0 THEN 'N' ELSE 'S'
                end ") . " as flashowhide,
                cron.numpercentualconcluido,
                cron.nomatividadecronograma,
                cron.domtipoatividade,
                cron.desobs,
                cron.datcadastro,
                cron.idmarcoanterior,
                cron.numdias,
                cron.vlratividadebaseline,
                cron.vlratividade,
                cron.numfolga,
                cron.descriterioaceitacao,
                cron.idelementodespesa,
                cron.idcadastrador,
                p1.nomparteinteressada as nomparteinteressada,
                cron.idparteinteressada,
                to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                cron.flaaquisicao,
                cron.flainformatica,
                cron.flaordenacao,
                cron.flacancelada,
                to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                cron.datinicio as inicio,
                cron.datfim as fim,
                cron.idresponsavel,
                (SELECT p1.nomparteinteressada FROM agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 LEFT OUTER JOIN
                    agepnet200.tb_parteinteressada p1 ON cron.idresponsavel = p1.idparteinteressada
                WHERE
                    p1.idparteinteressada = cron.idresponsavel
                    and cron.idprojeto = :idprojeto
                    and idatividadecronograma = :idatividadecronograma) as nomparteinteressadaentrega,
                (SELECT
                    atc.nomatividadecronograma
                    FROM agepnet200.tb_atividadecronograma atc
                    INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto and tbp.idtipoiniciativa = 1
                    WHERE
                        atc.idprojeto = :idprojeto
                        and atc.domtipoatividade = 1 
                        and idatividadecronograma = cron.idgrupo) as grupo
            FROM
                agepnet200.tb_atividadecronograma cron
            INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                  and tbp.idtipoiniciativa = 1 /* PROJETO */
            LEFT OUTER JOIN
                agepnet200.tb_parteinteressada p1 ON cron.idparteinteressada = p1.idparteinteressada
            WHERE
                cron.idprojeto = :idprojeto
                and cron.idatividadecronograma = :idatividadecronograma
                and cron.domtipoatividade = 2 order by cron.numseq, fim, inicio ASC ";
        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));

        if ($array) {
            return $resultado;
        }

        if ($collection) {
            $mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();
            $mapperPredecessora = new Projeto_Model_Mapper_AtividadeCronoPredecessora();

            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Entregacronograma');

            $o = new Projeto_Model_Entregacronograma($resultado);
            $o->atividades = new App_Model_Relation(
                $this, 'retornaAtividade', array(
                    array(
                        'idprojeto' => $o->idprojeto,
                        'idgrupo' => $o->idatividadecronograma
                    )
                )
            );

            $o->parteinteressada = $mapperParteInteressada->retornaPorId(array(
                'idparteinteressada' => $o->idparteinteressada
            ), true);
            $o->predecessoras = $mapperPredecessora->retornaPorAtividade(array(
                'idatividadecronograma' => $o->idatividadecronograma,
                'idprojeto' => $o->idprojeto
            ), false);
            $collection = $o;
            return $collection;
        }

        $entrega = new Projeto_Model_Entregacronograma($resultado);
        return $entrega;
    }


    public function retornaMarcoById($params)
    {
        $sql = "SELECT
                    cron.nomatividadecronograma,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM
                    agepnet200.tb_atividadecronograma cron
                WHERE
                        cron.idprojeto = :idprojeto
                    and cron.idatividadecronograma = :idatividadecronograma
                    ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma']
        ));

        return $resultado;
    }

    public function retornaProximoMarco($params)
    {
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.numseq,
                    cron.nomatividadecronograma,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                WHERE
                    cron.numpercentualconcluido != 100
                    and cron.idprojeto = :idprojeto
                    and cron.domtipoatividade = 4
                    order by cron.datfim
                    ";
        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        $marco = new Projeto_Model_Atividadecronograma($resultado);
        return $marco;
    }

    public function retornaNumFolgaAtividade($params)
    {
        $sql = "SELECT cron.numfolga
                FROM 
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                WHERE
                    cron.idprojeto =:idprojeto
                    and cron.idatividadecronograma = :idatividadecronograma";

        $resultado = $this->_db->fetchRow($sql, $params);
        return $resultado;
    }

    public function retornaCronogramaByArray($params)
    {
        try {
            $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;

            $sql = $this->_getSqlRecursive();

            $sql .= "SELECT a.idatividadecronograma, a.numseq, a.idprojeto, a.idgrupo,
                            a.numpercentualconcluido, a.nomatividadecronograma, a.domtipoatividade,
                            a.desobs, a.datcadastro, a.idmarcoanterior, a.numdias, a.vlratividadebaseline,
                            a.vlratividade, a.numfolga, a.descriterioaceitacao, a.idelementodespesa,
                            a.idcadastrador, a.flaaquisicao, a.flainformatica, a.flaordenacao, a.flacancelada, a.numcriteriofarol,
                            COALESCE(a.nomparteinteressada,NULL) as nomparteinteressada, COALESCE(a.responsavelaceitacao,NULL) AS responsavelaceitacao, 
                            a.numdiasbaseline, a.numdiascompletos,
                            a.numdiasrealizados,
                            a.numdiasrealizadosreal,
                            CASE 
                                 WHEN a.atividadeatrasada = TRUE AND a.atraso > a.numcriteriofarol THEN 'important'
                                 WHEN a.atividadeatrasada = TRUE AND a.atraso <= a.numcriteriofarol THEN 'warning' 
                                 ELSE 'success'
                             END AS domcoratraso,
                            CASE
                                 WHEN a.datfim IS NULL OR a.datfimbaseline IS NULL OR a.datfim = a.datfimbaseline THEN 0 
                                 WHEN a.atividadeatrasada = TRUE THEN (a.atraso - 1) 
                                 ELSE (a.atraso * (-1)) + 1  
                             END AS atraso,
                            a.atividadeatrasada,
                            a.predecessoras,                    
                            a.idparteinteressada,
                            (SELECT COALESCE (SUBSTRING(pint.desemail FROM 1 FOR (POSITION('@' IN pint.desemail) - 1)),NULL) AS desemail
                               FROM agepnet200.tb_parteinteressada pint 
                              WHERE pint.idparteinteressada=a.idparteinteressada
                                AND pint.idprojeto = a.idprojeto 
                            ) AS desemail,
                            a.pai,
                            a.ordenacao,
                            a.nivel,
                            a.datinicio,
                            a.datfim,
                            a.datiniciobaseline,
                            a.datfimbaseline,
                            CASE
                                 WHEN (SELECT COUNT(DISTINCT vs.idatividadecronograma)
                                         FROM agepnet200.tb_atividadeocultar vs
                                        WHERE vs.idprojeto = a.idprojeto
                                          AND vs.idatividadecronograma = a.idatividadecronograma
                                          AND vs.idpessoa = :idpessoa) > 0 THEN 'N'
                                 ELSE 'S' 
                             END AS flashowhide,
                            (SELECT COUNT(idcomentario)
                               FROM agepnet200.tb_comentario c
                              WHERE c.idprojeto = a.idprojeto
                                AND c.idatividadecronograma = a.idatividadecronograma) AS cont_comentario
                            FROM atividade a
                           ORDER BY a.ordenacao";

            $resultado = $this->_db->fetchAll($sql, array(
                'idprojeto' => $params['idprojeto'],
                'idpessoa' => $idpessoa
            ));

            return $resultado;

        } catch (Exception $e) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $e));
            return false;
        }
    }


    public function retornaEntregasEMarcosPorProjetoRelatorio($params)
    {
        try {
            $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;

            $sql = $this->_getSqlRecursive();

            $sql .= "SELECT a.idatividadecronograma, a.numseq, a.idprojeto, a.idgrupo,
                            a.numpercentualconcluido, a.nomatividadecronograma, a.domtipoatividade,
                            a.desobs, a.datcadastro, a.idmarcoanterior, a.numdias, a.vlratividadebaseline,
                            a.vlratividade, a.numfolga, a.descriterioaceitacao, a.idelementodespesa,
                            a.idcadastrador, a.flaaquisicao, a.flainformatica, a.flaordenacao, a.flacancelada, a.numcriteriofarol,
                            a.nomparteinteressada, a.numdiasbaseline, a.numdiascompletos,
                            a.numdiasrealizados,
                            a.numdiasrealizadosreal,
                            CASE 
                                 WHEN a.atividadeatrasada = TRUE AND a.atraso > a.numcriteriofarol THEN 'important'
                                 WHEN a.atividadeatrasada = TRUE AND a.atraso <= a.numcriteriofarol THEN 'warning' 
                                 ELSE 'success'
                             END AS domcoratraso,
                            CASE
                                 WHEN a.datfim IS NULL OR a.datfimbaseline IS NULL OR a.datfim = a.datfimbaseline THEN 0 
                                 WHEN a.atividadeatrasada = TRUE THEN (a.atraso - 1) 
                                 ELSE (a.atraso * (-1)) + 1  
                             END AS atraso,
                            a.atividadeatrasada,
                            a.predecessoras,                    
                            a.idparteinteressada,
                            (SELECT SUBSTRING(pes.desemail FROM 1 FOR (POSITION('@' IN pes.desemail) - 1))
                               FROM agepnet200.tb_parteinteressada pint
                               JOIN agepnet200.tb_pessoa pes
                                 ON pes.idpessoa=pint.idpessoainterna
                              WHERE pint.idparteinteressada=a.idparteinteressada
                                AND pint.idprojeto = a.idprojeto
                            ) AS desemail,
                            a.pai,
                            a.ordenacao,
                            a.nivel,
                            a.datinicio,
                            a.datfim,
                            a.datiniciobaseline,
                            a.datfimbaseline,
                            CASE
                                 WHEN (SELECT COUNT(DISTINCT vs.idatividadecronograma)
                                         FROM agepnet200.tb_atividadeocultar vs
                                        WHERE vs.idprojeto = a.idprojeto
                                          AND vs.idatividadecronograma = a.idatividadecronograma
                                          AND vs.idpessoa = :idpessoa) > 0 THEN 'N'
                                 ELSE 'S' 
                             END AS flashowhide,
                            (SELECT COUNT(idcomentario)
                               FROM agepnet200.tb_comentario c
                              WHERE c.idprojeto = a.idprojeto
                                AND c.idatividadecronograma = a.idatividadecronograma) AS cont_comentario
                            FROM atividade a 
                            WHERE a.domtipoatividade in(2,4)
                           ORDER BY a.ordenacao";

            $resultado = $this->_db->fetchAll($sql, array(
                'idprojeto' => $params['idprojeto'],
                'idpessoa' => $idpessoa
            ));

            return $resultado;

        } catch (Exception $e) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $e));
            return false;
        }
    }

    public function retornaAtividadeCronogramaByIdAtividade($params)
    {
        try {
            $sql = $this->_getSqlRecursive();
            $sql .= "SELECT a.*
                        FROM atividade a
                        where a.idatividadecronograma = :idatividadecronograma ";

            $resultado = $this->_db->fetchRow($sql, array(
                'idatividadecronograma' => (int)$params['idatividadecronograma'],
                'idprojeto' => (int)$params['idprojeto']

            ));

            return $resultado;

        } catch (Exception $e) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $e));
            return false;
        }
    }


    public function retornaPgpAssinadoPorId($params)
    {
        $sql = "SELECT flaaprovado
        FROM agepnet200.tb_statusreport
        WHERE idprojeto = :idprojeto
        AND flaaprovado = '2'
        order by idstatusreport desc limit 1 ";

        $resultado = $this->_db->fetchRow($sql, $params);
        return $resultado;
    }

    public function retornaAtividadePorId($params, $predecessoras = false, $pairspredecessoras = false)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.idprojeto,
                    cron.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
                        select count(*) FROM agepnet200.tb_atividadeocultar vs
                        where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
                        and vs.idpessoa =" . $idpessoa . "
                    )>0 THEN 'N' ELSE 'S'
                    end ") . " as flashowhide,
                    cron.numseq,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    cron.domtipoatividade,
                    cron.desobs,
                    cron.datcadastro,
                    cron.idmarcoanterior,
                    cron.numdias,
                    case
                    when cron.datfimbaseline is null or cron.datiniciobaseline is null or cron.domtipoatividade = 4 then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                    )
                    end as numdiasbaseline,
                    case
                    when cron.datfimbaseline is null or cron.datiniciobaseline is null  or cron.domtipoatividade = 4 then '0'
                    when to_date(to_char(cron.datiniciobaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then '0'
                    when to_char(cron.datiniciobaseline, 'DD/MM/YYYY') = to_char(now(), 'DD/MM/YYYY') then '0'
                    when to_date(to_char(cron.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                    (
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(now(),    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
                    )
                    when to_date(to_char(cron.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                    (
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
		            )
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                             FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
                    )
                    end as numdiascompletos,
		            case
                    when cron.datfim is null or cron.datinicio is null or cron.domtipoatividade = 4 then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                    end as numdiasrealizados,
                    coalesce(ROUND(
                    case
                    when cron.datfim is null or cron.datinicio is null or cron.domtipoatividade = 4 then '0'
                    else(
                    (((
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        ))*coalesce(cron.numpercentualconcluido,0))/100)
                    end ,2 ),1) as numdiasrealizadosreal,
                    '00'|| cron.vlratividadebaseline as vlratividadebaseline,
                    '00'|| cron.vlratividade as vlratividade,
                    cron.numfolga,
                    cron.descriterioaceitacao,
                    cron.idparteinteressada,
                    cron.idelementodespesa,
                    cron.idcadastrador,
                    to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flaordenacao,
                    cron.flacancelada,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                    pi.nomparteinteressada as nomparteinteressada
                FROM
                    agepnet200.tb_atividadecronograma cron
                    INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PLANO DE ACAO */
                    LEFT JOIN agepnet200.tb_parteinteressada pi on pi.idparteinteressada = cron.idparteinteressada
                    and pi.idprojeto=cron.idprojeto
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.idatividadecronograma = :idatividadecronograma
                    and cron.domtipoatividade in (3,4) ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));

        $dados = array(
            'idprojeto' => $resultado['idprojeto'],
            'idparteinteressada' => $resultado['idparteinteressada']
        );

        $mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();

        $modelParteInteressada = $mapperParteInteressada->retornaParteInteressadaByProjeto($dados, true);

        if (!empty($modelParteInteressada)) {
            $resultado['parteinteressada'] = $modelParteInteressada;
        } else {
            $resultado['parteinteressada'] = null;
        }

        if ($predecessoras) {
            $mapperAtividadePredecessora = new Projeto_Model_Mapper_AtividadeCronoPredecessora();
            $predecessoras = $mapperAtividadePredecessora->retornaPorAtividade($params);
            $resultado['predecessoras'] = $predecessoras;
        } else {
            if ($pairspredecessoras) {
                $mapperAtividadePredecessora = new Projeto_Model_Mapper_AtividadeCronoPredecessora();
                $predecessoras = $mapperAtividadePredecessora->fetchPairsPorAtividade($params);
                $resultado['predecessoras'] = $predecessoras;
            }
        }
        return $resultado;
    }

    public function retornaIdAtividadePorProjeto($params)
    {
        $sql = "SELECT
                    cron.idatividadecronograma, cron.nomatividadecronograma, cron.numseq,
                FROM 
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.domtipoatividade = 1";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto']

        ));

        return $resultado;
    }

    public function retornaDatasPorEntrega($params)
    {

        $sql = "SELECT
                    to_char(min(cron.datiniciobaseline), 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(max(cron.datfimbaseline), 'DD/MM/YYYY') as datfimbaseline,
                    to_char(min(cron.datinicio), 'DD/MM/YYYY') as datinicio,
                    to_char(max(cron.datfim), 'DD/MM/YYYY') as datfim
                FROM
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                WHERE
                    cron.idatividadecronograma = :idEntrega
                    and cron.idprojeto = :idprojeto";

        //and cron.flacancelada = 'N'
        //and cron.domtipoatividade IN (3, 4)

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idEntrega' => (int)$params['idEntrega'],
        ));

        return $resultado;
    }

    public function getAtividadeById($atividade, $projeto)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.numseq,
                    cron.idprojeto,
                    cron.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    cron.domtipoatividade,
                    cron.desobs,
                    cron.datcadastro,
                    cron.idmarcoanterior,
                    cron.numdias,
                    case
                    when cron.datfimbaseline is null or cron.datiniciobaseline is null or cron.domtipoatividade = 4 then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                    )
                    end as numdiasbaseline,
                    case
                    when cron.datfimbaseline is null or cron.datiniciobaseline is null  or cron.domtipoatividade = 4 then '0'
                    when to_date(to_char(cron.datiniciobaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then '0'
                    when to_char(cron.datiniciobaseline, 'DD/MM/YYYY') = to_char(now(), 'DD/MM/YYYY') then '0'
                    when to_date(to_char(cron.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                    (
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(now(),    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
                    )
                    when to_date(to_char(cron.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                    (
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
		            )
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                             FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
                    )
                    end as numdiascompletos,
                    case
                    when cron.datfim is null or cron.datinicio is null or cron.domtipoatividade = 4 then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                    end as numdiasrealizados,
                    coalesce(ROUND(
                    case
                    when cron.datfim is null or cron.datinicio is null  or cron.domtipoatividade = 4 then '0'
                    else(
                    (((
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        ))*coalesce(cron.numpercentualconcluido,0))/100)
                    end ),1) as numdiasrealizadosreal,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.descriterioaceitacao,
                    cron.idparteinteressada,
                    cron.idelementodespesa,
                    cron.idcadastrador,
                    to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flaordenacao,
                    cron.flacancelada,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.idatividadecronograma = :idatividadecronograma
                    and cron.domtipoatividade in (3,4)";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$projeto,
            'idatividadecronograma' => (int)$atividade,
        ));

        return $resultado;
    }


    public function retornaDataInicioPorIdAtividade($params)
    {

        $sql = "SELECT
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio
                FROM
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                WHERE
                    cron.idatividadecronograma = :idatividadecronograma and cron.idprojeto = :idprojeto";

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));

        return $resultado;
    }

    public function retornaMaiorDataPorEntrega($params)
    {

        $sql = $this->_getSqlRecursive();
        $sql .= "SELECT
                    TO_CHAR(MIN(TO_DATE(a.datiniciobaseline, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datiniciobaseline,
                    TO_CHAR(MAX(TO_DATE(a.datfimbaseline, 'DD/MM/YYYY')),'DD/MM/YYYY') AS datfimbaseline,
                    TO_CHAR(MIN(TO_DATE(a.datinicio, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datinicio,
                    TO_CHAR(MAX(TO_DATE(a.datfim, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datfim
                FROM atividade a
                WHERE
                    a.pai = :paiEntrega
                and a.idgrupo = :idEntrega
                and a.domtipoatividade IN (3,4)
                and a.flacancelada in('N') ";

        //and cron.flacancelada = 'N'
        //and cron.domtipoatividade IN (3, 4)

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idEntrega' => (int)$params['idEntrega'],
            'paiEntrega' => (int)$params['paiEntrega'],
        ));

        return $resultado;
    }


    public function retornaDataPorProjeto($params)
    {

        $sql = $this->_getSqlRecursive();
        $sql .= "SELECT 
                   x.datiniciobaseline,  
                   x.datfimbaseline, 
                   x.datinicio, 
                   x.datfim,
                   x.numdiasbaseline,
                   x.totaldiasbaseline,
                   x.numdiascompletos,
                   x.numdiasrealizados,
                   x.numdiasrealizadosreal,
                   CASE WHEN COALESCE(x.totaldiasbaseline, 0) > 0 THEN ROUND((x.numdiascompletos::NUMERIC / x.totaldiasbaseline) * 100, 2) ELSE 0::NUMERIC END AS numpercentualprevisto,
                   CASE WHEN COALESCE(x.numdiasrealizados, 0) > 0 THEN ROUND((x.numdiasrealizadosreal::NUMERIC / x.numdiasrealizados) * 100, 2)  ELSE 0::NUMERIC END AS numpercentualconcluido,
                   x.totaldiasrealizados,
                   x.vlratividade AS vlratividadet                
                FROM 
                 (SELECT :idprojeto AS idprojeto,
                    TO_CHAR(MIN(TO_DATE(a.datiniciobaseline, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datiniciobaseline, 
                    TO_CHAR(MAX(TO_DATE(a.datfimbaseline, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datfimbaseline,
                    TO_CHAR(MIN(TO_DATE(a.datinicio, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datinicio, 
                    TO_CHAR(MAX(TO_DATE(a.datfim, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datfim,
                    (SELECT COUNT(*) AS diasuteis
                       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(MIN(TO_DATE(a.datiniciobaseline, 'DD/MM/YYYY')), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                TO_TIMESTAMP(TO_CHAR(MAX(TO_DATE(a.datfimbaseline, 'DD/MM/YYYY')), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                      WHERE EXTRACT('ISODOW' FROM the_day) < 6
                        AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                             LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0')
                                            FROM agepnet200.tb_feriado tf 
                                           WHERE tf.flaativo = 'S'
                                             AND tf.tipoferiado = '1')
                                             AND TO_CHAR(the_day, 'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
                                                                       LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
                                                                       LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
                                                                      FROM agepnet200.tb_feriado tf
                                                                 WHERE tf.flaativo = 'S' AND tf.tipoferiado = '2')
                    ) AS numdiasbaseline,
                    SUM(CASE WHEN a.nivel = 3 THEN a.numdiasbaseline ELSE 0 END) AS totaldiasbaseline,
                    SUM(CASE WHEN a.nivel = 3 THEN a.numdiascompletos ELSE 0 END) AS numdiascompletos,
                    SUM(CASE WHEN a.nivel = 3 THEN a.numdiasrealizados ELSE 0 END) AS numdiasrealizados,
                    SUM(CASE WHEN a.nivel = 3 THEN a.numdiasrealizadosreal ELSE 0 END) AS numdiasrealizadosreal,
                    (SELECT COUNT(*) AS diasuteis
                       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(MIN(TO_DATE(a.datinicio, 'DD/MM/YYYY')), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                TO_TIMESTAMP(TO_CHAR(MAX(TO_DATE(a.datfim, 'DD/MM/YYYY')), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                      WHERE EXTRACT('ISODOW' FROM the_day) < 6
                        AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                             LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0')
                                            FROM agepnet200.tb_feriado tf 
                                           WHERE tf.flaativo = 'S'
                                             AND tf.tipoferiado = '1')
                                             AND TO_CHAR(the_day, 'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
                                                                       LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
                                                                       LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
                                                                      FROM agepnet200.tb_feriado tf
                                                                 WHERE tf.flaativo = 'S' AND tf.tipoferiado = '2')
                    ) AS totaldiasrealizados,
                    SUM(a.vlratividade) AS vlratividade
                   FROM atividade a ) x";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto']
        ));

        return $resultado;
    }

    public function retornaMaiorDataPredecessoraPorAtividade($params)
    {
        $sql = $this->_getSqlRecursive();
        $sql .= "SELECT
                    TO_CHAR(MAX(TO_DATE(a.datfim, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datfim
                FROM agepnet200.tb_atividadepredecessora ap
                JOIN atividade a
                    on a.idatividadecronograma=ap.idatividadepredecessora
                WHERE ap.idatividade = :idatividadecronograma
                  and ap.idprojeto=:idprojeto";

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));

        return $resultado;
    }

    public function retornaMaiorAndMenorDataPorProjeto($params)
    {

        $sql = $this->_getSqlRecursive();
        $sql .= "SELECT
                    TO_CHAR(MIN(a.datfimbaseline), 'DD/MM/YYYY') AS datfimbaseline,
                    TO_CHAR(MAX(a.datfim), 'DD/MM/YYYY') AS datfim
                FROM agepnet200.tb_atividadecronograma a
                WHERE a.domtipoatividade IN (3,4) and a.flacancelada in('N') ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado;
    }

    public function retornaEntregasPorGrupo($params)
    {

        $sql = $this->_getSqlRecursive();
        $sql .= "SELECT a.*
                FROM agepnet200.tb_atividadecronograma entrega
                INNER JOIN atividade a on a.pai = entrega.idatividadecronograma
                and a.idprojeto = entrega.idprojeto and a.nivel=2
                where entrega.idatividadecronograma IN(:idatividadecronograma)
                ORDER BY ordenacao";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));
        return $resultado;
    }


    public function retornaAtividadesPorEntrega($params)
    {

        $sql = $this->_getSqlRecursive();
        $sql .= "SELECT a.*
                FROM agepnet200.tb_atividadecronograma atividade
                INNER JOIN atividade a ON a.idgrupo = atividade.idatividadecronograma AND a.idprojeto = atividade.idprojeto AND a.nivel>2
                where atividade.idatividadecronograma in(:idatividadecronograma)
                ORDER BY ordenacao";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));
        return $resultado;
    }

    public function retornaDatasPorGrupo($params)
    {

        $sql = $this->_getSqlRecursive();
        $sql .= "SELECT
                    TO_CHAR(MIN(TO_DATE(a.datiniciobaseline, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datiniciobaseline,
                    TO_CHAR(MAX(TO_DATE(a.datfimbaseline, 'DD/MM/YYYY')),'DD/MM/YYYY') AS datfimbaseline,
                    TO_CHAR(MIN(TO_DATE(a.datinicio, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datinicio,
                    TO_CHAR(MAX(TO_DATE(a.datfim, 'DD/MM/YYYY')), 'DD/MM/YYYY') AS datfim
                FROM 
                    atividade a
                WHERE
                    a.pai = :idgrupo
                and a.domtipoatividade IN (3,4)
                and a.flacancelada in('N')";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idgrupo' => (int)$params['idgrupo'],
        ));
        return $resultado;
    }

    public function retornaMetaDadosPorProjeto($params)
    {
        $sql = "SELECT
                    to_char(min(cron.datiniciobaseline), 'DD/MM/YYYY') as datiniciobaseline, 
                    to_char(max(cron.datfimbaseline), 'DD/MM/YYYY') as datfimbaseline, 
                    to_char(max(cron.datfim), 'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.flacancelada = 'N'
                    and cron.domtipoatividade = 3";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado;
    }

    public function retornaAtividadePredecesorasDataFimPorAtividades($params)
    {
        $sql = "SELECT cron.idatividadecronograma,
                       cron.idprojeto
                  FROM agepnet200.tb_atividadecronograma cron
                 WHERE cron.idprojeto = :idprojeto
                   AND cron.idatividadecronograma IN (" . implode(", ", $params["atividadecronogramas"]) . ")
                 ORDER BY cron.datfim DESC
                 LIMIT 1";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado;
    }


    public function retornaAtividadesConcluidasPorPeríodo($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.numseq,
                    cron.idprojeto,
                    cron.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                    cron.datfim as datafim,
                    to_char(cron.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    cron.domtipoatividade,
                    cron.idparteinteressada,
                    cron.flacancelada,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flaordenacao,
                    cron.desobs,
                    cron.idcadastrador,
                    cron.idmarcoanterior,
                    cron.numdias,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.idelementodespesa
                FROM agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.domtipoatividade = 3
                    and cron.numpercentualconcluido = 100
                    and cron.datfim > to_date(:datainicial,'DD/MM/YYYY')
                    and cron.datfim <= to_date(:datafinal,'DD/MM/YYYY')
                ORDER BY cron.numseq, cron.datfim asc, cron.datinicio asc ";


        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'datainicial' => $params['datainicial'],
            'datafinal' => $params['datafinal']
        ));
        return $resultado;
    }

    public function retornaAtividadesEmAndamentoPorPeríodo($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.numseq,
                    cron.idprojeto,
                    cron.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                    cron.datfim as datafim,
                    to_char(cron.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    cron.domtipoatividade,
                    cron.idparteinteressada,
                    cron.flacancelada,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flaordenacao,
                    cron.desobs,
                    cron.idcadastrador,
                    cron.idmarcoanterior,
                    cron.numdias,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.idelementodespesa
                FROM agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.domtipoatividade = 3
                    and numpercentualconcluido != 100
                    and numpercentualconcluido != 0
                ORDER BY cron.numseq, cron.datfim asc, cron.datinicio asc ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));
        return $resultado;
    }

    public function atualizaNumSeqAtividade(Projeto_Model_Atividadecronograma $model, $indice)
    {
        $data = array(
            "numseq" => $indice,
        );
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function cancelaAtividade(Projeto_Model_Atividadecronograma $model, $flacancelada)
    {
        $data = array(
            "flacancelada" => $flacancelada,
        );
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }


    /**
     * @param Projeto_Model_Atividadecronograma $model
     * @return Projeto_Model_Atividadecronograma
     */
    public function atividadeAtualizarPercentual(Projeto_Model_Atividadecronograma $model)
    {

        if ($model->numpercentualconcluido > 0) {
            $data = array(
                "numdiasrealizados" => $model->numdiasrealizados,
                "numpercentualconcluido" => $model->numpercentualconcluido,
                "datinicio" => new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')"),
                "datfim" => new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')"),
            );
        } else {
            $data = array(
                "numdiasrealizados" => $model->numdiasrealizados,
                "datinicio" => new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')"),
                "datfim" => new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')"),
            );
        }
        $data = array_filter($data);
        if ($model->numpercentualconcluido == 0) {
            $adiconaArray = array("numpercentualconcluido" => $model->numpercentualconcluido);
            $data = array_replace($data, $adiconaArray);
        }

        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );

        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @param Projeto_Model_Atividadecronograma $model
     * @return Projeto_Model_Atividadecronograma
     */
    public function atualizarPercentuaisGrupoEntrega(Projeto_Model_Atividadecronograma $model)
    {
        $data = array(
            "numpercentualconcluido" => $model->numpercentualconcluido,
            "numdiasrealizados" => $model->numdiasrealizados,
        );
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );

        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @param Projeto_Model_Atividadecronograma $model
     * @return Projeto_Model_Atividadecronograma
     */
    public function atualizarTipoAtividade(Projeto_Model_Atividadecronograma $model)
    {
        $data = array(
            "idatividadecronograma" => $model->idatividadecronograma,
            "idprojeto" => $model->idprojeto,
            "domtipoatividade" => $model->domtipoatividade,
        );
        if ($model->domtipoatividade == "4") {
            if (@isset($model->datinicio)) {
                $data['datinicio'] = new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')");
                $data['datfim'] = $data['datinicio'];
            }
            if (@isset($model->datiniciobaseline)) {
                $data['datiniciobaseline'] = new Zend_Db_Expr("to_date('" . $model->datiniciobaseline->format('Y-m-d') . "','YYYY-MM-DD')");
                $data['datfimbaseline'] = $data['datiniciobaseline'];
            }
        } else {
            $data['datinicio'] = new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')");
            $data['datfim'] = new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')");
        }
        $data = array_filter($data);
        if ($model->domtipoatividade == "4") {
            $data['numdiasrealizados'] = "0";
            $data['numdiasbaseline'] = "0";
        }
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function fetchPairsMarcosPorProjeto($params)
    {
        $sql = "SELECT
                    cron.idatividadecronograma,
                    (cron.nomatividadecronograma || ' - ' || to_char(cron.datinicio, 'DD/MM/YYYY') || ' - '||
                    to_char(cron.datfim, 'DD/MM/YYYY')) as data
                FROM 
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.domtipoatividade = 4
                ORDER BY cron.numseq, cron.datfim asc, cron.datinicio asc ";

        $resultado = $this->_db->fetchPairs($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado;
    }

    public function retornaDatasDoPeriodo($params)
    {
        $sql = "SELECT
                    (SELECT TO_CHAR(CURRENT_DATE, 'DD/MM/YYYY')) as dataFinPeriodo,
                    case
                    when
                    (SELECT CURRENT_DATE) = (SELECT max(datacompanhamento) FROM agepnet200.tb_statusreport WHERE idprojeto = str.idprojeto limit 1)
                        then (SELECT  to_char (datacompanhamento, 'DD/MM/YYYY')
                              FROM agepnet200.tb_statusreport
                              WHERE idprojeto = str.idprojeto
                              ORDER BY datacompanhamento desc limit 1)
                        else
                        (SELECT  to_char (datacompanhamento + 1, 'DD/MM/YYYY')
                        FROM agepnet200.tb_statusreport
                        WHERE idprojeto = str.idprojeto
                        ORDER BY datacompanhamento desc limit 1)
                    end AS dataIniPeriodo,
                    str.idprojeto
                FROM agepnet200.tb_statusreport str
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = str.idprojeto
                where str.idprojeto = :idprojeto group by str.idprojeto ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));
        return $resultado;
    }


//    public function verificaPermissaoDeAtualizacaoBaseLine($params){
//        $sql = "SELECT 		
//                    MAX(datacompanhamento) as datacompanhamento,
//                    MAX(idstatusreport) as idstatusreport
//                FROM agepnet200.tb_statusreport
//                WHERE idprojeto  = :idprojeto
//                AND pgpassinado = 'S' ";
//        
//        $resultado = $this->_db->fetchRow($sql, array(
//            'idprojeto' => $params['idprojeto'],
//        ));  
//        return $resultado;
//    }											   					   

    public function pesquisar($params)
    {
        $params = array_filter($params);
        $sql = $this->_getSqlRecursive();
        $sql .= "SELECT  a.idatividadecronograma, a.numseq, a.idprojeto, a.idgrupo,
                        a.numpercentualconcluido, a.nomatividadecronograma, a.domtipoatividade,
                        a.desobs, a.datcadastro, a.idmarcoanterior, a.numdias, a.vlratividadebaseline,
                        a.vlratividade, a.numfolga, a.descriterioaceitacao, a.idelementodespesa,
                        a.idcadastrador, a.flaaquisicao, a.flainformatica, a.flaordenacao, a.flacancelada, a.numcriteriofarol,
                        a.nomparteinteressada, a.numdiasbaseline, a.numdiascompletos,
                        a.numdiasrealizados,
                        a.numdiasrealizadosreal,
                        CASE 
                             WHEN a.atividadeatrasada = TRUE AND a.atraso > a.numcriteriofarol THEN 'important'
                             WHEN a.atividadeatrasada = TRUE AND a.atraso <= a.numcriteriofarol THEN 'warning' 
                             ELSE 'success'
                         END AS domcoratraso,
                        CASE
                             WHEN a.datfim IS NULL OR a.datfimbaseline IS NULL OR a.datfim = a.datfimbaseline THEN 0 
                             WHEN a.atividadeatrasada = TRUE THEN (a.atraso - 1) 
                             ELSE (a.atraso * (-1)) + 1  
                         END AS atraso,
                        a.atividadeatrasada,
                        a.predecessoras,                    
                        a.idparteinteressada,
                        (SELECT SUBSTRING(pes.desemail FROM 1 FOR (POSITION('@' IN pes.desemail) - 1))
                           FROM agepnet200.tb_parteinteressada pint
                           JOIN agepnet200.tb_pessoa pes
                             ON pes.idpessoa=pint.idpessoainterna
                          WHERE pint.idparteinteressada=a.idparteinteressada
                            AND pint.idprojeto = a.idprojeto
                        ) AS desemail,
                        a.pai,
                        a.ordenacao,
                        a.nivel,
                        a.datinicio,
                        a.datfim,
                        a.datiniciobaseline,
                        a.datfimbaseline,
                        CASE
                             WHEN (SELECT COUNT(DISTINCT vs.idatividadecronograma)
                                     FROM agepnet200.tb_atividadeocultar vs
                                    WHERE vs.idprojeto = a.idprojeto
                                      AND vs.idatividadecronograma = a.idatividadecronograma
                                      AND vs.idpessoa = 11) > 0 THEN 'N'
                             ELSE 'S' 
                         END AS flashowhide,
                        (SELECT COUNT(idcomentario)
                           FROM agepnet200.tb_comentario c
                          WHERE c.idprojeto = a.idprojeto
                            AND c.idatividadecronograma = a.idatividadecronograma) AS cont_comentario
                FROM atividade a
				WHERE 1=1 ";

        $percentualinicio = null;
        if (!empty($params['percentualinicio'])) {
            $percentualinicio = $params['percentualinicio'];
            if (isset($params['status'])) {
                if ($params['status'] == 50) {
                    $percentualinicio = "1";
                }
            }
        }

        if ($percentualinicio != "") {
            $params['percentualinicio'] = $percentualinicio;
        }
        if (isset($params['status'])) {
            if ($params['status'] == 50) {
                $sql .= "AND a.numpercentualconcluido NOT IN(0,100) ";
            } else {
                $sql .= "AND a.numpercentualconcluido IN(100) ";
            }
        }

        if (!empty($params['domtipoatividade_pesq'])) {
            $domtipoatividade = $params['domtipoatividade_pesq'];
            $sql .= " AND a.domtipoatividade IN({$domtipoatividade}) ";
        }

        if (!empty($params['idatividadecronograma_pesq'])) {
            $idatividadecronograma = $params['idatividadecronograma_pesq'];
            $sql .= "AND a.idatividadecronograma IN({$idatividadecronograma}) ";
        }

        if (!empty($params['idparteinteressada_pesq'])) {
            $idparteinteressada = $params['idparteinteressada_pesq'];
            $sql .= "AND a.idparteinteressada IN({$idparteinteressada}) ";
        }

        if (array_key_exists('inicial_dti', $params) && !empty($params['inicial_dti'])) {
            $sql .= "AND to_date(a.datinicio,'DD/MM/YYYY') >= to_date('{$params['inicial_dti']}','DD/MM/YYYY') ";
        }

        if (array_key_exists('inicial_dtf', $params) && $params['inicial_dtf']) {
            $sql .= "AND to_date(a.datinicio,'DD/MM/YYYY') <= to_date('{$params['inicial_dtf']}','DD/MM/YYYY') ";
        }

        if (!empty($params['final_dti'])) {
            $sql .= "AND to_date(a.datfim,'DD/MM/YYYY') >= to_date('{$params['final_dti']}','DD/MM/YYYY') ";
        }

        if (!empty($params['final_dtf'])) {
            $sql .= "AND to_date(a.datfim,'DD/MM/YYYY') <= to_date('{$params['final_dtf']}','DD/MM/YYYY') ";
        }

        $sql .= "ORDER BY a.ordenacao";


        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto_pesq']
        ));

        return $resultado;
    }

    public function retornaGrupoPorAtividade($params)
    {

        $sql = "SELECT cron.idprojeto,
                       cron.idgrupo,
                       cron.idatividadecronograma
                FROM agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE cron.idprojeto = :idprojeto
                  and cron.idatividadecronograma = (select ativ.idgrupo
                                                from agepnet200.tb_atividadecronograma as ativ
                                                where ativ.idatividadecronograma = :idatividadecronograma
                                                and ativ.idprojeto = :idprojeto)
                ORDER BY cron.idatividadecronograma asc ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma'],
        ));

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Grupocronograma');

        $o = new Projeto_Model_Grupocronograma($resultado);
//        Zend_Debug::dump($o);die;
        $o->entregas = new App_Model_Relation(

            $this, 'retornaEntrega', array(
                array(
                    'idprojeto' => $o->idprojeto,
                    'idgrupo' => $o->idgrupo
                )
            )
        );
        $collection = $o;

        return $collection;

    }

    public function retornaUltimoMarco($params)
    {
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.numseq,
                    cron.nomatividadecronograma,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                WHERE
                    cron.numpercentualconcluido != 100
                    and cron.idprojeto = :idprojeto
                    and cron.domtipoatividade = 4";
        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */
        $sql .= "ORDER BY cron.datfimbaseline DESC";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        $marco = new Projeto_Model_Atividadecronograma($resultado);
        return $marco;
    }

    public function getById($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                cron.idatividadecronograma,
                cron.numseq,
                cron.idprojeto,
                cron.idgrupo,
                " . (empty($idpessoa) ? " 'S' "
                : " case
                when (
                    select count(*) FROM agepnet200.tb_atividadeocultar vs
                    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
                    and vs.idpessoa =" . $idpessoa . "
                )>0 THEN 'N' ELSE 'S'
                end ") . " as flashowhide,
                cron.numpercentualconcluido,
                cron.nomatividadecronograma,
                to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                to_char(cron.datcadastro, 'DD/MM/YYYY') as datcadastro,
                cron.domtipoatividade,
                cron.idparteinteressada,
                cron.flacancelada,
                cron.flaaquisicao,
                cron.flainformatica,
                cron.flaordenacao,
                cron.desobs,
                cron.idcadastrador,
                cron.idmarcoanterior,
                cron.numdias,
                cron.vlratividadebaseline,
                cron.vlratividade,
                cron.numfolga,
                cron.idelementodespesa
            FROM agepnet200.tb_atividadecronograma cron
            INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                  and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
            WHERE
                cron.idatividadecronograma = :idatividadecronograma";

        $resultado = $this->_db->fetchRow($sql, array(
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));

        $retorno = new Projeto_Model_Atividadecronograma($resultado);
        return $retorno;
    }

    public function retornaAtividadeById($params)
    {
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.numseq,
                    cron.idprojeto,
                    cron.idgrupo,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                    to_char(cron.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    cron.domtipoatividade,
                    cron.idparteinteressada,
                    cron.flacancelada,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flaordenacao,
                    cron.desobs,
                    cron.idcadastrador,
                    cron.idmarcoanterior,
                    cron.numdias,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.idelementodespesa
                FROM agepnet200.tb_atividadecronograma cron
                WHERE cron.idatividadecronograma =:idatividadecronograma and cron.idprojeto=:idprojeto ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
            'idprojeto' => (int)$params['idprojeto']
        ));
        return $resultado;
    }


    public function getAtividadeByProjetoId($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.numseq,
                    cron.idprojeto,
                    cron.idgrupo,
                    " . (trim($idpessoa) == "" ? " 'S' "
                : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    cron.numpercentualconcluido,
                    nomatividadecronograma,
                    to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                    to_char(cron.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    cron.domtipoatividade,
                    cron.idparteinteressada,
                    cron.flacancelada,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flaordenacao,
                    cron.desobs,
                    cron.idcadastrador,
                    cron.idmarcoanterior,
                    cron.numdias,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.idelementodespesa
                FROM agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                WHERE
                    cron.idatividadecronograma = :idatividadecronograma and
                    cron.idprojeto             = :idprojeto";

        $resultado = $this->_db->fetchRow($sql, array(
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
            'idprojeto' => (int)$params['idprojeto'],
        ));

        $retorno = new Projeto_Model_Atividadecronograma($resultado);
        return $retorno;
    }

    public function retornaGrupoPorEntrega($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                cron.idatividadecronograma,
                cron.numseq,
                cron.idprojeto,
                cron.idgrupo,
                " . (empty($idpessoa) ? " 'S' "
                : " case
                when (
                    select count(*) FROM agepnet200.tb_atividadeocultar vs
                    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
                    and vs.idpessoa =" . $idpessoa . "
                )>0 THEN 'N' ELSE 'S'
                end ") . " as flashowhide,
                cron.numpercentualconcluido,
                cron.nomatividadecronograma,
                to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                to_char(cron.datcadastro, 'DD/MM/YYYY') as datcadastro,
                cron.domtipoatividade,
                cron.idparteinteressada,
                cron.flacancelada,
                cron.flaaquisicao,
                cron.flainformatica,
                cron.flaordenacao,
                cron.desobs,
                cron.idcadastrador,
                cron.idmarcoanterior,
                cron.numdias,
                cron.vlratividadebaseline,
                cron.vlratividade,
                cron.numfolga,
                cron.idelementodespesa,
                tbp.numcriteriofarol
            FROM agepnet200.tb_atividadecronograma cron
            INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                  and tbp.idtipoiniciativa = 1 /* PROJETO */
            WHERE
                cron.idatividadecronograma = :idatividadecronograma
                and cron.idprojeto = :idprojeto";

        $resultado = $this->_db->fetchRow($sql, array(
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
            'idprojeto' => (int)$params['idprojeto']
        ));

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Grupocronograma');

        $o = new Projeto_Model_Grupocronograma($resultado);
        $o->entregas = new App_Model_Relation(
            $this, 'retornaEntrega', array(
                array(
                    'idprojeto' => $o->idprojeto,
                    'idgrupo' => $o->idgrupo
                )
            )
        );
        $collection = $o;

        return $collection;

    }

    // Trazendo todas as entregas por grupo
    public function retornaIdEntregaPorGrupo($params)
    {
        $sql = " select cron.idatividadecronograma, cron.numseq, cron.datfim, cron.datinicio
                   from agepnet200.tb_atividadecronograma cron
                   INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                        where cron.idprojeto = :idprojeto
                        and cron.idgrupo = :idgrupo
                        and cron.domtipoatividade = 2
                ORDER BY cron.numseq, cron.datfim asc, cron.datinicio asc
                ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idgrupo' => (int)$params['idgrupo']
        ));
        return $resultado;

    }

    // Trazendo todas as ativiadades por entrega
    public function retornaIdAtividadePorEntrega($idprojeto, $idgrupoEntrega)
    {
        if ($idgrupoEntrega != '') {
            $sql = "  select cron.idatividadecronograma, cron.numseq, cron.datfim, cron.datinicio
                      from agepnet200.tb_atividadecronograma cron
                      INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                        where cron.idprojeto = :idprojeto
                        and   cron.idgrupo in($idgrupoEntrega)
                        and cron.domtipoatividade in(3,4)
                      ORDER BY cron.numseq, cron.datfim asc, cron.datinicio asc ";

            $resultado = $this->_db->fetchAll($sql, array(
                'idprojeto' => $idprojeto
            ));
            return $resultado;
        } else {
            return false;
        }
    }

    // Trazendo lista de todas as ativiadades por entrega
    public function retornaListaAtividadesPorEntrega($idprojeto, $idgrupoEntrega, $predecessora = false)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        if ($idgrupoEntrega != '') {
            $sql = "SELECT
                    cron.numseq,
                    cron.idatividadecronograma,
                    cron.idprojeto,
                    cron.idgrupo,
                    " . (empty($idpessoa) ? " 'S' "
                    : " case
                    when (
					    select count(*) FROM agepnet200.tb_atividadeocultar vs
					    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
					    and vs.idpessoa =" . $idpessoa . "
					)>0 THEN 'N' ELSE 'S'
					end ") . " as flashowhide,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    cron.domtipoatividade,
                    cron.desobs,
                    cron.datcadastro,
                    cron.idmarcoanterior,
                    cron.numdias,
                    to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    case
                    when cron.datfimbaseline is null or cron.datiniciobaseline is null or cron.domtipoatividade = 4 then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                    )
                    end as numdiasbaseline,
                    case
                    when cron.datfimbaseline is null or cron.datiniciobaseline is null  or cron.domtipoatividade = 4 then '0'
                    when to_date(to_char(cron.datiniciobaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then '0'
                    when to_char(cron.datiniciobaseline, 'DD/MM/YYYY') = to_char(now(), 'DD/MM/YYYY') then '0'
                    when to_date(to_char(cron.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                    (
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(now(),    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
                    )
                    when to_date(to_char(cron.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                    (
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
		            )
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                             FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )
                    )
                    end as numdiascompletos,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                    case
                    when cron.datfim is null or cron.datinicio is null or cron.domtipoatividade = 4 then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                    end as numdiasrealizados,
                    coalesce(ROUND(
                    case
                    when cron.datfim is null or cron.datinicio is null  or cron.domtipoatividade = 4 then '0'
                    else(
                    (((
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(cron.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(cron.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        ))*coalesce(cron.numpercentualconcluido,0))/100)
                    end ),1) as numdiasrealizadosreal,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.descriterioaceitacao,
                    cron.idparteinteressada,
                    cron.idelementodespesa,
                    cron.idcadastrador,
                    to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flaordenacao,
                    cron.flacancelada,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                    pi.nomparteinteressada as nomparteinteressada,
                    tbp.numcriteriofarol
                FROM
                    agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                    LEFT JOIN agepnet200.tb_parteinteressada pi on pi.idparteinteressada = cron.idparteinteressada
                    and pi.idprojeto=cron.idprojeto
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.idgrupo   = :idgrupoEntrega
                    and cron.domtipoatividade in (3,4)
                ORDER  BY cron.numseq asc, cron.datfim asc, cron.datinicio asc
            ";
            $resultado = $this->_db->fetchAll($sql, array(
                'idprojeto' => (int)$idprojeto,
                'idgrupoEntrega' => (int)$idgrupoEntrega
            ));
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Atividadecronograma');
            $mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();
            $mapperPredecessora = new Projeto_Model_Mapper_AtividadeCronoPredecessora();
            foreach ($resultado as $res) {
                $o = new Projeto_Model_Atividadecronograma($res);
                $o->parteinteressada = $mapperParteInteressada->retornaPorId(
                    array(
                        'idparteinteressada' => $o->idparteinteressada
                    ), true
                );
                if ($predecessora) {
                    $o->predecessoras = $mapperPredecessora->retornaPorAtividade(
                        array(
                            'idatividadecronograma' => $o->idatividadecronograma,
                            'idprojeto' => $o->idprojeto
                        ), false
                    );
                }
                $collection[] = $o;
            }
            return $collection;
        } else {
            return false;
        }
    }

    public function retornaAtividadeGantt($params)
    {
        $sql = "select
                        nv1.idatividadecronograma as nv1_idatividadecronograma,
                        nv1.idprojeto as nv1_idprojeto,
                        nv1.domtipoatividade as nv1_domtipoatividade,
                        nv1.idgrupo as nv1_idgrupo,
                        nv1.datinicio as nv1_datinicio,
                        nv1.datfim as nv1_datfim,
                        nv1.nomatividadecronograma as nv1_nomatividadecronograma,
                        nv1.numdias as nv1_numdias,
                        nv2.idatividadecronograma as nv2_idatividadecronograma,
                        nv2.idprojeto as nv2_idprojeto,
                        nv2.idgrupo as nv2_idgrupo,
                        nv2.datinicio as nv2_datinicio,
                        nv2.datfim as nv2_datfim,
                        nv2.domtipoatividade as nv2_domtipoatividade,
                        nv2.nomatividadecronograma as nv2_nomatividadecronograma,
                        nv2.numdias as nv2_numdias,
                        nv3.idatividadecronograma as nv3_idatividadecronograma,
                        nv3.idprojeto as nv3_idprojeto,
                        nv3.idgrupo as nv3_idgrupo,
                        nv3.datinicio as nv3_datinicio,
                        nv3.datfim as nv3_datfim,
                        nv3.domtipoatividade as nv3_domtipoatividade,
                        nv3.nomatividadecronograma as nv3_nomatividadecronograma,
                        nv3.numdias as nv3_numdias,
                        nv3.numpercentualconcluido as nv3_numpercentualconcluido
                from agepnet200.tb_atividadecronograma nv1
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = nv1.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                        inner join agepnet200.tb_atividadecronograma nv2 on nv2.idgrupo = nv1.idatividadecronograma -- inner trata grupo sem entrega mas com marcor e/ou atividade
                        and nv2.idprojeto = nv1.idprojeto
                        and ((nv2.domtipoatividade = " . Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_ENTREGA . ")
                        " . (@is_numeric($params['identrega']) ? " and (nv2.idatividadecronograma = :identrega) " : "") . " -- **** ENTREGA
                        " . ((@is_numeric($params['idatividadecronograma'])) || (@is_numeric($params['idatividademarco'])) ? "and (nv2.idatividadecronograma in(
                            select gru.idgrupo from agepnet200.tb_atividadecronograma gru
                            where gru.idprojeto = nv1.idprojeto "
                . (@is_numeric($params['idatividadecronograma']) ? " and gru.idatividadecronograma = :idatividadecronograma " : "")
                . (@is_numeric($params['idatividademarco']) ? " and gru.idatividadecronograma = :idatividademarco " : "")
                . "))"
                : "") . " -- **** ATIVIDADE
                        )left join agepnet200.tb_atividadecronograma nv3 on nv3.idgrupo = nv2.idatividadecronograma
                        and nv3.idprojeto = nv1.idprojeto
                        and(
                            (nv3.domtipoatividade = " . Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM . "
                            or nv3.domtipoatividade = " . Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO . "
                            or nv3.domtipoatividade is null)
                            )
                where ((nv1.domtipoatividade = " . Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_GRUPO . ")
                        " . (@is_numeric($params['idgrupo']) ? " and (nv1.idatividadecronograma = :idgrupo) " : "") . " )
                        and nv1.idprojeto = :idprojeto
                        and nv1.datinicio is not null
                        and nv1.datfim is not null
                order by --nv1.numseq,
                         --nv1.idatividadecronograma,
                         nv1_datfim asc, nv1_datfim asc,
                         --nv2.numseq,
                         --nv2.idatividadecronograma,
                         nv2_datfim asc, nv2_datfim asc,
                         --nv3.numseq,
                         --nv3.idatividadecronograma,
                         nv3_datfim asc, nv3_datfim asc
                         ";
        if ((@is_numeric($params['idatividadecronograma'])) && (@is_numeric($params['idatividademarco']))) {
            $parametros = array(
                'idprojeto' => @$params['idprojeto'],
                'idgrupo' => @$params['idgrupo'],
                'identrega' => @$params['identrega'],
                'idatividadecronograma' => @$params['idatividadecronograma'],
                'idatividademarco' => @$params['idatividademarco'],
            );
        } else {
            if (@is_numeric($params['idatividadecronograma'])) {
                $parametros = array(
                    'idprojeto' => @$params['idprojeto'],
                    'idgrupo' => @$params['idgrupo'],
                    'identrega' => @$params['identrega'],
                    'idatividadecronograma' => @$params['idatividadecronograma'],
                );
            } else {
                $parametros = array(
                    'idprojeto' => @$params['idprojeto'],
                    'idgrupo' => @$params['idgrupo'],
                    'identrega' => @$params['identrega'],
                    'idatividademarco' => @$params['idatividademarco'],
                );
            }
        }
        $parametros = array_filter($parametros);
        $result = $this->_db->fetchAll($sql, $parametros);
        return $result;
    }

    public function atualizarEntregaEap(Projeto_Model_Atividadecronograma $model)
    {
        $data = array(
            "idgrupo" => $model->idgrupo
        );
        $data = array_filter($data);
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );

        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function verificarAtividadesDesatualizadas($params)
    {

        $hoje = date("Y-m-d");
        $sql = "SELECT
                       cron.*
				   FROM
				       agepnet200.tb_atividadecronograma cron
                   INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
				   WHERE
					   cron.idprojeto = :idprojeto
                   AND cron.domtipoatividade != 1
                   AND cron.domtipoatividade != 2
                   AND cron.numpercentualconcluido != 100
                   AND cron.flacancelada != 'S'
                   AND cron.datfim <= '$hoje'
                   order by cron.numseq asc, cron.datfim asc, cron.datinicio asc ";
        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */
        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado;
    }

    public function retornaAtividadesConcluidas($params)
    {
        $sql = "SELECT  cron.idatividadecronograma,cron.datfim, cron.datinicio,
	                    ARRAY_TO_STRING(ARRAY_AGG(to_char(cron.datinicio,'DD/MM/YYYY')::VARCHAR || ' - ' || to_char(cron.datfim, 'DD/MM/YYYY')::VARCHAR || ' - ' || cron.nomatividadecronograma::VARCHAR || '\n'),'','*') AS registro
                  FROM agepnet200.tb_atividadecronograma cron 
                 WHERE cron.idprojeto = :idprojeto 
                   AND cron.domtipoatividade IN (3, 4) 
                   AND cron.numpercentualconcluido = 100 
                   AND cron.datatividadeconcluida BETWEEN TO_DATE('{$params['dtInicio']}', 'DD/MM/YYYY') AND TO_DATE('{$params['dtFim']}', 'DD/MM/YYYY') 
                 GROUP BY cron.idatividadecronograma, cron.datfim, cron.datinicio
                  ORDER BY cron.datfim, cron.datinicio";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado;

    }

    public function retornaAtividadesEmAndamento($params)
    {
        $sql = "SELECT cron.idatividadecronograma,cron.datfim, cron.datinicio,
	                    ARRAY_TO_STRING(ARRAY_AGG(to_char(cron.datinicio,'DD/MM/YYYY')::VARCHAR || ' - ' || to_char(cron.datfim,'DD/MM/YYYY')::VARCHAR || ' - ' || cron.nomatividadecronograma::VARCHAR || '\n'),'','*') AS registro 
                  FROM agepnet200.tb_atividadecronograma cron
                 WHERE cron.idprojeto = :idprojeto 
                   AND cron.domtipoatividade IN (3, 4) 
                   AND cron.numpercentualconcluido IS NOT NULL 
                   AND cron.numpercentualconcluido NOT IN (0, 100) 
                   --AND cron.datfim <= TO_DATE('{$params['dtFim']}','DD/MM/YYYY') 
                  GROUP BY cron.idatividadecronograma, cron.datfim, cron.datinicio 
                  ORDER BY cron.datfim, cron.datinicio";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado;

    }


    public function verificarAtividadesConcluidas($params)
    {

        $sql = "SELECT
                       cron.*
				   FROM
				       agepnet200.tb_atividadecronograma cron
                   INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                                WHERE cron.idprojeto = :idprojeto
                   AND cron.domtipoatividade != 1
                   AND cron.domtipoatividade != 2
                   AND cron.numpercentualconcluido = 100
                AND cron.datinicio = (SELECT (datacompanhamento ) + integer '1'  
                      FROM agepnet200.tb_statusreport 
                     WHERE idprojeto  = :idprojeto
                      ORDER BY datacompanhamento desc limit 1) 
                AND cron.datinicio <= (SELECT CURRENT_DATE 
                    FROM agepnet200.tb_statusreport 
                    where idprojeto  = :idprojeto
                    ORDER BY datacompanhamento desc limit 1 )
                   order by cron.numseq asc, cron.datfim asc, cron.datinicio asc ";
        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */
        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado;
    }

    public function verificarAtividadesEmAndamento($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT
                cron.idatividadecronograma,
                cron.idprojeto,
                cron.idgrupo,
                " . (empty($idpessoa) ? " 'S' "
                : " case
                when (
                    select count(*) FROM agepnet200.tb_atividadeocultar vs
                    where vs.idprojeto=cron.idprojeto and vs.idatividadecronograma=cron.idatividadecronograma
                    and vs.idpessoa =" . $idpessoa . "
                )>0 THEN 'N' ELSE 'S'
                end ") . " as flashowhide,
                cron.numseq,
                cron.numpercentualconcluido,
                trim(cron.nomatividadecronograma) nomatividadecronograma,
                to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                cron.datfim as datafim,
                to_char(cron.datcadastro, 'DD/MM/YYYY') as datcadastro,
                cron.domtipoatividade,
                cron.idparteinteressada,
                cron.flacancelada,
                cron.flaaquisicao,
                cron.flainformatica,
                cron.flaordenacao,
                cron.desobs,
                cron.idcadastrador,
                cron.idmarcoanterior,
                cron.numdias,
                '00'|| cron.vlratividadebaseline as vlratividadebaseline,
                '00'|| cron.vlratividade as vlratividade,
                cron.numfolga,
                cron.idelementodespesa
            FROM agepnet200.tb_atividadecronograma cron
            INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                  and tbp.idtipoiniciativa = 1 /* PROJETO */
            WHERE
                cron.idprojeto = :idprojeto
                and cron.domtipoatividade != 1
                and cron.domtipoatividade != 2
                and cron.numpercentualconcluido != 100
                and cron.numpercentualconcluido != 0
            order by cron.numseq asc, cron.datfim asc, cron.datinicio asc ";
        //ORDER BY datiniciobaseline LIMIT 10";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));
        return $resultado;
    }

    public function retornaTendenciaProjeto($params)
    {
        $sql = "SELECT
                  to_char(MAX(cron.datfim), 'DD/MM/YYYY') as datfimprojetotendencia
                FROM
                  agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                WHERE
			      cron.idprojeto = :idprojeto ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado['datfimprojetotendencia'];
    }


    public function retornaCronogramaPorProjeto($params)
    {
        $sql = "SELECT
                  cron.*
                FROM
                  agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                WHERE
			      cron.idprojeto = :idprojeto ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado;
    }

    public function retornaIrregularidades($params)
    {

        $data = "";
        $hoje = date('Y-m-d');
        $sql = "SELECT cron.idatividadecronograma,
                cron.idprojeto, cron.idgrupo, cron.numpercentualconcluido,
                trim(cron.nomatividadecronograma) nomatividadecronograma,
                to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                cron.datfim as datafim,
                to_char(cron.datcadastro, 'DD/MM/YYYY') as datcadastro,
                cron.domtipoatividade, cron.idparteinteressada,
                cron.flacancelada, cron.flaaquisicao, cron.flainformatica, cron.flaordenacao, cron.desobs,
                cron.idcadastrador, cron.idmarcoanterior, cron.numdias, cron.vlratividadebaseline,
                cron.vlratividade, cron.numfolga, cron.idelementodespesa
                FROM
                agepnet200.tb_atividadecronograma cron
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                cron.idprojeto = :idprojeto
                AND cron.domtipoatividade != 1
                AND cron.numpercentualconcluido != 100
                AND cron.flacancelada != 'S'
                AND cron.datfim < now() order by
                cron.numseq asc, cron.domtipoatividade, cron.datfim asc, cron.datinicio asc ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
        if ($resultado) {
            foreach ($resultado as $r) {
                $data .= $r['nomatividadecronograma'] . "\n";
            }
        }
        return $data;
    }

    public function retornaIrregularidadesAtividades($params)
    {
        $data = "";
        $sql = "SELECT at.idatividadecronograma, at.idprojeto, at.numseq,
                    at.idgrupo, at.numpercentualconcluido,
                    trim(at.nomatividadecronograma) nomatividadecronograma,
                    to_char(at.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(at.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(at.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(at.datfim, 'DD/MM/YYYY') as datfim,
                    at.datfim as datafim,
                    to_char(at.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    at.domtipoatividade, at.idparteinteressada, at.flacancelada,
                    at.flaaquisicao, at.flainformatica, at.flaordenacao, at.desobs,
                    at.idcadastrador, at.idmarcoanterior,
                    at.numdias, at.vlratividadebaseline,
                    at.vlratividade, at.numfolga,
                    at.idelementodespesa,
                    CASE
                      WHEN at.datfim < now() THEN 'S'
                      ELSE 'N'
                    END atrasada,
                    CASE
                      WHEN at.numpercentualconcluido != 100 THEN 'N'
                      ELSE 'S'
                    END concluida,
                    CASE
                      WHEN at.flacancelada != 'S' THEN 'N'
                      ELSE 'S'
                    END cancelada
                FROM
                agepnet200.tb_atividadecronograma at
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = at.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                at.idprojeto = :idprojeto AND at.domtipoatividade != 1
                "
            . (isset($params['idgrupo']) ? " and coalesce(at.idgrupo,0)=" . $params['idgrupo'] : "")
            //. (@$params['domtipoatividade']>2       ? " AND at.numpercentualconcluido != 100 AND at.flacancelada != 'S' " : "")
            . (@$params['atividadeAtva'] ? " AND at.numpercentualconcluido != 100 AND at.flacancelada != 'S' " : "")
            . (@$params['atividadeConcluida'] ? " AND at.numpercentualconcluido = 100 " : "")
            . (@$params['atividadeAndamento'] ? " AND at.numpercentualconcluido != 100 AND at.numpercentualconcluido != 0 and at.domtipoatividade != 1 and at.domtipoatividade != 2 " : "")
            . (isset($params['domtipoatividade']) ? " and at.domtipoatividade=" . $params['domtipoatividade'] : "")
            . ($params['dtfim'] ? " AND at.datfim < now() " : "")
            . " order by
                at.numseq, at.domtipoatividade, coalesce(at.idgrupo,0), at.datfim asc, at.datinicio ASC ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado;
    }

    public function retornaIrregularidadesNovoAcompanhamentoAtividades($params)
    {
        $data = "";
        $sql = "SELECT at.idatividadecronograma, at.idprojeto, at.numseq,
                    at.idgrupo, at.numpercentualconcluido,
                    trim(at.nomatividadecronograma) nomatividadecronograma,
                    to_char(at.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(at.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(at.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(at.datfim, 'DD/MM/YYYY') as datfim,
                    at.datfim as datafim,
                    to_char(at.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    at.domtipoatividade, at.idparteinteressada, at.flacancelada,
                    at.flaaquisicao, at.flainformatica, at.flaordenacao, at.desobs,
                    at.idcadastrador, at.idmarcoanterior,
                    at.numdias, at.vlratividadebaseline,
                    at.vlratividade, at.numfolga,
                    at.idelementodespesa,
                    CASE
                      WHEN at.datfim < current_date THEN 'S'
                      ELSE 'N'
                    END atrasada,
                    CASE
                      WHEN at.numpercentualconcluido != 100 THEN 'N'
                      ELSE 'S'
                    END concluida,
                    CASE
                      WHEN at.flacancelada != 'S' THEN 'N'
                      ELSE 'S'
                    END cancelada,
                    at.numpercentualconcluido
                FROM
                agepnet200.tb_atividadecronograma at
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = at.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                at.idprojeto = :idprojeto"
            . (isset($params['idgrupo']) ? "  and coalesce(at.idgrupo,0)=" . $params['idgrupo'] : "")
            . (@$params['atividadeAtva'] ? " AND at.numpercentualconcluido != 100 AND at.flacancelada != 'S' " : "")
            . (@$params['atividadeAndamento'] ? " AND at.numpercentualconcluido != 100 AND  at.domtipoatividade != 1 and at.domtipoatividade != 2 " : "")
            . ($params['dtfim'] ? " AND at.datfim <= current_date " : "")
            . " ORDER BY
                at.numseq, at.domtipoatividade, coalesce(at.idgrupo,0), at.datfim asc, at.datinicio ASC ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));

        return $resultado;
    }

    public function retornaIrregularidadesAtividadesSemAtrasoEntrega($params)
    {
        $data = "";

        $sql = "SELECT at.idatividadecronograma, at.idprojeto, at.numseq,
                    at.idgrupo, at.numpercentualconcluido,
                    trim(at.nomatividadecronograma) nomatividadecronograma,
                    to_char(at.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(at.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(at.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(at.datfim, 'DD/MM/YYYY') as datfim,
                    at.datfim as datafim,
                    to_char(at.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    at.domtipoatividade, at.idparteinteressada, at.flacancelada,
                    at.flaaquisicao, at.flainformatica, at.flaordenacao, at.desobs,
                    at.idcadastrador, at.idmarcoanterior,
                    at.numdias, at.vlratividadebaseline,
                    at.vlratividade, at.numfolga,
                    at.idelementodespesa,
                    CASE
                      WHEN at.datfim < current_date THEN 'S'
                      ELSE 'N'
                    END atrasada,
                    CASE
                      WHEN at.numpercentualconcluido != 100 THEN 'N'
                      ELSE 'S'
                    END concluida,
                    CASE
                      WHEN at.flacancelada != 'S' THEN 'N'
                      ELSE 'S'
                    END cancelada
                    FROM
                agepnet200.tb_atividadecronograma at
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = at.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE
                at.idprojeto = :idprojeto AND at.domtipoatividade != 1
                and at.idgrupo in (select ar.idatividadecronograma from agepnet200.tb_atividadecronograma ar where coalesce(ar.idgrupo,0)=:idgrupo and ar.idprojeto = at.idprojeto  ) "
            . (@$params['atividadeAtva'] ? " AND at.numpercentualconcluido != 100 AND at.flacancelada != 'S' " : "")
            . (@$params['atividadeConcluida'] ? " AND at.numpercentualconcluido = 100 " : "")
            . (@$params['atividadeAndamento'] ? " AND at.numpercentualconcluido != 100 AND at.numpercentualconcluido != 0 and at.domtipoatividade != 1 and at.domtipoatividade != 2 " : "")
            . (isset($params['domtipoatividade']) ? " and at.domtipoatividade=" . $params['domtipoatividade'] : "")
            . ($params['dtfim'] ? " AND at.datfim < current_date " : "")
            . "
                order by at.numseq, at.domtipoatividade, coalesce(at.idgrupo,0), at.datfim asc, at.datinicio ASC ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idgrupo' => (int)$params['idgrupo'],
        ));

        return $resultado;
    }

    public function retornaGrupoAtividade($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "select b.idatividadecronograma, b.nomatividadecronograma,
            b.idprojeto, b.idgrupo,
            " . (empty($idpessoa) ? " 'S' "
                : " case
                when (
                    select count(*) FROM agepnet200.tb_atividadeocultar vs
                    where vs.idprojeto=b.idprojeto and vs.idatividadecronograma=b.idatividadecronograma
                    and vs.idpessoa =" . $idpessoa . "
                )>0 THEN 'N' ELSE 'S'
                end ") . " as flashowhide,
            b.numseq
            from agepnet200.tb_atividadecronograma b
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = b.idprojeto
                      and tbp.idtipoiniciativa = 1 /* PROJETO */
            where(
                b.idatividadecronograma in(
                    select e.idgrupo from agepnet200.tb_atividadecronograma e
                    where e.idatividadecronograma =:idatividadecronograma
                    and e.idprojeto=b.idprojeto
                    and e.domtipoatividade=2
                )
                or b.idatividadecronograma in(
                    select c.idgrupo from agepnet200.tb_atividadecronograma c
                    where c.idatividadecronograma in(
                      select d.idgrupo from agepnet200.tb_atividadecronograma d
                      where d.idatividadecronograma=:idatividadecronograma
                      and d.idprojeto=c.idprojeto
                    )
                    and c.idprojeto=b.idprojeto
                    and c.domtipoatividade=2
                )
            )
            and b.idprojeto=:idprojeto
            and b.domtipoatividade=1
            group by
              b.idatividadecronograma, b.nomatividadecronograma,
              b.idprojeto, b.idgrupo, 5, b.numseq ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));
        return $resultado;
    }

    public function retornaHierarquiaAtividadesProjetos($params)
    {
        $sql = "select A.idprojeto,
                A.idatividadecronograma idatividadecronogramapai, coalesce(A.idgrupo,0) idgrupopai,
                a.numpercentualconcluido numpercconclpai,
                A.nomatividadecronograma nomatividadepai, A.domtipoatividade domatividadepai,
                (select trim(t.nomtipo) from agepnet200.tb_tiposituacaoprojeto t
                where t.idtipo = A.domtipoatividade) as nomdomatividadepai,
                a.desobs desobspai, to_char(a.datcadastro,'DD/MM/YYYY') as datcadastropai,
                a.idmarcoanterior idmarcoanteriorpai, a.numdias numdiaspai,
                a.vlratividadebaseline vlratividadebaselinepai, a.vlratividade vlratividadepai,
                a.numfolga numfolgapai, a.descriterioaceitacao descriterioaceitacaopai,
                a.idelementodespesa idelementodespesapai, a.idcadastrador idcadastradorpai,
                a.idparteinteressada idparteinteressadapai,
		        (SELECT pes.nompessoa FROM agepnet200.tb_pessoa pes, agepnet200.tb_parteinteressada pa
		        WHERE pa.idprojeto = :idprojeto and pes.idpessoa = idpessoainterna
		        and pa.idparteinteressada = a.idparteinteressada) as nomparteinteressadapai,
                to_char(a.datiniciobaseline,'DD/MM/YYYY') as datiniciobaselinepai,
                to_char(a.datfimbaseline,'DD/MM/YYYY') as datfimbaselinepai,
                a.flaaquisicao flaaquisicaopai, a.flainformatica flainformaticapai, a.flaordenacao flaordenacaopai,
                a.flacancelada flacanceladapai,
                to_char(a.datinicio,'DD/MM/YYYY') as datiniciopai,
                to_char(a.datfim,'DD/MM/YYYY') as datfimpai,
                NULL as descricaoprazopai, NULL as prazopai,
                b.* from agepnet200.tb_atividadecronograma A
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = A.idprojeto
                      and tbp.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                LEFT JOIN
                (select C.idatividadecronograma idatividadecronogramafilho, coalesce(c.idgrupo,0) idgrupofilho,
                    c.numpercentualconcluido numpercconclfilho, C.nomatividadecronograma nomatividadefilho,
                    c.domtipoatividade domatividadefilho,(select trim(t.nomtipo) from agepnet200.tb_tiposituacaoprojeto t
                    where t.idtipo = c.domtipoatividade) as nomdomatividadefilho,c.desobs desobsfilho,
                    to_char(c.datcadastro,'DD/MM/YYYY') as datcadastrofilho,
                    c.idmarcoanterior idmarcoanteriorfilho, c.numdias numdiasfilho,
                    c.vlratividadebaseline vlratividadebaselinefilho, c.vlratividade vlratividadefilho,
                    c.numfolga numfolgafilho, c.descriterioaceitacao descriterioaceitacaofilho,
                    c.idelementodespesa idelementodespesafilho, c.idcadastrador idcadastradorfilho,
                    c.idparteinteressada idparteinteressadafilho, (SELECT pes.nompessoa FROM agepnet200.tb_pessoa pes,
		            agepnet200.tb_parteinteressada pf WHERE pf.idprojeto = :idprojeto and pes.idpessoa = pf.idpessoainterna
		            and pf.idparteinteressada = c.idparteinteressada) as nomparteinteressadafilho,
                    to_char(c.datiniciobaseline,'DD/MM/YYYY') as datiniciobaselinefilho,
                    to_char(c.datfimbaseline,'DD/MM/YYYY') as datfimbaselinefilho,
                    c.flaaquisicao flaaquisicaofilho, c.flainformatica flainformaticafilho, c.flaordenacao flaordenacaofilho,
                    c.flacancelada flacanceladafilho,
                    to_char(c.datinicio,'DD/MM/YYYY') as datiniciofilho,
                    to_char(c.datfim,'DD/MM/YYYY') as datfimfilho,
                    C.datfim dtfimfilho, C.datinicio dtinifilho,
                    NULL as descricaoprazofilho, NULL as prazofilho
                    from agepnet200.tb_atividadecronograma c
                    where c.idprojeto=:idprojeto  ORDER BY c.datfim desc, c.datinicio desc
                )as B
                on b.idgrupofilho=A.idatividadecronograma
                where A.idprojeto=:idprojeto
                ORDER BY A.domtipoatividade asc, coalesce(A.idgrupo,0) , A.datfim asc, A.datinicio asc, b.dtfimfilho asc, b.dtinifilho asc
                ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));
        return $resultado;
    }

    public function retornaFeriadosFixos()
    {
        $sql = "SELECT f.diaferiado, 
                       f.mesferiado, 
                       f.anoferiado, 
                       f.flaativo 
                  FROM agepnet200.tb_feriado f 
                 WHERE f.tipoferiado IN ('1', '2') 
                   AND f.flaativo = 'S' ";

        return $this->_db->fetchAll($sql);
    }

    public function atualizaNumseq($params)
    {
        $sql = 'SELECT agepnet200."AtualizarNumseqAtividade"(:idprojeto)';
        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));
        return $resultado;
    }

    public function atualizaSucessoras($params)
    {
        $sql = 'SELECT agepnet200."AtualizarAtividadeSucessora"(:idprojeto, :idatividadecronograma)';
        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));
        return $resultado;
    }

    public function atualizarPercentualProjeto($params)
    {
        $sql = 'SELECT agepnet200."AtualizarPercentualProjeto"(:idprojeto)';
        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));
        return $resultado;
    }

    public function copiarCronograma($params)
    {
        $sql = 'SELECT agepnet200."ClonarCronograma"(:idprojetobase, :idprojetonovo, :idcadastrador)';

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojetobase' => (int)$params['idprojeto'],
            'idprojetonovo' => (int)$params['idprojetoorigem'],
            'idcadastrador' => (int)$params['idcadastrador']
        ));

        return $resultado;
    }

    public function verificaCountNumSeq($params)
    {
        $sql = 'SELECT COUNT(numseq) from agepnet200.tb_atividadecronograma where idprojeto = :idprojeto and numseq = 1';
        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));
        return $resultado;
    }

    public function retornaDataAtividadeconcluida($params)
    {
        $sql = 'SELECT datatividadeconcluida 
                  FROM agepnet200.tb_atividadecronograma 
                 WHERE idprojeto = :idprojeto 
                   AND idatividadecronograma = :idatividadecronograma';

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));
        return $resultado;
    }

    public function retornaQtdeDiasUteisEntreDatas($params)
    {
        $sql = "SELECT count(*) AS diasuteis
                FROM generate_series(
                    to_timestamp(:datinicio || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                    to_timestamp(:datfim    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                ) the_day
                WHERE  extract('ISODOW' FROM the_day) < 6
                and to_char(the_day,'dd/mm') not in(
                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                )
                and to_char(the_day,'dd/mm/yyyy') not in(
                   SELECT
                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                ) ";
        $retorno = $this->_db->fetchRow($sql,
            array(
                'datinicio' => $params['datainicio'],
                'datfim' => $params['datafim'],
            )
        );
        return $retorno['diasuteis'];
    }

    public function retornaDataFimValidaPorDias($params)
    {
        $sql = 'SELECT agepnet200."CalcularDataFim"(:datainicio,:numdias) as datafim';
        $retorno = $this->_db->fetchRow($sql,
            array(
                'datainicio' => $params['datainicio'],
                'numdias' => $params['numdias'],
            )
        );
        return $retorno['datafim'];
    }

    public function retornaDataAnteriorValidaPorDias($params)
    {
        $sql = 'SELECT agepnet200."CalcularDataAnterior"(:datainicio,:numdias) as dataanterior';
        $retorno = $this->_db->fetchRow($sql,
            array(
                'datainicio' => $params['datainicio'],
                'numdias' => $params['numdias'],
            )
        );
        return $retorno['dataanterior'];
    }

    public function retornaDataFeriado($params)
    {
        $sql = "SELECT count(*) contadata
        FROM agepnet200.tb_feriado tf where (
            tf.flaativo='S' AND tf.tipoferiado='1' and
            lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') = substr(:data,1,5)
        ) OR (
            tf.flaativo='S' AND tf.tipoferiado='2' and
                lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') = :data
        ) ";
        $retorno = $this->_db->fetchRow($sql,
            array(
                'data' => $params['data'],
            )
        );
        return $retorno['contadata'];
    }

    public function verificaQtdeAtivPorProjeto($params)
    {
        $sql = 'SELECT count(idatividadecronograma) 
            FROM agepnet200.tb_atividadecronograma 
            WHERE idprojeto = :idprojeto ';

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
        ));
        return $resultado;
    }

    public static function getSqlRecursiveAtividade()
    {
        $atividade = new self();

        return $atividade->_getSqlRecursive();
    }

    public function _getSqlRecursive()
    {
        $sql = "WITH RECURSIVE atividade(idatividadecronograma, numseq, idprojeto, idgrupo, numpercentualconcluido,
                               nomatividadecronograma, domtipoatividade, desobs, datcadastro, idmarcoanterior,
                               numdias, vlratividadebaseline, vlratividade, numfolga, descriterioaceitacao,
                               idelementodespesa, idcadastrador, datiniciobaseline, datfimbaseline,
                               flaaquisicao, flainformatica, flaordenacao, flacancelada, datinicio, datfim,
                               numcriteriofarol, nomparteinteressada, responsavelaceitacao, numdiasbaseline, numdiascompletos,
                               numdiasrealizados, numdiasrealizadosreal, atraso, atividadeatrasada, predecessoras, 
                               idparteinteressada, pai, ordenacao, nivel) AS (
                     SELECT cron.idatividadecronograma,
                            cron.numseq,
                            cron.idprojeto,
                            cron.idgrupo,
                            cron.numpercentualconcluido,
                            cron.nomatividadecronograma,
                            cron.domtipoatividade,
                            cron.desobs,
                            cron.datcadastro,
                            cron.idmarcoanterior,
                            cron.numdias,
                            cron.vlratividadebaseline,
                            cron.vlratividade,
                            cron.numfolga,
                            cron.descriterioaceitacao,
                            cron.idelementodespesa,
                            cron.idcadastrador,
                            TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') AS datiniciobaseline,
                            TO_CHAR(cron.datfimbaseline, 'DD/MM/YYYY') AS datfimbaseline,
                            cron.flaaquisicao,
                            cron.flainformatica,
                            cron.flaordenacao,
                            cron.flacancelada,
                            TO_CHAR(cron.datinicio, 'DD/MM/YYYY') AS datinicio,
                            TO_CHAR(cron.datfim, 'DD/MM/YYYY') AS datfim,
                            tbp.numcriteriofarol,
                            ra.nomparteinteressada,
                            NULL::VARCHAR AS responsavelaceitacao,
                            CASE
                                WHEN cron.datfimbaseline IS NULL OR cron.datiniciobaseline IS NULL OR cron.domtipoatividade = 4 THEN '0'
                                ELSE (SELECT COUNT(*) AS diasuteis
                                        FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                        TO_TIMESTAMP(TO_CHAR(cron.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                                        WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                        AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                                                                     LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0')
                                      FROM agepnet200.tb_feriado tf
                                      WHERE tf.flaativo = 'S'
                                      AND tf.tipoferiado = '1')
                                      AND TO_CHAR(the_day, 'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
                                                                                        LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
                                                                                        LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
                                                                                 FROM agepnet200.tb_feriado tf
                                                                                 WHERE tf.flaativo = 'S' AND tf.tipoferiado = '2'))
                            END AS numdiasbaseline,
                            CASE
                                WHEN cron.datfimbaseline IS NULL OR cron.datiniciobaseline IS NULL OR cron.domtipoatividade = 4 THEN '0'
                                WHEN TO_DATE(TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY'), 'DD/MM/YYYY') > TO_DATE(TO_CHAR(now(), 'DD/MM/YYYY'), 'DD/MM/YYYY') THEN '0'
                                WHEN TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') = TO_CHAR(now(), 'DD/MM/YYYY') THEN '0'
                                WHEN TO_DATE(TO_CHAR(cron.datfimbaseline, 'DD/MM/YYYY'), 'DD/MM/YYYY') > TO_DATE(TO_CHAR(now(), 'DD/MM/YYYY'), 'DD/MM/YYYY') THEN
                                    (SELECT COUNT(*) AS diasuteis
                                      FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                                   TO_TIMESTAMP(TO_CHAR(now(), 'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours') the_day
                                     WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                       AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                                               LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')),2,'0') AS ddmm
                                                          FROM agepnet200.tb_feriado tf
                                                         WHERE tf.flaativo = 'S'
                                                           AND tf.tipoferiado = '1')
                                       AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
                                                                LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
                                                                LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
                                                               FROM agepnet200.tb_feriado tf
                                                              WHERE tf.flaativo = 'S'
                                                            AND tf.tipoferiado = '2'))
                                WHEN TO_DATE(TO_CHAR(cron.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(TO_CHAR(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') THEN
                                    (SELECT COUNT(*) AS diasuteis
                                       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                                TO_TIMESTAMP(TO_CHAR(cron.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                                      WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                        AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                                            LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')),2,'0') AS ddmm
                                                           FROM agepnet200.tb_feriado tf
                                                          WHERE tf.flaativo = 'S'
                                                            AND tf.tipoferiado = '1')
                                        AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0')|| '/' ||
                                                                 LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   , 2, '0')|| '/' ||
                                                                 LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) , 4, '0') AS ddmmaaaa
                                                            FROM agepnet200.tb_feriado tf
                                                               WHERE tf.flaativo = 'S'
                                                               AND tf.tipoferiado = '2'))
                                ELSE
                                    (SELECT COUNT(*) AS diasuteis
                                       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                                TO_TIMESTAMP(TO_CHAR(cron.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours') the_day
                                      WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                        AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')),2,'0') || '/' ||
                                                            LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')),2,'0') AS ddmm
                                                           FROM agepnet200.tb_feriado tf
                                                          WHERE tf.flaativo = 'S'
                                                           AND tf.tipoferiado = '1')
                                        AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0') || '/' ||
                                                                 LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   , 2, '0') || '/' ||
                                                                 LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) , 4, '0') AS ddmmaaaa
                                                            FROM agepnet200.tb_feriado tf
                                                               WHERE tf.flaativo = 'S'
                                                             AND tf.tipoferiado = '2'))
                            END AS numdiascompletos,
                            CASE WHEN cron.datfim IS NULL OR cron.datinicio IS NULL OR cron.domtipoatividade = 4 THEN '0'
                                 ELSE
                                    (SELECT COUNT(*) AS diasuteis
                                       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                                TO_TIMESTAMP(TO_CHAR(cron.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                                      WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                        AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                                            LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0') AS ddmm
                                                           FROM agepnet200.tb_feriado tf
                                                          WHERE tf.flaativo = 'S'
                                                            AND tf.tipoferiado = '1')
                                        AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0') || '/' ||
                                                                 LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2,'0') || '/' ||
                                                                 LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4,'0') AS ddmmaaaa

                                            FROM agepnet200.tb_feriado tf
                                                               WHERE tf.flaativo = 'S'
                                                             AND tf.tipoferiado = '2'))
                            END AS numdiasrealizados,
                            COALESCE(ROUND(CASE
                                WHEN cron.datfim IS NULL OR cron.datinicio IS NULL OR cron.domtipoatividade = 4 THEN '0'
                                ELSE
                                    (((SELECT COUNT(*) AS diasuteis
                                         FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                                  TO_TIMESTAMP(TO_CHAR(cron.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                                        WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                          AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                                               LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0') AS ddmm
                                                              FROM agepnet200.tb_feriado tf
                                                             WHERE tf.flaativo = 'S'
                                                               AND tf.tipoferiado = '1')
                                          AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0')||'/'||
                                                                   LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   , 2, '0')||'/'||
                                                                   LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) , 4, '0') AS ddmmaaaa

                                      FROM agepnet200.tb_feriado tf
                                                         WHERE tf.flaativo = 'S'
                                                          AND tf.tipoferiado = '2'))
                                    * COALESCE(cron.numpercentualconcluido, 0)) / 100)
                            END), 1) AS numdiasrealizadosreal,
                            CASE
                                WHEN cron.datfim IS NULL OR cron.datfimbaseline IS NULL OR cron.datfim = cron.datfimbaseline THEN 0 
                                ELSE (SELECT COUNT(*) AS diasuteis
                                FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR((CASE WHEN cron.datfimbaseline > cron.datfim THEN cron.datfim ELSE cron.datfimbaseline END), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                         TO_TIMESTAMP(TO_CHAR((CASE WHEN cron.datfimbaseline < cron.datfim THEN cron.datfim ELSE cron.datfimbaseline END), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                                   WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                 AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                              LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0')
                                             FROM agepnet200.tb_feriado tf
                                            WHERE tf.flaativo = 'S'
                                              AND tf.tipoferiado = '1')
                                 AND TO_CHAR(the_day, 'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
                                               LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
                                               LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
                                              FROM agepnet200.tb_feriado tf
                                             WHERE tf.flaativo = 'S' AND tf.tipoferiado = '2')
                                )  
                             END AS atraso,
                            cron.datfimbaseline < cron.datfim AS atividadeatrasada,
                            NULL::VARCHAR AS predecessoras,
                            ra.idparteinteressada,
                            cron.idatividadecronograma AS pai,
                            ARRAY[ROW_NUMBER() OVER (ORDER BY COALESCE(cron.datfim, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,'YYYY-MM-DD')), COALESCE(cron.datinicio, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,'YYYY-MM-DD')), cron.idatividadecronograma)] AS ordenacao,
                            1 AS nivel
                            FROM agepnet200.tb_atividadecronograma cron
                            JOIN agepnet200.tb_projeto tbp
                              ON tbp.idprojeto = cron.idprojeto
                             AND tbp.idtipoiniciativa = 1
                           LEFT JOIN agepnet200.tb_parteinteressada ra
                              ON ra.idparteinteressada = cron.idparteinteressada
                              AND ra.idprojeto = cron.idprojeto                              
                           WHERE cron.idprojeto = :idprojeto
                             AND cron.domtipoatividade = 1

                      UNION ALL

                     SELECT cron.idatividadecronograma,
                            cron.numseq,
                            cron.idprojeto,
                            cron.idgrupo,
                            cron.numpercentualconcluido,
                            cron.nomatividadecronograma,
                            cron.domtipoatividade,
                            cron.desobs,
                            cron.datcadastro,
                            cron.idmarcoanterior,
                            cron.numdias,
                            cron.vlratividadebaseline,
                            cron.vlratividade,
                            cron.numfolga,
                            cron.descriterioaceitacao,
                            cron.idelementodespesa,
                            cron.idcadastrador,
                            TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') AS datiniciobaseline,
                            TO_CHAR(cron.datfimbaseline, 'DD/MM/YYYY') AS datfimbaseline,
                            cron.flaaquisicao,
                            cron.flainformatica,
                            cron.flaordenacao,
                            cron.flacancelada,
                            TO_CHAR(cron.datinicio, 'DD/MM/YYYY') AS datinicio,
                            TO_CHAR(cron.datfim, 'DD/MM/YYYY') AS datfim,
                            ati.numcriteriofarol,
                            ra.nomparteinteressada,
                            re.nomparteinteressada as responsavelaceitacao,
                            CASE
                                WHEN cron.datfimbaseline IS NULL OR cron.datiniciobaseline IS NULL OR cron.domtipoatividade = 4 THEN '0'
                                ELSE (SELECT COUNT(*) AS diasuteis
                                    FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                                 TO_TIMESTAMP(TO_CHAR(cron.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                                       WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                     AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                                              LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0')
                                                         FROM agepnet200.tb_feriado tf
                                                        WHERE tf.flaativo = 'S'
                                                          AND tf.tipoferiado = '1')
                                     AND TO_CHAR(the_day, 'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
                                                               LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
                                                               LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
                                                              FROM agepnet200.tb_feriado tf
                                                             WHERE tf.flaativo = 'S' AND tf.tipoferiado = '2'))
                             END AS numdiasbaseline,
                            CASE
                                WHEN cron.datfimbaseline IS NULL OR cron.datiniciobaseline IS NULL OR cron.domtipoatividade = 4 THEN '0'
                                WHEN TO_DATE(TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY'), 'DD/MM/YYYY') > TO_DATE(TO_CHAR(now(), 'DD/MM/YYYY'), 'DD/MM/YYYY') THEN '0'
                                WHEN TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') = TO_CHAR(now(), 'DD/MM/YYYY') THEN '0'
                                WHEN TO_DATE(TO_CHAR(cron.datfimbaseline, 'DD/MM/YYYY'), 'DD/MM/YYYY') > TO_DATE(TO_CHAR(now(), 'DD/MM/YYYY'), 'DD/MM/YYYY') THEN
                                    (SELECT COUNT(*) AS diasuteis
                                      FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                                   TO_TIMESTAMP(TO_CHAR(now(), 'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours') the_day
                                     WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                       AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                                               LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')),2,'0') AS ddmm
                                                          FROM agepnet200.tb_feriado tf
                                                         WHERE tf.flaativo = 'S'
                                                           AND tf.tipoferiado = '1')
                                       AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
                                                                LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
                                                                LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
                                                               FROM agepnet200.tb_feriado tf
                                                              WHERE tf.flaativo = 'S'
                                                            AND tf.tipoferiado = '2'))
                                WHEN TO_DATE(TO_CHAR(cron.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(TO_CHAR(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') THEN
                                    (SELECT COUNT(*) AS diasuteis
                                       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                                TO_TIMESTAMP(TO_CHAR(cron.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                                      WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                        AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                                            LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')),2,'0') AS ddmm
                                                           FROM agepnet200.tb_feriado tf
                                                          WHERE tf.flaativo = 'S'
                                                            AND tf.tipoferiado = '1')
                                        AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0')|| '/' ||
                                                                 LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   , 2, '0')|| '/' ||
                                                                 LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) , 4, '0') AS ddmmaaaa
                                                            FROM agepnet200.tb_feriado tf
                                                               WHERE tf.flaativo = 'S'
                                                               AND tf.tipoferiado = '2'))
                                ELSE
                                    (SELECT COUNT(*) AS diasuteis
                                       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                                TO_TIMESTAMP(TO_CHAR(cron.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours') the_day
                                      WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                        AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')),2,'0') || '/' ||
                                                            LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')),2,'0') AS ddmm
                                                           FROM agepnet200.tb_feriado tf
                                                          WHERE tf.flaativo = 'S'
                                                           AND tf.tipoferiado = '1')
                                        AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0') || '/' ||
                                                                 LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   , 2, '0') || '/' ||
                                                                 LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) , 4, '0') AS ddmmaaaa
                                                            FROM agepnet200.tb_feriado tf
                                                               WHERE tf.flaativo = 'S'
                                                             AND tf.tipoferiado = '2'))
                              END AS numdiascompletos,
                             CASE WHEN cron.datfim IS NULL OR cron.datinicio IS NULL OR cron.domtipoatividade = 4 THEN '0'
                                  ELSE

                                (SELECT COUNT(*) AS diasuteis
                                   FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                            TO_TIMESTAMP(TO_CHAR(cron.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                                  WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                    AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                                        LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0') AS ddmm
                                                       FROM agepnet200.tb_feriado tf
                                                      WHERE tf.flaativo = 'S'
                                                        AND tf.tipoferiado = '1')
                                    AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0') || '/' ||
                                                             LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2,'0') || '/' ||
                                                             LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4,'0') AS ddmmaaaa

                                        FROM agepnet200.tb_feriado tf
                                                           WHERE tf.flaativo = 'S'
                                                         AND tf.tipoferiado = '2'))
                             END AS numdiasrealizados,
                             COALESCE(ROUND(CASE
                                WHEN cron.datfim IS NULL OR cron.datinicio IS NULL OR cron.domtipoatividade = 4 THEN '0'
                                ELSE
                                    (((SELECT COUNT(*) AS diasuteis
                                         FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(cron.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                                  TO_TIMESTAMP(TO_CHAR(cron.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                                        WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                          AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                                               LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0') AS ddmm
                                                              FROM agepnet200.tb_feriado tf
                                                             WHERE tf.flaativo = 'S'
                                                               AND tf.tipoferiado = '1')
                                          AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0')||'/'||
                                                                   LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   , 2, '0')||'/'||
                                                                   LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) , 4, '0') AS ddmmaaaa

                                      FROM agepnet200.tb_feriado tf
                                                         WHERE tf.flaativo = 'S'
                                                          AND tf.tipoferiado = '2'))
                                 * COALESCE(cron.numpercentualconcluido, 0)) / 100)
                            END), 1) AS numdiasrealizadosreal,
                            CASE
                                WHEN cron.datfim IS NULL OR cron.datfimbaseline IS NULL OR cron.datfim = cron.datfimbaseline THEN 0 
                                ELSE (SELECT COUNT(*) AS diasuteis
                                FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR((CASE WHEN cron.datfimbaseline > cron.datfim THEN cron.datfim ELSE cron.datfimbaseline END), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                         TO_TIMESTAMP(TO_CHAR((CASE WHEN cron.datfimbaseline < cron.datfim THEN cron.datfim ELSE cron.datfimbaseline END), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                                   WHERE EXTRACT('ISODOW' FROM the_day) < 6
                                 AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                              LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0')
                                             FROM agepnet200.tb_feriado tf
                                            WHERE tf.flaativo = 'S'
                                              AND tf.tipoferiado = '1')
                                 AND TO_CHAR(the_day, 'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
                                               LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
                                               LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
                                              FROM agepnet200.tb_feriado tf
                                             WHERE tf.flaativo = 'S' AND tf.tipoferiado = '2')
                                )  
                             END AS atraso,
                            cron.datfimbaseline < cron.datfim AS atividadeatrasada,
                            CASE
                                WHEN cron.domtipoatividade = 3 or cron.domtipoatividade = 4 THEN
                                     (SELECT ARRAY_TO_STRING(ARRAY_AGG(d.idatividadepredecessora::VARCHAR ||  '#-#' || d.numseq::VARCHAR ||  '#-#' || d.nomatividadecronograma ||  '#-#' || TO_CHAR(d.datinicio, 'DD/MM/YYYY')  ||  '#-#' || TO_CHAR(d.datfim, 'DD/MM/YYYY')), '&&&&&', '')
                                        FROM 
                                           (SELECT ap.idatividadepredecessora,
                                             ac.numseq,
                                             ac.nomatividadecronograma, 
                                             ac.datinicio, 
                                             ac.datfim
                                        FROM agepnet200.tb_atividadecronopredecessora ap
                                        JOIN agepnet200.tb_atividadecronograma ac	
                                          ON ac.idatividadecronograma = ap.idatividadepredecessora
                                         AND ac.idprojeto = ap.idprojetocronograma
                                       WHERE ap.idprojetocronograma = cron.idprojeto
                                         AND ap.idatividadecronograma = cron.idatividadecronograma 
                                       ORDER BY ac.datfim
                                        ) d )
                                ELSE
                                    NULL
                             END AS predecessoras,
                            ra.idparteinteressada,
                            ati.pai,
                            ati.ordenacao || (ROW_NUMBER() OVER (ORDER BY COALESCE(cron.datfim, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,'YYYY-MM-DD')), COALESCE(cron.datinicio, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,'YYYY-MM-DD')), cron.domtipoatividade, cron.idatividadecronograma)) AS ordenacao,
                            ati.nivel + 1 AS nivel
                        FROM agepnet200.tb_atividadecronograma cron
                        JOIN atividade ati
                          ON ati.idatividadecronograma = cron.idgrupo
                        LEFT JOIN agepnet200.tb_parteinteressada re
                          ON re.idparteinteressada = cron.idresponsavel
                          and re.idprojeto = cron.idprojeto
                        LEFT JOIN agepnet200.tb_parteinteressada ra
                          ON ra.idparteinteressada = cron.idparteinteressada
                          and ra.idprojeto = cron.idprojeto
                        WHERE cron.idprojeto = :idprojeto
                       )";
        return $sql;
    }
}