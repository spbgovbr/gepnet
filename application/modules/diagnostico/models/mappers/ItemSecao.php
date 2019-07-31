<?php

/**
 * Newton Carlos
 *
 * Criado em 05-11-2018
 * 12:49
 */
class Diagnostico_Model_Mapper_ItemSecao extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     *
     * @param string $value
     * @return Diagnostico_Model_ItemSecao
     */
    public function insert($dados)
    {
        try {
            $idItem = $this->maxVal('id_item');

            $data = array(
                "id_item" => $idItem,
                "ds_item" => $dados['ds_item'],
                "id_secao" => $dados['id_secao'],
                "idquestionariodiagnostico" => $dados['idquestionariodiagnostico'],
            );

            $data = array_filter($data);

            $retorno = $this->getDbTable()->insert($data);

            return $retorno;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param Diagnostico_Model_ItemSecao $model
     * @return Diagnostico_Model_ItemSecao
     */
    public function update($model)
    {
        $data = array(
            "id_item" => (int)$model->id_item,
            "ds_item" => $model->ds_item,
            "id_secao" => (int)$model->id_secao,
            "ativo" => true,
            "idquestionariodiagnostico" => $model->idquestionariodiagnostico,
        );

        try {
            $pks = array(
                "id_item" => $model->id_item,
            );

            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $model->idquestionariodiagnostico;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    public function getForm()
    {
        return $this->_getForm(Diagnostico_Form_ItemSecao);
    }

    public function getById($params)
    {
        $sql = "SELECT id_item, ds_item, id_secao, idquestionariodiagnostico
                  FROM agepnet200.tb_item_secao
                  WHERE idquestionariodiagnostico = :idquestionariodiagnostico";

        $resultado = $this->_db->fetchRow(
            $sql, array(
                'idquestionariodiagnostico' => (int)$params['idquestionariodiagnostico']
            )
        );

        $secao = new Diagnostico_Model_ItemSecao($resultado);
        return $secao;
    }

    public function getByIdClone($params)
    {
        $sql = "SELECT *
                  FROM agepnet200.tb_item_secao
                  WHERE idquestionariodiagnostico = {$params['idquestionariodiagnostico']}";

        return $this->_db->fetchAll($sql);
    }

    public function fetchPairsSecoes($param = null)
    {
        $tpquestionario = ($param['tpquestionario'] == '1') ? 'S' : 'C';

        $sql = "SELECT ARRAY[s.id_secao::BIGINT, COUNT(p.id_secao)] AS id_secao, s.ds_secao
                FROM agepnet200.tb_secao s
                LEFT JOIN agepnet200.tb_pergunta p
                  ON p.idquestionario = {$param['idquestionariodiagnostico']} AND p.id_secao = s.id_secao
                WHERE s.tp_questionario='{$tpquestionario}'
                AND s.id_secao NOT IN (SELECT i.id_secao
                                       FROM agepnet200.tb_item_secao i
                                       WHERE i.idquestionariodiagnostico = {$param['idquestionariodiagnostico']})
                GROUP BY s.id_secao, s.ds_secao ORDER BY s.ds_secao ";

        return $this->_db->fetchPairs($sql);
    }

    public function fetchPairQuest($param = null)
    {
//        $sql = "SELECT i.id_secao, i.ds_item,
//                FROM agepnet200.tb_item_secao i
//                WHERE idquestionariodiagnostico = {$param['idquestionariodiagnostico']}
//                ORDER BY ds_item ASC";

        $sql = "SELECT ARRAY[i.id_secao::BIGINT, COUNT(p.id_secao)] AS id_secao,
                         i.ds_item
                FROM agepnet200.tb_item_secao i
                LEFT JOIN agepnet200.tb_pergunta p
                  ON p.idquestionario = i.idquestionariodiagnostico
                 AND p.id_secao = i.id_secao
                WHERE i.idquestionariodiagnostico = {$param['idquestionariodiagnostico']}
                GROUP BY i.id_secao, i.ds_item
                ORDER BY i.ds_item ";

        $resultado = $this->_db->fetchPairs($sql);
        //Zend_Debug::dump($resultado);die;
        return $resultado;
    }

    public function fetchPairPerguntaQuest($param = null)
    {
        $sql = "SELECT id_secao, ds_secao 
            FROM agepnet200.tb_secao 
                    WHERE id_secao IN (SELECT id_secao 
                    FROM agepnet200.tb_item_secao 
                    WHERE idquestionariodiagnostico = {$param}
            )
            ORDER BY ds_secao ASC";

        return $this->_db->fetchPairs($sql);
    }

    /**
     * Lista todas as seções da tabela.
     * @param int $id_item
     * @param array $params
     * return array
     */
    public function listar($params = array())
    {
        $params = array_filter($params);
        $sql = "SELECT  dsdiagnostico, 
                        (SELECT sigla 
                        FROM   vw_comum_unidade 
                        WHERE  id_unidade = idunidadeprincipal) AS sigla, 
                        To_char(dtinicio, 'DD/MM/YYYY')           dtinicio, 
                        To_char(dtencerramento, 'DD/MM/YYYY')     dtencerramento, 
                        d.iddiagnostico, 
                        (SELECT unidade_responsavel 
                        FROM   vw_comum_unidade 
                        WHERE  id_unidade = idunidadeprincipal) AS idunidadeprincipal,
                        d.ativo
                FROM   agepnet200.tb_diagnostico d 
                        INNER JOIN vw_comum_unidade vw 
                                ON vw.id_unidade = unidade_responsavel 
                WHERE 1 = 1 
                AND d.ativo <> false  ";

        $params = array_filter($params);
        if (isset($params['dsdiagnostico']) && (!empty($params['dsdiagnostico']))) {
            $dsdiagnostico = strtoupper($params['dsdiagnostico']);
            $sql .= " AND upper(dsdiagnostico) LIKE '%{$dsdiagnostico}%'";
        }

        if (isset($params['idunidadeprincipal']) && (!empty($params['idunidadeprincipal']))) {
            $sql .= " AND idunidadeprincipal = {$params['idunidadeprincipal']}";
        }
        $sql .= " ORDER BY dtcadastro DESC ";

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
        $data = array(
            "ativo" => 'FALSE',
        );
        try {
            $pks = array(
                "iddiagnostico" => $params,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Função que verifica se o item existe na tabela TB_ITEM_SECAO.
     * @param array $params
     * @return boolean
     */
    public function isItemSecaoByIdAndQuestionario($params)
    {
        $sql = "SELECT COUNT(i.id_item) as total FROM agepnet200.tb_item_secao i
                WHERE i.id_secao =:id_secao
                 AND i.idquestionariodiagnostico =:idquestionariodiagnostico";

        $resultado = $this->_db->fetchRow($sql, array(
            'id_secao' => $params['id_secao'],
            'idquestionariodiagnostico' => $params['idquestionariodiagnostico'],
        ));

        return ($resultado['total'] == 0) ? false : true;
    }

    /**
     * Retorna os id's de seção existente na tabela TB_ITEM_SECAO.
     * @param array $params
     * @return array
     */
    public function retornaIdSecaoByIdQuestionario($params)
    {
        $sql = "SELECT ARRAY_AGG(i.id_secao) as secao
                FROM agepnet200.tb_item_secao i
                WHERE i.idquestionariodiagnostico =:idquestionariodiagnostico ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idquestionariodiagnostico' => $params['idquestionariodiagnostico'],
        ));

        return $resultado['secao'];
    }


    /**
     * Retorna se existe pergunta associada a seção.
     * @param array $params
     * @return boolean
     */
    public function isExistePeguntaAssociada($params)
    {
        $sql = "SELECT COUNT(id_secao) as secao
                FROM agepnet200.tb_pergunta
                WHERE idquestionario =:idquestionariodiagnostico
                 AND id_secao =:id_secao";

        $resultado = $this->_db->fetchRow($sql, array(
            'id_secao' => $params['id_secao'],
            'idquestionariodiagnostico' => $params['idquestionariodiagnostico'],
        ));

        return (count($resultado['secao']) > 0) ? true : false;
    }


    public function deleteItens($data)
    {
        try {
            $sql = "
                DELETE FROM agepnet200.tb_item_secao
                WHERE idquestionariodiagnostico = :idquestionariodiagnostico ";

            $resultado = $this->_db->fetchRow($sql, array(
                'idquestionariodiagnostico' => $data['idquestionariodiagnostico']
            ));
            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }
    }


    public function getMaxId()
    {
        $sql = "SELECT id_item
                FROM agepnet200.tb_item_secao 
                ORDER BY id_item DESC 
                LIMIT 1";
        $resultado = $this->_db->fetchOne($sql);
        return $resultado;
    }


}
