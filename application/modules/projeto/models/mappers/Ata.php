<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Ata extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Ata
     */
    public function insert(Projeto_Model_Ata $model)
    {
        try {
            $data = array(
                "idata" => $this->maxVal('idata'),
                "idprojeto" => $model->idprojeto,
                "desassunto" => $model->desassunto,
                "datata" => new Zend_Db_Expr("to_date('" . $model->datata . "','DD-MM-YYYY')"),
                "hrreuniao" => $model->hrreuniao,
                "deslocal" => $model->deslocal,
                "desparticipante" => $model->desparticipante,
                "despontodiscutido" => $model->despontodiscutido,
                "desdecisao" => $model->desdecisao,
                "despontoatencao" => $model->despontoatencao,
                "idcadastrador" => $model->idcadastrador,
                "datcadastro" => new Zend_Db_Expr('now()'),
                "desproximopasso" => $model->desproximopasso,
            );
            return $this->getDbTable()->insert($data);
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Ata
     */
    public function update(Projeto_Model_Ata $model)
    {
        $data = array(
            "idata" => $model->idata,
            "idprojeto" => $model->idprojeto,
            "desassunto" => $model->desassunto,
            "datata" => new Zend_Db_Expr("to_date('" . $model->datata . "','DD-MM-YYYY')"),
            "hrreuniao" => $model->hrreuniao,
            "deslocal" => $model->deslocal,
            "desparticipante" => $model->desparticipante,
            "despontodiscutido" => $model->despontodiscutido,
            "desdecisao" => $model->desdecisao,
            "despontoatencao" => $model->despontoatencao,
            "idcadastrador" => $model->idcadastrador,
            //"datcadastro" => new Zend_Db_Expr('now()'),
            "desproximopasso" => $model->desproximopasso,
        );
        try {
            return $this->getDbTable()->update($data, array("idata = ?" => $data['idata']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function delete($params)
    {
        $where = $this->quoteInto('idata = ?', (int)$params['idata']);

        $result = $this->getDbTable()->delete($where);
        return $result;
    }

    public function getById($params)
    {
        $sql = "SELECT
                    idata,
                    idprojeto,
                    desassunto,
                    datata,
                    hrreuniao,
                    deslocal,
                    desparticipante,
                    despontodiscutido,
                    desdecisao,
                    despontoatencao,
                    idcadastrador,
                    datcadastro,
                    desproximopasso
                FROM agepnet200.tb_ata
                WHERE idata = :idata";
        $resultado = $this->_db->fetchRow($sql, array('idata' => $params['idata']));
        return new Projeto_Model_Ata($resultado);
    }

    public function getByIdDetalhar($params)
    {
        try {
            $sql = "SELECT
                    idata,
                    idprojeto,
                    desassunto,
                    to_char(datata, 'DD/MM/YYYY') as datata,
                    hrreuniao,
                    deslocal,
                    desparticipante,
                    despontodiscutido,
                    desdecisao,
                    despontoatencao,
                    idcadastrador,
                    datcadastro,
                    desproximopasso
                FROM agepnet200.tb_ata
                WHERE idata = :idata";
            $resultado = $this->_db->fetchRow($sql, array('idata' => $params['idata']));
            return new Projeto_Model_Ata($resultado);
        } catch (Exception $exc) {
            throw new Exception($exc->getCode());
        }
    }

    public function getByIdImprimir($params)
    {
        $sql = "SELECT
                    idata,
                    idprojeto,
                    desassunto,
                    to_char(datata, 'DD/MM/YYYY') as datata,
                    hrreuniao,
                    deslocal,
                    desparticipante,
                    despontodiscutido,
                    desdecisao,
                    despontoatencao,
                    idcadastrador,
                    datcadastro,
                    desproximopasso
                FROM agepnet200.tb_ata
                WHERE idata = :idata";
        $resultado = $this->_db->fetchRow($sql, array('idata' => $params['idata']));
        return new Projeto_Model_Ata($resultado);
    }

    public function retornaPorProjeto($params)
    {
        $sql = "SELECT
                    idata,
                    idprojeto,
                    desassunto,
                    datata,
                    hrreuniao,
                    deslocal,
                    desparticipante,
                    despontodiscutido,
                    desdecisao,
                    despontoatencao,
                    idcadastrador,
                    datcadastro,
                    desproximopasso
                FROM agepnet200.tb_ata
                WHERE idprojeto = :idprojeto";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return new Projeto_Model_Ata($resultado);
    }

    public function findAllByProjeto($params)
    {
        $sql = "SELECT
                    idata,
                    idprojeto,
                    desassunto,
                    to_char(datata, 'DD/MM/YYYY') as datata,
                    hrreuniao,
                    deslocal,
                    desparticipante,
                    despontodiscutido,
                    desdecisao,
                    despontoatencao,
                    idcadastrador,
                    datcadastro,
                    desproximopasso
                FROM agepnet200.tb_ata
                WHERE idprojeto = :idprojeto ";

        if (@trim($params['idata']) != "") {
            $sql .= " and idata = :idata ";
        }
        if (@trim($params['idata']) != "") {
            $resultado = $this->_db->fetchAll(
                $sql, array(
                    'idprojeto' => $params['idprojeto'],
                    'idata' => $params['idata']
                )
            );
        } else {
            $resultado = $this->_db->fetchAll(
                $sql, array('idprojeto' => $params['idprojeto'])
            );
        }
        return $resultado;
    }

    public function retornaPorProjetoToGrid($params)
    {
        $params = array_filter($params);
        $sql = "SELECT
                    to_char(ata.datata, 'DD/MM/YYYY')||' - '||ata.hrreuniao as datahora,
                    ata.desassunto,
                    ata.deslocal,
                    pes.nompessoa,
                    ata.idata,
                    ata.idcadastrador,
                    ata.idprojeto,
                    ata.desparticipante,
                    ata.despontodiscutido,
                    ata.desdecisao,
                    ata.despontoatencao,
                    ata.datcadastro,
                    ata.desproximopasso
                FROM agepnet200.tb_ata ata,
                     agepnet200.tb_pessoa pes
                WHERE idprojeto = " . (int)$params['idprojeto'] . "
                AND ata.idcadastrador = pes.idpessoa ";
        if (isset($params['deslocal']) && $params['deslocal'] != "") {
            $sql .= " AND UPPER(ata.deslocal) LIKE '%" . strtoupper($params['deslocal']) . "%' ";
        }
        if (isset($params['desassunto']) && $params['desassunto'] != "") {
            $sql .= " AND UPPER(ata.desassunto) LIKE '%" . strtoupper($params['desassunto']) . "%' ";
        }
        if (isset($params['hrreuniao']) && $params['hrreuniao'] != "") {
            $sql .= " AND ata.hrreuniao = '{$params['hrreuniao']}' ";
        }
        if (isset($params['datata']) && $params['datata'] != "") {
            $sql .= " AND ata.datata = to_date('{$params['datata']}','DD/MM/YYYY') ";
        }

        $sql .= ' order by ' . $params['sidx'] . ' ' . $params['sord'];

        $page = (isset($params['page'])) ? $params['page'] : 1;
        $limit = (isset($params['rows'])) ? $params['rows'] : 20;
        $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }

    public function copiaAtaByProjeto($params)
    {

        $sql = "insert into agepnet200.tb_ata(idata,
        idprojeto, desassunto, datata, deslocal, desparticipante,
        despontodiscutido, desdecisao, despontoatencao, idcadastrador,
        datcadastro, desproximopasso, hrreuniao)(SELECT
        (SELECT MAX(idata) FROM agepnet200.tb_ata) + ROW_NUMBER()
                OVER (ORDER BY idata) idata,
       :idprojetoNovo, tb1.desassunto, tb1.datata, tb1.deslocal, tb1.desparticipante,
       tb1.despontodiscutido, tb1.desdecisao, tb1.despontoatencao, tb1.idcadastrador,
       tb1.datcadastro, tb1.desproximopasso, tb1.hrreuniao
       FROM agepnet200.tb_ata tb1
       WHERE tb1.idprojeto = :idprojeto and not exists(
		   select 1 FROM agepnet200.tb_ata tb2
		   where tb2.idprojeto = :idprojetoNovo and
		   tb2.desassunto = tb1.desassunto and tb2.datata = tb1.datata and
		   tb2.deslocal = tb1.deslocal and tb2.desparticipante = tb1.desparticipante and
		   tb2.despontodiscutido = tb1.despontodiscutido and tb2.desdecisao = tb1.desdecisao and
		   tb2.despontoatencao = tb1.despontoatencao and tb2.desproximopasso = tb1.desproximopasso and
		   tb2.hrreuniao = tb1.hrreuniao)
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

}
