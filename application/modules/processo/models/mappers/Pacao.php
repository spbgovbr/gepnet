<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Processo_Model_Mapper_PAcao extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Processo_Model_PAcao
     */
    public function insert(Processo_Model_PAcao $model)
    {
        $model->id_p_acao = $this->maxVal('id_p_acao');
        $model->numseq = $this->maxVal('numseq');
        $model->idcadastrador = '30605';
        $data = array(
            "id_p_acao" => $model->id_p_acao,
            "idprojetoprocesso" => $model->idprojetoprocesso,
            "nom_p_acao" => $model->nom_p_acao,
            "des_p_acao" => $model->des_p_acao,
            "datinicioprevisto" => new Zend_Db_Expr("to_date('" . $model->datinicioprevisto->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datinicioreal" => new Zend_Db_Expr("to_date('" . $model->datinicioreal->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datterminoprevisto" => new Zend_Db_Expr("to_date('" . $model->datterminoprevisto->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datterminoreal" => new Zend_Db_Expr("to_date('" . $model->datterminoreal->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "idsetorresponsavel" => $model->idsetorresponsavel,
            "flacancelada" => $model->flacancelada,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr("now()"),
            "numseq" => $model->numseq,
            "idresponsavel" => $model->idresponsavel,
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
     * @return Processo_Model_PAcao
     */
    public function update(Processo_Model_PAcao $model)
    {
        $model->numseq = $this->maxVal('numseq');
        $data = array(
            "id_p_acao" => $model->id_p_acao,
            "idprojetoprocesso" => $model->idprojetoprocesso,
            "nom_p_acao" => $model->nom_p_acao,
            "des_p_acao" => $model->des_p_acao,
            "datinicioprevisto" => new Zend_Db_Expr("to_date('" . $model->datinicioprevisto->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datinicioreal" => new Zend_Db_Expr("to_date('" . $model->datinicioreal->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datterminoprevisto" => new Zend_Db_Expr("to_date('" . $model->datterminoprevisto->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datterminoreal" => new Zend_Db_Expr("to_date('" . $model->datterminoreal->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "idsetorresponsavel" => $model->idsetorresponsavel,
            "flacancelada" => $model->flacancelada,
//          "idcadastrador"      => $model->idcadastrador,
//          "datcadastro"        => $model->datcadastro,
            "numseq" => $model->numseq,
            "idresponsavel" => $model->idresponsavel,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));

        try {
            $pks = array(
                "id_p_acao" => $model->id_p_acao,
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
        return $this->_getForm(Processo_Form_PAcao);
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $paginator = false)
    {
//        Zend_Debug::dump($params);
//        exit;
        $sql = "SELECT
                    nom_p_acao,
                    des_p_acao,
                    to_char(pacao.datinicioprevisto, 'DD/MM/YYYY') as datinicioprevisto,
                    to_char(pacao.datinicioreal, 'DD/MM/YYYY') as datinicioreal,
                    to_char(pacao.datterminoprevisto, 'DD/MM/YYYY') as datterminoprevisto,
                    to_char(pacao.datterminoreal, 'DD/MM/YYYY') as datterminoreal,
                    (SELECT pess.nompessoa FROM agepnet200.tb_pessoa pess WHERE pess.idpessoa = pacao.idresponsavel) as idresponsavel,
                    (SELECT setor.nomsetor FROM agepnet200.tb_setor setor WHERE setor.idsetor = pacao.idsetorresponsavel) as idsetorresponsavel,
                    '',
                    idprojetoprocesso,
                    id_p_acao
                    --flacancelada,
                    --(SELECT pess.nompessoa FROM agepnet200.tb_pessoa pess WHERE pess.idpessoa = pacao.idcadastrador) as idcadastrador,
                    --to_char(pacao.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    --numseq,
                FROM
                    agepnet200.tb_p_acao pacao
    		WHERE
                    1 = 1 ";

        $params = array_filter($params);
        //Zend_Debug::dump($sql);exit;

        if (isset($params['idprojetoprocesso'])) {
            $idprojetoprocesso = strtoupper($params['idprojetoprocesso']);
            $sql .= " AND idprojetoprocesso = {$idprojetoprocesso} ";
        }
        if (isset($params['ipph'])) {
            $ipph = strtoupper($params['ipph']);
            $sql .= " AND idprojetoprocesso = {$ipph} ";
        }
        if (isset($params['nom_p_acao'])) {
            $nom_p_acao = strtoupper($params['nom_p_acao']);
            $sql .= " AND nom_p_acao like '%{$nom_p_acao}%' ";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

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

    public function fetchCancelada()
    {
        $retorno = array(
            '' => 'Selecione',
            '1' => 'Sim',
            '2' => 'Não'
        );
        return $retorno;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "SELECT 
                    id_p_acao,
                    nom_p_acao,
                    des_p_acao,
                    to_char(pacao.datinicioprevisto, 'DD/MM/YYYY') as datinicioprevisto,
                    to_char(pacao.datinicioreal, 'DD/MM/YYYY') as datinicioreal,
                    to_char(pacao.datterminoprevisto, 'DD/MM/YYYY') as datterminoprevisto,
                    to_char(pacao.datterminoreal, 'DD/MM/YYYY') as datterminoreal,
                    pacao.idresponsavel,
                    pess.nompessoa as nomresponsavel,
                    setor.nomsetor as nomsetorresponsavel,
                    setor.idsetor as idsetorresponsavel,
                    idprojetoprocesso,
                    flacancelada,
                    pacao.idcadastrador,
                    cada.nompessoa as nomcadastrador,
                    to_char(pacao.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    numseq
		FROM
                    agepnet200.tb_p_acao pacao,
                    agepnet200.tb_pessoa pess,
                    agepnet200.tb_pessoa cada,
                    agepnet200.tb_setor setor

    		WHERE
                    pacao.idresponsavel = pess.idpessoa
                    and pacao.idsetorresponsavel = setor.idsetor
                    and pacao.idcadastrador = cada.idpessoa
                    and pacao.id_p_acao = :id_p_acao ";

        $resultado = $this->_db->fetchRow($sql, array('id_p_acao' => $params['id_p_acao']));
        $pacao = new Processo_Model_Pacao($resultado);
        return $pacao;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getByIdDetalhar($params)
    {
        $sql = "
    			SELECT 
						id_p_acao,
						nom_p_acao,
						des_p_acao,
						to_char(pacao.datinicioprevisto, 'DD/MM/YYYY') as datinicioprevisto,
						to_char(pacao.datinicioreal, 'DD/MM/YYYY') as datinicioreal,
						to_char(pacao.datterminoprevisto, 'DD/MM/YYYY') as datterminoprevisto,
						to_char(pacao.datterminoreal, 'DD/MM/YYYY') as datterminoreal,
						(SELECT pess.nompessoa FROM agepnet200.tb_pessoa pess WHERE pess.idpessoa = pacao.idresponsavel) as idresponsavel,	
						(SELECT setor.nomsetor FROM agepnet200.tb_setor setor WHERE setor.idsetor = pacao.idsetorresponsavel) as idsetorresponsavel,
						idprojetoprocesso,
						CASE flacancelada
    						WHEN 1 THEN 'Sim'
    						WHEN 2 THEN 'Não'
    					END as flacancelada,
						(SELECT pess.nompessoa FROM agepnet200.tb_pessoa pess WHERE pess.idpessoa = pacao.idcadastrador) as idcadastrador,
						to_char(pacao.datcadastro, 'DD/MM/YYYY') as datcadastro, 
						numseq
					FROM 
						agepnet200.tb_p_acao pacao
    				WHERE
    					pacao.id_p_acao = :id_p_acao 
    			";

        $resultado = $this->_db->fetchRow($sql, array('id_p_acao' => $params['id_p_acao']));
        $pacao = new Processo_Model_Pacao($resultado);
        return $pacao;
    }

}

