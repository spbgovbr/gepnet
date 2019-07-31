<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Statusreport extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Statusreport
     */
    public function insert(Projeto_Model_Statusreport $model)
    {
        $model->idstatusreport = $this->maxVal("idstatusreport");
//        $model->idcadastrador = '30605';
//        print "<PRE>aqui";
//        print_r($model);
//        exit;
        $model->idcadastrador = Zend_Auth::getInstance()->getIdentity()->idpessoa;

        $data = array(
            "idstatusreport" => $model->idstatusreport,
            "idprojeto" => $model->idprojeto,
//            "datacompanhamento" => new Zend_Db_Expr("to_date('" . $model->datacompanhamento->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datacompanhamento" => $model->datacompanhamento->toString('Y-m-d'),
            "numpercentualconcluido" => $model->numpercentualconcluido,
            "numpercentualprevisto" => $model->numpercentualprevisto,
            "desatividadeconcluida" => $model->desatividadeconcluida,
            "desatividadeandamento" => $model->desatividadeandamento,
            "desmotivoatraso" => $model->desmotivoatraso,
            "desirregularidade" => $model->desirregularidade,
            "idmarco" => $model->idmarco,
//            "datmarcotendencia" => new Zend_Db_Expr("to_date('" . $model->datmarcotendencia->toString('Y-m-d') . "','YYYY-MM-DD')"),
//            "datfimprojetotendencia" => new Zend_Db_Expr("to_date('" . $model->datfimprojetotendencia->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datfimprojetotendencia" => $model->datfimprojetotendencia->toString('Y-m-d'),
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => date('Y-m-d'),
            "domstatusprojeto" => $model->domstatusprojeto,
            "flaaprovado" => 2,
            //nao aprovada
            "domcorrisco" => $model->domcorrisco,
            "descontramedida" => $model->descontramedida,
            "desrisco" => $model->desrisco,
        );
//        print "<PRE>aqui";
//        print_r($data);
//        exit;
        try {
            $this->getDbTable()->insert($data);
            return $model;
//            echo $model;
        } catch (Exception $exc) {
            throw $exc;
//            echo $exc;
        }
//        exit;
//        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Statusreport
     */
    public function update(Projeto_Model_Statusreport $model)
    {
//        print "<PRE>";
//        print_r($model);


        $data = array(
            "idstatusreport" => $model->idstatusreport,
            "idprojeto" => $model->idprojeto,
//            "datacompanhamento" => $model->datacompanhamento,
//            "numpercentualconcluido" => $model->numpercentualconcluido,
//            "numpercentualprevisto" => $model->numpercentualprevisto,
            "domstatusprojeto" => $model->domstatusprojeto,
            "desatividadeconcluida" => $model->desatividadeconcluida,
            "desatividadeandamento" => $model->desatividadeandamento,
            "desmotivoatraso" => $model->desmotivoatraso,
            "desirregularidade" => $model->desirregularidade,
            "descontramedida" => $model->descontramedida,
            "desrisco" => $model->desrisco,
            "idmarco" => $model->idmarco,
//            "domcorrisco" => $model->domcorrisco,
//            "datmarcotendencia" => $model->datmarcotendencia,
//            "datfimprojetotendencia" => $model->datfimprojetotendencia,
//            "idcadastrador" => $model->idcadastrador,
//            "datcadastro" => $model->datcadastro,
//            "flaaprovado" => $model->flaaprovado,
        );

        $data = array_filter($data);

//        print_r($data);
//        exit;


        try {
            $this->getDbTable()->update($data, array("idstatusreport = ?" => $model->idstatusreport));
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }


//        Zend_Debug::dump($data); exit;

        /*try {
            $pks = array("idstatusreport" => $model->idstatusreport);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }*/
    }

    public function excluir($params)
    {
        try {
            $pks = array(
                "idstatusreport" => $params['idstatusreport'],
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function retornaUltimoPorProjeto($params)
    {
        $sql = "select
                    idstatusreport, idprojeto,
                    to_char(datacompanhamento, 'DD/MM/YYYY') as datacompanhamento,
                    numpercentualconcluido, numpercentualprevisto,
                    desatividadeconcluida, desatividadeandamento,
                    desmotivoatraso, desirregularidade,
                    to_char(datmarcotendencia, 'DD/MM/YYYY') as datmarcotendencia,
                    to_char(datfimprojetotendencia, 'DD/MM/YYYY') as datfimprojetotendencia,
                    domstatusprojeto, idmarco, flaaprovado, domcorrisco,
                    descontramedida, desrisco
                from agepnet200.tb_statusreport
                where idprojeto = :idprojeto
                order by datcadastro desc
                limit 1";

        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return new Projeto_Model_Statusreport($resultado);
    }

    public function pesquisar($params, $paginator = false)
    {

        $sql = "SELECT
          prog.nomprograma,
          proj.nomprojeto,
          pes1.nompessoa,
          to_char(proj.datinicio, 'DD/MM/YYYY') as datinicio,
          to_char(st.datfimprojetotendencia, 'DD/MM/YYYY') as datfimprojetotendencia,
          to_char(proj.datfim, 'DD/MM/YYYY') as datfim,
          '0%' as previsto,
          '0%' as concluido,
          '0%' as atraso,
          to_char(st.datacompanhamento, 'DD/MM/YYYY') as datacompanhamento,
          st.idprojeto
        FROM
          agepnet200.tb_projeto proj
            left join agepnet200.tb_statusreport st on st.idprojeto=proj.idprojeto
            left join agepnet200.tb_escritorio esc on esc.idescritorio=proj.idescritorio
            left join agepnet200.tb_objetivo obj on obj.idobjetivo=proj.idobjetivo
            left join agepnet200.tb_acao ac on ac.idacao=proj.idacao and ac.idobjetivo=proj.idobjetivo
            left join agepnet200.tb_programa prog on prog.idprograma=proj.idprograma
            inner join agepnet200.tb_pessoa pes1 on pes1.idpessoa = proj.idgerenteprojeto
        WHERE
          1 = 1 ";
        /* $sql = "
                 SELECT
                         prog.nomprograma,
                         proj.nomprojeto,
                         pes1.nompessoa,
                         to_char(proj.datinicio, 'DD/MM/YYYY') as datinicio,
                         to_char(st.datfimprojetotendencia, 'DD/MM/YYYY') as datfimprojetotendencia,
                         to_char(proj.datfim, 'DD/MM/YYYY') as datfim,
                         '0%' as previsto,
                         '0%' as concluido,
                         '0%' as atraso,
                         to_char(st.datacompanhamento, 'DD/MM/YYYY') as datacompanhamento,
                         st.idprojeto
                 FROM
                         agepnet200.tb_statusreport st,
                         agepnet200.tb_projeto proj,
                         agepnet200.tb_escritorio esc,
                         agepnet200.tb_objetivo obj,
                         agepnet200.tb_acao ac,
                         agepnet200.tb_programa prog,
                         agepnet200.tb_pessoa pes1
                 WHERE
                         st.idprojeto            = proj.idprojeto
                         AND proj.idescritorio   = esc.idescritorio
                         AND st.idprojeto        = proj.idprojeto
                         AND esc.idescritorio    = obj.idobjetivo
                         AND obj.idobjetivo      = ac.idobjetivo
                         AND proj.idprograma     = prog.idprograma
                         AND proj.idgerenteprojeto = pes1.idpessoa
         ";*/

        $params = array_filter($params);

        if (isset($params['idprojeto'])) {
            $sql .= " and st.idprojeto = {$params['idprojeto']}";
        }
        if (isset($params['nomprojeto'])) {
            $sql .= " and proj.nomprojeto like '%{$params['nomprojeto']}%'";
        }
        if (isset($params['idescritorio'])) {
            $sql .= " and esc.idescritorio = {$params['idescritorio']}";
        }
        if (isset($params['idprograma'])) {
            $sql .= " and proj.idprograma = {$params['idprograma']}";
        }
        if (isset($params['codobjetivo'])) {
            $sql .= " and obj.idobjetivo = {$params['codobjetivo']}";
        }
        if (isset($params['codacao'])) {
            $sql .= " and ac.idacao = {$params['codacao']}";
        }
        /*if (isset($params['codacao'])) {
            $sql.= " and ac.idacao = {$params['codacao']}";
        }*/

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
        //Zend_Debug::dump($id);exit;
        return $resultado;
    }

    public function retornaAcompanhamentosPorProjeto($params, $paginator, $array = false)
    {
        $sql = "SELECT
                    to_char(sr.datacompanhamento, 'DD/MM/YYYY') as datacompanhamento,
                    sr.numpercentualprevisto,
                    sr.numpercentualconcluido,
                    to_char(sr.datfimprojetotendencia, 'DD/MM/YYYY') as datfimprojetotendencia,
                    '' as cronogramapdf,
                    sr.idcadastrador,
                    '' as prazo,
                    sr.domcorrisco,
                    to_char(sr.datmarcotendencia, 'DD/MM/YYYY') as datmarcotendencia,
                    sr.idstatusreport, 
                    sr.idprojeto,
                    sr.desatividadeconcluida, sr.desatividadeandamento,
                    sr.desmotivoatraso, sr.desirregularidade,
                    sr.idmarco,
                    sr.datcadastro,
                    sr.domstatusprojeto, sr.flaaprovado, 
                    sr.descontramedida, sr.desrisco,
                    p1.nompessoa as nomcadastrador
                FROM 
                    agepnet200.tb_statusreport sr,
                    agepnet200.tb_pessoa p1
                WHERE 
                    1 = 1
                    and p1.idpessoa = sr.idcadastrador
                ";

        if (isset($params['idprojeto'])) {
            $idprojeto = $params['idprojeto'];
            $sql .= " and idprojeto = {$idprojeto}";
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

        if ($array) {
            return $resultado;
        }

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Statusreport');

        foreach ($resultado as $r) {
            $status = new Projeto_Model_Statusreport($r);
            $collection[] = $status;
        }

        return $collection;
    }

    public function retornaAcompanhamentoPorId($params)
    {
        $sql = "select
                    idstatusreport, idprojeto,
                    to_char(datacompanhamento, 'DD/MM/YYYY') as datacompanhamento,
                    numpercentualconcluido, numpercentualprevisto,
                    desatividadeconcluida, desatividadeandamento,
                    desmotivoatraso, desirregularidade,
                    idmarco,
                    to_char(datmarcotendencia, 'DD/MM/YYYY') as datmarcotendencia,
                    to_char(datfimprojetotendencia, 'DD/MM/YYYY') as datfimprojetotendencia,
                    idcadastrador, datcadastro,
                    domstatusprojeto, flaaprovado, domcorrisco,
                    descontramedida, desrisco
                from 
                    agepnet200.tb_statusreport
                where 
                    idstatusreport = :idstatusreport
                    and idprojeto  = :idprojeto
                ";

        $resultado = $this->_db->fetchRow($sql,
            array('idstatusreport' => $params['idstatusreport'], 'idprojeto' => $params['idprojeto']));
        return new Projeto_Model_Statusreport($resultado);
    }

    public function retornaUltimoAcompanhamento($params)
    {

        //zend_debug::dump($params['idprojeto']);

        $sql = "select
                    idstatusreport, idprojeto,
                    to_char(datacompanhamento, 'DD/MM/YYYY') as datacompanhamento,
                    numpercentualconcluido, numpercentualprevisto,
                    desatividadeconcluida, desatividadeandamento,
                    desmotivoatraso, desirregularidade,
                    idmarco,
                    to_char(datmarcotendencia, 'DD/MM/YYYY') as datmarcotendencia,
                    to_char(datfimprojetotendencia, 'DD/MM/YYYY') as datfimprojetotendencia,
                    idcadastrador, datcadastro,
                    domstatusprojeto, flaaprovado, domcorrisco,
                    descontramedida, desrisco
                from 
                    agepnet200.tb_statusreport
                where 
                    idprojeto  = :idprojeto
                order by idstatusreport DESC
                ";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));

        return new Projeto_Model_Statusreport($resultado);
//        return $resultado;
    }

    public function getChartPlanejadoRealizado($params, $paginator)
    {
        $sql = "select
                    datacompanhamento,
                    numpercentualconcluido,
                    numpercentualprevisto
                from 
                    agepnet200.tb_statusreport
                where 
                    1 = 1
                ";
        if (isset($params['idprojeto'])) {
            $sql .= " and idprojeto = {$params['idprojeto']}";
        }

        $sql .= ' ORDER BY datacompanhamento ASC ';

        $resultado = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($id);exit;
        return $resultado;
    }

    public function getChartEvolucaoAtraso($params, $paginator)
    {
        $sql = "select
                    datacompanhamento,
                    (numpercentualprevisto - numpercentualconcluido) as atraso
                from 
                    agepnet200.tb_statusreport
                where 
                    1 = 1
                ";
        if (isset($params['idprojeto'])) {
            $sql .= " and idprojeto = {$params['idprojeto']}";
        }

        $sql .= ' ORDER BY datacompanhamento ASC ';

        $resultado = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($id);exit;
        return $resultado;
    }

    public function getChartPrazo($params, $paginator)
    {
        $sql = "SELECT
                    proj.numcriteriofarol,
                    (report.datfimprojetotendencia - proj.datfim) as prazo
                FROM 
                    agepnet200.tb_projeto proj,
                    agepnet200.tb_statusreport report
                WHERE 
                    proj.idprojeto = report.idprojeto
                ";
        if (isset($params['idprojeto'])) {
            $sql .= " and proj.idprojeto = {$params['idprojeto']}";
        }
        if (isset($params['idstatusreport'])) {
            $sql .= " and report.idstatusreport = {$params['idstatusreport']}";
        }

        $resultado = $this->_db->fetchRow($sql);

        return $resultado;
    }

    public function getById($params)
    {
        $sql = "SELECT
                    idstatusreport,
                    idprojeto,
                    to_char(datacompanhamento,'DD/MM/YYYY') as datacompanhamento,
                    numpercentualconcluido,
                    numpercentualprevisto,
                    desatividadeconcluida,
                    desatividadeandamento,
                    desmotivoatraso,
                    desirregularidade,
                    idmarco,
                    to_char(datmarcotendencia,'DD/MM/YYYY') as datmarcotendencia,
                    to_char(datfimprojetotendencia,'DD/MM/YYYY') as datfimprojetotendencia,
                    idcadastrador,
                    to_char(datcadastro,'DD/MM/YYYY') as datcadastro,
                    domstatusprojeto,
                    flaaprovado,
                    domcorrisco,
                    descontramedida,
                    desrisco,
                    to_char(dataprovacao,'DD/MM/YYYY') as dataprovacao
                FROM 
                    agepnet200.tb_statusreport
                WHERE 
                    idstatusreport = :idstatusreport
                ";

        $resultado = $this->_db->fetchRow($sql, array('idstatusreport' => $params['idstatusreport']));
        $statusreport = new Projeto_Model_StatusReport($resultado);

        return $statusreport;
    }

    public function ultimoId()
    {
        $sql = "select
                    idstatusreport
                from agepnet200.tb_statusreport
                order by idstatusreport desc
                limit 1";

        $resultado = $this->_db->fetchRow($sql);
        return $resultado;
    }
}
