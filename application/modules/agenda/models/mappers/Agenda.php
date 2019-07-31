<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the DbTable "" @ 14-05-2013
 * 18:02
 */
class Agenda_Model_Mapper_Agenda extends App_Model_Mapper_MapperAbstract
{
    private $_mapperPessoaAgenda = null;

    protected function _init()
    {
        $this->_mapperPessoaAgenda = new Agenda_Model_Mapper_Pessoaagenda();
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Agenda_Model_Agenda
     */
    public function insert(Agenda_Model_Agenda $model)
    {
        $model->idagenda = $this->maxVal('idagenda');
        $model->idcadastrador = Zend_Auth::getInstance()->getIdentity()->idpessoa;

        $data = array(
            "idagenda" => $model->idagenda,
            "desassunto" => $model->desassunto,
            "datagenda" => new Zend_Db_Expr("to_date('" . $model->datagenda->toString('Y-m-d ') . $model->hragendada . ":00" . "','YYYY-MM-DD')"),
            "desagenda" => $model->desagenda,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "hragendada" => date('Y-m-d H:i:s',
                strtotime($model->datagenda->toString('Y-m-d') . " " . $model->hragendada . ".0")),
//            "hragendada"    => new Zend_Db_Expr("now()"),
            "deslocal" => $model->deslocal,
            "flaenviaemail" => 2,
            "idescritorio" => $model->idescritorio,
        );
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
     * @return Default_Model_Agenda
     */
    public function update(Agenda_Model_Agenda $model)
    {
        $data = array(
            "idagenda" => $model->idagenda,
            "desassunto" => $model->desassunto,
            "datagenda" => new Zend_Db_Expr("to_date('" . $model->datagenda->toString('Y-m-d ') . $model->hragendada . ":00" . "','YYYY-MM-DD')"),
            "desagenda" => $model->desagenda,
//            "idcadastrador" => $model->idcadastrador,
//            "nomcadastrador" => $model->nomcadastrador,
//            "datcadastro"   => $model->datcadastro,
            "hragendada" => date('Y-m-d H:i:s',
                strtotime($model->datagenda->toString('Y-m-d') . " " . $model->hragendada . ":00.0")),
            "deslocal" => $model->deslocal,
//            "flaenviaemail" => $model->flaenviaemail,
//            "idescritorio"  => $model->idescritorio,
        );
        try {
            $this->getDbTable()->update($data, array("idagenda = ?" => $model->idagenda));
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function getForm()
    {
        return $this->_getForm(Agenda_Form_Agenda);
    }

    public function pesquisar($params, $paginator = true)
    {
        //'Data', 'Hora', 'Local', 'Assunto', 'Participantes', 'Usuário', 'Enviou Email'
        $sql = "
                SELECT
                  to_char(datagenda,'DD/MM/YYYY') as datagenda,
                  to_char(hragendada, 'HH24:MI') as hragendada,
                  deslocal,
                  desassunto,
                  '' as nomparticipantes,
                  p1.nompessoa as nomcadastrador,
                  CASE
                    WHEN agenda.flaenviaemail = 1 THEN 'SIM'
                    WHEN agenda.flaenviaemail = 2 THEN 'NÃO'
                  END as falenviaemail,
                  idagenda
                FROM
                  agepnet200.tb_agenda agenda,
                  agepnet200.tb_pessoa p1
                WHERE
                  1 = 1
                  and agenda.idcadastrador = p1.idpessoa
               ";
        $params = array_filter($params);

        if (isset($params['data'])) {
            $data = $params['data'];
            $sql .= " and agenda.datagenda::text like '{$data}%'";
        } else {
            $hoje = new DateTime('now');
            $hoje = $hoje->format('Y-m-d');
//            var_dump($hoje); exit;
            $sql .= " and agenda.datagenda::text like '{$hoje}%'";
        }

        if (isset($params['idescritorio'])) {
            $escritorio = $params['idescritorio'];
            $sql .= " and agenda.idescritorio = {$escritorio}";
        }

//        var_dump($sql); exit;

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }
        $resultado = $this->_db->fetchAll($sql);

        $agenda = new Agenda_Model_Agenda($resultado);
        $agenda->participantes = new App_Model_Relation(
            $this->_mapperPessoaAgenda, 'getByAgenda', array(array('idagenda' => $resultado['idagenda']))
        );


        return $agenda;

    }

    public function getById($params, $model = false)
    {

        $sql = "SELECT
                    a.idagenda,
                    a.desassunto,
                    to_char(a.datagenda,'DD/MM/YYYY') as datagenda,
                    to_char(a.hragendada, 'HH24:MI') as hragendada,
                    a.desagenda,
                    a.idcadastrador,
                    a.datcadastro,
                    a.deslocal,
                    CASE
                      WHEN a.flaenviaemail = 1 THEN 'SIM'
                      WHEN a.flaenviaemail = 2 THEN 'NÃO'
                    END as flaenviaemail,
                    a.idescritorio,
                    e.nomescritorio,
                    p.nompessoa as nomcadastrador
                  FROM
                    agepnet200.tb_agenda a,
                    agepnet200.tb_escritorio e,
                    agepnet200.tb_pessoa p
                  WHERE
                    a.idagenda = :idagenda
                    and a.idescritorio = e.idescritorio
                    and a.idcadastrador = p.idpessoa";

//        Zend_Debug::dump($params['idprojeto']); exit;
//        Zend_Debug::dump($sql); exit;

        $resultado = $this->_db->fetchRow($sql, array('idagenda' => $params['idagenda']));

        if ($model) {
            $agenda = new Agenda_Model_Agenda($resultado);
            $agenda->participantes = new App_Model_Relation(
                $this->_mapperPessoaAgenda, 'retornaPartesPorAgenda', array(array('idagenda' => $resultado['idagenda']))
            );
            return $agenda;

        }

//        Zend_Debug::dump($resultado); exit;

        return $resultado;
    }

    public function excluir($params)
    {
        try {
            $pks = array(
                "idagenda" => $params['idagenda'],
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function retornaDiasComEventos($params, $paginator = true)
    {

        $mes = str_pad($params['mes'], 2, '0', STR_PAD_LEFT);
        $sql = "SELECT
                    to_char(a.datagenda,'YYYY-MM-DD') as datagenda
                  FROM
                    agepnet200.tb_agenda a
                  WHERE
                    to_char(datagenda,'MM') = '{$mes}'
                    and datagenda != now()
                    and to_char(a.datagenda,'YYYY-MM-DD') != to_char(now(),'YYYY-MM-DD') ";

        $sql .= " GROUP BY datagenda";
        $sql .= " ORDER BY datagenda";

//        Zend_Debug::dump($params['idprojeto']); exit;
//        Zend_Debug::dump($sql); exit;


        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

//        $resultado = $this->_db->fetchRow($sql, array('mes' => $params['mes']));
        $resultado = $this->_db->fetchRow($sql);

        return $resultado;
    }
}

