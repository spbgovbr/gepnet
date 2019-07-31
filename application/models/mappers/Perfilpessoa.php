<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Perfilpessoa extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Perfilpessoa
     */
    public function insert(Default_Model_Perfilpessoa $model)
    {
        $data = array(
            "idperfilpessoa" => $model->idperfilpessoa,
            "idperfil" => $model->idperfil,
            "idescritorio" => $model->idescritorio,
            "flaativo" => $model->flaativo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Perfilpessoa
     */
    public function update(Default_Model_Perfilpessoa $model)
    {
        $data = array(
            "idperfilpessoa" => $model->idperfilpessoa,
            "idperfil" => $model->idperfil,
            "idescritorio" => $model->idescritorio,
            "flaativo" => $model->flaativo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Perfilpessoa);
    }

    public function permissaoStatusReport($params)
    {

        $sql = "SELECT count(perm.idpermissao) as total
                FROM agepnet200.tb_permissao perm
                INNER JOIN agepnet200.tb_recurso r on r.idrecurso=perm.idrecurso and r.ds_recurso in(:controller) 
                WHERE perm.no_permissao in(:action) and tipo in('G') ";

        $resultado = $this->_db->fetchRow($sql,
            array('controller' => $params['controller'], 'action' => $params['action']));
        return ((int)$resultado['total'] > 0 ? true : false);
    }

    public function permitirAction($params)
    {
        $sql = "SELECT count(pp.idperfil) as total 
                FROM agepnet200.tb_permissaoperfil pp 
                INNER JOIN agepnet200.tb_permissao perm on perm.idpermissao=pp.idpermissao and perm.no_permissao in(:action)
                INNER JOIN agepnet200.tb_recurso r on r.idrecurso=perm.idrecurso and r.ds_recurso in(:controller) 
                WHERE pp.idperfil=7";

        $resultado = $this->_db->fetchRow($sql,
            array('controller' => $params['controller'], 'action' => $params['action']));
        return ((int)$resultado['total'] > 0 ? true : false);
    }


    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $idperfil, $idescritorio, $paginator = false)
    {
        $sql = "
                    SELECT 	
                                    pess.nompessoa as nompessoa,
                                    perf.nomperfil as nomperfil,
                                    esc.nomescritorio as nomescritorio,
                                    pess.desemail as desemail,
                                    CASE WHEN perfp.flaativo = 'S' THEN 'Ativo'	
                                         WHEN perfp.flaativo = 'N' THEN 'Inativo'
                                    END as situacao,
                                    perfp.idperfilpessoa,
                                    perfp.flaativo
                            FROM 
                                    agepnet200.tb_perfilpessoa perfp,
                                    agepnet200.tb_perfil perf,
                                    agepnet200.tb_escritorio esc,
                                    agepnet200.tb_pessoa pess
                            WHERE 	
                                    pess.idpessoa = perfp.idpessoa
                                    and perf.idperfil = perfp.idperfil
                                    and esc.idescritorio = perfp.idescritorio
                                    ";

        if (isset($idperfil) && $idperfil <> 1) {
            $sql .= "and perfp.idescritorio = " . $idescritorio . "";
        }
        $params = array_filter($params);
        if (isset($params['nompessoa'])) {
            $nompessoa = strtoupper($params['nompessoa']);
            $sql .= " AND upper(pess.nompessoa) LIKE '%{$nompessoa}%'";
        }
        if (isset($params['idescritorio'])) {
            $sql .= " AND perfp.idescritorio = {$params['idescritorio']}";
        }
        if (isset($params['idperfil'])) {
            $sql .= " AND perfp.idperfil = {$params['idperfil']}";
        }
        if (isset($params['flaativo'])) {
            $sql .= " AND perfp.flaativo = '{$params['flaativo']}' ";
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

    public function associarPerfil(Default_Model_Perfilpessoa $model)
    {
        try {
            $model->idperfilpessoa = $this->maxVal('idperfilpessoa');
            $data = array(
                "idperfilpessoa" => (int)$model->idperfilpessoa,
                "idperfil" => (int)$model->idperfil,
                "idescritorio" => (int)$model->idescritorio,
                "flaativo" => 'S',
                "idcadastrador" => (int)$model->idcadastrador,
                "datcadastro" => new Zend_Db_Expr("now()"),
                "idpessoa" => (int)$model->idpessoa,
            );
            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function updateSituacao(Default_Model_Perfilpessoa $model)
    {
        $data = array(
            "idperfilpessoa" => $model->idperfilpessoa,
            "flaativo" => $model->flaativo
        );

        try {
            $pks = array(
                "idperfilpessoa" => $model->idperfilpessoa,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

}

