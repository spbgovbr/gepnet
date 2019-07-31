<?php

/**
 * Newton Carlos
 *
 * Criado em 30-10-2018
 * 15:58
 */
class Diagnostico_Model_Mapper_Diagnostico extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     *
     * @param string $value
     * @return Diagnostico_Model_Diagnostico
     */
    public function insert(Diagnostico_Model_Diagnostico $model)
    {
        try {
            $model->iddiagnostico = $this->maxVal('iddiagnostico');

            $data = array(
                "iddiagnostico" => $model->iddiagnostico,
                "dsdiagnostico" => $model->dsdiagnostico,
                "idunidadeprincipal" => $model->idunidadeprincipal,
                "dtinicio" => new Zend_Db_Expr("to_date('" . $model->dtinicio->toString('d-m-Y') . "','DD/MM/YYYY')"),
                "dtencerramento" => new Zend_Db_Expr("to_date('" . $model->dtencerramento->toString('d-m-Y') . "','DD/MM/YYYY')"),
                "idcadastrador" => $model->idcadastrador,
                "dtcadastro" => new Zend_Db_Expr("now()"),
                "ano" => $model->ano,
                "sq_diagnostico" => $model->sq_diagnostico
            );

            $retorno = $this->getDbTable()->insert($data);
            return $model;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param Diagnostico_Model_Diagnostico $model
     * @return Diagnostico_Model_Diagnostico
     */
    public function update($model)
    {
        $data = array(
            "iddiagnostico" => $model->iddiagnostico,
            "dsdiagnostico" => $model->dsdiagnostico,
            "idunidadeprincipal" => $model->idunidadeprincipal,
            "dtinicio" => $model->dtinicio,
            "dtencerramento" => $model->dtencerramento
        );

        try {
            $pks = array(
                "iddiagnostico" => $model->iddiagnostico,
            );

            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    function inverteData($data)
    {
        $parteData = explode("/", $data);
        $dataInvertida = $parteData[2] . "-" . $parteData[1] . "-" . $parteData[0];
        return $dataInvertida;
    }

    public function getForm()
    {
        return $this->_getForm(Diagnostico_Form_Diagnostico);
    }

    public function getById($params)
    {
        $sql = "SELECT d.iddiagnostico,d.dtinicio,d.dtencerramento, d.dsdiagnostico, d.idunidadeprincipal,
                        (
                            SELECT sigla
                            FROM   vw_comum_unidade
                            WHERE  id_unidade = idunidadeprincipal) AS unidadeprincipal,
                        (
                            SELECT ARRAY_TO_STRING(ARRAY_AGG(u.sigla), '<br>')
                            FROM agepnet200.tb_unidade_vinculada uv
                            INNER JOIN vw_comum_unidade u on u.id_unidade=uv.idunidade
                            WHERE uv.iddiagnostico=d.iddiagnostico and uv.id_unidadeprincipal=d.idunidadeprincipal
                        ) AS unidadesvinculadas,
                        (
                            SELECT     par.idpessoa
                            FROM       agepnet200.tb_partediagnostico par
                            INNER JOIN agepnet200.tb_pessoa pe ON pe.idpessoa = par.idpessoa
                            WHERE      qualificacao = '1' AND iddiagnostico = d.iddiagnostico
                        ) AS idchefedaunidade,
                        (
                            SELECT     nompessoa
                            FROM       agepnet200.tb_partediagnostico par
                            INNER JOIN agepnet200.tb_pessoa pe
                            ON         pe.idpessoa = par.idpessoa
                            WHERE      qualificacao = '1'
                            AND        iddiagnostico = d.iddiagnostico
                        ) AS chefedaunidade,
                        (
                            SELECT     par.idpessoa
                            FROM       agepnet200.tb_partediagnostico par
                            INNER JOIN agepnet200.tb_pessoa pe
                            ON         pe.idpessoa = par.idpessoa
                            WHERE      qualificacao = '2'
                            AND        iddiagnostico = d.iddiagnostico
                        ) AS idpontofocal,
                        (
                            SELECT     nompessoa
                            FROM       agepnet200.tb_partediagnostico par
                            INNER JOIN agepnet200.tb_pessoa pe
                            ON         pe.idpessoa = par.idpessoa
                            WHERE      qualificacao = '2'
                            AND        iddiagnostico = d.iddiagnostico
                        ) AS pontofocal,
                        (
                            SELECT     ARRAY_TO_STRING(ARRAY_AGG(par.idpessoa), ', ')
                            FROM       agepnet200.tb_partediagnostico par
                            INNER JOIN agepnet200.tb_pessoa pe
                            ON         pe.idpessoa = par.idpessoa
                            WHERE      qualificacao = '3'
                            AND        iddiagnostico = d.iddiagnostico
                        ) AS idequipe,
                        (
                            SELECT     ARRAY_TO_STRING(ARRAY_AGG(nompessoa), '\n')
                            FROM       agepnet200.tb_partediagnostico par
                            INNER JOIN agepnet200.tb_pessoa pe
                            ON         pe.idpessoa = par.idpessoa
                            WHERE      qualificacao = '3'
                            AND        iddiagnostico = d.iddiagnostico) AS pessoasequipe
                FROM            agepnet200.tb_diagnostico d
                LEFT JOIN       agepnet200.tb_partediagnostico p
                ON              p.iddiagnostico = d.iddiagnostico
                WHERE           d.iddiagnostico = :iddiagnostico
                GROUP BY d.iddiagnostico,d.dtinicio,d.dtencerramento, d.dsdiagnostico, d.idunidadeprincipal";

        $resultado = $this->_db->fetchRow(
            $sql, array(
                'iddiagnostico' => (int)$params['iddiagnostico']
            )
        );

        $diagnostico = new Diagnostico_Model_Diagnostico($resultado);
        return $diagnostico;
    }

    /**
     * Lista todos os diagnósticos da tabela.
     * @param int $iddiagnostico
     * @param array $params
     * return array
     */
    public function listar($params = array())
    {
        $params = array_filter($params);
        $sql = "SELECT  dsdiagnostico, 
                        (SELECT sigla 
                           FROM vw_comum_unidade 
                          WHERE id_unidade = idunidadeprincipal) AS sigla, 
                        To_char(dtinicio, 'DD/MM/YYYY')           dtinicio, 
                        To_char(dtencerramento, 'DD/MM/YYYY')     dtencerramento, 
                        d.iddiagnostico, 
                        (SELECT unidade_responsavel 
                           FROM vw_comum_unidade 
                          WHERE id_unidade = idunidadeprincipal) AS idunidadeprincipal,
                        d.ativo
                   FROM agepnet200.tb_diagnostico d 
                   LEFT JOIN vw_comum_unidade vw 
                     ON vw.id_unidade = unidade_responsavel 
                  WHERE 1 = 1 
                    AND d.ativo <> false  ";

        $params = array_filter($params);
        if (isset($params['dsdiagnostico']) && (!empty($params['dsdiagnostico']))) {
            $dsdiagnostico = strtoupper($params['dsdiagnostico']);
            $sql .= " AND upper(dsdiagnostico) LIKE '%{$dsdiagnostico}%'";
        }

        if (isset($params['idunidadeprincipal']) && (!empty($params['idunidadeprincipal']))) {
            $sql .= " AND idunidadeprincipal = {$params['idunidadeprincipal']}";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

        try {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        } catch (Exception $exc) {
            throw new Exception($exc->code());
        }
    }

    public function delete($params = array())
    {
        $data = array(
            "ativo" => 'FALSE',
        );
        try {
            $pks = array(
                "iddiagnostico" => $params,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function getListarUnidadePrincipal()
    {
        $sql = "SELECT id_unidade, sigla 
            FROM vw_comum_unidade
            WHERE id_unidade IN 
            (SELECT DISTINCT (unidade_responsavel) FROM vw_comum_unidade)
            ORDER BY sigla";

        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function getUnidadesFilhas($idPai)
    {
        $sql = "select id_unidade, sigla 
	from (
	WITH RECURSIVE unidade(id_unidade,   
				  unidade_responsavel, 
				  sigla, 
				  pai, 
				  ordenacao, 
				  nivel) AS (
				    SELECT    
					u.id_unidade, 
					u.unidade_responsavel, 
					u.sigla, 
					u.id_unidade AS pai,
					ARRAY[u.id_unidade] AS ordenacao,
					1 AS nivel
				    FROM vw_comum_unidade u 
				    WHERE u.id_unidade=$idPai 
				    
				    UNION ALL

				    SELECT    
					u.id_unidade, 
					u.unidade_responsavel, 
					u.sigla, 
					u.id_unidade AS pai,
					up.ordenacao || u.id_unidade AS ordenacao,
					up.nivel + 1 AS nivel
				    FROM vw_comum_unidade u
				    JOIN unidade up on up.id_unidade=u.unidade_responsavel                            
				 )
				 SELECT u.* FROM unidade u
                                 WHERE u.nivel NOT IN (1)
                                 ORDER BY u.ordenacao) as uv
				 order by sigla ";
        if (isset($idPai) && !empty($idPai)) {
            $resultado = $this->_db->fetchAll($sql);
            return $resultado;
        } else {
            return array();
        }
    }

    public function fetchPairsPorDiagnostico($param)
    {
        $sql = "SELECT     par.idpessoa,nompessoa
                FROM       agepnet200.tb_partediagnostico par 
                INNER JOIN agepnet200.tb_pessoa pe 
                ON         pe.idpessoa = par.idpessoa 
                WHERE      iddiagnostico = :iddiagnostico 
                AND        qualificacao = '3' ";

        return $this->_db->fetchAll($sql, array('iddiagnostico' => $param));
    }

    public function getMaxId()
    {
        $sql = "SELECT idpartediagnostico
                FROM agepnet200.tb_partediagnostico  
                ORDER BY idpartediagnostico DESC 
                LIMIT 1";
        $resultado = $this->_db->fetchOne($sql);
        return $resultado;
    }

    public function fetchPairs($idDiagnostico = null)
    {
        $sql = " SELECT idpessoa,
                       nompessoa 
                FROM   agepnet200.tb_pessoa ";
        if (!empty($idDiagnostico)) {
            $sql .= " 
                WHERE  idpessoa NOT IN (SELECT idpessoa 
                                        FROM   agepnet200.tb_partediagnostico 
                                        WHERE  iddiagnostico = {$idDiagnostico}) ";
        }
        $sql .= " ORDER  BY nompessoa ASC ";

        return $this->_db->fetchPairs($sql);
    }

    public function getCheckbox($param)
    {
        $sql = "SELECT  ARRAY_TO_STRING(ARRAY_AGG(idunidade), ', ') as idunidade
                FROM    agepnet200.tb_unidade_vinculada
                WHERE   iddiagnostico = :iddiagnostico ";

        return $this->_db->fetchOne($sql, array('iddiagnostico' => $param));
    }

    public function getAll()
    {
        $sql = " SELECT iddiagnostico, dsdiagnostico  
                 FROM agepnet200.tb_diagnostico 
                 ORDER BY dsdiagnostico";
        return $this->_db->fetchPairs($sql);
    }

    public function getUnidadesVinculadas($idDiagnostico)
    {
        $sql = "SELECT idunidade, sigla
            FROM agepnet200.tb_unidade_vinculada uv
            inner join vw_comum_unidade vw
            on uv.idunidade = vw.id_unidade
            WHERE   iddiagnostico = $idDiagnostico";
        return $this->_db->fetchPairs($sql);
    }

    public function getNomeUnidade($idUnidade)
    {
        $sql = "SELECT sigla
            FROM vw_comum_unidade vw
            WHERE vw.id_unidade = $idUnidade";
        return $this->_db->fetchRow($sql);
    }

    public function getSequenceDiagnostico()
    {
        $sql = "SELECT nextval('agepnet200.sq_diagnostico')";
        return $this->_db->fetchRow($sql);
    }


}
