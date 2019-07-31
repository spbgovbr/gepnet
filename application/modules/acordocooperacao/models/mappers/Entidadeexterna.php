<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Acordocooperacao_Model_Mapper_Entidadeexterna extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Acordocooperacao_Model_Entidadeexterna
     */
    public function insert(Acordocooperacao_Model_Entidadeexterna $model)
    {
        try {
            $model->identidadeexterna = $this->maxVal('identidadeexterna');
            $data = array(
                "identidadeexterna" => $model->identidadeexterna,
                "nomentidadeexterna" => $model->nomentidadeexterna,
                "idcadastrador" => $model->idcadastrador,
                "datcadastro" => new Zend_Db_Expr("now()")
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
     * @return Acordocooperacao_Model_Entidadeexterna
     */
    public function update(Acordocooperacao_Model_Entidadeexterna $model)
    {
        $data = array(
            "nomentidadeexterna" => $model->nomentidadeexterna,
        );
        try {
            $pks = array(
                "identidadeexterna" => $model->identidadeexterna,
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
        return $this->_getForm(Acordocooperacao_Form_Entidadeexterna);
    }

    public function getById($params)
    {
        $sql = "SELECT 
                      e.nomentidadeexterna,
                      p.nompessoa,
                      to_char(e.datcadastro, 'DD/MM/YYYY') as datcadastro,
                      e.identidadeexterna
                 FROM 
                    agepnet200.tb_entidadeexterna e,
                    agepnet200.tb_pessoa p
                 WHERE
                    e.idcadastrador = p.idpessoa
                    and e.identidadeexterna = :identidadeexterna";

        $resultado = $this->_db->fetchRow($sql, array('identidadeexterna' => $params['identidadeexterna']));
        $entidadeexterna = new Acordocooperacao_Model_Entidadeexterna($resultado);
        return $entidadeexterna;
    }

    public function pesquisar($params, $paginator = false)
    {
        $sql = "SELECT 
                      e.nomentidadeexterna,
                      p.nompessoa,
                      to_char(e.datcadastro, 'DD/MM/YYYY') as datcadastro,
                      e.identidadeexterna
                 FROM 
                    agepnet200.tb_entidadeexterna e,
                    agepnet200.tb_pessoa p
                 WHERE
                    e.idcadastrador = p.idpessoa";


        $params = array_filter($params);
        if (isset($params['nomentidadeexterna'])) {
            $nomentidadeexterna = strtoupper($params['nomentidadeexterna']);
            $sql .= " AND upper(e.nomentidadeexterna) LIKE '%{$nomentidadeexterna}%'";
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

    public function fetchPairs()
    {
        $sql = " SELECT identidadeexterna, nomentidadeexterna FROM agepnet200.tb_entidadeexterna order by nomentidadeexterna asc";
        return $this->_db->fetchPairs($sql);
    }

    public function retornaEntidadesExternas($params)
    {
        $sql = "
                SELECT
                    ee.identidadeexterna,
                    ee.nomentidadeexterna
                FROM
                    agepnet200.tb_entidadeexterna ee,
                    agepnet200.tb_acordoentidadeexterna ae
                WHERE
                    ee.identidadeexterna = ae.identidadeexterna
                    and ae.idacordo = :idacordo;
        ";

        $resultado = $this->_db->fetchAll($sql, array('idacordo' => $params['idacordo']));

//        $entidadeexterna = new Acordocooperacao_Model_Entidadeexterna($resultado);
//        Zend_Debug::dump($entidadeexterna); exit;
        return $resultado;
    }
}

