<?php
/*
 * @By Danilo
 */
class Projeto_Model_Mapper_SituacaoProjeto extends App_Model_Mapper_MapperAbstract{
    
    
    public function retornaNomeSituacaoAtivo(){
        $sql = "select idtipo, nomtipo from agepnet200.tb_tiposituacaoprojeto where flatiposituacao = 1";
        $retorno = $this->_db->fetchAll($sql);
        return $retorno;
    }
    
    public function getById($params){
        
        $sql = "select * from agepnet200.tb_tiposituacaoprojeto "
                . "where idtipo = " .$params;
        $return = $this->_db->fetchRow($sql);
        
        return $return['nomtipo'];
        //Zend_Debug::dump($return);die;
        
    }
    
    public function retornaUltimo($params){
        
         $sql = "select
                  MAX(idtipo)AS idtipo,
                  tp.nomtipo
                  from agepnet200.tb_tiposituacaoprojeto tp
                  inner join agepnet200.tb_statusreport st on tp.idtipo = st.domstatusprojeto 
                  where st.idprojeto = " .$params . "
                  group by tp.nomtipo"; 
         
         //Zend_Debug::dump($sql);
          $return = $this->_db->fetchRow($sql);
          return $return['nomtipo'];
    }
}

