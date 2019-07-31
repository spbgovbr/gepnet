<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */

use Default_Service_Log as Log;

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
        $model->idcadastrador = Zend_Auth::getInstance()->getIdentity()->idpessoa;

        if ($model->flaaprovado == 1) {
            $model->dataprovacao = date('Y-m-d');
            $model->flaaprovado = 1;
        } else {
            $model->dataprovacao = null;
            $model->flaaprovado = 2;
        }
        if ($model->idmarco == '1') {
            $model->datcadastro = date('Y-m-d');
        }

        $data = array(
            "idstatusreport" => $model->idstatusreport,
            "idprojeto" => (int)$model->idprojeto,
            "datacompanhamento" => $model->datacompanhamento->toString('Y-m-d'),
            "numpercentualconcluido" => $model->numpercentualconcluido,
            "numpercentualprevisto" => $model->numpercentualprevisto,
            "desatividadeconcluida" => $model->desatividadeconcluida,
            "desatividadeandamento" => $model->desatividadeandamento,
            "desmotivoatraso" => $model->desmotivoatraso,
            "desirregularidade" => $model->desirregularidade,
            "idmarco" => (int)$model->idmarco,
            "datfimprojetotendencia" => $model->datfimprojetotendencia->toString('Y-m-d'),
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => date('Y-m-d'),
            "domstatusprojeto" => (int)$model->domstatusprojeto,
            "flaaprovado" => $model->flaaprovado, //2 nao aprovada
            "domcorrisco" => $model->domcorrisco,
            "descontramedida" => $model->descontramedida,
            "desrisco" => $model->desrisco,
            "pgpassinado" => $model->pgpassinado,
            "tepassinado" => $model->tepassinado,
            "desandamentoprojeto" => $model->desandamentoprojeto,
            "dataprovacao" => $model->dataprovacao,
            "diaatraso" => $model->diaatraso,
            "numpercentualconcluidomarco" => number_format($model->numpercentualconcluidomarco, 2),
            "domcoratraso" => $model->domcoratraso,
            "datfimprojeto" => $model->datfimprojeto instanceof Zend_Date ? $model->datfimprojeto->toString('Y-m-d') : $model->datfimprojeto,
            "numcriteriofarol" => $model->numcriteriofarol,
        );

        $arrayFilter = array_filter($data);
        try {
            $retorno = $this->getDbTable()->insert($arrayFilter);
            return $model;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Statusreport
     */
    public function update(Projeto_Model_Statusreport $model)
    {

        if ($model->flaaprovado == 1) {
            $model->dataprovacao = date('Y-m-d');
            $model->flaaprovado = 1;
        }

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
            "domcorrisco" => $model->domcorrisco,
            "flaaprovado" => $model->flaaprovado,
            "pgpassinado" => $model->pgpassinado,
            "tepassinado" => $model->tepassinado,
            "desandamentoprojeto" => $model->desandamentoprojeto,
            "dataprovacao" => $model->dataprovacao,
//            "datmarcotendencia" => $model->datmarcotendencia,
//            "datfimprojetotendencia" => $model->datfimprojetotendencia,
//            "idcadastrador" => $model->idcadastrador,
//            "datcadastro" => $model->datcadastro,
//            "flaaprovado" => $model->flaaprovado,
        );
        //Zend_Debug::dump($data);die;
        $data = array_filter($data);

        if ($model->flaaprovado == 2) {
            $model->dataprovacao = null;
            $model->flaaprovado = 2;
            $data['flaaprovado'] = 2;
            $data['dataprovacao'] = null;
        }

        try {
            $this->getDbTable()->update($data, array("idstatusreport = ?" => $model->idstatusreport));
            return $model;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    /**
     * Exclusao de statusreport
     *
     * @param array
     *
     * @return Projeto_Model_Statusreport
     */

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
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    public function retornaUltimoPorProjeto($params)
    {
        $sql = "select
                    str.idstatusreport, str.idprojeto,
                    to_char(str.datacompanhamento, 'DD/MM/YYYY') as datacompanhamento,
                    str.numpercentualconcluido, str.numpercentualprevisto,
                    str.desatividadeconcluida, str.desatividadeandamento,
                    str.desmotivoatraso, str.desirregularidade,
                    to_char(str.datmarcotendencia, 'DD/MM/YYYY') as datmarcotendencia,
                    to_char(str.datfimprojetotendencia, 'DD/MM/YYYY') as datfimprojetotendencia,
                    str.domstatusprojeto,
		            (select trim(t.nomtipo) from agepnet200.tb_tiposituacaoprojeto t
		            where t.idtipo = str.domstatusprojeto) as nomdomstatusprojeto,
                    str.idmarco, str.flaaprovado, str.domcorrisco,
		            (CASE
                        WHEN str.domcorrisco = 1 THEN 'Baixo'
                        WHEN str.domcorrisco = 2 THEN 'Medio'
                        ELSE 'Alto' END) as nomdomcorrisco,
                    str.descontramedida, str.desrisco, tbp.numprocessosei
                from agepnet200.tb_statusreport str
		        INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = str.idprojeto
		                        and tbp.idtipoiniciativa = 1 /* PROJETO */
                where str.idprojeto = :idprojeto
                order by str.idstatusreport desc
                limit 1";

        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return new Projeto_Model_Statusreport($resultado);
    }


    public function isAcompanhamentoAnterior($params)
    {
        $sql = "select
                    str.idstatusreport, str.idprojeto,
                    to_char(str.datacompanhamento, 'DD/MM/YYYY') as datacompanhamento,
                    str.numpercentualconcluido, str.numpercentualprevisto,
                    str.desatividadeconcluida, str.desatividadeandamento,
                    str.desmotivoatraso, str.desirregularidade,
                    to_char(str.datmarcotendencia, 'DD/MM/YYYY') as datmarcotendencia,
                    to_char(str.datfimprojetotendencia, 'DD/MM/YYYY') as datfimprojetotendencia,
                    str.domstatusprojeto,
                        (select trim(t.nomtipo) from agepnet200.tb_tiposituacaoprojeto t
                        where t.idtipo = str.domstatusprojeto) as nomdomstatusprojeto,
                    str.idmarco, str.flaaprovado, str.domcorrisco,
                        (CASE
                    WHEN str.domcorrisco = 1 THEN 'Baixo'
                    WHEN str.domcorrisco = 2 THEN 'Medio'
                    ELSE 'Alto' END) as nomdomcorrisco,
                    str.descontramedida, str.desrisco, tbp.numprocessosei
                from agepnet200.tb_statusreport str
                INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = str.idprojeto and tbp.idtipoiniciativa = 1
                where str.idprojeto = " . $params['idprojeto'] . "  and str.idstatusreport < " . $params['idstatusreport'] . "
                and str.datacompanhamento < to_date('" . $params['datacompanhamento'] . "','YYYY-MM-DD')
                order by str.idstatusreport desc limit 1";

        $resultado = $this->_db->fetchRow($sql);

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
        WHERE proj.idtipoiniciativa = 1 /* PROJETO */
          and 1 = 1 ";
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
        //Zend_Debug::dump($id);exit;
        return $resultado;
    }

    public function retornaMarcoConcluidoProjetoByStatusReport($params){
        $sql = "SELECT ROUND(COALESCE(str.numpercentualconcluidomarco,0),0)	AS numpercentualconcluidomarco
                  FROM agepnet200.tb_statusreport str
                 WHERE str.idprojeto = :idprojeto AND str.idstatusreport = :idstatusreport";

        $resultado = $this->_db->fetchRow($sql,array(
                'idprojeto'      => $params['idprojeto'],
                'idstatusreport' => $params['idstatusreport'],
        ));
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
                    p1.nompessoa as nomcadastrador,
                    sr.datacompanhamento as dt,
                    sr.datfimprojetotendencia as dtft,
                    sr.numpercentualconcluidomarco,
                    sr.diaatraso,
                    sr.domcoratraso,
                    sr.numpercentualconcluidomarco,
                    sr.diaatraso,
                    sr.domcoratraso,
                    sr.numcriteriofarol,
                    sr.datfimprojeto
                FROM 
                    agepnet200.tb_statusreport sr
		            INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = sr.idprojeto
		                  and tbp.idtipoiniciativa = 1 /* PROJETO */
		            INNER JOIN agepnet200.tb_pessoa p1  on  p1.idpessoa = sr.idcadastrador
                WHERE 
                    1 = 1 ";

        if (isset($params['idprojeto']) && (!empty($params['idprojeto']))) {
            $idprojeto = $params['idprojeto'];
            $sql .= " and sr.idprojeto = {$idprojeto}";
        }

        if (isset($params['idstatusreport']) && (!empty($params['idstatusreport']))) {
            $idstatusreport = $params['idstatusreport'];
            $sql .= " and sr.idstatusreport = {$idstatusreport}";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by sr." . $params['sidx'] . " " . $params['sord'];
        } else {
            $sql .= " order by dt, dtft ";
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
                    sr.idstatusreport, sr.idprojeto,
                    to_char(sr.datacompanhamento, 'DD/MM/YYYY') as datacompanhamento,
                    sr.numpercentualconcluido, sr.numpercentualprevisto,
                    sr.desatividadeconcluida, sr.desatividadeandamento,
                    sr.desmotivoatraso, sr.desirregularidade,
                    sr.idmarco,
                    to_char(sr.datmarcotendencia, 'DD/MM/YYYY') as datmarcotendencia,
                    to_char(sr.datfimprojetotendencia, 'DD/MM/YYYY') as datfimprojetotendencia,
                    sr.idcadastrador, sr.datcadastro,
                    sr.domstatusprojeto,
		            (select trim(t.nomtipo) from agepnet200.tb_tiposituacaoprojeto t
		            where t.idtipo = sr.domstatusprojeto) as nomdomstatusprojeto,
		            sr.flaaprovado, sr.domcorrisco,
		            (CASE
                        WHEN sr.domcorrisco = 1 THEN 'Baixo'
                        WHEN sr.domcorrisco = 2 THEN 'Medio'
                        ELSE 'Alto' END) as nomdomcorrisco,
                    sr.descontramedida, sr.desrisco, sr.pgpassinado, sr.tepassinado, tbp.numprocessosei, sr.desandamentoprojeto
                from 
                    agepnet200.tb_statusreport sr
		            INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = sr.idprojeto
		                  and tbp.idtipoiniciativa = 1 /* PROJETO */
                where 
                    sr.idstatusreport = :idstatusreport
                    and sr.idprojeto  = :idprojeto
                ";

        $resultado = $this->_db->fetchRow($sql,
            array('idstatusreport' => $params['idstatusreport'], 'idprojeto' => $params['idprojeto']));
        return new Projeto_Model_Statusreport($resultado);
    }

    public function retornarTodosAcompanhamento($params)
    {
        $sql = "SELECT
                    idstatusreport,
                    to_char(datacompanhamento, 'DD/MM/YYYY') AS datacompanhamento
                FROM agepnet200.tb_statusreport
                WHERE idprojeto  = :idprojeto
                order by idstatusreport asc";

        $resultado = $this->_db->fetchPairs($sql, array('idprojeto' => $params['idprojeto']));

        return $resultado;

    }


    public function retornaUltimoAcompanhamento($params)
    {

        $sql = "select
                    sr.idstatusreport, sr.idprojeto,
                    to_char(sr.datacompanhamento, 'DD/MM/YYYY') as datacompanhamento,
                    sr.numpercentualconcluido, sr.numpercentualprevisto,
                    sr.desatividadeconcluida, sr.desatividadeandamento,
                    sr.desmotivoatraso, sr.desirregularidade,
                    sr.idmarco,
                    to_char(sr.datmarcotendencia, 'DD/MM/YYYY') as datmarcotendencia,
                    to_char(sr.datfimprojetotendencia, 'DD/MM/YYYY') as datfimprojetotendencia,
                    sr.idcadastrador, sr.datcadastro,
                    sr.domstatusprojeto,
		            (select trim(t.nomtipo) from agepnet200.tb_tiposituacaoprojeto t
		            where t.idtipo = sr.domstatusprojeto) as nomdomstatusprojeto,
		            sr.flaaprovado, sr.domcorrisco,
		            (CASE
                        WHEN sr.domcorrisco = 1 THEN 'Baixo'
                        WHEN sr.domcorrisco = 2 THEN 'Medio'
                        ELSE 'Alto' END) as nomdomcorrisco,
                    sr.descontramedida, sr.desrisco,
                    sr.datacompanhamento as datUltimoAcompanhamento
                from
                    agepnet200.tb_statusreport sr
		            INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = sr.idprojeto
		                  and tbp.idtipoiniciativa = 1 /* PROJETO */
                where
                    sr.idprojeto  = :idprojeto
                order by sr.idstatusreport DESC 
                limit 1";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));

        return new Projeto_Model_Statusreport($resultado);
    }

    public function getChartPlanejadoRealizado($params, $paginator)
    {
        $sql = "select
                    sr.datacompanhamento,
                    sr.numpercentualconcluido,
                    sr.numpercentualprevisto
                from 
                    agepnet200.tb_statusreport sr
		            INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = sr.idprojeto
		                  and tbp.idtipoiniciativa = 1 /* PROJETO */
                where 
                    1 = 1
                ";
        if (isset($params['idprojeto'])) {
            $sql .= " and sr.idprojeto = {$params['idprojeto']}";
        }

        if (isset($params['idstatusreport'])) {
            $sql .= " and sr.idstatusreport <= {$params['idstatusreport']}";
        }

        $sql .= ' ORDER BY sr.idstatusreport ASC ';

        $resultado = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($id);exit;
        return $resultado;
    }

    public function getChartEvolucaoAtraso($params, $paginator)
    {
        $sql = "select
                    sr.datacompanhamento,
                    (sr.numpercentualprevisto - sr.numpercentualconcluido) as atraso
                from 
                    agepnet200.tb_statusreport sr
		            INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = sr.idprojeto
		                  and tbp.idtipoiniciativa = 1 /* PROJETO */
                where 
                    1 = 1
                ";
        if (isset($params['idprojeto'])) {
            $sql .= " and sr.idprojeto = {$params['idprojeto']}";
        }

        if (isset($params['idstatusreport'])) {
            $sql .= " and sr.idstatusreport <= {$params['idstatusreport']}";
        }

        $sql .= ' ORDER BY sr.idstatusreport ASC ';

        $resultado = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($id);exit;
        return $resultado;
    }

    public function getChartPrazo($params, $paginator)
    {
        $sql = "SELECT
                    proj.numcriteriofarol,
                    report.datfimprojetotendencia, proj.datfim,
                    report.numcriteriofarol as numcriteriofarol_report,
                    report.datfimprojeto as datfimprojeto_report,
                    report.diaatraso, report.diaatraso
                FROM 
                    agepnet200.tb_projeto proj,
                    agepnet200.tb_statusreport report
                WHERE
                    proj.idprojeto = report.idprojeto
                    and proj.idtipoiniciativa = 1 /* PROJETO */
                ";
        if (isset($params['idprojeto'])) {
            $sql .= " and proj.idprojeto = {$params['idprojeto']}";
        }
        if (isset($params['idstatusreport'])) {
            $sql .= " and report.idstatusreport = {$params['idstatusreport']}";
        }
        $sql .= " order by report.idstatusreport DESC limit 1";

        $resultado = $this->_db->fetchRow($sql);

        return $resultado;
    }

    public function getUltimoPrazo($params, $paginator)
    {
        $sql = "SELECT
                    proj.numcriteriofarol,                    
                    report.datfimprojetotendencia, proj.datfim
                FROM
                    agepnet200.tb_projeto proj,
                    agepnet200.tb_statusreport report
                WHERE
                    proj.idprojeto = report.idprojeto and proj.idtipoiniciativa = 1 /* PROJETO */
                ";
        if (isset($params['idprojeto'])) {
            $sql .= " and proj.idprojeto = {$params['idprojeto']}";
        }
        if (isset($params['idstatusreport'])) {
            $sql .= " and report.idstatusreport = {$params['idstatusreport']}";
        }
        $sql .= " order by report.idstatusreport desc limit 1 ";
        $resultado = $this->_db->fetchRow($sql);
        return $resultado;
    }

    public function getById($params)
    {
        $sql = "SELECT
                    sr.idstatusreport,
                    sr.idprojeto,
                    to_char(sr.datacompanhamento,'DD/MM/YYYY') as datacompanhamento,
                    sr.numpercentualconcluido,
                    sr.numpercentualprevisto,
                    sr.desatividadeconcluida,
                    sr.desatividadeandamento,
                    sr.desmotivoatraso,
                    sr.desirregularidade,
                    sr.idmarco,
                    to_char(sr.datmarcotendencia,'DD/MM/YYYY') as datmarcotendencia,
                    to_char(sr.datfimprojetotendencia,'DD/MM/YYYY') as datfimprojetotendencia,
                    sr.idcadastrador,
                    to_char(sr.datcadastro,'DD/MM/YYYY') as datcadastro,
                    sr.domstatusprojeto,
		            (select trim(t.nomtipo) from agepnet200.tb_tiposituacaoprojeto t
		            where t.idtipo = sr.domstatusprojeto) as nomdomstatusprojeto,
                    sr.flaaprovado,
                    sr.domcorrisco,
		            (CASE
                        WHEN sr.domcorrisco = 1 THEN 'Baixo'
                        WHEN sr.domcorrisco = 2 THEN 'Medio'
                        ELSE 'Alto' END) as nomdomcorrisco,
                    sr.descontramedida,
                    sr.desrisco,
                    to_char(sr.dataprovacao,'DD/MM/YYYY') as dataprovacao,
                    sr.pgpassinado, sr.tepassinado, tbp.numprocessosei, sr.desandamentoprojeto,
                    sr.datfimprojeto
                FROM 
                    agepnet200.tb_statusreport sr
		            INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = sr.idprojeto
		                  and tbp.idtipoiniciativa = 1 /* PROJETO */
                WHERE 
                    idstatusreport = :idstatusreport
                ";

        $resultado = $this->_db->fetchRow($sql, array('idstatusreport' => $params['idstatusreport']));
        $statusreport = new Projeto_Model_Statusreport($resultado);

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

    /**
     * Verifica se o PGP do Projeto foi assinado.
     * @param $idProjeto
     * @return string
     */
    public function getPgpAssinado($idProjeto)
    {
        $sql = "select
                    pgpassinado
                from agepnet200.tb_statusreport
                where idprojeto = $idProjeto
                order by idstatusreport desc";
        $resultado = $this->_db->fetchRow($sql);
        return $resultado['pgpassinado'] ?: 'N';
    }
}
