<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Acordocooperacao_Model_Mapper_Acordo extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Acordocooperacao_Model_Acordo
     */
    public function insert(Acordocooperacao_Model_Acordo $model)
    {

//        Zend_Debug::dump($model);
//        exit;

        $model->idacordo = $this->maxVal('idacordo');
        $model->flasituacaoatual = '2';
        $model->flarescindido = 'N';
        if (empty($model->numprazovigencia)) {
            $model->numprazovigencia = 0;
        }
        $data = array(
            "idacordo" => $model->idacordo,
            "idacordopai" => $model->idacordopai,
            "idtipoacordo" => $model->idtipoacordo,
            "nomacordo" => $model->nomacordo,
            "idresponsavelinterno" => $model->idresponsavelinterno,
            "destelefoneresponsavelinterno" => $model->destelefoneresponsavelinterno,
            "idsetor" => $model->idsetor,
            "idfiscal" => $model->idfiscal,
            "destelefonefiscal" => $model->destelefonefiscal,
            "despalavrachave" => $model->despalavrachave,
            "desobjeto" => $model->desobjeto,
            "desobservacao" => $model->desobservacao,
//            "datatualizacao"                => new Zend_Db_Expr("to_date('" . $model->datatualizacao->toString('Y-m-d') . "','YYYY-MM-DD')"),
//            "datatualizacao"                => new Zend_Db_Expr("now()"),
            "datcadastro" => new Zend_Db_Expr("now()"),
            "numprazovigencia" => (int)$model->numprazovigencia,
            "idcadastrador" => $model->idcadastrador,
            "flarescindido" => $model->flarescindido,
//            "flasituacaoatual"              => $model->flasituacaoatual,
            "flasituacaoatual" => $model->flasituacaoatual,
            "numsiapro" => $model->numsiapro,
            "descontatoexterno" => $model->descontatoexterno,
            "idfiscal2" => $model->idfiscal2,
            "idfiscal3" => $model->idfiscal3,
            "descargofiscal" => $model->descargofiscal,
        );

//        Zend_Debug::dump($model->datassinatura);
//        Zend_Debug::dump($model->datiniciovigencia);
//        Zend_Debug::dump($model->datfimvigencia);
//        Zend_Debug::dump($model->datpublicacao->toString('Y-m-d'));
//        exit;

        if ($model->datassinatura) {
            $data['datassinatura'] = new Zend_Db_Expr("to_date('" . $model->datassinatura->toString('Y-m-d') . "','YYYY-MM-DD')");
        }
        if ($model->datiniciovigencia) {
            $data['datiniciovigencia'] = new Zend_Db_Expr("to_date('" . $model->datiniciovigencia->toString('Y-m-d') . "','YYYY-MM-DD')");
        }
        if ($model->datfimvigencia) {
            $data['datfimvigencia'] = new Zend_Db_Expr("to_date('" . $model->datfimvigencia->toString('Y-m-d') . "','YYYY-MM-DD')");
        }
        if ($model->datpublicacao) {
            $data['datpublicacao'] = new Zend_Db_Expr("to_date('" . $model->datpublicacao->toString('Y-m-d') . "','YYYY-MM-DD')");
        }
        if ($model->descaminho) {
            $data['descaminho'] = $model->descaminho;
        }

        try {
            $this->getDbTable()->insert($data);
            return $model;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Acordocooperacao_Model_Acordo
     */
    public function update(Acordocooperacao_Model_Acordo $model)
    {
//        print('aqui');
//        Zend_Debug::dump($model->numprazovigencia); exit;
        $data = array(
            "idacordo" => $model->idacordo,
            "idacordopai" => $model->idacordopai,
            "idtipoacordo" => $model->idtipoacordo,
            "nomacordo" => $model->nomacordo,
            "idresponsavelinterno" => $model->idresponsavelinterno,
            "destelefoneresponsavelinterno" => $model->destelefoneresponsavelinterno,
            "idsetor" => $model->idsetor,
            "idfiscal" => $model->idfiscal,
            "destelefonefiscal" => $model->destelefonefiscal,
            "despalavrachave" => $model->despalavrachave,
            "desobjeto" => $model->desobjeto,
            "desobservacao" => $model->desobservacao,
//            "datassinatura"                 => $model->datassinatura,
//            "datiniciovigencia"             => $model->datiniciovigencia,
//            "datfimvigencia"                => $model->datfimvigencia,
//            "datpublicacao"                 => $model->datpublicacao,
            "numprazovigencia" => (int)$model->numprazovigencia,
            "datatualizacao" => new Zend_Db_Expr('now()'),
//            "datcadastro"                   => $model->datcadastro,
//            "idcadastrador"                 => $model->idcadastrador,
            "flarescindido" => $model->flarescindido,
//            "flasituacaoatual"              => $model->flasituacaoatual,
            "numsiapro" => $model->numsiapro,
            "descontatoexterno" => $model->descontatoexterno,
            "idfiscal2" => $model->idfiscal2,
            "idfiscal3" => $model->idfiscal3,
            "descargofiscal" => $model->descargofiscal,
        );

        if ($model->datassinatura) {
            $data['datassinatura'] = new Zend_Db_Expr("to_date('" . $model->datassinatura->toString('Y-m-d') . "','YYYY-MM-DD')");
        }
        if ($model->datiniciovigencia) {
            $data['datiniciovigencia'] = new Zend_Db_Expr("to_date('" . $model->datiniciovigencia->toString('Y-m-d') . "','YYYY-MM-DD')");
        }
        if ($model->datfimvigencia) {
            $data['datfimvigencia'] = new Zend_Db_Expr("to_date('" . $model->datfimvigencia->toString('Y-m-d') . "','YYYY-MM-DD')");
        }
        if ($model->datpublicacao) {
            $data['datpublicacao'] = new Zend_Db_Expr("to_date('" . $model->datpublicacao->toString('Y-m-d') . "','YYYY-MM-DD')");
        }
        if ($model->descaminho) {
            $data['descaminho'] = $model->descaminho;
        }
//         $this->getDbTable()->update($data, array("id = ?" => $model->idacordo));
        try {
            $pks = array("idacordo" => $model->idacordo);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }


    public function getForm()
    {
        return $this->_getForm(Acordocooperacao_Form_Acordo);
    }

    public function pesquisar($params, $paginator = false)
    {
//        Zend_Debug::dump($params); exit;
        $sql = "
                SELECT
                        ac.idacordo,
                        st.nomsetor,
                        ac.numsiapro,
                        ac.nomacordo,
                        p1.nompessoa as responsavelinterno,
                        p2.nompessoa as nomfiscal,
                        to_char(ac.datiniciovigencia, 'DD/MM/YYYY') as datiniciovigencia,
                        to_char(ac.datfimvigencia, 'DD/MM/YYYY') as datfimvigencia,
                        CASE
                          WHEN ac.flasituacaoatual = '1' THEN 'Vigente'
                          WHEN ac.flasituacaoatual = '2' THEN 'Proposta'
                          WHEN ac.flasituacaoatual = '3' THEN 'Vencido'
                          WHEN ac.flasituacaoatual = '4' THEN 'Rescindido'
                          ELSE ''
                        END as flasituacaoatual,
                        '' as pdf,
                        ac.idacordo,
                        ac.descaminho
                FROM
                        agepnet200.tb_acordo ac,
                        agepnet200.tb_setor st,
                        agepnet200.tb_pessoa p1,
                        agepnet200.tb_pessoa p2
                WHERE
                        ac.idsetor = st.idsetor
                        and ac.idresponsavelinterno = p1.idpessoa
                        and ac.idfiscal = p2.idpessoa
        ";

        $params = array_filter($params);

        if (isset($params['idacordo'])) {
            $sql .= " and ac.idacordo = {$params['idacordo']}";
        }
        if (isset($params['nomsetor'])) {
            $sql .= " and st.nomsetor like '%{$params['nomsetor']}%'";
        }
        if (isset($params['idsetor'])) {
            $sql .= " and st.idsetor = {$params['idsetor']}";
        }
        if (isset($params['nomacordo'])) {
            $sql .= " and ac.nomacordo like '%{$params['nomacordo']}%'";
        }
        if (isset($params['numsiapro'])) {
            $sql .= " and ac.numsiapro = {$params['numsiapro']}";
        }
        if (isset($params['reponsavelinterno'])) {
            $sql .= " and p1.nompessoa = {$params['reponsavelinterno']}";
        }
        if (isset($params['nomfiscal'])) {
            $sql .= " and p2.nomepessoa = {$params['nomfiscal']}";
        }
        if (isset($params['datiniciovigencia'])) {
            $sql .= " and ac.datiniciovigencia = {$params['datiniciovigencia']}";
        }
        if (isset($params['datfimvigencia'])) {
            $sql .= " and ac.datfimvigencia = {$params['datfimvigencia']}";
        }
        if (isset($params['flasituacaoatual'])) {
            $sql .= " and ac.flasituacaoatual = '{$params['flasituacaoatual']}'";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
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
//        Zend_Debug::dump($resultado);exit;
        return $resultado;
    }

    public function fetchPairs()
    {
        $sql = " SELECT idacordo, nomacordo FROM agepnet200.tb_acordo order by nomacordo asc";
        return $this->_db->fetchPairs($sql);
    }

    public function getById($params)
    {
        $sql = "
                SELECT
                  ac.idacordo,
                  st.nomsetor,
                  ac.numsiapro,
                  ac.nomacordo,
                  p1.nompessoa as nomresponsavelinterno,
                  ac.idresponsavelinterno,
                  p2.nompessoa as nomfiscal,
                  ac.idfiscal,
                  to_char(ac.datiniciovigencia, 'DD/MM/YYYY') as datiniciovigencia,
                  to_char(ac.datfimvigencia, 'DD/MM/YYYY') as datfimvigencia,
                  CASE
                  WHEN ac.flasituacaoatual = '1' THEN 'Vigente'
                  WHEN ac.flasituacaoatual = '2' THEN 'Proposta'
                  WHEN ac.flasituacaoatual = '3' THEN 'Vencido'
                  WHEN ac.flasituacaoatual = '4' THEN 'Rescindido'
                  ELSE ''
                  END as flasituacaoatual,
                  '' as pdf,
                  --ac.idacordo,
                  ac.descaminho,
                  ac.despalavrachave,
                  ac.flarescindido,
                  ac2.nomacordo as instrumentoprincipal,
                  p3.nompessoa as nomfiscal2,
                  ac.idfiscal2,
                  p4.nompessoa as nomfiscal3,
                  ac.idfiscal3,
                  ac.destelefoneresponsavelinterno,
                  ac.destelefonefiscal,
                  ac.descargofiscal,
                  ac.datassinatura,
                  ac.datatualizacao,
                  ac.datcadastro,
                  ac.datpublicacao,
                  ac.numprazovigencia,
                  ac.desobjeto,
                  ac.despalavrachave,
                  ac.descontatoexterno
                FROM agepnet200.tb_acordo ac
                  LEFT OUTER JOIN agepnet200.tb_acordo ac2 ON ac2.idacordopai = ac.idacordo
                  LEFT OUTER JOIN agepnet200.tb_setor st ON ac.idsetor = st.idsetor
                  LEFT OUTER JOIN agepnet200.tb_pessoa p1 ON ac.idresponsavelinterno = p1.idpessoa
                  LEFT OUTER JOIN agepnet200.tb_pessoa p2 ON ac.idfiscal = p2.idpessoa
                  LEFT OUTER JOIN agepnet200.tb_pessoa p3 ON ac.idfiscal2 = p3.idpessoa
                  LEFT OUTER JOIN agepnet200.tb_pessoa p4 ON ac.idfiscal3 = p4.idpessoa
                WHERE
                   ac.idacordo = :idacordo
        ";

        $resultado = $this->_db->fetchRow($sql, array('idacordo' => $params['idacordo']));
//        Zend_Debug::dump($resultado); exit;
//        return new Acordocooperacao_Model_Acordo($resultado);
        return $resultado;
    }

    public function getByIdDetalhar($params)
    {
        $sql = "
                SELECT
                  ac.idacordo,
                  st.nomsetor,
                  ac.numsiapro,
                  ac.nomacordo,
                  p1.nompessoa as responsavelinterno,
                  p2.nompessoa as nomfiscal,
                  to_char(ac.datiniciovigencia, 'DD/MM/YYYY') as datiniciovigencia,
                  to_char(ac.datfimvigencia, 'DD/MM/YYYY') as datfimvigencia,
                  CASE
                  WHEN ac.flasituacaoatual = '1' THEN 'Vigente'
                  WHEN ac.flasituacaoatual = '2' THEN 'Proposta'
                  WHEN ac.flasituacaoatual = '3' THEN 'Vencido'
                  WHEN ac.flasituacaoatual = '4' THEN 'Rescindido'
                  ELSE ''
                  END as flasituacaoatual,
                  '' as pdf,
                  --ac.idacordo,
                  ac.descaminho,
                  ac.despalavrachave,
                  CASE
                    WHEN ac.flarescindido = 'N' THEN 'NÃO'
                    WHEN ac.flarescindido = 'S' THEN 'SIM'
                  ELSE ''
                  END as flarescindido,
                  ac2.nomacordo as instrumentoprincipal,
                  p3.nompessoa as nomfiscal2,
                  p4.nompessoa as nomfiscal3,
                  ac.destelefoneresponsavelinterno,
                  ac.destelefonefiscal,
                  ac.descargofiscal,
                  ac.datassinatura,
                  ac.datatualizacao,
                  ac.datcadastro,
                  ac.datpublicacao,
                  ac.numprazovigencia,
                  ac.desobjeto,
                  ac.despalavrachave,
                  ac.descontatoexterno
                FROM agepnet200.tb_acordo ac
                  LEFT OUTER JOIN agepnet200.tb_acordo ac2 ON ac2.idacordopai = ac.idacordo
                  LEFT OUTER JOIN agepnet200.tb_setor st ON ac.idsetor = st.idsetor
                  LEFT OUTER JOIN agepnet200.tb_pessoa p1 ON ac.idresponsavelinterno = p1.idpessoa
                  LEFT OUTER JOIN agepnet200.tb_pessoa p2 ON ac.idfiscal = p2.idpessoa
                  LEFT OUTER JOIN agepnet200.tb_pessoa p3 ON ac.idfiscal2 = p3.idpessoa
                  LEFT OUTER JOIN agepnet200.tb_pessoa p4 ON ac.idfiscal3 = p4.idpessoa
                WHERE
                   ac.idacordo = :idacordo
        ";

        $resultado = $this->_db->fetchRow($sql, array('idacordo' => $params['idacordo']));
//        Zend_Debug::dump($resultado); exit;
        return new Acordocooperacao_Model_Acordo($resultado);
//        return $resultado;
    }
}

