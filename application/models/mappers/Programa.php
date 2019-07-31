<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Programa extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Programa
     */
    public function insert(Default_Model_Programa $model)
    {


        $model->idcadastrador = 1;
        $model->idprograma = $this->maxVal('idprograma');
        $model->flaativo = 'S';
        $data = array(
            "idprograma" => $model->idprograma,
            "nomprograma" => $model->nomprograma,
            "desprograma" => $model->desprograma,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "flaativo" => $model->flaativo,
            "idresponsavel" => $model->idresponsavel,
            "idsimpr" => $model->idsimpr,
            "idsimpreixo" => $model->idsimpreixo,
            "idsimprareatematica" => $model->idsimprareatematica

        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Programa
     */
    public function update(Default_Model_Programa $model)
    {
        $data = array(
            // "idprograma"          => $model->idprograma,
            "nomprograma" => $model->nomprograma,
            "desprograma" => $model->desprograma,
            "idcadastrador" => $model->idcadastrador,
            //"datcadastro"         => $model->datcadastro,
            "flaativo" => $model->flaativo,
            "idresponsavel" => $model->idresponsavel,
            "idsimpr" => $model->idsimpr,
            "idsimpreixo" => $model->idsimpreixo,
            "idsimprareatematica" => $model->idsimprareatematica
        );
        $pkey = array(
            "idprograma" => $model->idprograma,
        );

        $where = $this->_generateRestrictionsFromPrimaryKeys($pkey);

        $data = array_filter($data);
        $retorno = $this->getDbTable()->update($data, $where);
        return $retorno;
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Programa);
    }

    public function retornaPorId($params)
    {
        $resultado = $this->getById($params);
        $programa = new Default_Model_Programa($resultado);

        $pessoa = new Default_Model_Pessoa(array(
            'idpessoa' => $resultado['idresponsavel'],
            'nompessoa' => $resultado['nomresponsavel'],
        ));

        $programa->responsavel = $pessoa;


        $programa->idprograma = intval($params['idprograma']);

        //Zend_Debug::dump($programa); exit;

        return $programa;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "SELECT
                        e.idprograma,
    			e.idcadastrador,
                        e.nomprograma,
                        e.desprograma,
                        resp1.nompessoa as nomresponsavel,
                        resp1.idpessoa as idresponsavel,
                        e.flaativo ,
                        e.idsimpr,
                        e.idsimpreixo ,
                        e.idsimprareatematica ,
                        e.idprograma as id,
                        port.idportfolio,
                        port.noportfolio,
                        CASE
                          WHEN port.tipo = 1 THEN 'NORMAL'
                          WHEN port.tipo = 2 THEN 'ESTRATÉGICO'
                        END as tipo
                FROM agepnet200.tb_programa e
                    left JOIN agepnet200.tb_pessoa resp1
                    ON e.idresponsavel = resp1.idpessoa
                    left join agepnet200.tb_portifolioprograma portp
                    on portp.idprograma = e.idprograma
                    left join agepnet200.tb_portfolio port
                    on port.idportfolio = portp.idportfolio
		where e.idprograma = :idprograma";

        return $this->_db->fetchRow($sql, array('idprograma' => $params['idprograma']));
    }


    /**
     * @param array $params
     * @return array
     */
    public function getByName($params)
    {
        $sql = " SELECT
                        e.idprograma,
                        e.idcadastrador,
                        e.nomprograma,
                        e.desprograma,
                        resp1.nompessoa,
                        resp1.idpessoa as idresponsavel,
                        e.flaativo ,
                            e.idsimpr,
                            e.idsimpreixo ,
                            e.idsimprareatematica ,
                            e.idprograma as id
                    FROM agepnet200.tb_programa e
                        left JOIN agepnet200.tb_pessoa resp1
                        ON e.idresponsavel = resp1.idpessoa
				    where e.nomprograma = :nomprograma";

        $resultado = $this->_db->fetchAll($sql, array('nomprograma' => $params['nomprograma']));
        return $resultado;
    }


    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $paginator = false)
    {
        // 'Nome','Descrição','Responsavel','Situacao','Logo','Id'

        $sql = "

SELECT
	e.nomprograma as nome,
	e.desprograma as descricao,
	resp1.nompessoa as responsavel,
	e.flaativo as situacao,
    	e.idsimpr  as simprarea,
    	e.idsimpreixo as simpreixo,
    	e.idsimprareatematica as simprareatematica,						
        e.idprograma as id
FROM agepnet200.tb_programa e
	left JOIN agepnet200.tb_pessoa resp1
	ON e.idresponsavel = resp1.idpessoa
        		        where 1 = 1";
        $params = array_filter($params);

        if (isset($params['nomprograma'])) {
            $nomprograma = strtoupper($params['nomprograma']);
            $sql .= " and e.nomprograma LIKE '%{$nomprograma}%'";
        }

        // Zend_Debug::dump($sql);exit;

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
        //Zend_Debug::dump($id);exit;
        return $resultado;
    }


    public function fetchPairs()
    {
        $sql = "SELECT idprograma, nomprograma FROM agepnet200.tb_programa
                                   where flaativo = 'S' order by nomprograma asc";
        return $this->_db->fetchPairs($sql);
    }
}

