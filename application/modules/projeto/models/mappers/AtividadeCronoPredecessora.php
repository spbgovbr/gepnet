<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_AtividadeCronoPredecessora extends App_Model_Mapper_MapperAbstract
{

    public function insert(Projeto_Model_AtividadeCronoPredecessora $model)
    {
        $data = array(
            "idatividadecronograma" => $model->idatividadecronograma,
            "idprojetocronograma" => $model->idprojetocronograma,
            "idatividadepredecessora" => $model->idatividadepredecessora,
        );

        try {
            $this->getDbTable()->insert($data);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     *
     * @param $params
     * @return int
     * @throws Exception
     */
    public function excluir($params)
    {
        try {
            $pks = array();
            if (isset($params['idprojeto']) && !empty($params['idprojeto'])) {
                $pks['idprojetocronograma'] = $params['idprojeto'];
            }
            if (isset($params['idatividade']) && !empty($params['idatividade'])) {
                $pks['idatividadecronograma'] = $params['idatividade'];
            }
            if (isset($params['idatividadepredecessora']) && !empty($params['idatividadepredecessora'])) {
                $pks['idatividadepredecessora'] = $params['idatividadepredecessora'];
            }
            if (empty($pks)) {
                throw new Exception('Informe ao menos 1 parametro para exclusao.');
            }
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            return $this->getDbTable()->delete($where);
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    public function retornaPredecePorIdAtividade($params)
    {
        $sql = "select
                    idatividadepredecessora
                    ,idatividadecronograma
                    ,idprojetocronograma
                from 
                    agepnet200.tb_atividadecronopredecessora p
                where p.idatividadecronograma = :idatividadecronograma
                and p.idprojetocronograma = :idprojeto";

        $resultado = $this->_db->fetchAll($sql,
            array('idprojeto' => $params['idprojeto'], 'idatividadecronograma' => $params['idatividadecronograma']));
        return $resultado;
    }

    public function excluirPorAtividade($params)
    {
        try {
            if (isset($params['idatividade']) && isset($params['idprojeto'])) {
                $where = $this->_db->quoteInto('idatividadecronograma = ?', $params['idatividade']);
                $where = $this->_db->quoteInto($where . ' AND idprojetocronograma = ?', $params['idprojeto']);

                return $this->getDbTable()->delete($where);
            } else {
                throw new Exception('Algum parametro de exclusao faltando.');
            }
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }


    /**
     *
     * @param array $params
     * @return array | Projeto_Model_AtividadeCronoPredecessora
     */
    public function retornaPorAtividade($params, $array = true)
    {
        $sql = "select
                    p.idatividadepredecessora,
                    p.idatividadecronograma
                    ,c.numfolga
                    ,numdiasrealizados
                    ,c.numseq
                    ,c.nomatividadecronograma,
                    to_char(c.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(c.datfim, 'DD/MM/YYYY') as datfim,
                    p.idprojetocronograma,
                    c.datfim as fim
                from 
                    agepnet200.tb_atividadecronopredecessora p
                    inner join
                          agepnet200.tb_atividadecronograma c
                          on c.idatividadecronograma = p.idatividadepredecessora
                          and c.idprojeto = p.idprojetocronograma
                where 
                    p.idatividadecronograma = :idatividadecronograma 
                and p.idprojetocronograma = :idprojetocronograma ";

        //$sql .= ( isset($params['orderAsc']) ? " ORDER BY c.datfim asc " : " ORDER BY c.datfim desc ");
        $sql .= " ORDER BY c.numseq asc, c.datfim desc ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idatividadecronograma' => $params['idatividadecronograma'],
            'idprojetocronograma' => $params['idprojeto']
        ));

        if (false === $array) {
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_AtividadeCronoPredecessora');

            foreach ($resultado as $r) {
                $o = new Projeto_Model_AtividadeCronoPredecessora($r);
                $collection[] = $o;
            }
            return $collection;
        }

        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return array | Projeto_Model_AtividadeCronoPredecessora
     */
    public function fetchPairsPorAtividade($params)
    {
        $sql = "select
                    p.idatividadepredecessora,
                     c.numseq  || ' - ' || (to_char(c.datinicio, 'DD/MM/YYYY') || ' à ' ||
                    to_char(c.datfim, 'DD/MM/YYYY')|| ' -- ' ||c.nomatividadecronograma ) as data
                from
                    agepnet200.tb_atividadecronopredecessora p
                    inner join
                          agepnet200.tb_atividadecronograma c
                          on c.idatividadecronograma = p.idatividadepredecessora
                          and c.idprojeto = p.idprojetocronograma
                where
                    p.idatividadecronograma = :idatividadecronograma and
                    p.idprojetocronograma = :idprojetocronograma ";

        $sql .= " ORDER BY c.numseq asc,  c.datfim desc ";

        $resultado = $this->_db->fetchPairs($sql, array(
            'idatividadecronograma' => $params['idatividadecronograma'],
            'idprojetocronograma' => $params['idprojeto']
        ));
        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return array | Projeto_Model_AtividadeCronoPredecessora
     */
    public function listaPorAtividade($params)
    {
        $sql = "SELECT c.datfim, c.datinicio,
                    p.idatividadepredecessora,
                    c.numseq,
                    c.numseq  || ' - ' || (to_char(c.datinicio, 'DD/MM/YYYY') || ' - '||
                    to_char(c.datfim, 'DD/MM/YYYY')|| ' -- ' || c.nomatividadecronograma ) as data
                FROM agepnet200.tb_atividadecronopredecessora p
                INNER JOIN agepnet200.tb_atividadecronograma c
                  ON c.idatividadecronograma = p.idatividadepredecessora
                  AND c.idprojeto = p.idprojetocronograma
                WHERE p.idatividadecronograma = :idatividadecronograma 
                AND p.idprojetocronograma = :idprojetocronograma ";

        $sql .= (isset($params['orderAsc']) ? " ORDER BY c.datfim asc " : " ORDER BY c.datfim desc ");

        $resultado = $this->_db->fetchAll($sql, array(
            'idatividadecronograma' => $params['idatividadecronograma'],
            'idprojetocronograma' => $params['idprojeto']
        ));
        return $resultado;
    }

    // Retorna as atividades por predecessoras
    public function retornaAtividadePorPredec($params, $array = true)
    {
        $sql = "select
                    cron.nomatividadecronograma,
                    cron.numseq,
                    cron.numfolga,
                    cron.numfolga,
                    cron.numdiasrealizados,
                    cron.idatividadecronograma,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim,
                    CASE
                    WHEN domtipoatividade=1 THEN 'GRUPO'
                    WHEN domtipoatividade=2 THEN 'ENTREGA'
                    WHEN domtipoatividade=3 THEN 'ATIVIDADE'
                    WHEN domtipoatividade=4 THEN 'MARCO'
                    END as dominio_atividade,
                    cron.idgrupo,
                    CASE
                      WHEN cron.idgrupo IS NOT NULL THEN
                        (select CASE
                              WHEN domtipoatividade=1 THEN 'GRUPO'
                              WHEN domtipoatividade=2 THEN 'ENTREGA'
                              WHEN domtipoatividade=3 THEN 'ATIVIDADE'
                              WHEN domtipoatividade=4 THEN 'MARCO'
                              ELSE 'GRUPO'
                            END
                           from agepnet200.tb_atividadecronograma
                            where idatividadecronograma=cron.idgrupo and idprojeto=cron.idprojeto)
                    ELSE 'GRUPO'
                    END as dominio
                from agepnet200.tb_atividadecronopredecessora p
                INNER JOIN agepnet200.tb_atividadecronograma cron on cron.idatividadecronograma=p.idatividade
                          and cron.idprojeto=p.idprojeto and domtipoatividade in(3,4)
                where p.idprojetocronograma = :idprojeto and p.idatividadepredecessora = :idatividadepredecessora order by cron.datfim";

        $resultado = $this->_db->fetchAll($sql, array(
            'idatividadepredecessora' => $params['idatividadecronograma'],
            'idprojeto' => $params['idprojeto']
        ));

        if (false === $array) {
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_AtividadeCronoPredecessora');
            foreach ($resultado as $r) {
                $o = new Projeto_Model_AtividadeCronoPredecessora($r);
                $collection[] = $o;
            }
            return $collection;
        } else {
            return $resultado;
        }
    }

    public function retornaTodasPredecessorasPorIdAtividade($idprojeto, $idatividade)
    {
        if ($idatividade != '') {
            $sql = "SELECT
		    idatividadepredecessora
                FROM 
                    agepnet200.tb_atividadecronopredecessora p 
                WHERE
                    p.idprojetocronograma = " . $idprojeto . "
                    and p.idatividadecronograma in ($idatividade)
                   ";
            $resultado = $this->_db->fetchAll($sql);
            return $resultado;

        } else {
            return '';
        }
    }

    // Retorna quantidade atividades por predecessoras
    public function retornaAtividadeCountPredec($params)
    {
        $sql = "select count(*) ctatividade
                from agepnet200.tb_atividadecronopredecessora p
                INNER JOIN agepnet200.tb_atividadecronograma cron 
                  ON cron.idatividadecronograma=p.idatividadecronograma
                  AND cron.idprojeto=p.idprojetocronograma AND domtipoatividade in(3,4)
                WHERE p.idprojetocronograma = :idprojeto and p.idatividadepredecessora = :idatividadepredecessora ";

        $retorno = $this->_db->fetchRow($sql, array(
            'idatividadepredecessora' => $params['idatividadecronograma'],
            'idprojeto' => $params['idprojeto']
        ));
        return ($retorno['ctatividade'] > 0 ? true : false);
    }

    // Retorna quantidade atividades por predecessoras de uma entrega
    public function retornaAtividadeCountPredecEntrega($params)
    {
        $sql = "SELECT COUNT(aps.idatividadepredecessora) AS total
                FROM agepnet200.\"AtividadeCronogramaRecursivo\"(:idprojeto) cr 
                LEFT JOIN agepnet200.tb_atividadecronopredecessora aps 
                 ON aps.idatividadecronograma = cr.idatividadecronograma
                 AND aps.idprojetocronograma = cr.idprojeto
                WHERE cr.idgrupo = :identrega 
                AND aps.idatividadecronograma IS NOT NULL ";

        $retorno = $this->_db->fetchRow($sql, array(
            'identrega' => $params['identrega'],
            'idprojeto' => $params['idprojeto']
        ));
        return ($retorno['total'] > 0);
    }

    // Retorna quantidade atividades por predecessoras de um grupo
    public function retornaAtividadeCountPredecGrupo($params)
    {
        $sql = "SELECT COUNT(aps.idatividadepredecessora) AS total
                FROM agepnet200.\"AtividadeCronogramaRecursivo\"(:idprojeto) cr 
                LEFT JOIN agepnet200.tb_atividadecronopredecessora aps 
                 ON aps.idatividadecronograma = cr.idatividadecronograma
                 AND aps.idprojetocronograma = cr.idprojeto
                WHERE cr.pai = :idgrupo 
                AND cr.nivel > 2
                AND aps.idatividadecronograma IS NOT NULL";

        $retorno = $this->_db->fetchRow($sql, array(
            'idgrupo' => $params['idgrupo'],
            'idprojeto' => $params['idprojeto']
        ));
        return ($retorno['total'] > 0);
    }


    public function retornaPorAtividadeProjeto($params)
    {
        $sql = 'SELECT * FROM agepnet200.tb_atividadecronopredecessora ap
                WHERE ap.idprojetocronograma = :idprojeto
                AND ap.idatividadecronograma = :idatividadecronograma ';

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma']
        ));
        return $resultado;
    }

    public function retornaPoriDPredecessora($params)
    {
        $sql = "SELECT p.idatividadepredecessora                
                FROM agepnet200.tb_atividadecronopredecessora p
                WHERE p.idatividadepredecessora = :idatividadepredecessora
                AND   p.idprojetocronograma = :idprojeto ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadepredecessora' => $params['idatividadecronograma'],
        ));

        return $resultado;
    }

    public function retornaPredecessora($idprojeto, $idatividadepredecessora)
    {
        $sql = "SELECT 
                    cron.nomatividadecronograma,
                    cron.numseq,
                    cron.numfolga,
                    cron.numfolga,
                            p.idatividade,
                            p.idatividadepredecessora,
                    cron.numdiasrealizados,
                            cron.idatividadecronograma,
                            to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio, 
                            to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM agepnet200.tb_atividadecronopredecessora p 
                INNER JOIN agepnet200.tb_atividadecronograma cron 
                  ON p.idatividadepredecessora = cron.idatividadecronograma
                  AND p.idprojetocronograma = cron.idprojeto
                WHERE cron.idprojeto = " . $idprojeto . " AND cron.idatividadecronograma IN($idatividadepredecessora) ";

        $resultado = $this->_db->fetchAll($sql);

        return $resultado;

    }

    public function retornaMaiorDataPredecessoraByIdAtividade($params)
    {
        $sql = "SELECT MAX(cron.datfim) AS datfim
                FROM agepnet200.tb_atividadecronopredecessora ap
                INNER JOIN agepnet200.tb_atividadecronograma cron 
                  ON ap.idatividadepredecessora = cron.idatividadecronograma 
                  AND ap.idprojetocronograma = cron.idprojeto
                WHERE ap.idprojetocronograma = :idprojeto
                AND ap.idatividade =:idatividadecronograma";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma'],
        ));
        return $resultado;
    }

    public function verificaAtividadeDaPredec($params)
    {
        $sql = "SELECT p.idatividade              
                FROM agepnet200.tb_atividadecronopredecessora p
                WHERE p.idatividadepredecessora = :idatividadepredecessora 
                AND p.idprojetocronograma = :idprojeto";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma']
        ));
        return $resultado;
    }

    public function pesquisaPredecessoraAtividade($params)
    {
        $sql = "SELECT
                    idatividadepredecessora
                    ,idatividade
                    ,idprojeto
                    ,idprojeto
                FROM agepnet200.tb_atividadecronopredecessora p
                WHERE p.idatividadecronograma = :idatividade
                AND p.idatividadepredecessora = :idatividadepredecessora
                AND p.idprojetocronograma = :idprojeto ";

        $resultado = $this->_db->fetchAll($sql,
            array(
                'idprojeto' => $params['idprojeto'],
                'idatividade' => $params['idatividade'],
                'idatividadepredecessora' => $params['idatividadepredecessora']
            )
        );
        return $resultado;
    }

    public function isPredecessora($params)
    {
        $sql = "SELECT count(idatividadepredecessora) as total
                FROM agepnet200.tb_atividadecronopredecessora p
                WHERE p.idatividadecronograma = :idatividadecronograma
                AND p.idatividadepredecessora = :idatividadepredecessora
                AND p.idprojetocronograma = :idprojetocronograma ";

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojetocronograma' => $params['idprojetocronograma'],
            'idatividadecronograma' => $params['idatividadecronograma'],
            'idatividadepredecessora' => $params['idatividadepredecessora']
        ));

        return $resultado;
    }

    public function getById($params)
    {
        $sql = "SELECT  * 
                FROM agepnet200.tb_atividadecronopredecessora p
                INNER JOIN agepnet200.tb_atividadecronograma cron 
                  ON p.idatividadepredecessora = cron.idatividadecronograma
                  AND p.idprojetocronograma = cron.idprojeto
                WHERE p.idprojetocronograma = :idprojeto 
                AND p.idatividadepredecessora = :idatividadecronograma ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma'],
        ));

        return $resultado;
    }

}