<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 20-11-2018 10:07
 *
 */
class Diagnostico_Model_Mapper_Permissaodiagnostico extends App_Model_Mapper_MapperAbstract
{

    protected $_dependencies = array('log');

    /**
     *
     * @var Zend_Log
     */
    protected $_log;

    /**
     * Set the property
     *
     * @param $params array
     * @return Diagnostico_Model_Permissaodiagnostico
     */
    public function insert($params)
    {
        $data = array(
            "idpermissao" => $params['idpermissao'],
            "iddiagnostico" => $params['iddiagnostico'],
            "idpartediagnostico" => $params['idpartediagnostico'],
            "idpessoa" => $params['idpessoa'],
            "idrecurso" => $params['idrecurso'],
            "data" => new Zend_Db_Expr('now()'),
            "ativo" => 'S'
        );
        try {
            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
        } catch (Exception $exc) {
            Zend_Debug::dump($exc);
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Diagnostico_Model_Permissaodiagnostico
     */
    public function update($params)
    {
        $data = array(
            "ativo" => ($params['stpermissao'] == 'S' ? "N" : 'S')
        );
        try {
            return $this->getDbTable()->update($data, array(
                    "idpermissao        = ?" => $params['idpermissao'],
                    "iddiagnostico      = ?" => $params['iddiagnostico'],
                    "idpartediagnostico = ?" => $params['idpartediagnostico']
                )
            );
        } catch (Exception $exc) {
            Zend_Debug::dump($exc);
            throw $exc;
        }
    }

    public function updatePermissaoParteInteressada($params)
    {
        $data = array(
            "idpartediagnostico" => ($params['idpartediagnosticoNova'])
        );
        try {
            return $this->getDbTable()->update($data, array(
                    "iddiagnostico      = ?" => (int)$params['iddiagnostico'],
                    "idpartediagnostico = ?" => (int)$params['idpartediagnosticoDuplicada'],
                    "idpermissao        = ?" => (int)$params['idpermissao']
                )
            );
        } catch (Exception $exc) {
            Zend_Debug::dump($exc);
            throw $exc;
        }
    }


    public function excluir($params)
    {
        try {
            $sql = "DELETE FROM agepnet200.tb_permissaodiagnostico
                    WHERE iddiagnostico = :iddiagnostico
                    AND idpartediagnostico = :idpartediagnostico ";
            $resultado = $this->_db->fetchAll($sql, array(
                'iddiagnostico' => $params['iddiagnostico'],
                'idpartediagnostico' => $params['idpartediagnostico']
            ));
            return $resultado;
        } catch (Exception $exc) {
            Zend_Debug::dump($exc->getTrace());
            throw $exc;
        }
    }

    public function excluirPermissaoByParteInteressada($params)
    {
        try {
            $sql = "DELETE FROM agepnet200.tb_permissaodiagnostico
                    WHERE iddiagnostico = :iddiagnostico
                    AND idpartediagnostico = :idpartediagnostico
                    AND idpermissao = :idpermissao";

            $resultado = $this->_db->fetchAll($sql, array(
                'iddiagnostico' => $params['iddiagnostico'],
                'idpartediagnostico' => $params['idpartediagnostico'],
                'idpermissao' => $params['idpermissao']
            ));

            return $resultado;

        } catch (Exception $exc) {
            Zend_Debug::dump($exc);
            throw $exc;
        }
    }


    /**
     * Set the property
     * @return array
     */
    public function fetchPairs()
    {
        $sql = "SELECT c.idpartediagnostico, c.iddiagnostico,
                c.idrecurso, c.idpermissao, c.idpessoa, c.data, c.ativo
       FROM agepnet200.tb_permissaodiagnostico c ";

        return $this->_db->fetchPairs($sql);
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "SELECT c.idpartediagnostico, c.iddiagnostico, c.idrecurso,
                c.idpermissao, c.idpessoa,c.data, c.ativo
                FROM agepnet200.tb_permissaodiagnostico c
                where
                    c.idpermissao         = :idpermissao and
                    c.iddiagnostico       = :iddiagnostico   and
                    c.idpartediagnostico  = :idpartediagnostico and
                    c.idpessoa            = :idpessoa
        ";
        $resultado = $this->_db->fetchAll($sql, array(
            'idpermissao' => $params['idpermissao'],
            'iddiagnostico' => $params['iddiagnostico'],
            'idpartediagnostico' => $params['idpartediagnostico'],
            'idpessoa' => $params['idpessoa'],
        ));

        return $resultado;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getByIdParteInteressada($params)
    {
        $sql = "SELECT c.idpartediagnostico, c.iddiagnostico, c.idrecurso,
                c.idpermissao, c.idpessoa,c.data, c.ativo
                FROM agepnet200.tb_permissaodiagnostico c
                where
                    c.iddiagnostico       = :iddiagnostico   and
                    c.idpartediagnostico  = :idpartediagnostico
        ";
        $resultado = $this->_db->fetchAll($sql, array(
            'iddiagnostico' => $params['iddiagnostico'],
            'idpartediagnostico' => $params['idpartediagnostico']
        ));

        return $resultado;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getByIdParteInteressadaByPermissao($params)
    {
        $sql = "SELECT c.idpartediagnostico, c.iddiagnostico, c.idrecurso,
                c.idpermissao, c.idpessoa,c.data, c.ativo
                FROM agepnet200.tb_permissaodiagnostico c
                where
                    c.iddiagnostico       = :iddiagnostico   and
                    c.idpartediagnostico  = :idpartediagnostico and
                    c.idpermissao         = :idpermissao
        ";
        $resultado = $this->_db->fetchAll($sql, array(
            'iddiagnostico' => $params['iddiagnostico'],
            'idpartediagnostico' => $params['idpartediagnostico'],
            'idpermissao' => $params['idpermissao'],

        ));

        return $resultado;
    }


    /**
     *
     * @param array $params
     * @return boolean
     */
    public function getPermissaoAcaoDiagnosticoPorParte($params)
    {
        $controller = "diagnostico:" . $params["controller"];
        $action = $params["action"];

        $sql = "SELECT count(c.idpermissao)
                FROM agepnet200.tb_permissaodiagnostico c
                INNER JOIN agepnet200.tb_permissao p
                        on   p.idpermissao = c.idpermissao
                        and  p.idrecurso   = c.idrecurso
                        and  p.no_permissao in(trim('{$action}'))
                INNER JOIN agepnet200.tb_recurso r
                        on p.idrecurso=r.idrecurso
                        and r.ds_recurso in(trim('{$controller}'))
                WHERE c.ativo IN('S') ";


        if (!empty($params['iddiagnostico'])) {
            $sql .= "AND c.iddiagnostico in({$params['iddiagnostico']}) ";
        }

        if (!empty($params['idpartediagnostico'])) {
            $sql .= "AND c.idpartediagnostico  in({$params['idpartediagnostico']}) ";
        }

        $resultado = $this->_db->fetchOne($sql);

        return (($resultado > 0) ? true : false);
    }

    /**
     * @param array $params
     * @return array
     */

    public function verificaPermissaoByParteInteressadaAndDiagnostico($params)
    {
        $sql = "SELECT count(c.idpermissao)
                FROM agepnet200.tb_permissaodiagnostico c
                where
                    c.idpartediagnostico  = :idpartediagnostico
                    and c.iddiagnostico  = :iddiagnostico ";
        $resultado = $this->_db->fetchRow($sql, array(
            'idpartediagnostico' => $params['idpartediagnostico'],
            'iddiagnostico' => $params['iddiagnostico'],
        ));
        //Zend_Debug::dump($resultado['count']);exit;
        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getPermissaoPorParte($params)
    {
        $sql = "SELECT c.idpartediagnostico, c.iddiagnostico, c.idrecurso,
                c.idpermissao, c.idpessoa,c.data, c.ativo
                FROM agepnet200.tb_permissaodiagnostico c
                where
                    c.idpartediagnostico  = :idpartediagnostico AND
                    c.iddiagnostico  = :iddiagnostico
        ";
        $resultado = $this->_db->fetchAll($sql, array(
            'idpartediagnostico' => $params['idpartediagnostico'],
            'iddiagnostico' => $params['iddiagnostico'],
        ));
        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getPermissaoPorDiagnostico($params)
    {
        $sql = "SELECT c.idpartediagnostico, c.iddiagnostico, c.idrecurso,
                c.idpermissao, c.idpessoa,c.data, c.ativo
                FROM agepnet200.tb_permissaodiagnostico c
                where
                    c.iddiagnostico  = :iddiagnostico
        ";
        $resultado = $this->_db->fetchAll($sql, array(
            'iddiagnostico' => $params['iddiagnostico'],
        ));
        return $resultado;
    }

    /**
     * Set the property
     *
     * @param Diagnostico_Model_Permissaodiagnostico $params
     * @return Diagnostico_Model_Permissaodiagnostico
     */
    public function atualizaSituacaoPermissaoPorId($params)
    {
        $data = array(
            "ativo" => $params->ds_permissao,
            "data" => Zend_Date::now(),
        );
        try {
            $pks = array(
                "idpermissao" => $params->idpermissao,
                "iddiagnostico" => $params->iddiagnostico,
                "idpartediagnostico" => $params->idpartediagnostico,
                "idpessoa" => $params->idpessoa,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            return $this->getDbTable()->update($data, $where);
        } catch (Exception $exc) {
            throw $exc;
        }
    }
}