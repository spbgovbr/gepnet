<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Pessoa extends App_Model_Mapper_MapperAbstract
{

    protected $_dependencies = array('log');

    /**
     *
     * @var Zend_Log
     */
    protected $_log;

    /**
     *
     * @var Default_Model_Mapper_Pessoa
     */
    protected $_mapper;

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Pessoa
     */
    public function insert(Default_Model_Pessoa $model)
    {
        try {
            $model->idpessoa = $this->maxVal('idpessoa');
            $data = array(
                "idpessoa" => $model->idpessoa,
                "nompessoa" => $model->nompessoa,
                "numcpf" => $model->numcpf,
                "desobs" => $model->desobs,
                "numfone" => $model->numfone,
                "numcelular" => $model->numcelular,
				"token"      => $model->getToken(),
                "desemail" => $model->desemail,
//                "domcargo" => $model->domcargo,
                "idcadastrador" => $model->idcadastrador,
                "datcadastro" => new Zend_Db_Expr("now()"),
//                "id_unidade" => $model->id_unidade,
                "id_servidor" => $model->id_servidor,
                "nummatricula" => $model->nummatricula,
                "desfuncao" => $model->desfuncao,
                "flaagenda" => $model->flaagenda,
            );
            $data = array_filter($data);
            if ($model->isColaborador()) {
                unset($data['id_servidor']);
            }

            if ($data["domcargo"] == 'COL') {
                $data["domcargo"] = 'OUTROS';
            }
            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
        } catch (Exception $exc) {
            $this->_log->err($exc);
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Pessoa
     */
    public function update(Default_Model_Pessoa $model)
    {
        $data = array(
            /*
            "nompessoa"    => $model->nompessoa,
            "numcpf"       => $model->numcpf,
            "nummatricula" => $model->nummatricula,
            */
            "numfone" => $model->numfone,
            "numcelular" => $model->numcelular,
            "desemail" => $model->desemail,
			"token"    => $model->getToken(),
            "id_unidade" => $model->id_unidade,
            "desobs" => $model->desobs,
            "desfuncao" => $model->desfuncao,
            "flaagenda" => $model->flaagenda,
            "domcargo" => $model->domcargo,
            "versaosistema" => $model->versaosistema,
        );

        $data = array_filter($data);

        try {
            $pks = array(
                "idpessoa" => $model->idpessoa,
            );

            if (array_key_exists('domcargo', $data) && $data["domcargo"] == 'COL') {
                $data["domcargo"] = 'OUTROS';
            }
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     *
     * @param array $params
     * @return int numero de linhas apagadas
     * @throws Exception
     */
    public function delete($params)
    {
        try {
            $pks = array(
                "idpessoa" => $params['idpessoa'],
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "SELECT 
                    idpessoa, nompessoa, numcpf, desobs, numfone, numcelular, desemail, 
                    domcargo, idcadastrador, to_char(datcadastro,'DD/MM/YYYY') as datcadastro, pes.id_unidade as id_unidade, 
                    id_servidor, nummatricula, desfuncao, flaagenda, NULL as sigla
                FROM agepnet200.tb_pessoa pes
                WHERE 
                    pes.idpessoa = :idpessoa";

        $resultado = $this->_db->fetchRow($sql, array('idpessoa' => $params['idpessoa']));
        $pessoa = new Default_Model_Pessoa($resultado);


        return $pessoa;
    }


    /**
     * @param array $params
     * @return array
     */
    public function getPessoaById($params)
    {

        $sql = "SELECT 
                    idpessoa, nompessoa, numcpf, desobs, numfone, numcelular, desemail, 
                    domcargo, idcadastrador, to_char(datcadastro,'DD/MM/YYYY') as datcadastro, pes.id_unidade as id_unidade, 
                    id_servidor, nummatricula, desfuncao, flaagenda, NULL as sigla
                FROM agepnet200.tb_pessoa pes
                WHERE 
                    pes.nompessoa like " . "'%" . $params["nompessoa"] . "%'";

        return $params["nompessoa"];
    }


    /**
     * @param array $params
     * @return array
     */
    public function retornaPessoaProjeto($params)
    {
        $sql = "SELECT
                    idpessoa, nompessoa, numcpf, numfone, numcelular, desemail,
                    domcargo, pes.id_unidade as id_unidade,
                    id_servidor, nummatricula, desfuncao, flaagenda
                FROM agepnet200.tb_pessoa pes
                WHERE
                    pes.idpessoa = :idpessoa";

        $resultado = $this->_db->fetchRow($sql, array('idpessoa' => $params['idpessoa']));
        if (count($resultado) <= 0) {
            return false;
        }
        return new Default_Model_Pessoa($resultado);
    }

    /**
     * @param array $params
     * @return array
     */
    public function retornaPorId($params)
    {
        $sql = "SELECT 
                    idpessoa, pes.nompessoa, pes.numcpf, pes.desobs, pes.numfone, pes.numcelular, pes.desemail, 
                    null as domcargo, pes.idcadastrador, to_char(pes.datcadastro,'DD/MM/YYYY') as datcadastro, pes.id_unidade as id_unidade, 
                    pes.id_servidor, pes.nummatricula, pes.desfuncao, pes.flaagenda
                FROM 
                    agepnet200.tb_pessoa pes 
                WHERE pes.idpessoa = :idpessoa";

        $resultado = $this->_db->fetchRow($sql, array('idpessoa' => $params['idpessoa']));
        $pessoa = new Default_Model_Pessoa($resultado);
        return $pessoa;
    }

    /**
     *Retorna usuario por CPF
     * @param array $params
     * @return \Default_Model_Pessoa
     */
    public function retornaUsuario($params)
    {
        $sql = "SELECT
                    pes.idpessoa, pes.nompessoa,
                    pes.numcpf, pes.nummatricula,
                    COALESCE(pi.nomfuncao,
                    CASE
                       WHEN p.idgerenteprojeto=pes.idpessoa THEN 'Gerente de Projeto'
                       WHEN p.iddemandante=pes.idpessoa THEN 'Demandante'
                       WHEN p.idgerenteadjunto = pes.idpessoa THEN 'Gerente Adjunto'
                       WHEN p.idpatrocinador = pes.idpessoa THEN 'Patrocinador'
                       ELSE 'Parte Interessada'
                    END) AS nomfuncao,
	            pi.idparteinteressada
                FROM agepnet200.tb_pessoa pes
                LEFT JOIN agepnet200.tb_parteinteressada pi
                     ON pi.idpessoainterna=pes.idpessoa and pi.idprojeto = :idprojeto
                LEFT JOIN agepnet200.tb_projeto p
                     ON p.idprojeto= :idprojeto
                     AND (p.idgerenteprojeto=pes.idpessoa OR p.iddemandante=pes.idpessoa
                          OR p.idgerenteadjunto = pes.idpessoa OR p.idpatrocinador = pes.idpessoa)
                WHERE pes.numcpf = :numcpf";

        $resultado = $this->_db->fetchRow($sql, array(
                'numcpf' => (int)$params['numcpf'],
                'idprojeto' => (int)$params['idprojeto']
            )
        );

        if (count($resultado) < 1) {
            return false;
        }

        return $resultado;
    }


    /**
     *
     * @param array $params
     * @return \Default_Model_Pessoa
     */
    public function getByCpf($params)
    {
        $sql = "SELECT
                    idpessoa, nompessoa, numcpf, desobs, numfone, numcelular, desemail,
                    domcargo, idcadastrador, to_char(datcadastro,'DD/MM/YYYY') as datcadastro, pes.id_unidade as id_unidade,
                    id_servidor, nummatricula, desfuncao, flaagenda, NULL as sigla
                FROM agepnet200.tb_pessoa pes
                WHERE
                    pes.numcpf = :numcpf";

        $resultado = $this->_db->fetchRow($sql, array('numcpf' => $params));

        if (count($resultado) < 1) {
            return false;
        }

        $pessoa = new Default_Model_Pessoa($resultado);

        return $pessoa;
    }

    public function getByEmail($params)
    {
        $sql = "SELECT
                    idpessoa, nompessoa, numcpf, desobs, numfone, numcelular, desemail,
                    domcargo, idcadastrador, to_char(datcadastro,'DD/MM/YYYY') as datcadastro,lotacao,
                    id_servidor, nummatricula, desfuncao, flaagenda,
                    versaosistema, primeiroacesso
                FROM agepnet200.tb_pessoa pes
                WHERE
                     pes.desemail = :desemail";

        $resultado = $this->_db->fetchRow($sql, array(
                'desemail' => $params['desemail'],
            )
        );
        if (count($resultado) < 1) {
            return false;
        }

        $pessoa = new Default_Model_Pessoa($resultado);
        $unidade = new Default_Model_Unidade($resultado);
        $pessoa->unidade = $unidade;

        return $pessoa;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $paginator = false)
    {
        //$sql = "SELECT domcargo, nompessoa, nummatricula, numcpf,  uni.sigla as unidade, idpessoa
        $sql = "SELECT nompessoa, nummatricula, LPAD(numcpf::text, 11, '0') numcpf, idpessoa
                FROM agepnet200.tb_pessoa pes
                WHERE 1 = 1";
        $params = array_filter($params);
        if (isset($params['nompessoa'])) {
            $nompessoa = strtoupper($params['nompessoa']);
            $sql .= " AND upper(nompessoa) LIKE '%{$nompessoa}%'";
        }

        if (isset($params['numcpf'])) {
            $numcpf = preg_replace("/\D+/", "", $params['numcpf']);
            $sql .= " AND numcpf =  '{$numcpf}'";
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

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function buscarServidor($params, $paginator = false)
    {
        $sql = "SELECT cpes.id_pessoa as id, cpes.nome as nome
                   FROM
                        vw_comum_pessoa cpes,
                        vw_rh_servidor srv
                   where
                        cpes.tipo = 'F'
                        and cpes.id_pessoa = srv.id_pessoa 
                        and srv.cd_status in(1,5,7)
                        /*
                        and cpes.id_pessoa NOT IN (
                            SELECT pes.idpessoa
                            FROM agepnet200.tb_pessoa pes
                            WHERE pes.id_servidor = srv.id_servidor
                        )*/";
        $params = array_filter($params);
        if (isset($params['nome'])) {
            $nompessoa = strtoupper($params['nome']);
            $sql .= " AND upper(cpes.nome) LIKE '%{$nompessoa}%'";
        }

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['page_limit'])) ? $params['page_limit'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    /**
     * Retorna um registro da view pessoa do owner comum
     * @param array $params
     * @return Default_Model_Pessoa
     */
    public function getServidorById($params)
    {
        $sql = "SELECT 
                    srv.id_servidor, srv.matricula_interna as nummatricula,
                    srv.cd_cargo, srv.cd_lotacao as id_unidade, 
                    pes.nome as nompessoa, pes.cpf_cnpj as numcpf,                         
                    substr(replace(replace(replace(replace(replace(replace(replace(uni.telefones,'/',''),'(710 ','(71) '),'(',''),')',''),' ',''),'-',''),'.',''), 0, 11) as numfone,	
                    pes.celular as numcelular, pes.email as desemail, 
                    cgo.sigla as domcargo, pes.id_pessoa
                FROM 
                    vw_rh_servidor srv 
                    LEFT join vw_comum_pessoa pes on pes.id_pessoa = srv.id_pessoa and pes.tipo = 'F'
                    LEFT join vw_comum_unidade uni on uni.id_unidade=srv.cd_lotacao 
                    LEFT join vw_rh_cargo cgo on cgo.id = srv.cd_cargo and cgo.inativo = false
                WHERE srv.id_pessoa = :idpessoa ";

        $row = $this->_db->fetchRow($sql, array('idpessoa' => $params['id']));
        return new Default_Model_Pessoa($row);
    }

    public function fetchPairs()
    {
        $sql = " SELECT idpessoa, nompessoa FROM agepnet200.tb_pessoa order by nompessoa asc";
        return $this->_db->fetchPairs($sql);
    }

    /**
     * Recebe um array com o indice idprojeto e retorna um array de pessoas
     * @param array $params
     * @return array
     */
    public function fetchPairsPorProjeto($params)
    {
        $sql = "select
                    idpessoa, nompessoa
                from agepnet200.tb_pessoa
                where idpessoa in (
                    select idpessoa
                    from agepnet200.tb_projetopessoa
                    where idprojeto = :idprojeto)
                order by nompessoa";

        return $this->_db->fetchPairs($sql, array('idprojeto' => $params['idprojeto']));
    }

    /**
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function buscarColaborador($params, $paginator = false)
    {
        /* @var $db Zend_Db_Adapter_Abstract */
        $db = $this->getBootstrap()->getPluginResource('multidb')->getDb('dpf_oracle');
        $sql = "SELECT CD_PESSOA id, NO_PESSOA nome
                FROM TB_PESSOA PES
                WHERE
                PES.TP_PESSOA = 2
                AND PES.ST_PESSOA = 'A'";
        $params = array_filter($params);
        if (isset($params['nome'])) {
            $nompessoa = strtoupper($params['nome']);
            $sql .= " AND upper(PES.NO_PESSOA) LIKE '%{$nompessoa}%'";
        }

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['page_limit'])) ? $params['page_limit'] : 20;
            $paginatorAdapter = new App_Paginator_Adapter_Sql_Oracle($sql);
            $paginatorAdapter->setDb($db);
            $paginator = new Zend_Paginator($paginatorAdapter);
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $db->fetchAll($sql);
        return $resultado;
    }

    /**
     * Valida usuarios no Oracle
     * @param array $params
     * @return boolean
     */

    /**
     * Retorna um registro da view pessoa do owner comum
     * @param array $params
     * @return Default_Model_Pessoa
     */
    public function getColaboradorById($params)
    {
        /* @var $db Zend_Db_Adapter_Abstract */
        $db = $this->getBootstrap()->getPluginResource('multidb')->getDb('dpf_oracle');
        $sql = "select
                    cd_matricula as nummatricula,
                    lot_cd_lotacao as id_unidade,
                    no_pessoa as nompessoa, nr_cpf as numcpf,
                    ds_e_mail as desemail
                  from tb_pessoa pes
                  where
                     tp_pessoa = 2
                     and st_pessoa = 'A'
                     and cd_pessoa = :idpessoa";

        $row = $db->fetchRow($sql, array('idpessoa' => $params['id']));
        $resultado = array_change_key_case($row, CASE_LOWER);
        return new Default_Model_Pessoa($resultado);
    }

    /**
     * Retorna usuario oracle
     * @param array $params
     * @return array | boolean
     */
    public function getPessoaOracle($params)
    {
        /* @var $db Zend_Db_Adapter_Abstract */
        $db = $this->getBootstrap()->getPluginResource('multidb')->getDb('dpf_oracle');
        $sql = "select
                    cd_matricula as nummatricula,
                    lot_cd_lotacao as id_unidade,
                    no_pessoa as nompessoa, nr_cpf as numcpf,
                    ds_e_mail as desemail
                  from tb_pessoa pes
                  where
                     st_pessoa = 'A'
                     and nr_cpf = :cpf";

        $row = $db->fetchRow($sql, array(
                'cpf' => $params['numcpf']
            )
        );
        $resultado = array_change_key_case($row, CASE_LOWER);

        if (count($resultado) <= 0) {
            return false;
        }
        return $resultado;
    }

    /**
     * Valida servidor por login e senha
     * @param array $params
     * @return boolean
     */
    public function validaServidor($params)
    {
        $sql = "SELECT count(id_servidor)
                FROM vw_comum_usuario
                WHERE login = :desemail
                AND senha = :senha";

        $resultado = $this->_db->fetchRow($sql, array(
                'desemail' => $params['desemail'],
                'senha' => $params['senha']
            )
        );

        return (count($resultado) > 0) ? true : false;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisarSemUnidade($params, $paginator = false)
    {
        $sql = "SELECT nompessoa, idpessoa
                FROM agepnet200.tb_pessoa pes
                WHERE 1 = 1";

        if (isset($params['agenda'])) {
            $sql .= " and flaagenda = 'S' ";
        }
        $params = array_filter($params);
        if (isset($params['gridpessoa'])) {
            $nompessoa = strtoupper($params['gridpessoa']);
            $sql .= " AND upper(nompessoa) LIKE '%{$nompessoa}%'";
        }

        $sql .= " ORDER BY pes.nompessoa ";

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

    /**
     * Verifica se a ultima nota de versao foi visualizada pelo usuario.
     * @param array $params
     * @return boolean
     *
     **/
    public function verificaVersaoByIdPessoa($params)
    {

        $sql = "SELECT COUNT(idpessoa) as total
                FROM agepnet200.tb_pessoa
                WHERE idpessoa IN(:idpessoa)
                AND versaosistema IN(trim(:versaosistema))";

        $resultado = $this->_db->fetchRow($sql,
            array('idpessoa' => $params['idpessoa'], 'versaosistema' => $params['versaosistema']));

        return ($resultado['total'] > 0) ? true : false;
    }

    /**
     * @param $params
     * @return bool|mixed
     */
    public function getTokenByEmail($params)
    {
        $sql = "SELECT token
                FROM agepnet200.tb_pessoa
                WHERE desemail = :desemail";
        $resultado = $this->_db->fetchRow($sql, array('desemail' => $params));
        if (count($resultado) < 1) {
            return false;
        }
        return $resultado;
    }

}

