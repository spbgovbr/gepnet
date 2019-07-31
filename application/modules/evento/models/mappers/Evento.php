<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Evento_Model_Mapper_Evento extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Evento_Model_Evento
     */
    public function insert(Evento_Model_Evento $model)
    {
        try {

            $model->idevento = $this->maxVal('idevento');
            // var_dump($model->idevento); exit;

            $data = array(
                "idevento" => $model->idevento,
                "nomevento" => $model->nomevento,
                "desevento" => $model->desevento,
                "desobs" => $model->desobs,
                "idcadastrador" => $model->idcadastrador,
                "idresponsavel" => $model->idresponsavel,
                "datcadastro" => new Zend_Db_Expr("now()"),
                "datinicio" => new Zend_Db_Expr("to_date('" . $model->datinicio->toString('Y-m-d') . "','YYYY-MM-DD')"),
                "datfim" => new Zend_Db_Expr("to_date('" . $model->datfim->toString('Y-m-d') . "','YYYY-MM-DD')"),
                "uf" => $model->uf,
            );
            $retorno = $this->getDbTable()->insert($data);
            return $retorno;

        } catch (Exeption $exc) {
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Evento
     */
    public function update(Evento_Model_Evento $model)
    {
        $data = array(
            "idevento" => $model->idevento,
            "nomevento" => $model->nomevento,
            "desevento" => $model->desevento,
            "desobs" => $model->desobs,
            "idresponsavel" => $model->idresponsavel,
            "datinicio" => new Zend_Db_Expr("to_date('" . $model->datinicio->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datfim" => new Zend_Db_Expr("to_date('" . $model->datfim->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "uf" => $model->uf,
        );
        //var_dump($data); exit;
        try {
            $pks = array(
                "idevento" => $model->idevento,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function getForm()
    {
        return $this->_getForm(Evento_Form_Evento);
    }

    public function getById($params)
    {
        $sql = "SELECT 
                      e.nomevento,
                      e.uf,
                      to_char(e.datinicio, 'DD/MM/YYYY') as datinicio,
                      to_char(e.datfim, 'DD/MM/YYYY') as datfim,
                      (e.datfim - e.datinicio) as dias,
                      p.nompessoa as nomresponsavel,
                      e.idevento,
                      e.desevento,
                      e.desobs,
                      e.idresponsavel as idresponsavel,
                      p.idpessoa,
                      to_char(e.datcadastro, 'DD/MM/YYYY') as datcadastro
                 FROM 
                    agepnet200.tb_evento e,
                    agepnet200.tb_pessoa p
                 WHERE
                    e.idresponsavel = p.idpessoa
                    and e.idevento = :idevento";

        $resultado = $this->_db->fetchRow($sql, array('idevento' => $params['idevento']));
        $evento = new Evento_Model_Evento($resultado);
        return $evento;
    }

    public function fetchPairs()
    {
        $sql = "SELECT 
                      idevento,
                      nomevento
                 FROM 
                    agepnet200.tb_evento";

        return $this->_db->fetchPairs($sql);
    }

    public function pesquisar($params, $paginator = false)
    {
        $sql = "SELECT 
                      e.nomevento,
                      e.uf,
                      to_char(e.datinicio, 'DD/MM/YYYY') as datinicio,
                      to_char(e.datfim, 'DD/MM/YYYY') as datfim,
                      (e.datfim - e.datinicio) as dias,
                      p.nompessoa as nomresponsavel,
                      e.idevento,
                      e.desevento,
                      e.desobs,
                      e.idresponsavel,
                      e.datcadastro
                 FROM 
                    agepnet200.tb_evento e,
                    agepnet200.tb_pessoa p
                 WHERE
                    e.idresponsavel = p.idpessoa";


        $params = array_filter($params);
        if (isset($params['nomevento'])) {
            $nomevento = strtoupper($params['nomevento']);
            $sql .= " AND upper(e.nomevento) LIKE '%{$nomevento}%'";
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
        return $resultado;

    }

}

