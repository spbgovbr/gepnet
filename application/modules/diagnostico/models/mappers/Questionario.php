<?php

/**
 * Newton Carlos
 *
 * Criado em 30-10-2018
 * 15:58
 */
class Diagnostico_Model_Mapper_Questionario extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     *
     * @param string $value
     * @return Diagnostico_Model_Questionario
     */
    public function insert(Diagnostico_Model_Questionario $model)
    {
        try {
            $data = array(
                "idquestionariodiagnostico" => null,
                "nomquestionario" => $model->nomquestionario,
                "tipo" => $model->tipo,
                "observacao" => $model->observacao,
                "idpescadastrador" => $model->idpescadastrador,
                "dtcadastro" => new Zend_Db_Expr("now()"),
            );

            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
        } catch (Zend_Db_Table_Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param Diagnostico_Model_Questionario $model
     * @return Diagnostico_Model_Questionario
     */
    public function update($model)
    {
        $data = array(
            "idquestionariodiagnostico" => $model->idquestionariodiagnostico,
            "nomquestionario" => $model->nomquestionario,
            "tipo" => $model->tipo,
            "observacao" => $model->observacao,
        );

        try {
            $pks = array(
                "idquestionariodiagnostico" => $model->idquestionariodiagnostico,
            );

            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $model->idquestionariodiagnostico;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    function inverteData($data)
    {
        $parteData = explode("/", $data);
        $dataInvertida = $parteData[2] . "-" . $parteData[1] . "-" . $parteData[0];
        return $dataInvertida;
    }

    public function getForm()
    {
        return $this->_getForm(Diagnostico_Form_Diagnostico);
    }

    public function atualizaStatusQuestionario($params)
    {
        $data = array(
            "dtrespondido" => new Zend_Db_Expr("now()"),
            "respondido" => true,
        );

        try {
            $pks = array(
                "idquestionariodiagnostico" => $params['idquestionariodiagnostico'],
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }


    public function getById($params)
    {
        $sql = "SELECT idquestionariodiagnostico, nomquestionario, tipo, observacao
                  FROM agepnet200.tb_questionario_diagnostico
                  WHERE idquestionariodiagnostico = :idquestionariodiagnostico";

        $resultado = $this->_db->fetchRow(
            $sql, array(
                'idquestionariodiagnostico' => (int)$params['idquestionariodiagnostico']
            )
        );

        $diagnostico = new Diagnostico_Model_Questionario($resultado);
        return $diagnostico;
    }

    public function fetchPairsQuestionario($params)
    {
        $iddiagnostico = (int)$params['iddiagnostico'];
        $tpquestionario = $params['tpquestionario'];

        $sql = "  SELECT q.idquestionariodiagnostico, q.nomquestionario
                  FROM agepnet200.tb_questionario_diagnostico q
                  WHERE q.idquestionariodiagnostico NOT IN(
                        SELECT idquestionario
                         FROM agepnet200.tb_vincula_questionario
                         WHERE iddiagnostico IN({$iddiagnostico}))
                  AND q.tipo IN('{$tpquestionario}')
                  ORDER BY q.nomquestionario";
        $resultado = $this->_db->fetchPairs($sql);

        return $resultado;
    }

    public function fetchPairsQuestionarioVinculado($params)
    {
        $iddiagnostico = (int)$params['iddiagnostico'];
        $tpquestionario = $params['tpquestionario'];
        $sql = "  SELECT q.idquestionariodiagnostico, q.nomquestionario
                  FROM agepnet200.tb_questionario_diagnostico q
                  WHERE q.idquestionariodiagnostico IN(
                        SELECT idquestionario
                         FROM agepnet200.tb_vincula_questionario
                         WHERE iddiagnostico IN(" . $iddiagnostico . "))
                         AND q.tipo IN('" . $tpquestionario . "')
                  ORDER BY q.nomquestionario";
        $resultado = $this->_db->fetchPairs($sql);

        return $resultado;
    }

    public function getByResposta($params)
    {
        $sql = "SELECT idresposta, idpergunta, desresposta, escala, COALESCE(ordenacao,0) AS ordenacao
                  FROM agepnet200.tb_opcao_resposta
                  WHERE idpergunta = :idpergunta ";
        return $this->_db->fetchAll($sql, array(
                'idpergunta' => (int)$params['idpergunta']
            )
        );
    }

    /**
     * Lista todos os diagnósticos da tabela.
     * @param int $iddiagnostico
     * @param array $params
     * return array
     */
    public function listar($params = array())
    {
        $params = array_filter($params);

        $sql = "SELECT qd.nomquestionario, 
                    CASE 
                    WHEN (qd.tipo = '1') THEN 'Servidor'
                    ELSE 'Cidadão'
                    END AS tipo,
                    td.dsdiagnostico, to_char(qd.dtcadastro, 'DD/MM/YYYY'),
                    qd.idquestionariodiagnostico,
                    qd.dtcadastro
                FROM agepnet200.tb_questionario_diagnostico qd
                LEFT JOIN agepnet200.tb_vincula_questionario vq
                ON vq.idquestionario = qd.idquestionariodiagnostico
                LEFT JOIN agepnet200.tb_diagnostico td
		ON td.iddiagnostico = vq.iddiagnostico
                WHERE 1 = 1 ";
        $params = array_filter($params);
        if (isset($params['nomquestionario']) && (!empty($params['nomquestionario']))) {
            $nomquestionario = mb_strtoupper($params['nomquestionario']);
            $sql .= " AND upper(qd.nomquestionario) LIKE '%{$nomquestionario}%' ";
        }
        if (isset($params['tipo']) && (!empty($params['tipo']))) {
            $sql .= " AND qd.tipo = '{$params['tipo']}'";
        }
        if (isset($params['dtcadastro']) && (!empty($params['dtcadastro']))) {
            $sql .= " AND to_char(qd.dtcadastro, 'DD/MM/YYYY') = '{$params['dtcadastro']}' ";
        }
        if (isset($params['idunidadeprincipal']) && (!empty($params['idunidadeprincipal']))) {
            $sql .= " AND qd.idunidadeprincipal = {$params['idunidadeprincipal']} ";
        }
        if (isset($params['iddiagnostico']) && (!empty($params['iddiagnostico']))) {
            $sql .= " AND vq.iddiagnostico = {$params['iddiagnostico']} ";
        }
        //$sql.= " ORDER BY qd.dtcadastro DESC ";
        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }
        try {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        } catch (Exception $exc) {
            throw new Exception($exc->code());
        }
    }

    public function delete($params = array())
    {
        $pks = array("idquestionariodiagnostico" => $params['idquestionario']);
        $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
        $retorno = $this->getDbTable()->delete($where);
        return $retorno;
    }

    public function getQuestionarioSecaoPerguntaOpcao($idQuestionario)
    {
        $sql = "select bp.*, ts.*, rq.desresposta, rq.idresposta
                from agepnet200.tb_pergunta bp
                left join agepnet200.tb_secao ts
                on bp.id_secao = ts.id_secao
                left join agepnet200.tb_opcao_resposta rq
                on rq.idpergunta = bp.idpergunta
                where bp.idquestionario = :idquestionario
                and bp.ativa = true
                order by bp.id_secao";
        return $this->_db->fetchAll($sql, array('idquestionario' => $idQuestionario));
    }

    public function getMaxId()
    {
        $sql = "SELECT MAX(idquestionariodiagnostico) AS idquestionariodiagnostico
                FROM agepnet200.tb_questionario_diagnostico";
        $resultado = $this->_db->fetchOne($sql);
        return $resultado;
    }

}
