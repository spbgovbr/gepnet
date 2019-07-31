<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Diariobordo extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Diariobordo
     */
    public function insert(Projeto_Model_Diariobordo $model)
    {
        $data = array(
            "iddiariobordo" => $this->maxVal('iddiariobordo'),
            "idprojeto" => $model->idprojeto,
            "datdiariobordo" => new Zend_Db_Expr("to_date('" . $model->datdiariobordo . "','DD-MM-YYYY')"),
            "domreferencia" => $model->domreferencia,
            "domsemafaro" => $model->domsemafaro,
            "desdiariobordo" => $model->desdiariobordo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
        );
        try {
            return $this->getDbTable()->insert($data);
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Diariobordo
     */
    public function update(Projeto_Model_Diariobordo $model)
    {
        $data = array(
            "iddiariobordo" => $model->iddiariobordo,
            "idprojeto" => $model->idprojeto,
            "datdiariobordo" => new Zend_Db_Expr("to_date('" . $model->datdiariobordo . "','DD-MM-YYYY')"),
            "domreferencia" => $model->domreferencia,
            "domsemafaro" => $model->domsemafaro,
            "desdiariobordo" => $model->desdiariobordo,
            "idalterador" => $model->idalterador,
        );
        try {
            return $this->getDbTable()->update($data,
                array("iddiariobordo = ?" => $model->iddiariobordo, "idprojeto = ?" => $model->idprojeto));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function delete($params)
    {
        $where = $this->quoteInto('iddiariobordo = ?', (int)$params['iddiariobordo']);

        $result = $this->getDbTable()->delete($where);
        return $result;
    }

    public function getForm()
    {
        return $this->_getForm(Projeto_Form_DiarioBordo);
    }

    public function getById($params)
    {
        $sql = "SELECT
                    to_char(db.datdiariobordo, 'DD/MM/YYYY') as datdiariobordo,
                    db.domreferencia,
                    CASE 
                        WHEN db.domsemafaro=1 THEN 'Vermelho'
                        WHEN db.domsemafaro=2 THEN 'Amarelo'
                        ELSE 'Verde'
                    END as domsemafarodecode,
                    pes.nompessoa,                    
                    db.domsemafaro,
                    db.desdiariobordo,
                    db.iddiariobordo,
                    db.idprojeto
                FROM agepnet200.tb_diariobordo db,
                     agepnet200.tb_pessoa pes
                WHERE db.iddiariobordo = :iddiariobordo
                 AND db.idcadastrador = pes.idpessoa";

        $resultado = $this->_db->fetchRow($sql, array('iddiariobordo' => (int)$params['iddiariobordo']));
        return $resultado;
    }

    public function copiaDiarioByProjeto($params)
    {

        $sql = "insert into agepnet200.tb_diariobordo(iddiariobordo,
        idprojeto, datdiariobordo, domreferencia, domsemafaro,
        desdiariobordo, idcadastrador, datcadastro, idalterador)(SELECT
	      (SELECT MAX(iddiariobordo) FROM agepnet200.tb_diariobordo) + ROW_NUMBER()
                OVER (ORDER BY iddiariobordo) iddiariobordo,
	      :idprojetoNovo, tb1.datdiariobordo, tb1.domreferencia, tb1.domsemafaro,
	      tb1.desdiariobordo, tb1.idcadastrador, tb1.datcadastro, tb1.idalterador
        FROM agepnet200.tb_diariobordo tb1
        where tb1.idprojeto=:idprojeto and not exists(
		   select 1 FROM agepnet200.tb_diariobordo tb2
		   where tb2.idprojeto = :idprojetoNovo and
		   tb2.datdiariobordo = tb1.datdiariobordo and tb2.domreferencia = tb1.domreferencia and
		   tb2.domsemafaro = tb1.domsemafaro and tb2.desdiariobordo = tb1.desdiariobordo)
        )";

        if ($this->_db->query($sql, array(
                'idprojeto' => $params['idprojeto'],
                'idprojetoNovo' => $params['idprojetoNovo']
            )
        )) {
            return true;
        } else {
            return false;
        }

    }

    public function retornaPorProjetoToGrid($params)
    {
        $params = array_filter($params);
        $sql = "SELECT
                    to_char(db.datdiariobordo, 'DD/MM/YYYY') as datdiariobordo,
                    db.domreferencia,
                    CASE 
                        WHEN db.domsemafaro=1 THEN '<span class=\"badge badge-important\">Vermelho</span>'
                        WHEN db.domsemafaro=2 THEN '<span class=\"badge badge-warning\">Amarelo</span>'
                        ELSE '<span class=\"badge badge-success\">Verde</span>'
                    END,
                    pes.nompessoa,
                    db.iddiariobordo,
                    db.idprojeto
                FROM agepnet200.tb_diariobordo db,
                     agepnet200.tb_pessoa pes
                WHERE db.idprojeto = " . (int)$params['idprojeto'] .
            " AND db.idcadastrador = pes.idpessoa ";
        if (isset($params['domreferencia']) && $params['domreferencia'] != "") {
            $sql .= " AND db.domreferencia = '{$params['domreferencia']}'";
        }
        if (isset($params['domsemafaro']) && $params['domsemafaro'] != "") {
            $sql .= " AND db.domsemafaro = '" . (int)$params['domsemafaro'] . "'";
        }
        if (isset($params['datdiariobordo']) && $params['datdiariobordo'] != "") {
            $sql .= " AND db.datdiariobordo >= to_date('{$params['datdiariobordo']}','DD/MM/YYYY') ";
        }
        if (isset($params['datdiariobordofim']) && $params['datdiariobordofim'] != "") {
            $sql .= " AND db.datdiariobordo <= to_date('{$params['datdiariobordofim']}','DD/MM/YYYY') ";
        }

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
    }

}
