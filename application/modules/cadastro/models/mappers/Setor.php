<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Cadastro_Model_Mapper_Setor extends App_Model_Mapper_MapperAbstract
{

    /**
     *
     * @var Cadastro_Model_Mapper_Setor
     */
    protected $_mapper;

    /**
     * Set the property
     *
     * @param string $value
     * @return Cadastro_Model_Setor
     */
    public function insert(Cadastro_Model_Setor $model)
    {
        try {
            $model->idsetor = $this->maxVal('idsetor');
            $data = array(
                "idsetor" => $model->idsetor,
                "nomsetor" => $model->nomsetor,
                "idcadastrador" => $model->idcadastrador,
                "datcadastro" => new Zend_Db_Expr("now()"),
                "flaativo" => $model->flaativo,
            );

            $retorno = $this->getDbTable()->insert($data);
            return $retorno;

        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Set the  property
     *
     * @param string $value
     * @return Cadastro_Model_Setor
     */
    public function update(Cadastro_Model_Setor $model)
    {
        $data = array(
            "nomsetor" => $model->nomsetor,
            "flaativo" => $model->flaativo
        );
        try {
            $pks = array(
                "idsetor" => $model->idsetor,
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
        return $this->_getForm(Cadastro_Form_Setor);
    }

    public function fetchPairs()
    {
        $sql = " SELECT idsetor, nomsetor FROM agepnet200.tb_setor order by nomsetor asc";
        return $this->_db->fetchPairs($sql);
    }

    public function pesquisar($params, $paginator = false)
    {
        $sql = "SELECT 
                      s.nomsetor,
                      s.flaativo,
                      to_char(s.datcadastro, 'DD/MM/YYYY') as datcadastro,
                      s.idsetor
                 FROM 
                    agepnet200.tb_setor s,
                    agepnet200.tb_pessoa p
                 WHERE
                    s.idcadastrador = p.idpessoa";


        $params = array_filter($params);
        if (isset($params['nomsetor'])) {
            $nomsetor = strtoupper($params['nomsetor']);
            $sql .= " AND upper(s.nomsetor) LIKE '%{$nomsetor}%'";
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

    public function getById($params)
    {
        $sql = "
                SELECT
                    idsetor,
                    nomsetor,
                    idcadastrador,
                    datcadastro,
                    flaativo
                FROM
                    agepnet200.tb_setor
                WHERE
                    idsetor = :idsetor";

        $resultado = $this->_db->fetchRow($sql, array('idsetor' => $params["idsetor"]));
        $setor = new Cadastro_Model_Setor($resultado);
        return $setor;
    }
}

