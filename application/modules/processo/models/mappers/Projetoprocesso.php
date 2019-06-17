<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Processo_Model_Mapper_Projetoprocesso extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Processo_Model_Projetoprocesso
     */
    public function insert(Processo_Model_Projetoprocesso $model)
    {
        $model->idprojetoprocesso = $this->maxVal('idprojetoprocesso');
        $model->idcadastrador = '30605';
        $data = array(
            "idprojetoprocesso" => $model->idprojetoprocesso,
            "idprocesso" => $model->idprocesso,
            "numano" => $model->numano,
            "domsituacao" => $model->domsituacao,
            "datsituacao" => new Zend_Db_Expr("to_date('" . $model->datsituacao->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "idresponsavel" => $model->idresponsavel,
            "desprojetoprocesso" => $model->desprojetoprocesso,
            "datinicioprevisto" => new Zend_Db_Expr("to_date('" . $model->datinicioprevisto->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datterminoprevisto" => new Zend_Db_Expr("to_date('" . $model->datterminoprevisto->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "vlrorcamento" => $model->vlrorcamento,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr("now()"),
        );
//      $this->getDbTable()->insert($data);

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
     * @return Processo_Model_Projetoprocesso
     */
    public function update(Processo_Model_Projetoprocesso $model)
    {
        $data = array(
            "idprojetoprocesso" => $model->idprojetoprocesso,
            "idprocesso" => $model->idprocesso,
            "numano" => $model->numano,
            "domsituacao" => $model->domsituacao,
            "datsituacao" => new Zend_Db_Expr("to_date('" . $model->datsituacao->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "idresponsavel" => $model->idresponsavel,
            "desprojetoprocesso" => $model->desprojetoprocesso,
            "datinicioprevisto" => new Zend_Db_Expr("to_date('" . $model->datinicioprevisto->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datterminoprevisto" => new Zend_Db_Expr("to_date('" . $model->datterminoprevisto->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "vlrorcamento" => $model->vlrorcamento,
//             "idcadastrador"      => $model->idcadastrador,
//             "datcadastro"        => $model->datcadastro,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));

        try {
            $pks = array(
                "idprojetoprocesso" => $model->idprojetoprocesso,
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
        return $this->_getForm(Processo_Form_Projetoprocesso);
    }

    public function fetchSituacao()
    {
        $retorno = array(
            '1' => 'Não Iniciado',
            '2' => 'Em andamento',
            '3' => 'Concluído',
            '4' => 'Cancelado'
        );
        return $retorno;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $paginator = false)
    {
        // 'Ano','Processo','Diretoria','Responsável','Início Previsto','Término Previsto','Orçamento','Situação','Data Situação'

        $sql = "
	    			SELECT 
						proj.numano,
						(SELECT p.nomprocesso FROM agepnet200.tb_processo p WHERE p.idprocesso = proj.idprocesso) as nomprocesso,
						(SELECT setor.nomsetor FROM agepnet200.tb_setor setor WHERE setor.idsetor = proc.idsetor) as nomsetor,
						(SELECT pess.nompessoa FROM agepnet200.tb_pessoa pess WHERE pess.idpessoa = proj.idresponsavel) as idresponsavel,
						to_char(proj.datinicioprevisto, 'DD/MM/YYYY') as datinicioprevisto,
						to_char(proj.datterminoprevisto, 'DD/MM/YYYY') as datterminoprevisto,
						proj.vlrorcamento,
    					CASE proj.domsituacao 
    						 WHEN 1 THEN 'Não Iniciado'
    						 WHEN 2 THEN 'Em Andamento'
    						 WHEN 3 THEN 'Concluído'
    						 WHEN 4 THEN 'Cancelado'
    					END,
						to_char(proj.datsituacao, 'DD/MM/YYYY') as datsituacao,
                                                proj.idprojetoprocesso
					FROM
						agepnet200.tb_projetoprocesso proj,
						agepnet200.tb_processo proc
					WHERE
						proj.idprocesso = proc.idprocesso
    			";

        $params = array_filter($params);
        if (isset($params['nomprocesso'])) {
            $nomprocesso = strtoupper($params['nomprocesso']);
            $sql .= " AND upper(proc.nomprocesso) LIKE '%{$nomprocesso}%'";
        }

        if (isset($params['diretoria'])) {
            $sql .= " AND proc.idsetor =  '{$params['diretoria']}'";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

        //Zend_Debug::dump($sql);//exit;

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
        //	Zend_Debug::dump($resultado);
        return $resultado;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "SELECT
                    idprojetoprocesso,
                    idprocesso,
                    numano,
                    domsituacao,
                    to_char(datsituacao, 'DD/MM/YYYY') as datsituacao,
                    idresponsavel,
                    pes.nompessoa as nomresponsavel,
                    desprojetoprocesso,
                    to_char(datinicioprevisto, 'DD/MM/YYYY') as datinicioprevisto,
                    to_char(datterminoprevisto, 'DD/MM/YYYY') as datterminoprevisto,
                    vlrorcamento,
                    proj.idcadastrador,
                    to_char(datsituacao, 'DD/MM/YYYY') as datsituacao
                FROM
                    agepnet200.tb_projetoprocesso proj,
                    agepnet200.tb_pessoa as pes
                WHERE
                    idresponsavel = pes.idpessoa
                    and idprojetoprocesso	= :idprojetoprocesso";

        $resultado = $this->_db->fetchRow($sql, array('idprojetoprocesso' => $params['idprojetoprocesso']));
        $processo = new Processo_Model_Projetoprocesso($resultado);
        return $processo;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getByIdDetalhar($params)
    {
        $sql = "
    			SELECT 
						proj.numano,
						(SELECT p.nomprocesso FROM agepnet200.tb_processo p WHERE p.idprocesso = proj.idprocesso) as nomprocesso,
						(SELECT setor.nomsetor FROM agepnet200.tb_setor setor WHERE setor.idsetor = proc.idsetor) as nomsetor,
						(SELECT pess.nompessoa FROM agepnet200.tb_pessoa pess WHERE pess.idpessoa = proj.idresponsavel) as idresponsavel,
    					(SELECT pess.nompessoa FROM agepnet200.tb_pessoa pess WHERE pess.idpessoa = proj.idcadastrador) as idcadastrador,
						to_char(proj.datinicioprevisto, 'DD/MM/YYYY') as datinicioprevisto,
						to_char(proj.datterminoprevisto, 'DD/MM/YYYY') as datterminoprevisto,
						proj.vlrorcamento,
    					CASE proj.domsituacao 
    						 WHEN 1 THEN 'Não Iniciado'
    						 WHEN 2 THEN 'Em Andamento'
    						 WHEN 3 THEN 'Concluído'
    						 WHEN 4 THEN 'Cancelado'
    					END as domsituacao,
						to_char(proj.datsituacao, 'DD/MM/YYYY') as datsituacao,
    					to_char(proj.datcadastro, 'DD/MM/YYYY') as datcadastro,
    					proj.desprojetoprocesso,
    					proj.idprojetoprocesso
					FROM
						agepnet200.tb_projetoprocesso proj,
						agepnet200.tb_processo proc
					WHERE
						proj.idprocesso = proc.idprocesso
    					AND idprojetoprocesso	= :idprojetoprocesso
    			";

        $resultado = $this->_db->fetchRow($sql, array('idprojetoprocesso' => $params['idprojetoprocesso']));
        $processo = new Processo_Model_Projetoprocesso($resultado);
        return $processo;
    }

    public function fetchPairs()
    {
        $sql = " SELECT 
    					idprojetoprocesso, 
    					(SELECT p.nomprocesso FROM agepnet200.tb_processo p WHERE p.idprocesso = proj.idprocesso) as nomprocesso 
    			FROM 
    					agepnet200.tb_projetoprocesso proj 
    			order by nomprocesso asc";
        return $this->_db->fetchPairs($sql);
    }
}

