<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Parteinteressada extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Parteinteressada
     */
    public function insert(Projeto_Model_Parteinteressada $model)
    {
        $model->idparteinteressada = $this->maxVal('idparteinteressada');

        $data = array(
            "idparteinteressada" => (int)$model->idparteinteressada,
            "idprojeto" => (int)$model->idprojeto,
            "nomparteinteressada" => $model->nomparteinteressada,
            "nomfuncao" => $model->nomfuncao,
            "destelefone" => $model->destelefone,
            "desemail" => $model->desemail,
            "domnivelinfluencia" => $model->domnivelinfluencia,
            "idcadastrador" => (int)$model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "idpessoainterna" => (int)$model->idpessoainterna,
            "observacao" => $model->observacao,
            "tppermissao" => $model->tppermissao
        );
        if (trim($data['destelefone']) == "0000000000") {
            $data['destelefone'] = null;
        }
        $data = array_filter($data);

        if (empty($model->idpessoainterna)) {
            $data["idpessoainterna"] = null;
            $data["tppermissao"] = null;
        }

        try {
            return $this->getDbTable()->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Parteinteressada
     */
    public function update(Projeto_Model_Parteinteressada $model)
    {
        $data = array(
            "idparteinteressada" => $model->idparteinteressada,
            "idprojeto" => $model->idprojeto,
            "nomparteinteressada" => $model->nomparteinteressada,
            "nomfuncao" => $model->nomfuncao,
            "destelefone" => $model->destelefone,
            "desemail" => $model->desemail,
            "domnivelinfluencia" => $model->domnivelinfluencia,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "idpessoainterna" => $model->idpessoainterna,
            "observacao" => $model->observacao
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    /**
     * Atualiza os dados de Parte Interessada
     *
     * @param Projeto_Model_Parteinteressada $model
     */
    public function updateParte($dados)
    {
        $data = array(
            "idparteinteressada" => $dados['idparteinteressada'],
            "idprojeto" => $dados['idprojeto'],
            "nomparteinteressada" => isset($dados['nomparteinteressada']) ? $dados['nomparteinteressada'] : "",
            "nomfuncao" => (isset($dados['nomfuncao'])) ? $dados['nomfuncao'] : "",
            "destelefone" => isset($dados['destelefone']) != "" ? $dados['destelefone'] : "",
            "desemail" => isset($dados['desemail']) != "" ? $dados['desemail'] : "",
            "domnivelinfluencia" => $dados['domnivelinfluencia'] != "" ? $dados['domnivelinfluencia'] : "",
            "idcadastrador" => isset($dados['idcadastrador']) != "" ? $dados['idcadastrador'] : "",
            "datcadastro" => isset($dados['datcadastro']) != "" ? $dados['datcadastro'] : "",
            "idpessoainterna" => $dados['idpessoainterna'] != "" ? $dados['idpessoainterna'] : "",
            "observacao" => isset($dados['observacao']) != "" ? $dados['observacao'] : "",
            "tppermissao" => isset($dados['tppermissao']) != "" ? $dados['tppermissao'] : "",
        );
        $data = array_filter($data);

        if (!empty($dados['idpessoainterna'])) {
            $data["idpessoainterna"] = (int)$dados['idpessoainterna'];
        } else {
            $data["idpessoainterna"] = null;
        }

        try {
            $this->getDbTable()->update($data, array(
                "idparteinteressada = ?" => (int)$dados['idparteinteressada'],
                "idprojeto = ?" => (int)$dados['idprojeto']
            ));
            return true;
        } catch (Exception $exc) {
            throw $exc;
        }

    }

    public function updateParteInterna(Projeto_Model_Parteinteressada $model)
    {
        $data = array(
            "idparteinteressada" => $model->idparteinteressada,
            "idprojeto" => $model->idprojeto,
            "nomparteinteressada" => (isset($model->nomparteinteressada)) ? $model->nomparteinteressada : "",
            "nomfuncao" => (isset($model->nomfuncao)) ? $model->nomfuncao : "",
            "destelefone" => (isset($model->destelefone)) ? $model->destelefone : "",
            "desemail" => (isset($model->desemail)) ? $model->desemail : "",
            "domnivelinfluencia" => (@trim($model->domnivelinfluencia) != "" ? $model->domnivelinfluencia : ""),
            "idcadastrador" => (@trim($model->idcadastrador) != "" ? $model->idcadastrador : ""),
            "datcadastro" => (@trim($model->datcadastro) != "" ? $model->datcadastro : ""),
            "idpessoainterna" => (@trim($model->idpessoainterna) != "" ? $model->idpessoainterna : ""),
            "observacao" => (@trim($model->observacao) != "" ? $model->observacao : "")
        );
        $data = array_filter($data);
        try {
            $this->getDbTable()->update($data, array("idparteinteressada = ?" => $model->idparteinteressada));
            return true;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function updatePartes($dados)
    {
        try {
            $data["nomfuncao"] = $dados["nomfuncao"];
            $pks = array(
                "idparteinteressada" => $dados["idparteinteressada"],
            );
            $where1 = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where1);
            return true;

        } catch (Exception $exc) {
            throw $exc;
            return false;
        }
    }


    public function updatePartesDemAdj($dados)
    {
        $data["nomfuncao"] = $dados["nomfuncao"];
        $pks1 = array(
            "idparteinteressada" => $dados["idDeman"],
        );
        $pks2 = array(
            "idparteinteressada" => $dados["idadjunto"],
        );

        $where1 = $this->_generateRestrictionsFromPrimaryKeys($pks1);
        $this->getDbTable()->update($data, $where1);

        $where2 = $this->_generateRestrictionsFromPrimaryKeys($pks2);
        $this->getDbTable()->update($data, $where2);
        return true;
    }

    public function updatePartesDem($dados)
    {
        $data["nomfuncao"] = $dados["nomfuncao"];
        $pks = array(
            "idparteinteressada" => $dados["idDeman"],
        );

        $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
        $this->getDbTable()->update($data, $where);
        return true;
    }

    public function updatePartesAdj($dados)
    {
        $data["nomfuncao"] = $dados["nomfuncao"];
        $pks = array(
            "idparteinteressada" => $dados["idadjunto"],
        );

        $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
        $this->getDbTable()->update($data, $where);
        return true;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "SELECT
                    pin.idparteinteressada,
                    pin.idprojeto,
                    pin.nomparteinteressada,
                    (
                      SELECT ARRAY_TO_STRING(ARRAY_AGG(pf.nomfuncao),', ','') AS nomfuncao 
                       FROM agepnet200.tb_parteinteressada_funcoes f 
                       INNER JOIN agepnet200.tb_parteinteressadafuncao pf 
                         ON pf.idparteinteressadafuncao = f.idparteinteressadafuncao 
                       WHERE f.idparteinteressada = pin.idparteinteressada
                    ) AS nomfuncao,
                    pin.destelefone,
                    pin.desemail,
                    pin.domnivelinfluencia,
                    pin.idpessoainterna,
                    pin.observacao,
                    pin.tppermissao
            FROM
                    agepnet200.tb_parteinteressada pin
            WHERE
                    pin.idprojeto = :idprojeto";

        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));

        return $resultado;
    }

    public function verificaParteInteressadaByProjeto($params)
    {

        $sql = "SELECT
                   COUNT(idparteinteressada) as total
                FROM
                   agepnet200.tb_parteinteressada
                WHERE
                   idprojeto = :idprojeto
                AND
                   idparteinteressada = :idparteinteressada and status = true  ";

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idparteinteressada' => $params['idparteinteressada']
        ));

        return $resultado;
    }

    public function verificaParteExternaByProjeto($params)
    {
        $nomeparte = (@trim($params['nomparteinteressadaexterno']) != "" ? $params['nomparteinteressadaexterno'] : $params['nomparteinteressada']);
        $emailparte = (@trim($params['desemailexterno']) != "" ? $params['desemailexterno'] : $params['desemail']);
        $sql = "SELECT
                   idparteinteressada, nomparteinteressada
                FROM
                   agepnet200.tb_parteinteressada
                WHERE idprojeto = :idprojeto 
                  AND nomparteinteressada = lower(trim(:nomparteinteressada)) 
                  AND desemail = LOWER (TRIM(:desemail)) 
                  AND status = TRUE  ";
        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'nomparteinteressada' => strtolower($nomeparte),
            'desemail' => strtolower($emailparte)
        ));
        return (count($resultado) > 0 ? true : false);
    }

    public function verificaParteInteressadaInternaByProjeto($params)
    {

        $sql = "SELECT
                   COUNT(idparteinteressada) as total
                FROM
                   agepnet200.tb_parteinteressada
                WHERE idprojeto = :idprojeto
                  AND idpessoainterna = :idparteinteressada  
                  AND status = TRUE";

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idparteinteressada' => $params['idparteinteressada']
        ));

        return $resultado;
    }

    public function verificaParteByProjeto($params)
    {

        $sql = "SELECT
                   COUNT(idparteinteressada) as total
                FROM
                   agepnet200.tb_parteinteressada
                WHERE idprojeto = {$params['idprojeto']} 
                  AND status = TRUE ";

        if (!empty($params['idpessoainterna'])) {
            $sql .= "AND idpessoainterna = {$params['idpessoainterna']} ";
        }

        if (!empty($params['idparteinteressada'])) {
            $sql .= "AND idparteinteressada = {$params['idparteinteressada']} ";
        }

        $resultado = $this->_db->fetchOne($sql);

        return $resultado;
    }




    /**
     * @param array $params
     * @return array
     */
    public function getByProjeto($params)
    {
        $sql = "
                        SELECT
                                pin.idparteinteressada,
                                pin.idprojeto,
                                pin.nomparteinteressada,
                                (
                                  SELECT ARRAY_TO_STRING(ARRAY_AGG(pf.nomfuncao),', ','') AS nomfuncao 
                                   FROM agepnet200.tb_parteinteressada_funcoes f 
                                   INNER JOIN agepnet200.tb_parteinteressadafuncao pf 
                                     ON pf.idparteinteressadafuncao = f.idparteinteressadafuncao 
                                   WHERE f.idparteinteressada = pin.idparteinteressada
                                ) AS nomfuncao,
                                pin.destelefone,
                                pin.desemail,
                                pin.domnivelinfluencia,
                                pin.idpessoainterna,
                                pin.observacao
                        FROM
                                agepnet200.tb_parteinteressada pin
                        WHERE   pin.idprojeto = :idprojeto
                          AND   pin.status = TRUE 
                        ORDER BY pin.idparteinteressada DESC";

        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));

//      $projeto = new Projeto_Model_Gerencia($resultado);
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Parteinteressada');

        foreach ($resultado as $r) {
            $parte = new Projeto_Model_Parteinteressada($r);
            $collection[] = $parte;
        }

        return $collection;
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return
     */
    public function excluir($params)
    {
        try {

            if (isset($params['idprojeto']) != null) {

                $sqlParteFuncao = "DELETE FROM agepnet200.tb_parteinteressada_funcoes 
                               WHERE idparteinteressada = :idparteinteressada ";
                $res = $this->_db->fetchAll($sqlParteFuncao,
                    array('idparteinteressada' => $params['idparteinteressada']));

                $sql = "UPDATE agepnet200.tb_parteinteressada SET status = FALSE 
                         WHERE idprojeto = :idprojeto 
                           AND idparteinteressada = :idparteinteressada ";

                $params = array_filter($params);

                $resultado = $this->_db->fetchAll($sql,
                    array('idprojeto' => $params['idprojeto'], 'idparteinteressada' => $params['idparteinteressada']));

                return $resultado;
            }
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function excluirParteInteressadasDuplicadas($params)
    {
        try {
            $sql = "
                DELETE from agepnet200.tb_parteinteressada
                WHERE idprojeto = :idprojeto
                AND nomparteinteressada in
                  ( select nomparteinteressada 
                        from agepnet200.tb_parteinteressada
                   group by nomparteinteressada
                   having Count(nomparteinteressada)>1 )
                   and not idparteinteressada in
                  ( select Max(idparteinteressada) 
                          from agepnet200.tb_parteinteressada
                           group by nomparteinteressada
                           having Count(nomparteinteressada)>1 ) ";

            $params = array_filter($params);

            $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));

            return $resultado;

        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function removerPartes($params)
    {
        try {

            $sqlParteFuncao = "DELETE FROM agepnet200.tb_parteinteressada_funcoes 
                               WHERE idparteinteressada = :idparteinteressada ";
            $res = $this->_db->fetchAll($sqlParteFuncao,
                array('idparteinteressada' => $params['idparteinteressada']));

            $sql = "UPDATE agepnet200.tb_parteinteressada SET status = FALSE
                     WHERE idprojeto = :idprojeto 
                       AND idparteinteressada = :idparteinteressada ";

            $resultado = $this->_db->fetchAll($sql,
                array('idprojeto' => $params['idprojeto'], 'idparteinteressada' => $params['idparteinteressada']));

            return $resultado;

        } catch (Exception $exc) {
            throw $exc;
        }

    }


    public function retiraDuplicidade($params)
    {
        try {
            $sql = "
                DELETE from agepnet200.tb_parteinteressada
                WHERE idprojeto = :idprojeto
                AND nomparteinteressada in
                  ( select nomparteinteressada 
                        from agepnet200.tb_parteinteressada
                   group by nomparteinteressada
                   having Count(nomparteinteressada)>1 ) ";

            $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params));

            return $resultado;

        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function retiraDuplicidadePorIdPessInterna($params)
    {
        try {
            $sql = "
                DELETE from agepnet200.tb_parteinteressada
                WHERE idprojeto = :idprojeto
                AND idpessoainterna = :idpessoainterna
                AND nomparteinteressada in
                  ( select nomparteinteressada 
                        from agepnet200.tb_parteinteressada
                   group by nomparteinteressada
                   having Count(nomparteinteressada)>1 )
               AND idpessoainterna in
                  ( select idpessoainterna 
                        from agepnet200.tb_parteinteressada
                   group by idpessoainterna
                   having Count(idpessoainterna)>1 )
               AND idprojeto in
                  ( select idprojeto 
                        from agepnet200.tb_parteinteressada
                   group by idprojeto
                   having Count(idprojeto)>1 ) ";

            $resultado = $this->_db->fetchAll($sql, array(
                'idpessoainterna' => $params['idpessoainterna'],
                'idprojeto' => $params['idprojeto']
            ));

            return $resultado;

        } catch (Exception $exc) {
            throw $exc;
        }
    }


    public function verificaPartesInteressadasDuplicadas($params)
    {
        try {
            $resultado = null;
            $sql = "
                SELECT nomparteinteressada, idprojeto, idpessoainterna 
                FROM agepnet200.tb_parteinteressada 
                WHERE idprojeto = :idprojeto 
                GROUP BY nomparteinteressada, idprojeto, idpessoainterna
                HAVING COUNT(nomparteinteressada) > 1 ";

            if (isset($params['idprojeto'])) {
                $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));
            } elseif (isset($params['idprojetoexterno'])) {
                $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojetoexterno']));
            }
            return $resultado;

        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @param array $params
     * @return array
     */
    public function fetchPairsPorProjeto($params)
    {
        $sql = "SELECT
                  idparteinteressada,
                  nomparteinteressada
                FROM
                    agepnet200.tb_parteinteressada
                WHERE idprojeto = :idprojeto 
                  AND status = TRUE
                ORDER BY nomparteinteressada";

        return $this->_db->fetchPairs($sql, array('idprojeto' => $params['idprojeto']));
    }


    /**
     * Retorna os valores da parte interessada interna
     * @param array $params
     * @return array
     */
    public function buscarParteInteressadaInterna($params)
    {
        $sql = "
                        SELECT
                              pin.idparteinteressada,
                              pin.idprojeto,
                              pin.nomparteinteressada,
                              (
                                SELECT ARRAY_TO_STRING(ARRAY_AGG(pf.nomfuncao),', ','') AS nomfuncao 
                                FROM agepnet200.tb_parteinteressada_funcoes f 
                                INNER JOIN agepnet200.tb_parteinteressadafuncao pf 
                                  ON pf.idparteinteressadafuncao = f.idparteinteressadafuncao 
                                WHERE f.idparteinteressada = pin.idparteinteressada
                              ) AS nomfuncao,
                              pin.destelefone,
                              pin.desemail,
                              pin.domnivelinfluencia,
                              pin.idcadastrador,
                              pin.datcadastro,
                              pin.idpessoainterna,
                              pin.observacao
                        FROM
                              agepnet200.tb_parteinteressada pin
                        WHERE pin.idpessoainterna = :idpessoainterna
                          AND pin.idprojeto = :idprojeto 
                          AND status = TRUE";

        $resultado = $this->_db->fetchRow($sql, array(
                'idpessoainterna' => $params['idpessoainterna'],
                'idprojeto' => $params['idprojeto']
            )
        );

        return $resultado;
    }


    /**
     * Retorna os valores da parte interessada interna ou externa
     * @param array $params
     * @return array
     */
    public function getParteInteressada($params)
    {
        $condition = "";

        if (isset($params['idprojeto']) && !empty($params['idprojeto'])) {
            $condition .= " AND p.idprojeto = {$params['idprojeto']}";
        }

        if (isset($params['idparteinteressada']) && !empty($params['idparteinteressada'])) {
            $condition .= " AND pin.idparteinteressada = {$params['idparteinteressada']}";
        }

        if (isset($params['idpessoainterna']) && !empty($params['idpessoainterna'])) {
            $condition .= " AND pin.idpessoainterna = {$params['idpessoainterna']}";
        }

        if (isset($params['idparteinteressadafuncao']) && !empty($params['idparteinteressadafuncao'])) {
            $condition .= " AND pif.idparteinteressadafuncao = {$params['idparteinteressadafuncao']}";
        }

        $sql = "SELECT pin.idparteinteressada,
                       pin.idprojeto,
                       pin.nomparteinteressada AS nomparteinteressada,
                       ARRAY_TO_STRING(ARRAY_AGG(DISTINCT pif.nomfuncao), ', ', '') AS nomfuncao, 
                       pin.destelefone,
                       pin.desemail, 
                       pin.domnivelinfluencia,
                       pin.tppermissao, 
                       pin.idcadastrador, 
                       pin.datcadastro, 
                       pin.idpessoainterna, 
                       pin.observacao,
                       ARRAY_TO_STRING(ARRAY_AGG(pif.idparteinteressadafuncao), ',', '') AS idparteinteressadafuncao,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 1 THEN 1 ELSE 0 END) > 0 AS is_gerenteprojeto,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 2 THEN 1 ELSE 0 END) > 0 AS is_gerenteadjunto,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 3 THEN 1 ELSE 0 END) > 0 AS is_demandante,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 4 THEN 1 ELSE 0 END) > 0 AS is_patrocinador,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 5 THEN 1 ELSE 0 END) > 0 AS is_parteinteressada,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 6 THEN 1 ELSE 0 END) > 0 AS is_equipeprojeto
                  FROM agepnet200.tb_parteinteressada pin 
                  JOIN agepnet200.tb_projeto p 
                    ON p.idprojeto = pin.idprojeto
                  LEFT JOIN agepnet200.tb_parteinteressada_funcoes piff
                    ON piff.idparteinteressada = pin.idparteinteressada
                  LEFT JOIN agepnet200.tb_parteinteressadafuncao pif
                    ON pif.idparteinteressadafuncao = piff.idparteinteressadafuncao  
                  LEFT JOIN agepnet200.tb_parteinteressada_funcoes pifv
                    ON pifv.idparteinteressada = pin.idparteinteressada
                  LEFT JOIN agepnet200.tb_pessoa pes 
                    ON pin.idpessoainterna = pes.idpessoa 
                 WHERE 1 = 1 {$condition} 
                   AND pin.status = TRUE
                 GROUP BY pin.nomparteinteressada, 
                          pin.desemail, 
                          pin.destelefone, 
                          pin.domnivelinfluencia, 
                          pin.idparteinteressada";


        return $this->_db->fetchRow($sql);
    }

    public function verificarPartesPorProjeto($params)
    {
        $sql = "SELECT CASE 
                            WHEN 
                                 p.idgerenteprojeto = pin.idpessoainterna OR  
                                 p.idgerenteadjunto = pin.idpessoainterna OR  
                                 p.iddemandante     = pin.idpessoainterna OR 
                                 p.idpatrocinador   = pin.idpessoainterna OR 
                                 pin.idpessoainterna IS NOT NULL 
                            THEN TRUE 
                            ELSE FALSE 
                        END AS is_parteinteressada     
                  FROM agepnet200.tb_projeto p
                  LEFT JOIN agepnet200.tb_parteinteressada pin
                    ON pin.idprojeto = p.idprojeto 
                   AND pin.idpessoainterna = :idpessoa 
                   AND pin.status = TRUE 
                 WHERE p.idprojeto = :idprojeto";
        if (isset($params['idpessoa']) && (!empty($params['idpessoa']))) {
            return $this->_db->fetchRow($sql,
                array('idprojeto' => $params['idprojeto'], 'idpessoa' => $params['idpessoa']));
        }
        return false;
    }

    public function buscaParteIntgeressadaByNome($noParte, $idprojeto)
    {
        $sql = "SELECT idparteinteressada,
                       idprojeto,
                       nomparteinteressada,
                       nomfuncao,
                       destelefone,
                       desemail,
                       domnivelinfluencia,
                       idcadastrador,
                       datcadastro,
                       idpessoainterna,
                       observacao                   
                  FROM agepnet200.tb_parteinteressada
                 WHERE idprojeto = :idprojeto
                   AND nomparteinteressada = :noParteInteressada 
                   AND status = TRUE";

        $resultado = $this->_db->fetchAll($sql, array('noParteInteressada' => $noParte, 'idprojeto' => $idprojeto));

        return $resultado;

    }

    public function buscaParteIntgeressadaByIdAndprojeto($params)
    {
        $sql = "SELECT 
                    p2.idparteinteressada,                                
                    p2.idprojeto                    
                  FROM agepnet200.tb_parteinteressada p1
                  JOIN agepnet200.tb_parteinteressada p2 
                    ON p2.nomparteinteressada=p1.nomparteinteressada
                   AND p2.idprojeto=p1.idprojeto
                   AND p2.status = TRUE
                  JOIN agepnet200.tb_projeto pr
                    ON pr.idprojeto=p1.idprojeto
                   AND pr.idtipoiniciativa = 1
                 WHERE p1.idprojeto = :idprojeto 
                   AND p1.idparteinteressada=:idparteinteressada 
                   AND p1.status = TRUE
                 GROUP BY p2.idparteinteressada,p2.idprojeto 
                 ORDER BY p2.idparteinteressada";

        $resultado = $this->_db->fetchAll($sql,
            array('idparteinteressada' => $params['id'], 'idprojeto' => $params['idprojeto']));

        return $resultado;

    }

    public function buscaParteInteressadaByProjeto($params)
    {

        $sql = "SELECT idparteinteressada 
                  FROM agepnet200.tb_parteinteressada 
                 WHERE idparteinteressada =:idparteinteressada 
                   AND idprojeto = :idprojeto 
                   AND status = TRUE";

        $resultado = $this->_db->fetchAll($sql,
            array('idparteinteressada' => $params['idparteinteressada'], 'idprojeto' => $params['idprojeto']));

        return $resultado;
    }

    public function buscaParteInteressadaPermissaoProjeto($params)
    {

        $sql = "SELECT idparteinteressada 
                  FROM agepnet200.tb_permissaoprojeto 
                 WHERE idparteinteressada =:idparteinteressada 
                   AND idprojeto = :idprojeto 
                   AND status = TRUE";

        $resultado = $this->_db->fetchAll($sql,
            array('idparteinteressada' => $params['idparteinteressada'], 'idprojeto' => $params['idprojeto']));

        return $resultado;
    }

    public function buscaParteInteressadaComunicacao($params)
    {
        $sql = "SELECT idresponsavel 
                  FROM agepnet200.tb_comunicacao 
                 WHERE idresponsavel =:idresponsavel 
                   AND idprojeto = :idprojeto 
                   AND status = TRUE";

        $resultado = $this->_db->fetchAll($sql,
            array('idresponsavel' => $params['idparteinteressada'], 'idprojeto' => $params['idprojeto']));

        return $resultado;
    }

    /**
     * Retorna os valores da parte interessada interna ou externa
     * @param array $params
     * @return array
     */
    public function retornaPorId($params, $model = false)
    {
        $sql = "SELECT pin.idparteinteressada,
                       pin.idprojeto,
                       pin.nomparteinteressada AS nomparteinteressada,
                       ARRAY_TO_STRING(ARRAY_AGG(DISTINCT pif.nomfuncao), ', ', '') AS nomfuncao, 
                       pin.destelefone,
                       pin.desemail, 
                       pin.domnivelinfluencia,
                       pin.tppermissao, 
                       pin.idcadastrador, 
                       pin.datcadastro, 
                       pin.idpessoainterna, 
                       pin.observacao,
                       ARRAY_TO_STRING(ARRAY_AGG(pif.idparteinteressadafuncao), ',', '') AS idparteinteressadafuncao,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 1 THEN 1 ELSE 0 END) > 0 AS is_gerenteprojeto,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 2 THEN 1 ELSE 0 END) > 0 AS is_gerenteadjunto,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 3 THEN 1 ELSE 0 END) > 0 AS is_demandante,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 4 THEN 1 ELSE 0 END) > 0 AS is_patrocinador,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 5 THEN 1 ELSE 0 END) > 0 AS is_parteinteressada,
                       SUM(CASE WHEN pifv.idparteinteressadafuncao = 6 THEN 1 ELSE 0 END) > 0 AS is_equipeprojeto
                  FROM agepnet200.tb_parteinteressada pin 
                  JOIN agepnet200.tb_projeto p 
                    ON p.idprojeto = pin.idprojeto
                  JOIN agepnet200.tb_parteinteressada_funcoes piff
                    ON piff.idparteinteressada = pin.idparteinteressada                    
                  JOIN agepnet200.tb_parteinteressadafuncao pif
                    ON pif.idparteinteressadafuncao = piff.idparteinteressadafuncao
                  JOIN agepnet200.tb_parteinteressada_funcoes pifv
                    ON pifv.idparteinteressada = pin.idparteinteressada
                  JOIN agepnet200.tb_pessoa pes 
                    ON pin.idpessoainterna = pes.idpessoa 
                 WHERE pin.idparteinteressada = :idparteinteressada 
                   AND pin.status = TRUE
                 GROUP BY pin.nomparteinteressada, 
			           pin.desemail, 
			           pin.destelefone, 
			           pin.domnivelinfluencia, 
			           pin.idparteinteressada";

        $resultado = $this->_db->fetchRow($sql, array('idparteinteressada' => $params['idparteinteressada']));

        if ($model) {
            return new Projeto_Model_Parteinteressada($resultado);
        }

        return $resultado;
    }

    public function isParteInteressada($params)
    {
        $sql = "SELECT count(idparteinteressada) AS total
                FROM agepnet200.tb_parteinteressada  
                WHERE idpessoainterna=:idpessoa 
                  AND idprojeto=:idprojeto 
                  AND status = TRUE";

        $resultado = $this->_db->fetchRow($sql,
            array('idpessoa' => $params['idpessoa'], 'idprojeto' => $params['idprojeto']));

        return ((int)$resultado['total'] > 0 ? true : false);
    }

    public function retornaParteInteressadaByProjeto($params, $model = false)
    {
        //print_r($params); exit;
        $sql = "SELECT
                        pin.idparteinteressada,
                        pin.idprojeto,
                        pin.nomparteinteressada,
                        pin.nomfuncao,
                        pin.destelefone,
                        pin.desemail,
                        pin.domnivelinfluencia,
                        pin.idcadastrador,
                        to_char(pin.datcadastro,'DD/MM/YYYY') AS datcadastro,
                        pin.idpessoainterna,
                        pin.observacao
                  FROM agepnet200.tb_parteinteressada pin
                 WHERE pin.idparteinteressada = :idparteinteressada
                   AND pin.idprojeto= :idprojeto 
                   AND pin.status = TRUE";

        $resultado = $this->_db->fetchRow($sql,
            array(
                'idparteinteressada' => $params['idparteinteressada'],
                'idprojeto' => $params['idprojeto']
            ));

        if ($model) {
            return new Projeto_Model_Parteinteressada($resultado);
        }

        return $resultado;
    }

    public function buscaParteInteressadaInterna($params)
    {
        $sql = "SELECT
                        pin.idparteinteressada,
                        pin.idprojeto,
                        pin.nomparteinteressada,
                        pin.nomfuncao,
                        pin.destelefone,
                        pin.desemail,
                        pin.domnivelinfluencia,
                        pin.idcadastrador,
                        pin.datcadastro,
                        pin.idpessoainterna,
                        pin.observacao,
                        pin.tppermissao
                  FROM agepnet200.tb_parteinteressada pin
                 WHERE pin.idpessoainterna = :idpessoainterna 
                   AND pin.idprojeto= :idprojeto 
                   AND pin.status = TRUE";

        $resultado = $this->_db->fetchRow($sql,
            array(
                'idpessoainterna' => $params['idpessoainterna'],
                'idprojeto' => $params['idprojeto']
            ));

        return new Projeto_Model_Parteinteressada($resultado);

    }


    public function buscaParteInteressadaExterna($params)
    {
        $sql = "SELECT
                        pin.idparteinteressada,
                        pin.idprojeto,
                        pin.nomparteinteressada,
                        pin.nomfuncao,
                        pin.destelefone,
                        pin.desemail,
                        pin.domnivelinfluencia,
                        pin.idcadastrador,
                        pin.datcadastro,
                        pin.idpessoainterna,
                        pin.observacao
                   FROM agepnet200.tb_parteinteressada pin
                  WHERE pin.nomparteinteressada LIKE (:nomparteinteressada)
                    AND pin.idprojeto= :idprojeto 
                    AND pin.status = TRUE";

        $resultado = $this->_db->fetchRow($sql,
            [
                'nomparteinteressada' => $params['nomparteinteressada'],
                'idprojeto' => $params['idprojeto']
            ]);

        return new Projeto_Model_Parteinteressada($resultado);

    }


    /**
     * Retorna os valores da parte interessada interna ou externa
     * @param array $params
     * @return array
     */

    public function retornaPartes($params, $model = false)
    {

        $sql = "SELECT pi.idprojeto,
                       pi.idpessoainterna,
                       pi.nomparteinteressada,
                       pi.idcadastrador,
                       to_char(pi.datcadastro, 'DD/MM/YYYY') AS datcadastro,
                       pi.desemail,
                       pi.destelefone,
                       pi.domnivelinfluencia,
                       (SELECT ARRAY_TO_STRING(ARRAY_AGG(pf.nomfuncao),', ','') AS nomfuncao
                          FROM agepnet200.tb_parteinteressada_funcoes f 
                        INNER JOIN agepnet200.tb_parteinteressadafuncao pf
                                ON pf.idparteinteressadafuncao = f.idparteinteressadafuncao
                         WHERE f.idparteinteressada = pi.idparteinteressada
                       ) AS nomfuncao,
                       pi.observacao,
                       pi.idparteinteressada
                  FROM agepnet200.tb_parteinteressada pi                
                 WHERE pi.idprojeto = :idprojeto::INTEGER 
                   AND pi.status = TRUE ";

        if (isset($params['nomparteinteressadapesquisar']) && (!empty($params['nomparteinteressadapesquisar']))) {
            $sql .= "AND UPPER(pi.nomparteinteressada) ILIKE '%{$params['nomparteinteressadapesquisar']}%' ";
        }

        if (isset($params['idparteinteressada']) && (!empty($params['idparteinteressada']))) {
            $sql .= "AND pi.idparteinteressada IN({$params['idparteinteressada']}) ";
        }
        $sql .= "ORDER BY pi.nomparteinteressada";

        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));

        if ($model) {
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Parteinteressada');

            foreach ($resultado as $r) {
                $status = new Projeto_Model_Parteinteressada($r);
                $collection[] = $status;
            }
            return $collection;
        }
        return $resultado;
    }


    /**
     * Retorna os valores da parte interessada interna
     * @param array $params
     * @return array
     */
    public function retornaPartesInternas($params, $model = false)
    {
        $sql = "SELECT
                    pin.idparteinteressada,
                    pin.idprojeto,
                    pin.idpessoainterna,
                    pin.nomparteinteressada,
                    (
                      SELECT ARRAY_TO_STRING(ARRAY_AGG(pf.nomfuncao),', ','') AS nomfuncao 
                       FROM agepnet200.tb_parteinteressada_funcoes f 
                       INNER JOIN agepnet200.tb_parteinteressadafuncao pf 
                         ON pf.idparteinteressadafuncao = f.idparteinteressadafuncao 
                       WHERE f.idparteinteressada = pin.idparteinteressada
                    ) AS nomfuncao,
                    pin.destelefone,
                    pin.desemail,
                    pin.domnivelinfluencia,
                    pin.idcadastrador,
                    to_char(pin.datcadastro,'DD/MM/YYYY') AS datcadastro,
                    pin.idpessoainterna,
                    pin.observacao
                  FROM
                    agepnet200.tb_parteinteressada pin                   
                  WHERE pin.idprojeto = :idprojeto 
                    AND pin.status = TRUE ";

        if (isset($params['idpessoainterna']) && (!empty($params['idpessoainterna']))) {
            $sql .= "AND pin.idpessoainterna IN({$params['idpessoainterna']}) ";
        }

        if (isset($params['idparteinteressada']) && (!empty($params['idparteinteressada']))) {
            $sql .= "AND pin.idparteinteressada IN({$params['idparteinteressada']})";
        }

        $sql .= "ORDER BY pin.nomparteinteressada";


        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));

        if ($model) {
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Parteinteressada');

            foreach ($resultado as $r) {
                $status = new Projeto_Model_Parteinteressada($r);
                $collection[] = $status;
            }
            return $collection;
        }
        return $resultado;
    }

    public function retornaPartesGrid($params)
    {
        $sqlCondition = "";
        if (isset($params['nomparteinteressadapesquisar']) && $params['nomparteinteressadapesquisar'] != '') {
            $sqlCondition .= " AND UPPER( pin.nomparteinteressada) LIKE '%" . strtoupper($params['nomparteinteressadapesquisar']) . "%' ";
        }
        if (isset($params['nomfuncaopesquisar']) && $params['nomfuncaopesquisar'] != '') {
            $sqlCondition .= " AND UPPER( pif.nomfuncao) LIKE '%" . strtoupper($params['nomfuncaopesquisar']) . "%' ";
        }
        if (isset($params['destelefonepesquisar']) && $params['destelefonepesquisar'] != '') {
            $sqlCondition .= " AND pin.destelefone = '{$params['destelefonepesquisar']}'";
        }
        if (isset($params['desemailpesquisar']) && $params['desemailpesquisar'] != '') {
            $sqlCondition .= " AND pin.desemail = '{$params['desemailpesquisar']}'";
        }
        if (isset($params['domnivelinfluenciapesquisar']) && $params['domnivelinfluenciapesquisar'] != '') {
            $sqlCondition .= " AND pin.domnivelinfluencia =  '{$params['domnivelinfluenciapesquisar']}'";
        }

        $sql = "SELECT pin.nomparteinteressada AS nomparteinteressada,
                       ARRAY_TO_STRING(ARRAY_AGG(DISTINCT pif.nomfuncao), ', ', '') AS nomfuncao, 
                       pin.desemail, 
                       pin.destelefone,
                       pin.domnivelinfluencia,
                       pin.idparteinteressada,
                       pin.idprojeto,
                       CASE 
                        WHEN pin.tppermissao = '1' THEN 'Editar'
                        WHEN pin.tppermissao = '2' THEN 'Visualizar'
                    END AS tppermissao, 
                       pin.idcadastrador, 
                       TO_CHAR(pin.datcadastro, 'DD/MM/YYYY') AS datcadastro, 
                       pin.idpessoainterna, 
                       pin.observacao,
                       ARRAY_TO_STRING(ARRAY_AGG(pif.idparteinteressadafuncao), ',', '') AS idparteinteressadafuncao
                  FROM agepnet200.tb_parteinteressada pin  
                  JOIN agepnet200.tb_projeto p 
                    ON p.idprojeto = pin.idprojeto
                  JOIN agepnet200.tb_parteinteressada_funcoes piff
                    ON piff.idparteinteressada = pin.idparteinteressada
                  JOIN agepnet200.tb_parteinteressadafuncao pif
                    ON pif.idparteinteressadafuncao = piff.idparteinteressadafuncao
                  LEFT JOIN agepnet200.tb_pessoa pes 
                    ON pin.idpessoainterna = pes.idpessoa 
                 WHERE pin.idprojeto = {$params['idprojeto']} {$sqlCondition} 
                   AND pin.status = TRUE
                 GROUP BY pin.nomparteinteressada, 
			           pin.desemail, 
			           pin.destelefone, 
			           pin.domnivelinfluencia, 
			           pin.idparteinteressada 
			     ORDER BY {$params['sidx']} {$params['sord']}";


        $page = (isset($params['page'])) ? $params['page'] : 1;
        $limit = (isset($params['rows'])) ? $params['rows'] : 20;
        $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function parteInteressadaGrid($params, $paginator = false)
    {
        $params = array_filter($params);
        $sql = "SELECT pint.idparteinteressada, pint.nomparteinteressada
                  FROM agepnet200.tb_parteinteressada pint
                 WHERE pint.idprojeto = " . (int)$params['idprojeto']."
                   AND pint.status = TRUE ";

        if (isset($params['nomparteinteressada'])) {
            $strInteressado = strtoupper($params['nomparteinteressada']);
            $sql .= " AND upper(pint.nomparteinteressada) LIKE '%{$strInteressado}%' ";
        }

        $sql .= ' order by pint.nomparteinteressada ';

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }
        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));
        return $resultado;
    }

    /**
     * @param array $params
     * @return array
     */
    public function setById($params)
    {
        $sql = "SELECT 
                       pin.idparteinteressada,
                       pin.idprojeto,
                       pin.nomparteinteressada,
                       (SELECT ARRAY_TO_STRING(ARRAY_AGG(pf.nomfuncao),', ','') AS nomfuncao
                          FROM agepnet200.tb_parteinteressada_funcoes f
                        INNER JOIN agepnet200.tb_parteinteressadafuncao pf
                                ON pf.idparteinteressadafuncao = f.idparteinteressadafuncao
                         WHERE f.idparteinteressada = pin.idparteinteressada
                       ) AS nomfuncao,
                       pin.destelefone,
                       pin.desemail,
                       pin.domnivelinfluencia,
                       pin.idpessoainterna,
                       pin.observacao,
                       pin.tppermissao
                  FROM agepnet200.tb_parteinteressada pin
                 WHERE pin.idparteinteressada = :idparteinteressada 
                   AND pin.status = TRUE ";

        $resultado = $this->_db->fetchAll($sql, array('idparteinteressada' => $params['idparteinteressada']));

        return $resultado;
    }

    /**
     * Método que retorna os dados da parte interessada de acordo com a atividade do projeto.
     * @param $idProjeto
     * @param $idAtividade
     * @return array
     */
    public function parteInteressadaPorAtividade($idProjeto, $idAtividade)
    {
        $sql = "SELECT * 
                  FROM agepnet200.tb_parteinteressada 
                 WHERE idparteinteressada = (SELECT idparteinteressada
                                               FROM agepnet200.tb_atividadecronograma ac 
                                              WHERE ac.idatividadecronograma = $idAtividade 
                                                AND ac.idprojeto = $idProjeto 
                                            ) 
                   AND status = TRUE";
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function retornaIdPartaPorprojeto($params)
    {
        $sql = "SELECT
                    pin.idparteinteressada,
                    pin.idpessoainterna
                  FROM agepnet200.tb_parteinteressada pin
                INNER JOIN agepnet200.tb_projeto p ON p.idprojeto = pin.idprojeto
                 WHERE pin.idprojeto = {$params['idprojeto']} 
                   AND pin.status = TRUE";
        if (isset($params['idpessoainterna'])) {
            $sql .= " AND pin.idpessoainterna = {$params['idpessoainterna']} ";
        }
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function excluirPelaInfoIniciais($params, $model = false)
    {
        try {
            $sqlParte       = "UPDATE agepnet200.tb_parteinteressada SET status = FALSE ";
            $sqlParteFuncao = "DELETE FROM agepnet200.tb_parteinteressada_funcoes ";

            if (isset($params['idprojeto']) != null) {

                if ($params['nomdemandante'] == "Não detalhado" &&
                    $params['nomgerenteadjunto'] != "Não detalhado") {

                    $sqlParteDeman  = " WHERE idprojeto = :idprojeto AND idparteinteressada = :idDeman ";
                    $sqlFuncaoDeman = " WHERE idparteinteressada = :idDeman ";

                    $res = $this->_db->fetchAll($sqlParteFuncao.$sqlFuncaoDeman, array(
                            'idprojeto' => $params['idprojeto'],
                            'idDeman'   => $params['idDeman']
                        )
                    );

                    $resultado = $this->_db->fetchAll($sqlParte.$sqlParteDeman, array(
                            'idprojeto' => $params['idprojeto'],
                            'idDeman'   => $params['idDeman']
                        )
                    );
                    return $resultado;

                } elseif ($params['nomgerenteadjunto'] == "Não detalhado" &&
                    $params['nomdemandante'] != "Não detalhado") {

                    $sqlParteAdjunto  = " WHERE idprojeto = :idprojeto AND idparteinteressada = :idadjunto";
                    $sqlFuncaoAdjunto = " WHERE idparteinteressada = :idadjunto ";

                    $res = $this->_db->fetchAll($sqlParteFuncao.$sqlFuncaoAdjunto, array(
                            'idprojeto' => $params['idprojeto'],
                            'idDeman'   => $params['idDeman']
                        )
                    );

                    $resultado = $this->_db->fetchAll($sqlParte.$sqlParteAdjunto, array(
                            'idprojeto' => $params['idprojeto'],
                            'idadjunto' => $params['idadjunto']
                        )
                    );
                    return $resultado;

                } else {
                    $sql3       = " WHERE idprojeto = :idprojeto  AND idparteinteressada IN(:idDeman, :idadjunto) ";
                    $sqlFuncoes = " WHERE idparteinteressada IN(:idDeman, :idadjunto) ";

                    $res = $this->_db->fetchAll($sqlParteFuncao.$sqlFuncoes, array(
                            'idprojeto' => $params['idprojeto'],
                            'idDeman'   => $params['idDeman'],
                            'idadjunto' => $params['idadjunto']
                        )
                    );

                    $resultado = $this->_db->fetchAll($sqlParte.$sql3, array(
                            'idprojeto' => $params['idprojeto'],
                            'idDeman'   => $params['idDeman'],
                            'idadjunto' => $params['idadjunto']
                        )
                    );
                    return $resultado;
                }

            }
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function retornaFuncaoPorprojeto($params)
    {
        if (isset($params['idprojetoexterno'])) {
            $idprojeto = $params['idprojetoexterno'];
            $idparteinteressada = $params['idparteinteressadaexterno'];
        } else {
            $idprojeto = $params['idprojeto'];
            $idparteinteressada = $params['idparteinteressada'];
        }

        $sql = "SELECT
                    (
                      SELECT ARRAY_TO_STRING(ARRAY_AGG(pf.nomfuncao),', ','') AS nomfuncao 
                       FROM agepnet200.tb_parteinteressada_funcoes f 
                       INNER JOIN agepnet200.tb_parteinteressadafuncao pf 
                         ON pf.idparteinteressadafuncao = f.idparteinteressadafuncao 
                       WHERE f.idparteinteressada = pin.idparteinteressada
                    ) AS nomfuncao,
                FROM agepnet200.tb_parteinteressada pin
                WHERE pin.idprojeto = {$idprojeto} 
                  AND pin.idparteinteressada = {$idparteinteressada} 
                  AND pin.status = TRUE";

        $return = $this->_db->fetchRow($sql);
        return $return;
    }

    public function atualizarFuncaoRhProjeto($params)
    {
        $sql = 'SELECT agepnet200."AtualizarFuncaoRhProjeto"(:idprojeto, :idcadastrador, :idpessoaantiga, 
                                                             :idpessoanova, :idparteinteressadafuncao)';

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idcadastrador' => (int)$params['idcadastrador'],
            'idpessoaantiga' => (int)$params['idpessoaantiga'],
            'idpessoanova' => (int)$params['idpessoanova'],
            'idparteinteressadafuncao' => (int)$params['idparteinteressadafuncao'],
        ));
        return $resultado;
    }
}
