<?php

class Projeto_Model_Mapper_Contramedida extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Contramedida
     */
    public function insert(Projeto_Model_Contramedida $model)
    {
        $data = array(
            "idcontramedida" => $this->maxVal('idcontramedida'),
            "idrisco" => $model->idrisco,
            "nocontramedida" => $model->nocontramedida,
            "descontramedida" => $model->descontramedida,
            "datprazocontramedida" => $model->datprazocontramedida ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedida . "','DD-MM-YYYY')") : null,
            "datprazocontramedidaatraso" => $model->datprazocontramedidaatraso ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedidaatraso . "','DD-MM-YYYY')") : null,
            "domstatuscontramedida" => !empty($model->domstatuscontramedida) ? $model->domstatuscontramedida : null,
            "flacontramedidaefetiva" => $model->flacontramedidaefetiva,
            "desresponsavel" => $model->desresponsavel,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "idtipocontramedida" => $model->idtipocontramedida,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Contramedida
     */
    public function update(Projeto_Model_Contramedida $model)
    {
        $data = array(
            "idcontramedida" => $model->idcontramedida,
            "idrisco" => $model->idrisco,
            "nocontramedida" => $model->nocontramedida,
            "descontramedida" => $model->descontramedida,
            "datprazocontramedida" => $model->datprazocontramedida ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedida . "','DD-MM-YYYY')") : null,
            "datprazocontramedidaatraso" => $model->datprazocontramedidaatraso ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedidaatraso . "','DD-MM-YYYY')") : null,
            "domstatuscontramedida" => !empty($model->domstatuscontramedida) ? $model->domstatuscontramedida : null,
            "desresponsavel" => $model->desresponsavel,
            "flacontramedidaefetiva" => $model->flacontramedidaefetiva == "" ? null : $model->flacontramedidaefetiva,
        );
        $ret = $this->getDbTable()->update($data, array("idcontramedida = ?" => $model->idcontramedida));
        return $ret;
    }

    public function delete($params)
    {
        $where = $this->quoteInto('idcontramedida = ?', (int)$params['idcontramedida']);
        $result = $this->getDbTable()->delete($where);
        return $result;
    }

    public function copiaContramedidaByRisco($params)
    {
        $sql = "insert into agepnet200.tb_contramedida(idcontramedida, idrisco, descontramedida,
        datprazocontramedida,datprazocontramedidaatraso, domstatuscontramedida,
        flacontramedidaefetiva, desresponsavel, idcadastrador,
        datcadastro, idtipocontramedida, nocontramedida)(SELECT
        (SELECT MAX(idcontramedida) FROM agepnet200.tb_contramedida) + ROW_NUMBER()
          OVER (ORDER BY idcontramedida) idcontramedida1,
          :idriscoNovo, tb1.descontramedida, tb1.datprazocontramedida,tb1.datprazocontramedidaatraso,
          tb1.domstatuscontramedida, tb1.flacontramedidaefetiva, tb1.desresponsavel, tb1.idcadastrador,
          tb1.datcadastro, tb1.idtipocontramedida, tb1.nocontramedida
        FROM agepnet200.tb_contramedida tb1
        WHERE tb1.idrisco = :idrisco and not exists(
	       select 1 FROM agepnet200.tb_contramedida tb2
	       where tb2.idrisco = :idriscoNovo and
	       tb2.datprazocontramedida =  tb1.datprazocontramedida and
	       tb2.datprazocontramedidaatraso = tb1.datprazocontramedidaatraso and
	       tb2.domstatuscontramedida = tb1.domstatuscontramedida and
	       tb2.flacontramedidaefetiva = tb1.flacontramedidaefetiva and
	       tb2.idtipocontramedida = tb1.idtipocontramedida and
	       tb2.nocontramedida = tb1.nocontramedida)
        );";

        if ($this->_db->query($sql, array(
                'idrisco' => $params['idrisco'],
                'idriscoNovo' => $params['idriscoNovo']
            )
        )) {
            return true;
        } else {
            return false;
        }
    }

    public function getForm()
    {
        return $this->_getForm(Projeto_Form_Contramedida);
    }

    public function getByIdDetalhar($params)
    {
        $sql = "SELECT
                    tc.nocontramedida,
                    tc.descontramedida,
                    to_char(tc.datprazocontramedida, 'DD/MM/YYYY') as datprazocontramedida, 
                    to_char(tc.datprazocontramedidaatraso, 'DD/MM/YYYY') as datprazocontramedidaatraso, 
                    CASE 
                        WHEN tc.domstatuscontramedida = 1 THEN 'Atrasada'
                        WHEN tc.domstatuscontramedida = 2 THEN 'Cancelada'
                        WHEN tc.domstatuscontramedida = 3 THEN 'Concluída'
                        WHEN tc.domstatuscontramedida = 4 THEN 'Em Andamento'
                        WHEN tc.domstatuscontramedida = 5 THEN 'Não Iniciada'
                        WHEN tc.domstatuscontramedida = 6 THEN 'Paralisada'
                        ELSE '-'
                    END as domstatuscontramedida,
                    CASE 
                        WHEN tc.flacontramedidaefetiva = 1 THEN 'Sim'
                        WHEN tc.flacontramedidaefetiva = 2 THEN 'Não'
                        ELSE '-'
                    END as flacontramedidaefetiva,
                    ttc.notipocontramedida,
                    tc.desresponsavel, 
                    tc.idcontramedida, 
                    tc.idrisco 
                FROM agepnet200.tb_contramedida tc
                INNER JOIN agepnet200.tb_tipocontramedida ttc on ttc.idtipocontramedida = tc.idtipocontramedida
                WHERE tc.idcontramedida = :idcontramedida";
        $resultado = $this->_db->fetchRow($sql, array('idcontramedida' => (int)$params['idcontramedida']));
        return $resultado;
    }

    public function getById($params)
    {
        $sql = "SELECT
                    idcontramedida, 
                    idrisco, 
                    nocontramedida, 
                    descontramedida, 
                    to_char(datprazocontramedida, 'DD/MM/YYYY') as datprazocontramedida, 
                    to_char(datprazocontramedidaatraso, 'DD/MM/YYYY') as datprazocontramedidaatraso, 
                    domstatuscontramedida, 
                    flacontramedidaefetiva, 
                    desresponsavel, 
                    idtipocontramedida 
                FROM agepnet200.tb_contramedida
                WHERE idcontramedida = :idcontramedida";
        $resultado = $this->_db->fetchRow($sql, array('idcontramedida' => (int)$params['idcontramedida']));
        return new Projeto_Model_Contramedida($resultado);
    }

    public function retornaPorRiscoToGrid($params)
    {
        $params = array_filter($params);

        $sql = "SELECT
                    tr.norisco,
                    tc.nocontramedida, 
                    to_char(tc.datprazocontramedida, 'DD/MM/YYYY') as datprazocontramedida, 
                    to_char(tc.datprazocontramedidaatraso, 'DD/MM/YYYY') as datprazocontramedidaatraso, 
                    CASE 
                        WHEN tc.domstatuscontramedida = 1 THEN 'Atrasada'
                        WHEN tc.domstatuscontramedida = 2 THEN 'Cancelada'
                        WHEN tc.domstatuscontramedida = 3 THEN 'Concluída'
                        WHEN tc.domstatuscontramedida = 4 THEN 'Em Andamento'
                        WHEN tc.domstatuscontramedida = 5 THEN 'Não Iniciada'
                        WHEN tc.domstatuscontramedida = 6 THEN 'Paralisada'
                        ELSE '-'
                    END as domstatuscontramedida,
                    CASE 
                        WHEN tc.flacontramedidaefetiva = 1 THEN 'Sim'
                        WHEN tc.flacontramedidaefetiva = 2 THEN 'Não'
                        ELSE '-'
                    END as flacontramedidaefetiva,
                    ttc.notipocontramedida,
                    tc.desresponsavel, 
                    tc.idcontramedida, 
                    tc.idrisco 
                FROM agepnet200.tb_contramedida tc
                INNER JOIN agepnet200.tb_risco tr on tr.idrisco = tc.idrisco
                INNER JOIN agepnet200.tb_tipocontramedida ttc on ttc.idtipocontramedida = tc.idtipocontramedida
                WHERE tc.idrisco = " . (int)$params['idrisco'];

        if (isset($params['nocontramedida']) && $params['nocontramedida'] != "") {
            $sql .= " AND tc.nocontramedida ilike '%{$params['nocontramedida']}%'";
        }
        if (isset($params['desresponsavel']) && $params['desresponsavel'] != "") {
            $sql .= " AND tc.desresponsavel ilike '%{$params['desresponsavel']}%'";
        }
        if (isset($params['flacontramedidaefetiva']) && $params['flacontramedidaefetiva'] != "") {
            $sql .= " AND tc.flacontramedidaefetiva = '" . (int)$params['flacontramedidaefetiva'] . "'";
        }
        if (isset($params['idtipocontramedida']) && $params['idtipocontramedida'] != "") {
            $sql .= " AND tc.idtipocontramedida = '" . (int)$params['idtipocontramedida'] . "'";
        }
        if (isset($params['domstatuscontramedida']) && $params['domstatuscontramedida'] != "") {
            $sql .= " AND tc.domstatuscontramedida = '" . (int)$params['domstatuscontramedida'] . "'";
        }
        if (isset($params['datprazocontramedida']) && $params['datprazocontramedida'] != "") {
            $sql .= " AND tc.datprazocontramedida = to_date('{$params['datprazocontramedida']}','DD/MM/YYYY') ";
        }
        if (isset($params['datprazocontramedidaatraso']) && $params['datprazocontramedidaatraso'] != "") {
            $sql .= " AND tc.datprazocontramedidaatraso = to_date('{$params['datprazocontramedidaatraso']}','DD/MM/YYYY') ";
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

        return;
    }

    /**
     * Possíveis status para as contramedidas.
     * @return array
     */
    public function statusContramendida()
    {
        return array(
            '' => 'Selecione',
            '6' => 'Atrasada',
            '4' => 'Cancelada',
            '3' => 'Concluída',
            '2' => 'Em andamento',
            '1' => 'Não iniciada',
            '5' => 'Paralizada',
        );
    }

    public function getContramedidaPorRisco($params)
    {
        $sql = "SELECT
                    idcontramedida, 
                    idrisco, 
                    nocontramedida, 
                    descontramedida, 
                    to_char(datprazocontramedida, 'DD/MM/YYYY') as datprazocontramedida, 
                    to_char(datprazocontramedidaatraso, 'DD/MM/YYYY') as datprazocontramedidaatraso, 
                    domstatuscontramedida, 
                    flacontramedidaefetiva, 
                    desresponsavel, 
                    idtipocontramedida 
                FROM agepnet200.tb_contramedida
                WHERE idrisco = :idrisco";
        $resultado = $this->_db->fetchRow($sql, array('idrisco' => (int)$params['idrisco']));
        return new Projeto_Model_Contramedida($resultado);
    }

}
