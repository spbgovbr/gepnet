<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Planejamento_Model_Mapper_Portfolio extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Planejamento_Model_Portfolio
     */
    public function insert(Planejamento_Model_Portfolio $model)
    {

        $this->_db->beginTransaction();

        try {

            //var_dump($model); exit;

            $model->idportfolio = $this->maxVal('idportfolio');

            $data = array(
                "idportfolio" => $model->idportfolio,
                "noportfolio" => $model->noportfolio,
                "idportfoliopai" => $model->idportfoliopai,
                "ativo" => $model->ativo,
                "tipo" => $model->tipo,
                "idresponsavel" => $model->idresponsavel,
                "idescritorio" => (string)$model->idescritorio,
            );

            $data = array_filter($data, 'strlen');
            $retorno = $this->getDbTable()->insert($data);

            if (count($model->idprograma) > 0) {
                $mapperPortProg = new Planejamento_Model_Mapper_Portfolioprograma();
                foreach ($model->idprograma as $programa) {
                    $modelPortProg = new Planejamento_Model_Portfolioprograma(array(
                        'idportfolio' => $model->idportfolio,
                        'idprograma' => $programa
                    ));
                    $insere = $mapperPortProg->insert($modelPortProg);
                }
            }
            $this->_db->commit();
            return $retorno;

        } catch (Exception $exc) {
            $this->_db->rollBack();
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Planejamento_Model_Portfolio
     */
    public function update(Planejamento_Model_Portfolio $model)
    {
        $this->_db->beginTransaction();
        try {

            $data = array(
                "noportfolio" => $model->noportfolio,
                "idportfoliopai" => $model->idportfoliopai,
                "ativo" => $model->ativo,
                "tipo" => $model->tipo,
                "idresponsavel" => $model->idresponsavel,
                "idescritorio" => $model->idescritorio,
            );

            $data = array_filter($data);
            $pks = array(
                "idportfolio" => $model->idportfolio,
            );

            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);

            $modelPortP = new Planejamento_Model_Portfolioprograma(array(
                'idportfolio' => $model->idportfolio,
                'idprograma' => $model->idprograma
            ));
            $mapperPortP = new Planejamento_Model_Mapper_Portfolioprograma();
            $editarPortP = $mapperPortP->update($modelPortP);

            $this->_db->commit();
            return $retorno;
        } catch (Exception $exc) {
            $this->_db->rollBack();
            throw $exc;
        }
    }

    public function fetchPairs()
    {
        $sql = " SELECT idportfolio, noportfolio FROM agepnet200.tb_portfolio
                 where ativo = 'S' order by noportfolio asc";
        return $this->_db->fetchPairs($sql);
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */

    /// by @Danilo
    // Busca pesquisa por id escritorio
    public function pesquisaPortfolio($params, $paginator = false)
    {
        $sql = "SELECT distinct
                    proj.idescritorio
                    from agepnet200.tb_projeto proj
                    --inner join  agepnet200.tb_projeto proj on esc.idescritorio = proj.idescritorio
                where 
			1=1
    		";

        //Zend_Debug::dump($params);
        if (isset($params['nomprojeto']) && $params['nomprojeto'] != '') {
            $nomprojeto = $params['nomprojeto'];
            $sql .= " AND proj.nomprojeto like '%{$nomprojeto}%'";
        }
        if (isset($params['idacao']) && $params['idacao'] != '') {
            $idacao = $params['idacao'];
            $sql .= " AND proj.idacao = {$idacao} ";
        }
        if (isset($params['idsetor']) && $params['idsetor'] != '') {
            $idsetor = $params['idsetor'];
            $sql .= " AND proj.idsetor = {$idsetor} ";
        }
        if (isset($params['idnatureza']) && $params['idnatureza'] != '') {
            $idnatureza = $params['idnatureza'];
            $sql .= " AND proj.idnatureza  = {$idnatureza}";
        }
        if (isset($params['idobjetivo']) && $params['idobjetivo'] != '') {
            $idobjetivo = $params['idobjetivo'];
            $sql .= " AND proj.idobjetivo  = {$idobjetivo}";
        }
        if (isset($params['idprograma']) && $params['idprograma'] != '') {
            $idprograma = $params['idprograma'];
            $sql .= " AND proj.idprograma  = {$idprograma}";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

        //Zend_Debug::dump($sql);exit;
        $resultado = $this->_db->fetchAll($sql);
//     	Zend_Debug::dump($resultado);exit;
        return $resultado;
    }


    public function pesquisarPortfolio($params, $paginator = false)
    {
        $sql = " SELECT
                            p.noportfolio,
                            (SELECT pes.nompessoa FROM agepnet200.tb_pessoa pes WHERE pes.idpessoa = p.idresponsavel) as idresponsavel,
                            CASE p.tipo
                              WHEN 1 THEN 'NORMAL'
                              WHEN 2 THEN 'ESTRATÉGICO'
                            END AS tipo,
                            e.nomescritorio as nomescritorio,
                            e.desemail as email,
                            e.numfone as telefone,
                            p.ativo,
                            p.idportfolio as idportfolio
                    FROM
                            agepnet200.tb_portfolio p,
                            agepnet200.tb_escritorio e
                    WHERE 
                            p.idescritorio = e.idescritorio
    		";

        //Zend_Debug::dump($params);
        if (isset($params['idescritorio']) && $params['idescritorio'] != '') {
            $idescritorio = $params['idescritorio'];
            $sql .= " AND p.idescritorio  = {$idescritorio} ";
        }
        $params = array_filter($params);
        if (isset($params['tipo'])) {
            $tipo = $params['tipo'];
            $sql .= " AND p.tipo = {$tipo}";
        }
        if (isset($params['ativo'])) {
            $ativo = strtoupper($params['ativo']);
            $sql .= " AND UPPER(p.ativo) like '%{$ativo}%'";
        }
        if (isset($params['noportfolio'])) {
            $noportfolio = strtoupper($params['noportfolio']);
            $sql .= " AND UPPER(p.noportfolio) LIKE '%{$noportfolio}%' ";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

        //Zend_Debug::dump($sql);exit;

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
//     	Zend_Debug::dump($resultado);exit;
        return $resultado;
    }

    public function pesquisarIdPrograma($params)
    {

        $sql = "SELECT distinct
                       
                        proj.idprograma 
                FROM
                        agepnet200.tb_projeto proj
                WHERE 
                      1 = 1";

        if (isset($params['idprojeto']) && (!empty($params['idprojeto']))) {
            $idprojeto = $params['idprojeto'];
            $sql .= " and proj.idprojeto not in({$idprojeto}) ";
        }

        if (isset($params['idescritorio']) && (!empty($params['idescritorio']))) {
            $idescritorio = $params['idescritorio'];
            $sql .= " and proj.idescritorio = {$idescritorio}";
        }
        $params = array_filter($params);

        if (isset($params['nomprojeto']) && (!empty($params['nomprojeto']))) {
            $nomprojeto = $params['nomprojeto'];
            $sql .= " and proj.nomprojeto LIKE '%{$nomprojeto}%'";
        }

        if (isset($params['idprograma']) && (!empty($params['idprograma']))) {
            $idprograma = $params['idprograma'];
            $sql .= " and proj.idprograma = {$idprograma}";
        }

        if (isset($params['sidx']) && (!empty($params['sidx']))) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }
        // Zend_Debug::dump($sql);

        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function getPortfolioById($params)
    {

        $sql = "
                 SELECT
                        port.idportfolio,
                        port.noportfolio,
                        port.idportfoliopai,
                        port.ativo,
                        port.tipo,
                        port.idresponsavel,
                        port.idescritorio,
                        pes.nompessoa as nomresponsavel,
                        esc.nomescritorio as nomescritorio,
                        (select noportfolio from agepnet200.tb_portfolio port2 where port2.idportfolio = port.idportfoliopai) as noportfoliopai,
                        CASE
                          WHEN ativo = 'S' THEN 'ATIVO'
                          WHEN ativo = 'N' THEN 'INATIVO'
                        END as desativo,
                        CASE
                          WHEN tipo = 1 THEN 'NORMAL'
                          WHEN tipo = 2 THEN 'ESTRATÉGICO'
                        END as destipo
                 FROM
                        agepnet200.tb_portfolio port,
                        agepnet200.tb_escritorio esc,
                        agepnet200.tb_pessoa pes
                 WHERE
                        port.idescritorio = esc.idescritorio
                        and pes.idpessoa = port.idresponsavel
                        and port.idportfolio = :idportfolio
                ";

        $resultado = $this->_db->fetchRow($sql, array('idportfolio' => $params['idportfolio']));
        $portfolio = new Planejamento_Model_Portfolio($resultado);
        return $portfolio;
    }


}

