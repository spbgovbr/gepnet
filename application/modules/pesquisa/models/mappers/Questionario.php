<?php

class Pesquisa_Model_Mapper_Questionario extends App_Model_Mapper_MapperAbstract
{

    protected $auth = null;

    protected function _init()
    {
        $this->auth = App_Service_ServiceAbstract::getService('Default_Service_Login');
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_Questionario
     */
    public function insert(Pesquisa_Model_Questionario $model)
    {
        $data = array(
            "idquestionario" => $this->maxVal('idquestionario'),
            "nomquestionario" => $model->nomquestionario,
            "desobservacao" => $model->desobservacao ?: null,
            "tipoquestionario" => $model->tipoquestionario,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "idescritorio" => $model->idescritorio,
            "disponivel" => Pesquisa_Model_Questionario::INDISPONILVEL,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_Questionario
     */
    public function update(Pesquisa_Model_Questionario $model)
    {
        $idescritorio = $this->auth->retornaUsuarioLogado()->perfilAtivo->idescritorio;
        $data = array(
            "idquestionario" => $model->idquestionario,
            "nomquestionario" => $model->nomquestionario,
            "desobservacao" => $model->desobservacao ?: null,
            "tipoquestionario" => $model->tipoquestionario,
            "disponivel" => $model->disponivel,
        );
        return $this->getDbTable()->update($data,
            array("idquestionario = ?" => $model->idquestionario, "idescritorio = ?" => $idescritorio));
    }

    public function updateDisponibilidade(Pesquisa_Model_Questionario $model)
    {
        $idescritorio = $this->auth->retornaUsuarioLogado()->perfilAtivo->idescritorio;
        $data = array(
            "disponivel" => $model->disponivel,
        );
        return $this->getDbTable()->update($data,
            array("idquestionario = ?" => $model->idquestionario, "idescritorio = ?" => $idescritorio));
    }

    public function getForm()
    {
        return $this->_getForm(Pesquisa_Form_Questionario);
    }

    public function getById($params)
    {
        $sql = "SELECT * FROM agepnet200.tb_questionario where idquestionario =  :idquestionario ";

        return $this->_db->fetchRow($sql, array('idquestionario' => (int)$params['idquestionario']));
    }

    public function getByIdAndEscritorio($params)
    {
        $idescritorio = $this->auth->retornaUsuarioLogado()->perfilAtivo->idescritorio;
        $sql = "SELECT * FROM agepnet200.tb_questionario where idquestionario =  :idquestionario AND idescritorio = $idescritorio";

        return $this->_db->fetchRow($sql, array('idquestionario' => (int)$params['idquestionario']));
    }

    public function getByIdDetalhar($params)
    {
        $idescritorio = $this->auth->retornaUsuarioLogado()->perfilAtivo->idescritorio;
        $params = array_filter($params);

        $sql = "SELECT
                    nomquestionario, 
                    CASE 
                        WHEN tipoquestionario = " . Pesquisa_Model_Questionario::PUBLICADO_COM_SENHA . " THEN 'Publicado com senha'
                        WHEN tipoquestionario = " . Pesquisa_Model_Questionario::PUBLICADO_SEM_SENHA . " THEN 'Publicado sem senha'
                     END as tipoquestionario, 
                     desobservacao,
                     idquestionario                     
                FROM agepnet200.tb_questionario 
                WHERE idquestionario =  :idquestionario
                AND idescritorio =  $idescritorio";

        return $this->_db->fetchRow($sql, array('idquestionario' => $params['idquestionario']));
    }

    public function retornaQuestionariosToGrid($params)
    {

        $idescritorio = $this->auth->retornaUsuarioLogado()->perfilAtivo->idescritorio;

        $params = array_filter($params);
        $sql = "SELECT 
                    nomquestionario,
                    CASE 
                       WHEN tipoquestionario = " . Pesquisa_Model_Questionario::PUBLICADO_COM_SENHA . " THEN 'Publicado com senha'
                       WHEN tipoquestionario = " . Pesquisa_Model_Questionario::PUBLICADO_SEM_SENHA . " THEN 'Publicado sem senha'
                     END as tipoquestionario,   
                    idquestionario,
                    CASE 
                       WHEN disponivel = " . Pesquisa_Model_Questionario::DISPONILVEL . " THEN 'Disponível'
                       WHEN disponivel = " . Pesquisa_Model_Questionario::INDISPONILVEL . " THEN 'Indisponível'
                     END as disponivel  
                FROM agepnet200.tb_questionario 
                WHERE $idescritorio = " . (int)$idescritorio;

        if (isset($params['nomquestionario']) && $params['nomquestionario'] != "") {
            $sql .= $this->_db->quoteInto('  AND nomquestionario ilike ? ', "%" . $params['nomquestionario'] . "%");
        }
        if (isset($params['tipoquestionario']) && $params['tipoquestionario'] != "") {
            $sql .= $this->_db->quoteInto('  AND tipoquestionario  = ? ', $params['tipoquestionario']);
        }

        $sql .= ' order by ' . $params['sidx'] . ' ' . $params['sord'];
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

    public function retornaQuestionarioPesquisaGrid($params)
    {

        $params = array_filter($params);
        $sql = "SELECT 
                    nomquestionario,
                    CASE 
                        WHEN tipoquestionario = " . Pesquisa_Model_Questionario::PUBLICADO_COM_SENHA . " THEN 'Publicado com senha'
                        WHEN tipoquestionario = " . Pesquisa_Model_Questionario::PUBLICADO_SEM_SENHA . " THEN 'Publicado sem senha'
                    END as tipoquestionario,
                    idquestionario
                FROM agepnet200.tb_questionario 
                WHERE disponivel = " . Pesquisa_Model_Questionario::DISPONILVEL;

        if (isset($params['nomquestionario']) && $params['nomquestionario'] != "") {
            $sql .= $this->_db->quoteInto('  AND nomquestionario ilike ? ', "%" . $params['nomquestionario'] . "%");
        }
        if (isset($params['tipoquestionario']) && $params['tipoquestionario'] != "") {
            $sql .= $this->_db->quoteInto('  AND tipoquestionario  = ? ', $params['tipoquestionario']);
        }

        $sql .= ' order by ' . $params['sidx'] . ' ' . $params['sord'];
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

    public function getQuestionarioPublicar($params)
    {
        try {
            $params = array_filter($params);
            $sql = "SELECT 
                    * 
                FROM agepnet200.tb_questionario 
                WHERE disponivel = " . Pesquisa_Model_Questionario::DISPONILVEL . " 
                AND idquestionario = :idquestionario";

            return $this->_db->fetchRow($sql, array('idquestionario' => $params['id']));
        } catch (Exception $exc) {
            throw new Exception($exc->getCode());
        }
    }

}
