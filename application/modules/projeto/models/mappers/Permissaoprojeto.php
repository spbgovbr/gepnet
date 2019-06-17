<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 28-06-2013
 * 10:07
 */
class Projeto_Model_Mapper_Permissaoprojeto extends App_Model_Mapper_MapperAbstract
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
     * @return Projeto_Model_Permissaoprojeto
     */
    public function insert($params)
    {
        $data = array(
            "idpermissao" => $params['idpermissao'],
            "idprojeto" => $params['idprojeto'],
            "idparteinteressada" => $params['idparteinteressada'],
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
     * @return Projeto_Model_Permissaoprojeto
     */
    public function update($params)
    {
        $data = array(
            //"data"                  => Zend_Date::now(),
            "ativo" => ($params['stpermissao'] == 'S' ? "N" : 'S')
        );
        try {
            return $this->getDbTable()->update($data, array(
                    "idpermissao        = ?" => $params['idpermissao'],
                    "idprojeto          = ?" => $params['idprojeto'],
                    "idparteinteressada = ?" => $params['idparteinteressada']
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
            "idparteinteressada" => ($params['idparteinteressadaNova'])
        );
        try {
            return $this->getDbTable()->update($data, array(
                    "idprojeto          = ?" => (int)$params['idprojeto'],
                    "idparteinteressada = ?" => (int)$params['idparteinteressadaDuplicada'],
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
            $sql = "
                        DELETE FROM agepnet200.tb_permissaoprojeto
                        WHERE idprojeto = :idprojeto 
                        AND idparteinteressada = :idparteinteressada ";

            $resultado = $this->_db->fetchAll($sql,
                array('idprojeto' => $params['idprojeto'], 'idparteinteressada' => $params['idparteinteressada']));

            return $resultado;


//            return $this->getDbTable()->delete(array(
//                        "idprojeto          = ?"   => $params['idprojeto'],
//                        "idparteinteressada = ?"   => $params['idparteinteressada'])
//                    );
        } catch (Exception $exc) {
            Zend_Debug::dump($exc);
            throw $exc;
        }
    }

    public function excluirPermissaoByParteInteressada($params)
    {
        try {
            $sql = "DELETE FROM agepnet200.tb_permissaoprojeto
                    WHERE idprojeto = :idprojeto 
                    AND idparteinteressada = :idparteinteressada
                    AND idpermissao = :idpermissao";

            $resultado = $this->_db->fetchAll($sql, array(
                'idprojeto' => $params['idprojeto'],
                'idparteinteressada' => $params['idparteinteressada'],
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
     *
     * @param string $value
     * @return Projeto_Model_Permissaoprojeto
     */
    public function fetchPairs()
    {
        $sql = "SELECT c.idparteinteressada, c.idprojeto,
                c.idrecurso, c.idpermissao, c.idpessoa, c.data, c.ativo
       FROM agepnet200.tb_permissaoprojeto c ";

        return $this->_db->fetchPairs($sql);
    }

    /**
     *
     * @param array $params
     * @return Projeto_Model_Permissaoprojeto
     */
    public function getById($params)
    {
        $sql = "SELECT c.idparteinteressada, c.idprojeto, c.idrecurso,
                c.idpermissao, c.idpessoa,c.data, c.ativo
                FROM agepnet200.tb_permissaoprojeto c
                where
                    c.idpermissao         = :idpermissao and
                    c.idprojeto           = :idprojeto   and
                    c.idparteinteressada  = :idparteinteressada and
                    c.idpessoa            = :idpessoa
        ";
        $resultado = $this->_db->fetchAll($sql, array(
            'idpermissao' => $params['idpermissao'],
            'idprojeto' => $params['idprojeto'],
            'idparteinteressada' => $params['idparteinteressada'],
            'idpessoa' => $params['idpessoa'],
        ));

        return $resultado;
    }

    public function getByIdParteInteressada($params)
    {
        //Zend_Debug::dump($params);exit;
        $sql = "SELECT c.idparteinteressada, c.idprojeto, c.idrecurso,
                c.idpermissao, c.idpessoa,c.data, c.ativo
                FROM agepnet200.tb_permissaoprojeto c
                where
                    c.idprojeto           = :idprojeto   and
                    c.idparteinteressada  = :idparteinteressada 
        ";
        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idparteinteressada' => $params['idparteinteressada']
        ));

        return $resultado;
    }

    public function getByIdParteInteressadaByPermissao($params)
    {
        //Zend_Debug::dump($params);exit;
        $sql = "SELECT c.idparteinteressada, c.idprojeto, c.idrecurso,
                c.idpermissao, c.idpessoa,c.data, c.ativo
                FROM agepnet200.tb_permissaoprojeto c
                where
                    c.idprojeto           = :idprojeto   and
                    c.idparteinteressada  = :idparteinteressada and
                    c.idpermissao  = :idpermissao 
        ";
        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idparteinteressada' => $params['idparteinteressada'],
            'idpermissao' => $params['idpermissao'],

        ));

        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getPermissaoProjetoById($params)
    {
        $sql = "SELECT c.idparteinteressada, c.idprojeto, c.idrecurso,
                c.idpermissao, c.idpessoa,c.data, c.ativo
                FROM agepnet200.tb_permissaoprojeto c,
                agepnet200.tb_projeto proj
                where
                    c.idprojeto           = proj.idprojeto   and
                    c.idpermissao         = :idpermissao     and
                    c.idprojeto           = :idprojeto       and
                    c.idparteinteressada  = :idparteinteressada and
                    c.idpessoa            = :idpessoa and
                    c.idrecurso IN( /* RECURSOS DE PROJETO */
                    16,21,28,29,30,35,37,38,40,41,42,43,44,49,58,60)
        ";
        if ((@trim($params['idescritorio']) != "") && (@trim($params['perfilAtivo']) != "") && (@trim($params['perfilAtivo']) != "1")) {
            $sql = $sql . " and proj.idescritorio=:idescritorio ";
            $resultado = $this->_db->fetchAll($sql, array(
                'idpermissao' => $params['idpermissao'],
                'idprojeto' => $params['idprojeto'],
                'idparteinteressada' => $params['idparteinteressada'],
                'idpessoa' => $params['idpessoa'],
                'idescritorio' => $params['idescritorio'],
            ));
        } else {
            $resultado = $this->_db->fetchAll($sql, array(
                'idpermissao' => $params['idpermissao'],
                'idprojeto' => $params['idprojeto'],
                'idparteinteressada' => $params['idparteinteressada'],
                'idpessoa' => $params['idpessoa'],
            ));
        }
        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getPermissaoPorProjeto($params)
    {
        $sql = " SELECT proj.idprojeto
                 FROM agepnet200.tb_projeto proj
                 where proj.idprojeto = :idprojeto ";
        if ((@trim($params['idprojeto']) != "") && (@trim($params['idescritorio']) != "") && (@trim($params['perfilAtivo']) != "") && (@trim($params['perfilAtivo']) != "1")) {
            $sql = $sql . " and proj.idescritorio=:idescritorio ";
            $resultado = $this->_db->fetchAll($sql, array(
                'idprojeto' => $params['idprojeto'],
                'idescritorio' => $params['idescritorio'],
            ));
        } else {
            return true;
        }
        return (count($resultado) > 0 ? true : false);
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getPermissaoAcaoProjetoPorParte($params)
    {
        $sql = "SELECT count(c.idpermissao)
                FROM agepnet200.tb_permissaoprojeto c
                INNER JOIN agepnet200.tb_permissao p 
                        on   p.idpermissao = c.idpermissao 
                        and  p.idrecurso   = c.idrecurso
                        and  p.no_permissao in(trim(:no_permissao)) 
                INNER JOIN agepnet200.tb_recurso r 
                        on p.idrecurso=r.idrecurso 
                        and r.ds_recurso in(trim(:no_recurso))
                WHERE
                        c.idprojeto in(:idprojeto) and
                        c.idparteinteressada  in(:idpessoa) and
                        c.ativo     = 'S' ";

        $idprojeto = (int)$params["idprojeto"];
        $controller = "projeto:" . $params["controller"];
        $action = $params["action"];
        $idpessoa = $params["idparteinteressada"];
        $resultado = $this->_db->fetchOne($sql,
            array(
                "idprojeto" => $idprojeto,
                "no_recurso" => $controller,
                "no_permissao" => $action,
                "idpessoa" => $idpessoa
            )
        );
        return (($resultado > 0) ? true : false);
    }

    public function verificaPermissaoByParteInteressadaAndProjeto($params)
    {
        $sql = "SELECT count(c.idpermissao)
                FROM agepnet200.tb_permissaoprojeto c
                where
                    c.idparteinteressada  = :idparteinteressada
                    and c.idprojeto  = :idprojeto ";
        $resultado = $this->_db->fetchRow($sql, array(
            'idparteinteressada' => $params['idparteinteressada'],
            'idprojeto' => $params['idprojeto'],
        ));
        //Zend_Debug::dump($resultado['count']);exit;
        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return Projeto_Model_Permissaoprojeto
     */
    public function getPermissaoPorParte($params)
    {
        $sql = "SELECT c.idparteinteressada, c.idprojeto, c.idrecurso,
                c.idpermissao, c.idpessoa,c.data, c.ativo
                FROM agepnet200.tb_permissaoprojeto c
                where
                    c.idparteinteressada  = :idparteinteressada
        ";
        $resultado = $this->_db->fetchAll($sql, array(
            'idparteinteressada' => $params['idparteinteressada'],
        ));
        return $resultado;
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Permissaoprojeto
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
                "idprojeto" => $params->idprojeto,
                "idparteinteressada" => $params->idparteinteressada,
                "idpessoa" => $params->idpessoa,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            return $this->getDbTable()->update($data, $where);
        } catch (Exception $exc) {
            throw $exc;
        }
    }
}