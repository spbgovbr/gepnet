<?php

use Default_Service_Log as Log;

class Diagnostico_Model_Mapper_QuestionarioVinculado extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     * @param Diagnostico_Model_QuestionarioVinculado $model
     * @return boolean
     */
    public function insert($model)
    {
        try {
            $dados = array(
                'idquestionario' => $model->idquestionario,
                'iddiagnostico' => $model->iddiagnostico,
                'idpesdisponibiliza' => $model->idpesdisponibiliza,
                'dtdisponibilidade' => $model->dtdisponibilidade,
                'disponivel' => $model->disponivel,
            );

            $data = array_filter($dados);

            $retorno = $this->getDbTable()->insert($data);
            return true;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    /**
     * Função que verificar questionário vinculado
     * @param array $params
     * @return boolean
     */
    public function isQuestionarioVinculado($params)
    {
        $iddiagnostico = (int)$params['iddiagnostico'];

        $sql = "SELECT
                    count(vq.idquestionario) as TOTAL
                FROM agepnet200.tb_vincula_questionario vq
                INNER JOIN agepnet200.tb_questionario_diagnostico q
                    ON  q.idquestionariodiagnostico = vq.idquestionario
                    AND q.tipo IN('{$params['tpquestionario']}')
                WHERE vq.iddiagnostico = {$iddiagnostico}
                 GROUP BY vq.iddiagnostico";

        $resultado = $this->_db->fetchRow($sql);

        return ($resultado['total'] > 0) ? true : false;
    }

    /**
     * Função que verificar se o questionário esta vinculado a um diagnostico
     * @param array $params
     * @return boolean
     */
    public function isQuestionarioVinculadoByQuestionarioAndDiagnostico($params)
    {
        $iddiagnostico = (int)$params['iddiagnostico'];
        $idquestionario = $params['idquestionario'];

        $sql = "SELECT
                    count(vq.idquestionario) AS total
                FROM agepnet200.tb_vincula_questionario vq
                INNER JOIN agepnet200.tb_questionario_diagnostico q
                    ON  q.idquestionariodiagnostico = vq.idquestionario
                    AND q.tipo IN('{$params['tpquestionario']}')
                WHERE vq.iddiagnostico = {$iddiagnostico}
                AND vq.idquestionario = {$idquestionario}
                 GROUP BY vq.iddiagnostico";

        $resultado = $this->_db->fetchRow($sql);

        return ($resultado['total'] > 0) ? true : false;
    }

    /**
     * Função que remove todos os questionários vinculados ao diagnostico.
     * @param array $params
     * @return boolean
     */
    public function removeVinculo($params)
    {
        try {
            $sql = "DELETE FROM agepnet200.tb_vincula_questionario WHERE iddiagnostico=:iddiagnostico";

            $resultado = $this->_db->fetchRow($sql, array(
                'iddiagnostico' => (int)$params['iddiagnostico']
            ));
            return true;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    public function atualizaStatusQuestionario($params)
    {
        $data = array(
            "dtencerrramento" => new Zend_Db_Expr("now()"),
            "idpesencerrou" => $params['idpesencerrou'],
            "disponivel" => Diagnostico_Model_QuestionarioVinculado::INDISPONIVEL,
        );

        try {
            $pks = array(
                "iddiagnostico" => $params['iddiagnostico'],
                "idquestionario" => $params['idquestionariodiagnostico']
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
        $sql = "SELECT
                        q.nomquestionario,
                        qr.numero,
                        qr.iddiagnostico,
                        qr.idquestionario
                FROM agepnet200.tb_questionariodiagnostico_respondido qr
                INNER JOIN agepnet200.tb_questionario_diagnostico q
                    ON q.idquestionariodiagnostico = qr.idquestionario
                    AND q.tipo IN('" . $params['tpquestionario'] . "')
                WHERE qr.idquestionario = :idquestionario
                AND qr.iddiagnostico=:iddiagnostico
                AND qr.numero=:numero";

        $resultado = $this->_db->fetchRow(
            $sql, array(
                'iddiagnostico' => (int)$params['iddiagnostico'],
                'idquestionario' => (int)$params['idquestionariodiagnostico'],
                'numero' => (int)$params['numero'],
            )
        );
        return $resultado;
    }

    public function lista($params)
    {

        $sql = "SELECT
                    q.nomquestionario,
                    q.idquestionariodiagnostico,
                    vq.iddiagnostico,
                    CASE
                      WHEN (select count(idpergunta) from agepnet200.tb_opcao_resposta o where o.idquestionario = q.idquestionariodiagnostico) = 0  THEN 'Não'
                      ELSE 'Sim'
                    END AS disponivel
                    
                FROM agepnet200.tb_questionario_diagnostico q
                INNER JOIN agepnet200.tb_vincula_questionario vq
                    ON  vq.idquestionario = q.idquestionariodiagnostico
                    AND vq.iddiagnostico = " . (int)$params['iddiagnostico'] . "
		LEFT JOIN agepnet200.tb_pergunta p
		ON p.idquestionario = q.idquestionariodiagnostico
		AND p.tiporegistro not in ('1')
                WHERE q.tipo = '" . $params['tpquestionario'] . "'";

        if (isset($params['nomquestionario']) && (!empty($params['nomquestionario']))) {
            $sql .= " AND q.nomquestionario like '%{$params['nomquestionario']}%'";
        }
        $sql .= "GROUP BY q.nomquestionario,
                    q.idquestionariodiagnostico,
                    vq.iddiagnostico,
                     vq.disponivel";
        $sql .= ' order by ' . $params['sidx'] . ' ' . $params['sord'];

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

        return;
    }
}