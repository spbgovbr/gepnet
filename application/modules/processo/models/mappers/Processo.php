<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Processo_Model_Mapper_Processo extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Processo_Model_Processo
     */
    public function insert(Processo_Model_Processo $model)
    {
        $model->idprocesso = $this->maxVal('idprocesso');
        $model->idcadastrador = '30605';
        $data = array(
            "idprocesso" => $model->idprocesso,
            "idprocessopai" => $model->idprocessopai,
            "nomcodigo" => $model->nomcodigo,
            "nomprocesso" => $model->nomprocesso,
            "idsetor" => $model->idsetor,
            "desprocesso" => $model->desprocesso,
            "iddono" => $model->iddono,
            "idexecutor" => $model->idexecutor,
            "idgestor" => $model->idgestor,
            "idconsultor" => $model->idconsultor,
            "numvalidade" => $model->numvalidade,
// 	        "datatualizacao" => new Zend_Db_Expr("now()"),
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr("now()"),
        );

        try {
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
     * @return Processo_Model_Processo
     */
    public function update(Processo_Model_Processo $model)
    {
        $data = array(
            "idprocesso" => $model->idprocesso,
            "idprocessopai" => $model->idprocessopai,
            "nomcodigo" => $model->nomcodigo,
            "nomprocesso" => $model->nomprocesso,
            "idsetor" => $model->idsetor,
            "desprocesso" => $model->desprocesso,
            "iddono" => $model->iddono,
            "idexecutor" => $model->idexecutor,
            "idgestor" => $model->idgestor,
            "idconsultor" => $model->idconsultor,
            "numvalidade" => $model->numvalidade,
            "datatualizacao" => new Zend_Db_Expr("now()"),
//          "idcadastrador"  => $model->idcadastrador,
//          "datcadastro"    => $model->datcadastro,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));

        try {
            $pks = array(
                "idprocesso" => $model->idprocesso,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            //$where = $this->_db->quoteInto('idpessoa = ?', $model->idpessoa);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function getForm()
    {
        return $this->_getForm(Processo_Form_Processo);
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $paginator = false)
    {
        // 'Carbo','Nome','Matrícula','CPF','Lotação','Operações'

        $sql = "
	    			SELECT 	
    					proc.nomprocesso,
						setor.nomsetor, 
						(SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = proc.iddono) as dono, 
						(SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = proc.idgestor) as gestor, 
						(SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = proc.idexecutor) as executor,
						(SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = proc.idconsultor) as consultor,
						to_char(proc.datatualizacao, 'DD/MM/YYYY') as datatualizacao, 
						proc.numvalidade,
    					proc.idprocesso
					FROM 
						agepnet200.tb_processo proc, 
						agepnet200.tb_setor setor
					WHERE 	
						proc.idsetor 		= setor.idsetor
    			";

        $params = array_filter($params);
        if (isset($params['nomprocesso'])) {
            $nomprocesso = strtoupper($params['nomprocesso']);
            $sql .= " AND upper(nomprocesso) LIKE '%{$nomprocesso}%'";
        }

        if (isset($params['diretoria'])) {
            $sql .= " AND proc.idsetor =  '{$params['diretoria']}'";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }
        //Zend_Debug::dump($sql);exit;

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
//     	Zend_Debug::dump($resultado);exit;
        return $resultado;
    }

    /**
     * @param array $params
     * @return array
     * @todo aguardando a criação da view da tabela de unidade do owner comum
     */
    public function getById($params)
    {
        $sql = "
    			SELECT 	
                            proc.idprocessopai,
                            proc.nomprocesso,
                            proc.iddono,
                            proc.nomcodigo,
                            proc.idsetor,
                            proc.desprocesso,
                            proc.idcadastrador,
                            proc.datcadastro,
                            iddono, 
                            idgestor, 
                            idexecutor,
                            idconsultor,
                            to_char(proc.datatualizacao, 'DD/MM/YYYY') as datatualizacao, 
                            proc.numvalidade,
                            proc.idprocesso,
                            p1.nompessoa as nomdono, 
                            p2.nompessoa as nomgestor, 
                            p3.nompessoa as nomexecutor,
                            p4.nompessoa as nomconsultor
                       FROM 
                            agepnet200.tb_processo proc, 
                            agepnet200.tb_setor setor,
                            agepnet200.tb_pessoa p1,
                            agepnet200.tb_pessoa p2,
                            agepnet200.tb_pessoa p3,
                            agepnet200.tb_pessoa p4
                       WHERE 	
                            proc.idsetor        = setor.idsetor
                            and p1.idpessoa     = proc.iddono
                            and p2.idpessoa     = proc.idgestor
                            and p3.idpessoa     = proc.idexecutor
                            and p4.idpessoa     = proc.idconsultor
                            and proc.idprocesso	= :idprocesso";

        $resultado = $this->_db->fetchRow($sql, array('idprocesso' => $params['idprocesso']));
        $processo = new Processo_Model_Processo($resultado);
        return $processo;
    }

    /**
     * @param array $params
     * @return array
     * @todo aguardando a criação da view da tabela de unidade do owner comum
     */
    public function getByIdDetalhar($params)
    {
        $sql = "SELECT 	proc.nomprocesso,
    					(SELECT p.nomprocesso FROM agepnet200.tb_processo p WHERE p.idprocesso = proc.idprocessopai) as nomprocessopai,
						proc.iddono,
						setor.nomsetor as idsetor, 
						(SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = proc.iddono) as iddono, 
						(SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = proc.idgestor) as idgestor, 
						(SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = proc.idexecutor) as idexecutor,
						(SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = proc.idconsultor) as idconsultor,
						to_char(proc.datatualizacao, 'DD/MM/YYYY') as datatualizacao,
   						proc.nomcodigo,
    					proc.desprocesso,
						proc.numvalidade
					FROM 
						agepnet200.tb_processo proc, 
						agepnet200.tb_setor setor
					WHERE 	
						proc.idsetor 		= setor.idsetor
    					AND proc.idprocesso	= :idprocesso
    			";

        $resultado = $this->_db->fetchRow($sql, array('idprocesso' => $params['idprocesso']));
        $processo = new Processo_Model_Processo($resultado);
        return $processo;
    }

    public function fetchPairs()
    {
        $sql = " SELECT idprocesso, nomprocesso FROM agepnet200.tb_processo order by nomprocesso asc";
        return $this->_db->fetchPairs($sql);
    }

}

