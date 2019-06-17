<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Escritorio extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Escritorio
     */
    public function insert(Default_Model_Escritorio $model)
    {
        /**
         * @todo remover o idcadastrador abaixo
         */
        $model->idcadastrador = 1;
        $model->idescritorio = $this->maxVal('idescritorio');
        $model->flaativo = 'S';
        $data = array(
            "idescritorio" => $model->idescritorio,
            "nomescritorio" => $model->nomescritorio,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "flaativo" => $model->flaativo,
            "idresponsavel1" => $model->idresponsavel1,
            "idresponsavel2" => $model->idresponsavel2,
            "idescritoriope" => $model->idescritoriope,
            "nomescritorio2" => $model->nomescritorio2,
            "desemail" => $model->desemail,
            "numfone" => $model->numfone,
        );

        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Escritorio
     */
    public function update(Default_Model_Escritorio $model)
    {
        $data = array(
            "nomescritorio" => $model->nomescritorio,
            "flaativo" => $model->flaativo,
            "idresponsavel1" => $model->idresponsavel1,
            "idresponsavel2" => $model->idresponsavel2,
            "idescritoriope" => $model->idescritoriope,
            "nomescritorio2" => $model->nomescritorio2,
            "desemail" => $model->desemail,
            "numfone" => $model->numfone,
        );

        $pks = array("idescritorio" => $model->idescritorio);
        $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
        return $this->getDbTable()->update($data, $where);
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $paginator = false)
    {
        // 'Sigla','Nome','Responsavel1','Responsavel2','Mapa','Situação'

        $sql = "SELECT  e.nomescritorio AS sigla, 
                        e.nomescritorio2 AS nome,
                        resp1.nompessoa AS responsavel1,
                        resp2.nompessoa AS responsavel2,
                        (SELECT nomescritorio 
                           FROM agepnet200.tb_escritorio 
                          WHERE idescritorio = e.idescritoriope) AS mapa,
                        e.flaativo AS situacao,
                        'logo.jpg' AS logo,
                        e.idescritorio AS id
                   FROM agepnet200.tb_escritorio e
                   LEFT JOIN agepnet200.tb_pessoa resp1
                     ON e.idresponsavel1 = resp1.idpessoa
                   LEFT JOIN agepnet200.tb_pessoa resp2
                     ON e.idresponsavel2 = resp2.idpessoa
        		  WHERE 1 = 1";
        $params = array_filter($params);

        if (isset($params['nomescritorio'])) {
            $nomescritorio = strtoupper($params['nomescritorio']);
            $sql .= " AND e.nomescritorio2 LIKE '%{$nomescritorio}%'";
        }

        if (isset($params['sidx'])) {
            $sql .= " ORDER BY " . $params['sidx'] . " " . $params['sord'];
        }

        // Zend_Debug::dump($sql);exit;

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

    public function getForm()
    {
        return $this->_getForm(Default_Form_Escritorio);
    }

    public function fetchPairs()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $auth = $login->retornaUsuarioLogado();
        $nomeperfilACL = $auth->perfilAtivo->nomeperfilACL;
        if ($nomeperfilACL == 'admin_gepnet') {
            $sql = "  SELECT idescritorio, nomescritorio 
                      FROM agepnet200.tb_escritorio 
                      WHERE flaativo = 'S' ORDER BY nomescritorio ASC";
        } else {
            $sql = "SELECT idescritorio, nomescritorio 
                    FROM agepnet200.tb_escritorio 
                    WHERE idescritorio NOT IN({$auth->escritorioAtivo}) 
                    AND flaativo = 'S' ORDER BY nomescritorio ASC";
        }

        return $this->_db->fetchPairs($sql);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "SELECT  e.idescritoriope,
                        e.idresponsavel1,
                        e.idresponsavel2,
                        e.nomescritorio AS sigla,
                        e.nomescritorio2 AS nome,
                        resp1.nompessoa AS nomresponsavel1,
                        resp2.nompessoa AS nomresponsavel2,
                        (SELECT nomescritorio 
                           FROM agepnet200.tb_escritorio 
                          WHERE idescritorio = e.idescritoriope
                        ) AS mapa,
                        e.flaativo,
                        'logo.jpg' AS logo,
                        e.idescritorio AS id,
                        e.desemail,
                        e.numfone
                   FROM agepnet200.tb_escritorio e
                   LEFT JOIN agepnet200.tb_pessoa resp1
                     ON e.idresponsavel1 = resp1.idpessoa
                   LEFT JOIN agepnet200.tb_pessoa resp2
                     ON e.idresponsavel2 = resp2.idpessoa
                  WHERE e.idescritorio = :idescritorio";


        $resultado = $this->_db->fetchRow($sql, array('idescritorio' => $params['idescritorio']));


        $escritorio = new Default_Model_Escritorio($resultado);
        $pessoa1 = new Default_Model_Pessoa(array(
            'nompessoa' => $resultado['nomresponsavel1'],
        ));
        $pessoa2 = new Default_Model_Pessoa(array(
            'nompessoa' => $resultado['nomresponsavel2'],
        ));
        $escritorio->responsavel1 = $pessoa1;
        $escritorio->responsavel2 = $pessoa2;

        $escritorio->idescritorio = intval($params['idescritorio']);

        //Zend_Debug::dump($escritorio); exit;

        return $escritorio;
    }

    public function getProjetosPorEscritorio($idescritorio)
    {

        $sql = " SELECT
                       (SELECT nomprograma from agepnet200.tb_programa prog WHERE prog.idprograma = proj.idprograma) AS nomprograma,
                        proj.nomprojeto
                FROM
                        agepnet200.tb_projeto proj
                WHERE 
                        proj.idescritorio = " . $idescritorio;

        //Zend_Debug::dump($sql); die;
        $retorno = $this->_db->fetchAll($sql);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getByName($params)
    {
        $sql = "
				SELECT
     		        e.idescritoriope,
     		        e.idresponsavel1,
     		        e.idresponsavel2,
					e.nomescritorio AS sigla,
					e.nomescritorio2 AS nome,
					resp1.nompessoa AS responsavel1,
					resp2.nompessoa AS responsavel2,
					(select nomescritorio from agepnet200.tb_escritorio where idescritorio = e.idescritoriope) AS mapa,
					e.flaativo,
				        'logo.jpg' AS logo,
				        e.idescritorio AS id
				FROM agepnet200.tb_escritorio e
					left JOIN agepnet200.tb_pessoa resp1
					ON e.idresponsavel1 = resp1.idpessoa
					left JOIN agepnet200.tb_pessoa resp2
					ON e.idresponsavel2 = resp2.idpessoa
				    where e.nomescritorio2 = :nomescritorio2 ";

        if (@trim($params['nomescritorio']) != "") {
            $sql = $sql . " or e.nomescritorio = :nomescritorio ";
        }

        if (@trim($params['nomescritorio']) != "") {
            $resultado = $this->_db->fetchAll($sql,
                array(
                    'nomescritorio2' => $params['nomescritorio2'],
                    'nomescritorio' => $params['nomescritorio']
                )
            );
        } else {
            $resultado = $this->_db->fetchAll($sql,
                array(
                    'nomescritorio2' => $params['nomescritorio2']
                )
            );
        }
        return $resultado;
    }

    public function mapaFetchPairs()
    {
        $sql = "SELECT DISTINCT idescritorio,nomescritorio from agepnet200.tb_escritorio where flaativo = 'S' order by nomescritorio asc ";
        return $this->_db->fetchPairs($sql);
    }

    public function selecionarPorIdObjetivo($idobjeitivo)
    {
        $sql = "SELECT * from agepnet200.tb_escritorio";
        return $this->_db->fetchAll($sql);
    }

    public function selecionarTodoEscritorio()
    {
        $sql = "SELECT * from agepnet200.tb_escritorio where flaativo = 'S' order by nomescritorio asc";
        return $this->_db->fetchAll($sql);
    }

    public function getfetchPairsEscritorio($idEscritorio)
    {


        $sql = "select idescritorio, nomescritorio "
            . "from agepnet200.tb_escritorio "
            . "WHERE idescritorio = $idEscritorio ";


        $retorno = $this->_db->fetchPairs($sql);

        return $retorno;
    }


    public function getEscritorioAndSubordinado($idEscritorioPai = null)
    {

        if ($idEscritorioPai != null) {
            $sql = "SELECT e.idescritorio,e.nomescritorio FROM connectby ('agepnet200.tb_escritorio', 'idescritorio', 'idescritoriope', '" . $idEscritorioPai . "', 0, '->') ";
        } else {
            $sql = "SELECT e.idescritorio,e.nomescritorio FROM connectby ('agepnet200.tb_escritorio', 'idescritorio', 'idescritoriope', '0', 0, '->') ";
        }
        $sql .= "AS t (idescritorio integer, idescritoriope integer, level int, branch text) ";
        $sql .= "INNER JOIN agepnet200.tb_escritorio e on e.idescritorio=t.idescritorio order by e.nomescritorio";

        $retorno = $this->_db->fetchPairs($sql);

        return $retorno;
    }
}

