<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Pesquisa_Model_Mapper_Questionariofrase extends App_Model_Mapper_MapperAbstract
{

    protected function _init()
    {
        $this->auth = App_Service_ServiceAbstract::getService('Default_Service_Login');
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_Questionariofrase
     */
    public function insert(Pesquisa_Model_Questionariofrase $model)
    {
        $data = array(
            "idfrase" => $model->idfrase,
            "idquestionario" => $model->idquestionario,
            "numordempergunta" => $model->numordempergunta,
            "obrigatoriedade" => $model->obrigatoriedade,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_Questionariofrase
     */
    public function update(Pesquisa_Model_Questionariofrase $model)
    {
        $data = array(
            "numordempergunta" => $model->numordempergunta,
            "obrigatoriedade" => $model->obrigatoriedade,
        );
        return $this->getDbTable()->update($data,
            array("idquestionario = ?" => $model->idquestionario, 'idfrase = ?' => $model->idfrase));
    }

    public function delete($params)
    {
        $where = $this->quoteInto(' idquestionario = ? ', (int)$params['idquestionario']);
        $where .= $this->quoteInto(' AND idfrase = ? ', (int)$params['idfrase']);
        $result = $this->getDbTable()->delete($where);
        return $result;
    }

    public function getForm()
    {
        return $this->_getForm(Pesquisa_Form_Questionariofrase);
    }

    public function getById($params)
    {
        $sql = "SELECT tqf.*, tf.desfrase, te.nomescritorio 
                    FROM agepnet200.tb_questionariofrase  tqf
                    INNER JOIN agepnet200.tb_frase tf ON tf.idfrase = tqf.idfrase
                    INNER JOIN agepnet200.tb_escritorio te ON te.idescritorio = tf.idescritorio
                WHERE tqf.idquestionario =  :idquestionario
                AND tqf.idfrase = :idfrase
                ";

        return $this->_db->fetchRow($sql,
            array('idquestionario' => (int)$params['idquestionario'], 'idfrase' => $params['idfrase']));
    }

    public function getByIdDetalhar($params)
    {
        $sql = "SELECT tqf.numordempergunta,
                        tqf.idfrase,
                        tqf.idquestionario,
                        CASE 
                            WHEN tqf.obrigatoriedade = 'S' THEN 'Sim'
                            WHEN tqf.obrigatoriedade = 'N' THEN 'Não'
                        END as obrigatoriedade,
                        tf.desfrase, 
                        te.nomescritorio
                    FROM agepnet200.tb_questionariofrase  tqf
                    INNER JOIN agepnet200.tb_frase tf ON tf.idfrase = tqf.idfrase
                    INNER JOIN agepnet200.tb_escritorio te ON te.idescritorio = tf.idescritorio
                WHERE tqf.idquestionario =  :idquestionario
                AND tqf.idfrase = :idfrase
                ";

        return $this->_db->fetchRow($sql,
            array('idquestionario' => (int)$params['idquestionario'], 'idfrase' => $params['idfrase']));
    }


    public function getAllByIdQuestionario($params)
    {
        $idescritorio = $this->auth->retornaUsuarioLogado()->perfilAtivo->idescritorio;
        $params = array_filter($params);

        try {
            $sql = "SELECT 
                    te.nomescritorio,
                    tf.desfrase as desfrase, 
                    tqf.numordempergunta as numordempergunta,                    
                    tqf.idfrase as idfrase,
                    tqf.idquestionario as idquestionario
                    FROM agepnet200.tb_frase tf
                    INNER JOIN agepnet200.tb_questionariofrase tqf ON tqf.idfrase = tf.idfrase
                    INNER JOIN agepnet200.tb_questionario tq ON tq.idquestionario = tqf.idquestionario
                    INNER JOIN agepnet200.tb_escritorio te ON te.idescritorio = tf.idescritorio
                    WHERE tqf.idquestionario  = :idquestionario
                    and tq.idescritorio  = $idescritorio
                    ORDER BY tqf.numordempergunta";

            return $this->_db->fetchAll($sql, array('idquestionario' => (int)$params['idquestionario']));

        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    /**
     * Retorna as perguntas vinculadas ao questionario
     *
     * @param array $params
     * @return \Zend_Paginator
     * @throws Exception
     */
    public function retornaPerguntasToGrid($params)
    {
        $idescritorio = $this->auth->retornaUsuarioLogado()->perfilAtivo->idescritorio;
        $params = array_filter($params);

        $sql = "SELECT 
                    te.nomescritorio,
                    tf.desfrase as desfrase, 
                    tqf.numordempergunta as numordempergunta,                    
                    CASE 
                        WHEN tqf.obrigatoriedade = 'S' THEN 'Sim'
                        WHEN tqf.obrigatoriedade = 'N' THEN 'Não'
                        END as obrigatoriedade,
                    tqf.idfrase as idfrase,
                    tqf.idquestionario as idquestionario
                    FROM agepnet200.tb_frase tf
                    INNER JOIN agepnet200.tb_questionariofrase tqf ON tqf.idfrase = tf.idfrase
                    INNER JOIN agepnet200.tb_questionario tq ON tq.idquestionario = tqf.idquestionario
                    INNER JOIN agepnet200.tb_escritorio te ON te.idescritorio = tf.idescritorio
                    WHERE tqf.idquestionario  = " . (int)$params['idquestionario'] . "
                    AND tf.flaativo = 'S'
                    AND tq.idescritorio = $idescritorio ";

        if (isset($params['desfrase']) && $params['desfrase'] != "") {
            $sql .= $this->_db->quoteInto(" AND tf.desfrase ilike ?", "%" . $params['desfrase'] . "%");
        }
        if (isset($params['numordempergunta']) && $params['numordempergunta'] != "") {
            $sql .= $this->quoteInto(" AND tqf.numordempergunta  = ? ", (int)$params['numordempergunta']);
        }
        if (isset($params['obrigatoriedade']) && $params['obrigatoriedade'] != "") {
            $sql .= $this->quoteInto(" AND tqf.obrigatoriedade  = ? ", $params['obrigatoriedade']);
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

    /**
     * Retorna a relação de perguntas existentes e ainda nao vinculadas ao questionario
     *
     * @param array $params
     * @return \Zend_Paginator
     * @throws Exception
     */
    public function retornaVincularPerguntasToGrid($params)
    {
        $params = array_filter($params);

        $sql = "SELECT
                    te.nomescritorio,
                    tf.desfrase as desfrase, 
                    CASE 
                        WHEN tf.domtipofrase = 1 THEN 'Uma-escolha'
                        WHEN tf.domtipofrase = 2 THEN 'Multipla-escolha'
                        WHEN tf.domtipofrase = 3 THEN 'Descritivo (em várias linhas)'
                        WHEN tf.domtipofrase = 4 THEN 'Texto (em uma linha)'
                        WHEN tf.domtipofrase = 5 THEN 'Número'
                        WHEN tf.domtipofrase = 6 THEN 'Data'
                        WHEN tf.domtipofrase = 7 THEN 'UF'
                     END as domtipofrase,
                    tf.idfrase as idfrase
                FROM agepnet200.tb_frase tf
                INNER JOIN agepnet200.tb_escritorio te ON te.idescritorio = tf.idescritorio
                    WHERE tf.flaativo = 'S'
                    AND tf.idfrase NOT IN (SELECT idfrase FROM agepnet200.tb_questionariofrase WHERE idquestionario = " . (int)$params['idquestionario'] . ")";

        if (isset($params['desfrase']) && $params['desfrase'] != "") {
            $sql .= $this->_db->quoteInto(" AND tf.desfrase ilike ?", "%" . $params['desfrase'] . "%");
        }

        $sql .= 'order by ' . $params['sidx'] . ' ' . $params['sord'];

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

    /**
     * Retorna todas a perguntas ativas e com resposta ou perguntas ativas sem resposta do questionario
     *
     *
     * @param type $params
     * @return type
     * @throws Exception
     */
    public function retornaPerguntasRespostasByQuestionario($params)
    {
        try {
            $params = array_filter($params);
            $sql = "SELECT 
                            tqf.idfrase as tqf_idfrase, 
                            tqf.idquestionario as tqf_idquestionario, 
                            tqf.numordempergunta as tqf_mumordempergunta,
                            tqf.idcadastrador as tqf_idcadastrador, 
                            tqf.datcadastro as tqf_datcadastro,
                            tqf.obrigatoriedade as tqf_obrigatoriedade,
                            tf.idfrase as tf_idfrase,
                            tf.domtipofrase as tf_domtipofrase,
                            tf.flaativo as tf_flaativo,
                            tf.datcadastro as tf_datcadastro,
                            tf.idescritorio as tf_idescritorio,
                            tf.idcadastrador as tf_idcadastrador,
                            tf.desfrase as tf_desfrase,
                            tr.idresposta as tr_idresposta,
                            tr.numordem as tr_numordem,
                            tr.flaativo as tr_flaativo,
                            tr.datcadastro as tr_datcadastro,
                            tr.idcadastrador as tr_idcadastrador,
                            tr.desresposta as tr_desresposta,
                            trf.idfrase as trf_idfrase,
                            trf.idresposta as trf_idresposta
                            FROM agepnet200.tb_questionariofrase tqf
                                INNER JOIN agepnet200.tb_frase tf ON tf.idfrase =  tqf.idfrase
                                LEFT JOIN agepnet200.tb_respostafrase trf ON trf.idfrase = tf.idfrase
                                LEFT JOIN agepnet200.tb_resposta tr ON tr.idresposta = trf.idresposta
                            WHERE (
                                    tf.domtipofrase <> " . Pesquisa_Model_Frase::UMA_ESCOLHA . " AND tf.domtipofrase <> " . Pesquisa_Model_Frase::MULTIPLA_ESCOLHA . " AND tf.domtipofrase <> " . Pesquisa_Model_Frase::UF . "
                                    AND tf.flaativo = '" . Pesquisa_Model_Frase::ATIVO . "' AND tqf.idquestionario = :idquestionario
                                  )
                            OR  (
                                  (tf.domtipofrase = " . Pesquisa_Model_Frase::UMA_ESCOLHA . " OR tf.domtipofrase = " . Pesquisa_Model_Frase::MULTIPLA_ESCOLHA . " OR tf.domtipofrase = " . Pesquisa_Model_Frase::UF . ") 
                                  AND tf.flaativo = '" . Pesquisa_Model_Frase::ATIVO . "' 
                                  AND tr.flaativo = '" . Pesquisa_Model_Resposta::ATIVO . "' AND tqf.idquestionario = :idquestionario
                                )
                            ORDER BY tf.idfrase, tr.idresposta";

            return $this->_db->fetchAll($sql, array('idquestionario' => (int)$params['idquestionario']));
        } catch (Exception $exc) {
            throw new Exception($exc->code());
        }

    }
}

