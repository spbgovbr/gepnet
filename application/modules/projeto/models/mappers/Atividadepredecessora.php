<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Atividadepredecessora extends App_Model_Mapper_MapperAbstract
{


    public function insert(Projeto_Model_Atividadepredecessora $model)
    {
        $data = array(
            "idatividadepredecessora" => $model->idatividadepredecessora,
            "idatividade" => $model->idatividade,
            "idprojeto" => $model->idprojeto,
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
                $pks['idprojeto'] = $params['idprojeto'];
            }
            if (isset($params['idatividade']) && !empty($params['idatividade'])) {
                $pks['idatividade'] = $params['idatividade'];
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

    public function excluirPorAtividade($params)
    {
        try {
            if (isset($params['idatividade']) && isset($params['idprojeto'])) {
                $where = $this->_db->quoteInto(
                    'idatividade = ?', $params['idatividade'], 'and idprojeto = ?', $params['idprojeto']
                );
                $retorno = $this->getDbTable()->delete($where);
                return $retorno;
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
     * @return array | Projeto_Model_Atividadepredecessora
     */
    public function retornaPorAtividade($params, $array = true)
    {
        $sql = "select
                    p.idatividadepredecessora,
                    p.idatividade
                    ,c.numfolga
                    ,numdiasrealizados
                    ,c.numseq
                    ,c.nomatividadecronograma,
                    to_char(c.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(c.datfim, 'DD/MM/YYYY') as datfim,
                    p.idprojeto,
                    c.datfim as fim
                from 
                    agepnet200.tb_atividadepredecessora p
                    inner join
                          agepnet200.tb_atividadecronograma c
                          on c.idatividadecronograma = p.idatividadepredecessora
                          and c.idprojeto = p.idprojeto
                where 
                    p.idatividade = :idatividadecronograma and p.idprojeto = :idprojeto ";

        //$sql .= ( isset($params['orderAsc']) ? " ORDER BY c.datfim asc " : " ORDER BY c.datfim desc ");
        $sql .= " ORDER BY c.numseq asc, c.datfim desc ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idatividadecronograma' => $params['idatividadecronograma'],
            'idprojeto' => $params['idprojeto']
        ));

        if (false === $array) {
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Atividadepredecessora');

            foreach ($resultado as $r) {
                $o = new Projeto_Model_Atividadepredecessora($r);
                $collection[] = $o;
            }
            return $collection;
        }

        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return array | Projeto_Model_Atividadepredecessora
     */
    public function fetchPairsPorAtividade($params)
    {
        $sql = "select
                    p.idatividadepredecessora,
                     c.numseq  || ' - ' || (to_char(c.datinicio, 'DD/MM/YYYY') || ' à ' ||
                    to_char(c.datfim, 'DD/MM/YYYY')|| ' -- ' ||c.nomatividadecronograma ) as data
                from
                    agepnet200.tb_atividadepredecessora p
                    inner join
                          agepnet200.tb_atividadecronograma c
                          on c.idatividadecronograma = p.idatividadepredecessora
                          and c.idprojeto = p.idprojeto
                where
                    p.idatividade = :idatividadecronograma and
                    p.idprojeto = :idprojeto ";
        //$sql .= ( isset($params['orderAsc']) ? " ORDER BY c.datfim asc " : " ORDER BY c.datfim desc ");
        $sql .= " ORDER BY c.numseq asc,  c.datfim desc ";

        $resultado = $this->_db->fetchPairs($sql, array(
            'idatividadecronograma' => $params['idatividadecronograma'],
            'idprojeto' => $params['idprojeto']
        ));
        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return array | Projeto_Model_Atividadepredecessora
     */
    public function listaPorAtividade($params)
    {
        $sql = "select c.datfim, c.datinicio,
                    p.idatividadepredecessora,
                    c.numseq,
                    c.numseq  || ' - ' || (to_char(c.datinicio, 'DD/MM/YYYY') || ' - '||
                    to_char(c.datfim, 'DD/MM/YYYY')|| ' -- ' || c.nomatividadecronograma ) as data
                from
                    agepnet200.tb_atividadepredecessora p
                    inner join
                          agepnet200.tb_atividadecronograma c
                          on c.idatividadecronograma = p.idatividadepredecessora
                          and c.idprojeto = p.idprojeto
                where
                    p.idatividade = :idatividadecronograma and
                    p.idprojeto = :idprojeto ";

        $sql .= (isset($params['orderAsc']) ? " ORDER BY c.datfim asc " : " ORDER BY c.datfim desc ");

        $resultado = $this->_db->fetchAll($sql, array(
            'idatividadecronograma' => $params['idatividadecronograma'],
            'idprojeto' => $params['idprojeto']
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
                from agepnet200.tb_atividadepredecessora p
                INNER JOIN agepnet200.tb_atividadecronograma cron on cron.idatividadecronograma=p.idatividade
                          and cron.idprojeto=p.idprojeto and domtipoatividade in(3,4)
                where p.idprojeto = :idprojeto and p.idatividadepredecessora = :idatividadepredecessora order by cron.datfim";

        $resultado = $this->_db->fetchAll($sql, array(
            'idatividadepredecessora' => $params['idatividadecronograma'],
            'idprojeto' => $params['idprojeto']
        ));

        if (false === $array) {
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Atividadepredecessora');
            foreach ($resultado as $r) {
                $o = new Projeto_Model_Atividadepredecessora($r);
                $collection[] = $o;
            }
            return $collection;
        } else {
            return $resultado;
        }
    }

    // Retorna quantidade atividades por predecessoras
    public function retornaAtividadeCountPredec($params)
    {
        $sql = "select count(*) ctatividade
                from agepnet200.tb_atividadepredecessora p
                INNER JOIN agepnet200.tb_atividadecronograma cron on cron.idatividadecronograma=p.idatividade
                          and cron.idprojeto=p.idprojeto and domtipoatividade in(3,4)
                where p.idprojeto = :idprojeto and p.idatividadepredecessora = :idatividadepredecessora ";

        $retorno = $this->_db->fetchRow($sql, array(
            'idatividadepredecessora' => $params['idatividadecronograma'],
            'idprojeto' => $params['idprojeto']
        ));
        return ($retorno['ctatividade'] > 0 ? true : false);
    }

    public function retornaTodasPredecessorasPorIdAtividade($idprojeto, $idatividade)
    {
        if ($idatividade != '') {
            $sql = "SELECT
		    idatividadepredecessora
                FROM 
                    agepnet200.tb_atividadecronopredecessora p 
                WHERE
                    p.idprojeto = " . $idprojeto . "
                    and p.idatividadecronograma in ($idatividade)
                   ";
            $resultado = $this->_db->fetchAll($sql);
            return $resultado;

        } else {
            return '';
        }
    }

    // Retorna quantidade atividades por predecessoras de uma entrega
    public function retornaAtividadeCountPredecEntrega($params)
    {
        $sql = "select count(*) ctatividade
                from agepnet200.tb_atividadepredecessora p
                INNER JOIN agepnet200.tb_atividadecronograma cron on cron.idatividadecronograma=p.idatividade
                      and cron.idprojeto=p.idprojeto and cron.domtipoatividade in(3,4)
                where p.idprojeto = :idprojeto and p.idatividadepredecessora in(
                   select cron1.idatividadecronograma from
                   agepnet200.tb_atividadecronograma cron1
                   where cron1.idprojeto = :idprojeto and cron1.idgrupo=:identrega
                )";

        $retorno = $this->_db->fetchRow($sql, array(
            'identrega' => $params['identrega'],
            'idprojeto' => $params['idprojeto']
        ));
        return ($retorno['ctatividade'] > 0 ? true : false);
    }

    // Retorna quantidade atividades por predecessoras de um grupo
    public function retornaAtividadeCountPredecGrupo($params)
    {
        $sql = "select count(*) ctatividade
                from agepnet200.tb_atividadepredecessora p
                INNER JOIN agepnet200.tb_atividadecronograma cron on cron.idatividadecronograma=p.idatividade
                      and cron.idprojeto=p.idprojeto and cron.domtipoatividade in(3,4)
                where p.idprojeto = :idprojeto and p.idatividadepredecessora in(
                   select cron1.idatividadecronograma from
                   agepnet200.tb_atividadecronograma cron1
                   where cron1.idprojeto = :idprojeto and cron1.idgrupo in(
                    select cron2.idatividadecronograma from
                    agepnet200.tb_atividadecronograma cron2
                    where cron2.idprojeto = :idprojeto and cron2.domtipoatividade=2 and cron2.idgrupo=:idgrupo
                   )
                )";

        $retorno = $this->_db->fetchRow($sql, array(
            'idgrupo' => $params['idgrupo'],
            'idprojeto' => $params['idprojeto']
        ));
        return ($retorno['ctatividade'] > 0 ? true : false);
    }


    public function retornaPorAtividadeProjeto($params)
    {
        $sql = 'select * from agepnet200.tb_atividadepredecessora ap
                where ap.idprojeto = :idprojeto
                and ap.idatividade = :idatividade ';

        $resultado = $this->_db->fetchAll($sql, $params);
        return $resultado;
    }

    public function retornaPoriDPredecessora($params)
    {
        $sql = "
                SELECT  
                   p.idatividadepredecessora                
                FROM 
                    agepnet200.tb_atividadepredecessora p
                WHERE 
                        p.idatividadepredecessora   = :idatividadepredecessora
                        and p.idprojeto = :idprojeto
                        ";
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
                FROM 
                    agepnet200.tb_atividadepredecessora p 
                    inner join agepnet200.tb_atividadecronograma cron on  p.idatividadepredecessora = cron.idatividadecronograma
                    and p.idprojeto = cron.idprojeto
                WHERE
                    cron.idprojeto = " . $idprojeto . "
                    and cron.idatividadecronograma in ($idatividadepredecessora)
                   ";
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;

    }

    public function retornaMaiorDataPredecessoraByIdAtividade($params)
    {
        $sql = "SELECT
                    max(cron.datfim) as datfim
                FROM
                    agepnet200.tb_atividadepredecessora ap
                   inner join agepnet200.tb_atividadecronograma cron on ap.idatividadepredecessora=cron.idatividadecronograma and ap.idprojeto=cron.idprojeto
                WHERE
                    ap.idprojeto = :idprojeto
                    and ap.idatividade =:idatividadecronograma";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma'],
        ));
        return $resultado;
    }

    public function verificaAtividadeDaPredec($params)
    {
        $sql = "
                SELECT  
                   p.idatividade               
                FROM 
                    agepnet200.tb_atividadepredecessora p
                WHERE 
                        p.idatividadepredecessora   = :idatividadepredecessora
                         and p.idprojeto = :idprojeto
                         ";

        $resultado = $this->_db->fetchAll($sql, $params);
        return $resultado;

    }

    public function retoraConjuntoDeAtividades($idatividade, $idprojeto)
    {
        $sql = "
                 SELECT *
                    from agepnet200.tb_atividadecronograma
			where idatividadecronograma in($idatividade)
			and idprojeto = " . $idprojeto;
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;

    }

    public function retornaAtividade($idprojeto, $idpredecessora)
    {
        $sql = "
                  SELECT distinct
		    cron.nomatividadecronograma,
		    cron.numseq,
		    cron.numfolga,
		    cron.numfolga,
		    cron.numdiasrealizados,
                    cron.idatividadecronograma,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio, 
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                    inner join agepnet200.tb_atividadepredecessora p on cron.idatividadecronograma = p.idatividade
                    and p.idprojeto = cron.idprojeto
                WHERE
                    cron.idprojeto = " . $idprojeto . "
                    and p.idatividadepredecessora = " . $idpredecessora;

        $resultado = $this->_db->fetchAll($sql);
        return $resultado;

    }

    public function retoraPredPorProjeto($idprojeto)
    {
        $sql = "
                 select distinct
                   pred.idatividadepredecessora
                   ,pred.idatividade
                    from  agepnet200.tb_atividadepredecessora pred
                    where pred.idprojeto = " . $idprojeto;
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;

    }

    //////////////////////////// MULTIPLAS PREDECESSORAS//////////////////////////
    public function retornaAtividadePorId($idatividade, $idprojeto)
    {
        $sql = "
                 select *
                    from  agepnet200.tb_atividadecronograma cron
                    where cron.idatividadecronograma = " . $idatividade . "
                    and cron.idprojeto = " . $idprojeto;
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }


    public function retornaPredecePorIdAtividade($params)
    {
        $sql = "select
                    idatividadepredecessora
                    ,idatividade
                    ,idprojeto
                from 
                    agepnet200.tb_atividadepredecessora p
                where p.idatividade = :idatividadecronograma
                and p.idprojeto = :idprojeto";

        $resultado = $this->_db->fetchAll($sql,
            array('idprojeto' => $params['idprojeto'], 'idatividadecronograma' => $params['idatividadecronograma']));
        return $resultado;
    }

    public function pesquisaPredecessoraAtividade($params)
    {
        $sql = "select
                    idatividadepredecessora
                    ,idatividade
                    ,idprojeto
                    ,idprojeto
                from
                    agepnet200.tb_atividadepredecessora p
                where
                p.idatividade = :idatividade
                and p.idatividadepredecessora = :idatividadepredecessora
                and p.idprojeto = :idprojeto ";

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
        $sql = "select
                    count(idatividadepredecessora) as total
                from
                    agepnet200.tb_atividadepredecessora p
                where
                p.idatividade = :idatividade
                and p.idatividadepredecessora = :idatividadepredecessora
                and p.idprojeto = :idprojeto ";
        //Zend_Debug::dump($params);exit;
        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividade' => $params['idatividade'],
            'idatividadepredecessora' => $params['idatividadepredecessora']
        ));
        //Zend_Debug::dump($resultado);exit;
        return $resultado;
    }

    public function getById($params)
    {
        $sql = "select 
                    * 
                from 
                    agepnet200.tb_atividadepredecessora p
                    inner join agepnet200.tb_atividadecronograma cron on p.idatividadepredecessora = cron.idatividadecronograma
                where 
                     p.idprojeto = cron.idprojeto
                     and p.idatividadepredecessora = :idatividadecronograma
                     and p.idprojeto = :idprojeto";

        $resultado = $this->_db->fetchAll($sql, $params);
        return $resultado;

    }
}