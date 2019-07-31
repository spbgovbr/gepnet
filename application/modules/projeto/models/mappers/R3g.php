<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_R3g extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_R3g
     */
    public function insert(Projeto_Model_R3g $model)
    {
        $model->idr3g = $this->maxVal('idr3g');

        $data = array(
            "idr3g" => $model->idr3g,
            "idprojeto" => $model->idprojeto,
//            "datdeteccao" => $model->datdeteccao,
            "datdeteccao" => new Zend_Db_Expr("to_date('" . $model->datdeteccao->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "domtipo" => $model->domtipo,
            "desplanejado" => $model->desplanejado,
            "desrealizado" => $model->desrealizado,
            "descausa" => $model->descausa,
            "desconsequencia" => $model->desconsequencia,
            "descontramedida" => $model->descontramedida,
//            "datprazocontramedida" => $model->datprazocontramedida,
            "datprazocontramedida" => isset($model->datprazocontramedida) ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedida->toString('Y-m-d') . "','YYYY-MM-DD')") : null,
//            "datprazocontramedidaatraso" => $model->datprazocontramedidaatraso,
            "datprazocontramedidaatraso" => isset($model->datprazocontramedidaatraso) ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedidaatraso->toString('Y-m-d') . "','YYYY-MM-DD')") : null,
            "domcorprazoprojeto" => $model->domcorprazoprojeto,
            "domstatuscontramedida" => $model->domstatuscontramedida,
            "flacontramedidaefetiva" => $model->flacontramedidaefetiva,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "desresponsavel" => $model->desresponsavel,
            "desobs" => $model->desobs,
        );

        $data = array_filter($data);
//        $this->getDbTable()->insert($data);
        try {
            $this->getDbTable()->insert($data);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_R3g
     */
    public function update(Projeto_Model_R3g $model)
    {
        $datdeteccao = !empty($model->datdeteccao) ? new Zend_Db_Expr("to_date('" . $model->datdeteccao->toString('Y-m-d') . "','YYYY-MM-DD')") : '';
        $datprazocontramedida = !empty($model->datprazocontramedida) ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedida->toString('Y-m-d') . "','YYYY-MM-DD')") : '';
        $datprazocontramedidaatraso = !empty($model->datprazocontramedidaatraso) ? new Zend_Db_Expr("to_date('" . $model->datprazocontramedidaatraso->toString('Y-m-d') . "','YYYY-MM-DD')") : '';

//        Zend_Debug::dump($model); exit;

        $data = array(
            "idr3g" => $model->idr3g,
//            "idprojeto" => $model->idprojeto,
            "datdeteccao" => $datdeteccao,
            "domtipo" => $model->domtipo,
            "desplanejado" => $model->desplanejado,
            "desrealizado" => $model->desrealizado,
            "descausa" => $model->descausa,
            "desconsequencia" => $model->desconsequencia,
            "descontramedida" => $model->descontramedida,
            "datprazocontramedida" => $datprazocontramedida,
            "datprazocontramedidaatraso" => $datprazocontramedidaatraso,
            "domcorprazoprojeto" => $model->domcorprazoprojeto,
            "domstatuscontramedida" => $model->domstatuscontramedida,
            "flacontramedidaefetiva" => $model->flacontramedidaefetiva,
//            "idcadastrador" => $model->idcadastrador,
//            "datcadastro" => $model->datcadastro,
            "desresponsavel" => $model->desresponsavel,
            "desobs" => $model->desobs,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));

        $data = array_filter($data);

//        Zend_Debug::dump($data); exit;

        try {
            $pks = array("idr3g" => $model->idr3g);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @return boolean
     */
    public function excluir($params)
    {
        $result = $this->_db->delete('agepnet200.tb_r3g', array('idr3g = ?' => (int)$params));
        return $result;
    }

    public function getForm()
    {
        return $this->_getForm(Projeto_Model_R3g);
    }

    public function getById($params)
    {
        $sql = "SELECT
                    r3g.idr3g,
                    r3g.idprojeto,
                    to_char(r3g.datdeteccao, 'DD/MM/YYYY') as datdeteccao,
                    r3g.domtipo,
                    r3g.desplanejado,
                    r3g.desrealizado,
                    r3g.descausa,
                    r3g.desconsequencia,
                    r3g.descontramedida,
                    to_char(r3g.datprazocontramedida, 'DD/MM/YYYY') as datprazocontramedida,
                    to_char(r3g.datprazocontramedidaatraso, 'DD/MM/YYYY') as datprazocontramedidaatraso,
                    r3g.domcorprazoprojeto,
                    r3g.domstatuscontramedida,
                    r3g.flacontramedidaefetiva,
                    r3g.idcadastrador,
                    to_char(r3g.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    r3g.desresponsavel,
                    r3g.desobs
                FROM
                    agepnet200.tb_r3g r3g
                WHERE
                    idr3g = :idr3g";
        $resultado = $this->_db->fetchRow($sql, array('idr3g' => $params['idr3g']));
        return new Projeto_Model_R3g($resultado);
    }

    public function retornaPorProjeto($params)
    {
        $sql = "SELECT
                    r3g.idr3g,
                    r3g.idprojeto,
                    to_char(r3g.datdeteccao, 'DD/MM/YYYY') as datdeteccao,
                    r3g.domtipo,
                    r3g.desplanejado,
                    r3g.desrealizado,
                    r3g.descausa,
                    r3g.desconsequencia,
                    r3g.descontramedida,
                    to_char(r3g.datprazocontramedida, 'DD/MM/YYYY') as datprazocontramedida,
                    to_char(r3g.datprazocontramedidaatraso, 'DD/MM/YYYY') as datprazocontramedidaatraso,
                    r3g.domcorprazoprojeto,
                    r3g.domstatuscontramedida,
                    r3g.flacontramedidaefetiva,
                    r3g.idcadastrador,
                    to_char(r3g.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    r3g.desresponsavel,
                    r3g.desobs
                FROM
                    agepnet200.tb_r3g r3g
                WHERE
                    idprojeto = :idprojeto";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return new Projeto_Model_R3g($resultado);
    }

    public function pesquisar($params, $paginator)
    {
        $sql = "SELECT
                    to_char(r3g.datdeteccao, 'DD/MM/YYYY') as datdeteccao,
                    r3g.domtipo,
                    r3g.desplanejado,
                    r3g.desrealizado,
                    to_char(r3g.datprazocontramedidaatraso, 'DD/MM/YYYY') as datprazocontramedidaatraso,
                    r3g.descontramedida,
                    to_char(r3g.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    r3g.desresponsavel,
                    r3g.flacontramedidaefetiva,
                    r3g.domstatuscontramedida,
                    r3g.descausa,
                    r3g.desconsequencia,
                    to_char(r3g.datprazocontramedida, 'DD/MM/YYYY') as datprazocontramedida,
                    r3g.domcorprazoprojeto,
                    r3g.idcadastrador,
                    r3g.desobs,
                    r3g.idprojeto,
                    r3g.idr3g
                FROM
                    agepnet200.tb_r3g r3g
                ORDER BY r3g.datdeteccao DESC
                ";

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 50;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
//        return new Default_Model_R3g($resultado);
    }

    public function getPaginatortById($params, $paginator)
    {
        $sql = "SELECT
                    to_char(r3g.datdeteccao, 'DD/MM/YYYY') as datdeteccao,
                    r3g.domtipo,
                    r3g.desplanejado,
                    r3g.desrealizado,
                    to_char(r3g.datprazocontramedidaatraso, 'DD/MM/YYYY') as datprazocontramedidaatraso,
                    r3g.descontramedida,
                    to_char(r3g.datcadastro, 'DD/MM/YYYY') as datcadastro,
                    r3g.desresponsavel,
                    r3g.flacontramedidaefetiva,
                    r3g.domstatuscontramedida,
                    r3g.descausa,
                    r3g.desconsequencia,
                    to_char(r3g.datprazocontramedida, 'DD/MM/YYYY') as datprazocontramedida,
                    r3g.domcorprazoprojeto,
                    r3g.idcadastrador,
                    r3g.desobs,
                    r3g.idprojeto,
                    r3g.idr3g
                FROM
                    agepnet200.tb_r3g r3g
                WHERE
                    r3g.idprojeto = " . $params['idprojeto'] . "
                ORDER BY r3g.datdeteccao DESC
                ";

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 50;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

//        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function retornaContramedida($params)
    {
        $sql = "SELECT
                    descontramedida
		        FROM
		            agepnet200.tb_r3g
		        WHERE
		            idprojeto = :idprojeto
		            AND  domstatuscontramedida!= 6 --cancelada
		            AND  domstatuscontramedida!= 3 --concluida";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return $resultado['descontramedida'];
    }

    public function retornaTodasContramedidas($params)
    {
        $sql = "SELECT
                    idr3g, idprojeto, datdeteccao, trim(desplanejado) desplanejado, trim(desrealizado) desrealizado,
                    trim(descausa) descausa,trim(desconsequencia) desconsequencia, trim(descontramedida) descontramedida,
                    datprazocontramedida, datprazocontramedidaatraso,
                    idcadastrador, datcadastro, desresponsavel, desobs, domtipo,
                    domcorprazoprojeto, domstatuscontramedida, flacontramedidaefetiva
		        FROM
		            agepnet200.tb_r3g
		        WHERE
		            idprojeto = :idprojeto
		            AND  domstatuscontramedida!= 6 --cancelada
		            AND  domstatuscontramedida!= 3 --concluida";
        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));
        return $resultado;
    }

    public function getProjeto($idR3g)
    {
        $sql = "SELECT
                    idprojeto
		        FROM
		            agepnet200.tb_r3g
		        WHERE
                    idr3g = $idR3g";
        $resultado = $this->_db->fetchAll($sql);
        return $resultado[0]['idprojeto'];
    }
}

