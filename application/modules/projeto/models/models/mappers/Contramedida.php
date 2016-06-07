<?php

class Projeto_Model_Mapper_Contramedida extends App_Model_Mapper_MapperAbstract {

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
            "datprazocontramedida" => $model->datprazocontramedida ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedida . "','DD-MM-YYYY')") : NULL,
            "datprazocontramedidaatraso" => $model->datprazocontramedidaatraso ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedidaatraso . "','DD-MM-YYYY')") : NULL,
            "domstatuscontramedida" => $model->domstatuscontramedida,
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
            "datprazocontramedida" => $model->datprazocontramedida ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedida . "','DD-MM-YYYY')") : NULL,
            "datprazocontramedidaatraso" => $model->datprazocontramedidaatraso ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedidaatraso . "','DD-MM-YYYY')") : NULL,
            "domstatuscontramedida" => $model->domstatuscontramedida,
            "flacontramedidaefetiva" => $model->flacontramedidaefetiva,
            "desresponsavel" => $model->desresponsavel,
            "idtipocontramedida" => $model->idtipocontramedida,
        );
         
        return $this->getDbTable()->update($data, array("idcontramedida = ?" => $model->idcontramedida));
    }

    public function delete($params)
    {
       $where =  $this->quoteInto('idcontramedida = ?', (int)$params['idcontramedida']);        
       $result =  $this->getDbTable()->delete($where);
       return $result;
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
        $resultado = $this->_db->fetchRow($sql, array('idcontramedida' => (int) $params['idcontramedida']));
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
                WHERE tc.idrisco = " . (int) $params['idrisco'];        

        if(isset($params['nocontramedida']) && $params['nocontramedida'] != "") {
            $sql .= " AND tc.nocontramedida ilike '%{$params['nocontramedida']}%'";
        }
        if(isset($params['desresponsavel']) && $params['desresponsavel'] != "") {
            $sql .= " AND tc.desresponsavel ilike '%{$params['desresponsavel']}%'";
        }
        if(isset($params['flacontramedidaefetiva']) && $params['flacontramedidaefetiva'] != "") {
            $sql .= " AND tc.flacontramedidaefetiva = '".(int) $params['flacontramedidaefetiva']."'";
        }
        if(isset($params['idtipocontramedida']) && $params['idtipocontramedida'] != "") {
            $sql .= " AND tc.idtipocontramedida = '".(int) $params['idtipocontramedida']."'";
        }
        if(isset($params['domstatuscontramedida']) && $params['domstatuscontramedida'] != "") {
            $sql .= " AND tc.domstatuscontramedida = '".(int) $params['domstatuscontramedida']."'";
        }
        if(isset($params['datprazocontramedida']) && $params['datprazocontramedida'] != "") {
            $sql .= " AND tc.datprazocontramedida = to_date('{$params['datprazocontramedida']}','DD/MM/YYYY') ";
        }
        if(isset($params['datprazocontramedidaatraso']) && $params['datprazocontramedidaatraso'] != "") {
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
        } catch ( Exception $exc ) {
            throw new Exception($exc->code());
        }

        return;
    }

}
