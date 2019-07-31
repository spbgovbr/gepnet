<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Mudanca extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Mudanca
     */
    public function insert(Projeto_Model_Mudanca $model)
    {
        try{
            
            $model->idmudanca = $this->maxVal('idmudanca');
            $datdecisao = !empty($model->datdecisao)? new Zend_Db_Expr("to_date('" . $model->datdecisao->toString('Y-m-d') . "','YYYY-MM-DD')"): null;
            
            $data = array(
                "idmudanca"             => $model->idmudanca,
                "idprojeto"             => $model->idprojeto,
                "nomsolicitante"        => $model->nomsolicitante,
                "datsolicitacao"        => new Zend_Db_Expr("to_date('" . $model->datsolicitacao->toString('Y-m-d') . "','YYYY-MM-DD')"),
                "datdecisao"            => $datdecisao,
                "flaaprovada"           => $model->flaaprovada,
                "desmudanca"            => $model->desmudanca,
                "desjustificativa"      => $model->desjustificativa,
                "despareceregp"         => $model->despareceregp,
                "desaprovadores"        => $model->desaprovadores,
                "despareceraprovadores" => $model->despareceraprovadores,
                "idcadastrador"         => $model->idcadastrador,
                "idtipomudanca"         => $model->idtipomudanca,
                "datcadastro"           => new Zend_Db_Expr("now()")
            );
            
            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
            
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Mudanca
     */
    public function update(Projeto_Model_Mudanca $model)
    {
        
         $datdecisao = !empty($model->datdecisao)? new Zend_Db_Expr("to_date('" . $model->datdecisao->toString('Y-m-d') . "','YYYY-MM-DD')"): null;
        
         $data = array(
            "idmudanca"             => $model->idmudanca,
            "idprojeto"             => $model->idprojeto,
            "nomsolicitante"        => $model->nomsolicitante,
            "datsolicitacao"        => new Zend_Db_Expr("to_date('" . $model->datsolicitacao->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datdecisao"            => $datdecisao,
            "flaaprovada"           => $model->flaaprovada,
            "desmudanca"            => $model->desmudanca,
            "desjustificativa"      => $model->desjustificativa,
            "despareceregp"         => $model->despareceregp,
            "desaprovadores"        => $model->desaprovadores,
            "despareceraprovadores" => $model->despareceraprovadores,
            "idtipomudanca"         => $model->idtipomudanca
        );
        
        try {
        	$pks     = array(
        			"idmudanca" => $model->idmudanca,
        			"idprojeto" => $model->idprojeto
        	);
        	$where   = $this->_generateRestrictionsFromPrimaryKeys($pks);
        	$retorno = $this->getDbTable()->update($data, $where);
        	return $retorno;
        } catch ( Exception $exc ) {
        	throw $exc;
        }
    }

    public function delete($params)
    {
        try {
            $pks = array(
                "idmudanca" => $params['idmudanca'],
                "idprojeto" => $params['idprojeto']
            );
            $where   = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            //var_dump($retorno); //exit;
            return $retorno;
        } catch ( Exception $exc ) {
            throw $exc;
            //exit;
        }
    }
    
    public function getForm()
    {
        return $this->_getForm(Projeto_Form_Mudanca);
    }
    
    public function getById($params)
    {
        $idprojeto = $params['idprojeto'];
        $idmudanca = $params['idmudanca'];
        
        $sql = "select
                    m.nomsolicitante,
                    to_char(m.datsolicitacao, 'DD/MM/YYYY') as datsolicitacao,
                    tm.dsmudanca,
                    CASE 
                      WHEN   m.flaaprovada = 'S' THEN 'Sim'
                      WHEN   m.flaaprovada = 'N' THEN 'Não'
                      ELSE '' 
                    END as flaaprovada,
                    to_char(m.datdecisao, 'DD/MM/YYYY') as datdecisao,
                    p.nompessoa,
                    m.idmudanca,
                    m.idprojeto,
                    m.idcadastrador,
                    m.desmudanca as desmudanca,
                    m.desjustificativa,
                    m.despareceregp,
                    m.desaprovadores,
                    m.despareceraprovadores,
                    m.datcadastro
                from 
                    agepnet200.tb_mudanca m, 
                    agepnet200.tb_tipomudanca tm,
                    agepnet200.tb_pessoa p
                where 
                    m.idtipomudanca = tm.idtipomudanca
                    and m.idcadastrador = p.idpessoa
                    and idprojeto = :idprojeto
                    and m.idmudanca = :idmudanca
                order by m.datsolicitacao asc";
                    
         $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $idprojeto, 'idmudanca' => $idmudanca));
         return new Projeto_Model_Mudanca($resultado);            
        
    }
    
    public function retornaPorProjeto($params, $paginator = false) 
    {
     
        $idprojeto = $params['idprojeto'];
        $sql = "select
                    m.nomsolicitante,
                    to_char(m.datsolicitacao, 'DD/MM/YYYY') as datsolicitacao,
                    tm.dsmudanca,
                    CASE 
                      WHEN   m.flaaprovada = 'S' THEN 'Sim'
                      WHEN   m.flaaprovada = 'N' THEN 'Não'
                      ELSE '' 
                    END as flaaprovada,
                    to_char(m.datdecisao, 'DD/MM/YYYY') as datdecisao,
                    p.nompessoa,
                    m.idmudanca,
                    m.idprojeto,
                    m.idcadastrador,
                    m.desmudanca,
                    m.desjustificativa,
                    m.despareceregp,
                    m.desaprovadores,
                    m.despareceraprovadores,
                    m.datcadastro
                from 
                    agepnet200.tb_mudanca m, 
                    agepnet200.tb_tipomudanca tm,
                    agepnet200.tb_pessoa p
                where 
                    m.idtipomudanca = tm.idtipomudanca
                    and m.idcadastrador = p.idpessoa
                    and idprojeto = {$idprojeto}
                order by m.datsolicitacao asc";

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }
        
        
        $resultado = $this->_db->fetchAll($sql);
        
        return $resultado;
        
    }

}

