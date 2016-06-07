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
        $model->idescritorio  = $this->maxVal('idescritorio');
        $model->flaativo      = 'S';
        $data                 = array(
            "idescritorio"   => $model->idescritorio,
            "nomescritorio"  => $model->nomescritorio,
            "idcadastrador"  => $model->idcadastrador,
            "datcadastro"    => new Zend_Db_Expr('now()'),
            "flaativo"       => $model->flaativo,
            "idresponsavel1" => $model->idresponsavel1,
            "idresponsavel2" => $model->idresponsavel2,
            "idescritoriope" => $model->idescritoriope,
            "nomescritorio2" => $model->nomescritorio2,
            "desemail"       => $model->desemail,
            "numfone"        => $model->numfone,
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
            "nomescritorio"  => $model->nomescritorio,
            "flaativo"       => $model->flaativo,
            "idresponsavel1" => $model->idresponsavel1,
            "idresponsavel2" => $model->idresponsavel2,
            "idescritoriope" => $model->idescritoriope,
            "nomescritorio2" => $model->nomescritorio2,
            "desemail"       => $model->desemail,
            "numfone"        => $model->numfone,
        );

        $pks   = array("idescritorio" => $model->idescritorio);
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
        // 'Sigla','Nome','Responsavel1','Responsavel2','Mapa','Situa��o'

        $sql    = "SELECT
	e.nomescritorio as sigla,
	e.nomescritorio2 as nome,
	resp1.nompessoa as responsavel1,
	resp2.nompessoa as responsavel2,
	(select nomescritorio from agepnet200.tb_escritorio where idescritorio = e.idescritoriope) as mapa,
	e.flaativo as situacao,
        'logo.jpg' as logo,
        e.idescritorio as id
FROM agepnet200.tb_escritorio e
	left JOIN agepnet200.tb_pessoa resp1
	ON e.idresponsavel1 = resp1.idpessoa
	left JOIN agepnet200.tb_pessoa resp2
	ON e.idresponsavel2 = resp2.idpessoa
        		            where 1 = 1";
        $params = array_filter($params);

        if ( isset($params['nomescritorio']) ) {
            $nomescritorio = strtoupper($params['nomescritorio']);
            $sql.= " and e.nomescritorio2 LIKE '%{$nomescritorio}%'";
        }
        
        if ( isset($params['sidx']) ) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

        // Zend_Debug::dump($sql);exit;

        if ( $paginator ) {
            $page      = (isset($params['page'])) ? $params['page'] : 1;
            $limit     = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($id);exit;
        return $resultado;
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Escritorio);
    }

    public function fetchPairs()
    {
        $sql = "  SELECT idescritorio, nomescritorio FROM agepnet200.tb_escritorio where flaativo = 'S' order by nomescritorio asc";


        return $this->_db->fetchPairs($sql);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "SELECT
                    e.idescritoriope,
     		        e.idresponsavel1,
     		        e.idresponsavel2,
                    e.nomescritorio as sigla,
                    e.nomescritorio2 as nome,
                    resp1.nompessoa as nomresponsavel1,
                    resp2.nompessoa as nomresponsavel2,
                    (   
                        select nomescritorio from agepnet200.tb_escritorio where idescritorio = e.idescritoriope
                    ) as mapa,
                        e.flaativo,
                        'logo.jpg' as logo,
                        e.idescritorio as id,
                        e.desemail,
                        e.numfone
                FROM agepnet200.tb_escritorio e
                left JOIN agepnet200.tb_pessoa resp1
                ON e.idresponsavel1 = resp1.idpessoa
                left JOIN agepnet200.tb_pessoa resp2
                ON e.idresponsavel2 = resp2.idpessoa
                where e.idescritorio = :idescritorio";
        
      

        $resultado = $this->_db->fetchRow($sql, array('idescritorio' => $params['idescritorio']));



        $escritorio               = new Default_Model_Escritorio($resultado);
        $pessoa1                  = new Default_Model_Pessoa(array(
            'nompessoa' => $resultado['nomresponsavel1'],
        ));
        $pessoa2                  = new Default_Model_Pessoa(array(
            'nompessoa' => $resultado['nomresponsavel2'],
        ));
        $escritorio->responsavel1 = $pessoa1;
        $escritorio->responsavel2 = $pessoa2;

        $escritorio->idescritorio = intval($params['idescritorio']);

        //Zend_Debug::dump($escritorio); exit;

        return $escritorio;
    }
    
      public function getProjetosPorEscritorio($idescritorio){
        
        $sql = " SELECT
                       (SELECT nomprograma from agepnet200.tb_programa prog WHERE prog.idprograma = proj.idprograma) as nomprograma,
                        proj.nomprojeto
                FROM
                        agepnet200.tb_projeto proj
                WHERE 
                        proj.idescritorio = " .$idescritorio;
        
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
					e.nomescritorio as sigla,
					e.nomescritorio2 as nome,
					resp1.nompessoa as responsavel1,
					resp2.nompessoa as responsavel2,
					(select nomescritorio from agepnet200.tb_escritorio where idescritorio = e.idescritoriope) as mapa,
					e.flaativo,
				        'logo.jpg' as logo,
				        e.idescritorio as id
				FROM agepnet200.tb_escritorio e
					left JOIN agepnet200.tb_pessoa resp1
					ON e.idresponsavel1 = resp1.idpessoa
					left JOIN agepnet200.tb_pessoa resp2
					ON e.idresponsavel2 = resp2.idpessoa
				    where e.nomescritorio2 = :nomescritorio2";

        $resultado = $this->_db->fetchAll($sql, array('nomescritorio2' => $params['nomescritorio2']));
        return $resultado;
    }

    public function mapaFetchPairs()
    {
        $sql = "SELECT DISTINCT idescritorio,nomescritorio from agepnet200.tb_escritorio where flaativo = 'S' ";
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

    /*
      public function retornarEscritoriosPorPessoa($params)
      {
      $sql = "select distinct(ppe.idescritorio), esc.nomescritorio
      from
      agepnet200.tb_perfilpessoa ppe,
      agepnet200.tb_escritorio esc
      where
      esc.idescritorio = ppe.idescritorio
      and ppe.idpessoa = :idpessoa";

      return $this->_db->fetchPairs($sql, array('idpessoa' => $params['idpessoa']));
      }
     */
    /*
      public function retornaEscritorioPorPerfilEPessoa($params)
      {
      $sql = "select
      per.nomperfil, esc.nomescritorio, per.idperfil, esc.idescritorio
      from
      agepnet200.tb_perfilpessoa ppe,
      agepnet200.tb_perfil per,
      agepnet200.tb_escritorio esc
      where ppe.idperfil = per.idperfil
      and esc.idescritorio = ppe.idescritorio
      and ppe.idpessoa = :idpessoa
      and per.idperfil = :idperfil
      order by per.nomperfil";

      return $this->_db->fetchAll($sql, array(
      'idpessoa' => $params['idpessoa'],
      'idperfil' => $params['idperfil']
      ));
      }
     * 
     */
}

