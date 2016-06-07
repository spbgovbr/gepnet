<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Atividadepredecessora extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Atividadepredecessora
     */
    public function insert(Projeto_Model_Atividadepredecessora $model)
    {
        //zend_debug::dump($model);exit;

        try {
            //$model->idatividadepredecessora = $this->maxVal("idatividadepredecessora");
            //$model->idatividadepredecessora = $this->maxVal("idatividadepredecessora", "idprojeto = :idprojeto", array('idprojeto' => $model->idprojeto));
            //Zend_Debug::dump($model);exit;
            $data = array(
                "idatividadepredecessora" => $model->idatividadepredecessora,
                "idatividade"             => $model->idatividade,
                "idprojeto"               => $model->idprojeto,
            );

            //$data = array_filter($data);
            
            //$this->_db->insert($data, $bind)
            
            $this->getDbTable()->insert($data);

            return $model;
        } catch ( Exception $exc ) {
            Zend_Debug::dump($exc);exit;

        }
    }
    
    public function excluir($params)
    {
        try {
            $pks = array(
                "idprojeto" => $params['idprojeto'],
                "idatividade" => $params['idatividade'],
                "idatividadepredecessora" => $params['idatividadepredecessora']
            );
            $where        = $this->_generateRestrictionsFromPrimaryKeys($pks);
            //$where = $this->_db->quoteInto('idpessoa = ?', $model->idpessoa);
            $retorno      = $this->getDbTable()->delete($where);
            return $retorno;
        } catch ( Exception $exc ) {
            throw $exc;
        }
    }
    
    /**
     * 
     * @param type $params
     * @param type $array
     * @return array | Projeto_Model_Atividadepredecessora
     */
    public function retornaPorAtividade($params, $array = true)
    {
        $sql = "select 
                    p.idatividadepredecessora,
                    p.idatividade
                    ,c.numfolga
                    ,numdiasrealizados
                    ,c.nomatividadecronograma,
                    to_char(c.datinicio, 'DD/MM/YYYY') as datinicio, 
                    to_char(c.datfim, 'DD/MM/YYYY') as datfim,
                    p.idprojeto
                from 
                    agepnet200.tb_atividadepredecessora p
                    inner join agepnet200.tb_atividadecronograma c on c.idatividadecronograma = p.idatividadepredecessora
                                and c.idprojeto = p.idprojeto
                where p.idatividade = :idatividadecronograma and p.idprojeto = :idprojeto";
        
        $resultado = $this->_db->fetchAll($sql,array(
            'idatividadecronograma' => $params['idatividadecronograma'],
            'idprojeto'             => $params['idprojeto']
        ));
        
        if(false === $array) {
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Atividadepredecessora');

            foreach ( $resultado as $r )
            {
                $o        = new Projeto_Model_Atividadepredecessora($r);
                $collection[] = $o;
            }

            return $collection;
        }
        
        return $resultado;
    }
    
    // Retorna as atividades por predecessoras
    public function retornaAtividadePorPredec($params, $array = true)
    {
        $sql = "SELECT distinct
		    cron.nomatividadecronograma,
		    cron.numfolga,
		    cron.numfolga,
		    cron.numdiasrealizados,
                    cron.idatividadecronograma,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio, 
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                    inner join agepnet200.tb_atividadepredecessora p on cron.idatividadecronograma = p.idatividade
                WHERE
                    cron.idprojeto = :idprojeto
                    and p.idatividadepredecessora = :idatividadepredecessora";
        $resultado = $this->_db->fetchAll($sql,array(
            'idatividadepredecessora' => $params['idatividadepredecessora'],
            'idprojeto'               => $params['idprojeto']
        ));
        
        if(false === $array) {
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Atividadepredecessora');

            foreach ( $resultado as $r )
            {
                $o        = new Projeto_Model_Atividadepredecessora($r);
                $collection[] = $o;
            }

            return $collection;
        }
        
        return $resultado;
    }

    public function retornaPorAtividadeProjeto($params) 
    {
        $sql = 'select * from agepnet200.tb_atividadepredecessora ap
                where ap.idprojeto = :idprojeto
                and ap.idatividade = :idatividade';
        
        $resultado = $this->_db->fetchAll($sql,$params);
        return $resultado;
                
    }
    public function retornaDataMaiorPredecessora($params) 
    {
        $sql = "select 
                    p.idatividadepredecessora
                    ,max(to_char(c.datfim, 'DD/MM/YYYY')) as datfim
                from 
                    agepnet200.tb_atividadepredecessora p
                    inner join agepnet200.tb_atividadecronograma c on c.idatividadecronograma = p.idatividadepredecessora
                                and c.idprojeto = p.idprojeto
                where p.idatividade = :idatividadecronograma
                and p.idprojeto = :idprojeto
                group by p.idatividadepredecessora";
        
        $resultado = $this->_db->fetchAll($sql,$params);
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
        //Zend_Debug::dump($sql); die;
        $resultado = $this->_db->fetchRow($sql,$params);
        return $resultado;
                
    }
    public function retornaPredecessora($idprojeto,$idatividadepredecessora) 
    {
        $sql = "SELECT distinct
		    cron.nomatividadecronograma,
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
                    cron.idprojeto = ".$idprojeto."
                    and cron.idatividadecronograma in ($idatividadepredecessora)
                   ";
        //Zend_Debug::dump($sql); die;
        $resultado = $this->_db->fetchAll($sql);
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
        
        $resultado = $this->_db->fetchAll($sql,$params);
        return $resultado;
                
    }
         public function retoraConjuntoDeAtividades($idatividade,$idprojeto) 
    {
        $sql = "
                 SELECT *
                    from agepnet200.tb_atividadecronograma
			where idatividadecronograma in($idatividade)
			and idprojeto = " .$idprojeto;
        //Zend_Debug::dump($sql); die;
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
                
    }
         public function retoraAtividade($idprojeto,$idpredecessora) 
    {
        $sql = "
                  SELECT distinct
		    cron.nomatividadecronograma,
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
                    cron.idprojeto = ".$idprojeto . "
                    and p.idatividadepredecessora = ".$idpredecessora;
		    
        //Zend_Debug::dump($sql); die;
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
                
    }
         public function retoraPredPorProjeto($idprojeto) 
    {
        $sql = "
                 select distinct
                   pred.idatividadepredecessora
                    from  agepnet200.tb_atividadepredecessora pred
                    where pred.idprojeto = " .$idprojeto ;
       // Zend_Debug::dump($sql); die;
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
                
    }
    
    /*
      public function fetchPairsGrupo($params)
      {
      $sql = "SELECT
      idatividadecronograma, nomatividadecronograma
      FROM agepnet200.tb_atividadecronograma
      WHERE
      idprojeto = :idprojeto
      and domtipoatividade = 1";

      return $this->_db->fetchPairs($sql, array(
      'idprojeto' => $params['idprojeto'],
      ));
      }
     */
}