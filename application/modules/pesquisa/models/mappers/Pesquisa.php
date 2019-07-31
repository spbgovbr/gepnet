<?php

class Pesquisa_Model_Mapper_Pesquisa extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_Pesquisa
     */
    public function insert(Pesquisa_Model_Pesquisa $model)
    {
        $data = array(
            "idpesquisa" => $this->maxVal('idpesquisa'),
            "situacao" => Pesquisa_Model_Pesquisa::PUBLICADO,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "datpublicacao" => new Zend_Db_Expr('now()'),
            "idpespublica" => $model->idpespublica,
            "idquestionario" => $model->idquestionario,
        );
        return $this->getDbTable()->insert($data);
    }

    public function update(Pesquisa_Model_Pesquisa $model)
    {
        $data = array(
            "situacao" => $model->situacao,
            "idpespublica" => $model->idpespublica,
            "idpesencerra" => $model->idpesencerra,
            "dtencerramento" => $model->dtencerramento,
            "datpublicacao" => $model->datpublicacao,
        );
        return $this->getDbTable()->update($data, array('idpesquisa = ?' => $model->idpesquisa));
    }

    public function retornaQuestionarioPesquisaGrid($params)
    {
        $sql = "SELECT 
                    tqp.nomquestionario, 
                     CASE 
                        WHEN tqp.tipoquestionario = " . Pesquisa_Model_Questionario::PUBLICADO_COM_SENHA . " THEN 'Publicado com senha'
                        WHEN tqp.tipoquestionario = " . Pesquisa_Model_Questionario::PUBLICADO_SEM_SENHA . " THEN 'Publicado sem senha'
                    END as tipoquestionario,
                     CASE 
                        WHEN tp.situacao = " . Pesquisa_Model_Pesquisa::PUBLICADO . " THEN 'Publicada'
                        WHEN tp.situacao = " . Pesquisa_Model_Pesquisa::ENCERRADO . " THEN 'Encerrada'
                    END as situacao,
                    (SELECT COUNT(idresultado) 
                        FROM (SELECT DISTINCT
                                    trp.idresultado 
                                FROM agepnet200.tb_resultado_pesquisa trp 
                                WHERE trp.idquestionariopesquisa = tqp.idquestionariopesquisa
                    )AS total) AS respondidas,
                    to_char(tp.datcadastro, 'dd-mm-yyyy HH24:MM:SS') AS datcadastro,
                    tp.idpesquisa
                FROM agepnet200.tb_pesquisa tp
                INNER JOIN agepnet200.tb_questionario_pesquisa tqp ON tqp.idpesquisa = tp.idpesquisa ";

        if (isset($params['nomquestionario']) && $params['nomquestionario'] != "") {
            $sql .= $this->_db->quoteInto('  AND tqp.nomquestionario ilike ? ', "%" . $params['nomquestionario'] . "%");
        }

        if (isset($params['tipoquestionario']) && $params['tipoquestionario'] != "") {
            $sql .= $this->_db->quoteInto('  AND tqp.tipoquestionario  = ? ', $params['tipoquestionario']);
        }

        if (isset($params['situacao']) && $params['situacao'] != "") {
            $sql .= $this->_db->quoteInto('  AND tp.situacao  = ? ', $params['situacao']);
        }

        if (isset($params['datcadastroinicio']) && $params['datcadastroinicio'] != "") {
            $sql .= $this->_db->quoteInto("  AND tp.datcadastro >= to_timestamp(?, 'dd-mm-yyyy HH24:MI:SS') ",
                $params['datcadastroinicio'] . ' 00:00:00');
        }

        if (isset($params['datcadastrofim']) && $params['datcadastrofim'] != "") {
            $sql .= $this->_db->quoteInto("  AND tp.datcadastro <= to_timestamp(?, 'dd-mm-yyyy HH24:MI:SS') ",
                $params['datcadastrofim'] . ' 23:59:59');
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
            throw new Exception($exc->getMessage());
        }
    }

    public function retornaPesquisasRelatorioGrid($params)
    {
        $sql = "SELECT 
                    tqp.nomquestionario,
                    CASE 
                        WHEN tqp.tipoquestionario = " . Pesquisa_Model_QuestionarioPesquisa::PUBLICADO_COM_SENHA . " THEN 'Publicado com senha'
                        WHEN tqp.tipoquestionario = " . Pesquisa_Model_QuestionarioPesquisa::PUBLICADO_SEM_SENHA . " THEN 'Publicado sem senha'
                    END as tipoquestionario,
                     CASE 
                        WHEN tp.situacao = " . Pesquisa_Model_Pesquisa::PUBLICADO . " THEN 'Publicada'
                        WHEN tp.situacao = " . Pesquisa_Model_Pesquisa::ENCERRADO . " THEN 'Encerrada'
                    END as situacao,
                    (SELECT COUNT(idresultado) 
                        FROM (SELECT DISTINCT
                                    trp.idresultado 
                                FROM agepnet200.tb_resultado_pesquisa trp 
                                WHERE trp.idquestionariopesquisa = tqp.idquestionariopesquisa
                    )AS total) AS respondidas,
                    tp.idpesquisa
                FROM agepnet200.tb_pesquisa tp
                INNER JOIN agepnet200.tb_questionario_pesquisa tqp ON tqp.idpesquisa = tp.idpesquisa ";

        if (isset($params['nomquestionario']) && $params['nomquestionario'] != "") {
            $sql .= $this->_db->quoteInto('  AND tqp.nomquestionario ilike ? ', "%" . $params['nomquestionario'] . "%");
        }

        if (isset($params['situacao']) && $params['situacao'] != "") {
            $sql .= $this->_db->quoteInto('  AND tp.situacao  = ? ', $params['situacao']);
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
            throw new Exception($exc->getMessage());
        }
    }

    public function retornaPesquisaById($params)
    {
        $params = array_filter($params);

        try {
            $sql = ' SELECT  * FROM agepnet200.tb_pesquisa WHERE idpesquisa = :idpesquisa ';
            return $this->_db->fetchRow($sql, array('idpesquisa' => $params['idpesquisa']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function retornaPesquisaPublicadaById($params)
    {
        $params = array_filter($params);

        try {
            $sql = ' SELECT  * 
                        FROM agepnet200.tb_pesquisa tp
                     INNER JOIN agepnet200.tb_questionario_pesquisa tqp ON tqp.idpesquisa =  tp.idpesquisa
                     WHERE tp.idpesquisa = :idpesquisa AND tp.situacao = ' . Pesquisa_Model_Pesquisa::PUBLICADO;
            return $this->_db->fetchRow($sql, array('idpesquisa' => $params['idpesquisa']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function detalharPesquisaById($params)
    {
        $params = array_filter($params);
        //Zend_Debug::dump($params);exit;
        $sql = "SELECT 
                    tp.idpesquisa,			                        
                    tqp.nomquestionario,
                    tqp.idquestionariopesquisa,
                    tqfp.idfrasepesquisa,
                    tqfp.numordempergunta,
                    tqfp.obrigatoriedade,
                    tfp.desfrase, 
                    tfp.domtipofrase, 
                    trp.idrespostapesquisa,
                    trp.desresposta
                FROM agepnet200.tb_pesquisa tp
                    INNER JOIN agepnet200.tb_questionario_pesquisa tqp ON tqp.idpesquisa = tp.idpesquisa
                    INNER JOIN agepnet200.tb_questionariofrase_pesquisa tqfp ON tqfp.idquestionariopesquisa = tqp.idquestionariopesquisa
                    INNER JOIN agepnet200.tb_frase_pesquisa tfp ON tfp.idfrasepesquisa = tqfp.idfrasepesquisa
                    LEFT JOIN agepnet200.tb_respostafrase_pesquisa trfp ON trfp.idfrasepesquisa = tfp.idfrasepesquisa
                    LEFT JOIN agepnet200.tb_resposta_pesquisa trp ON trp.idrespostapesquisa = trfp.idrespostapesquisa			
                WHERE tp.idpesquisa = :idpesquisa		
                ORDER BY tqfp.numordempergunta, tqfp.idfrasepesquisa, trp.numordem";

        try {
            return $this->_db->fetchAll($sql, array('idpesquisa' => $params['idpesquisa']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    /**
     * Retorna as pesquisas disponiveis para resposta
     *
     * @param type $params
     * @return type
     * @throws Exception
     */
    public function retornaPesquisasResponderGrid($params)
    {
        $sql = " SELECT 
                    tqp.nomquestionario,
                    tqp.desobservacao,
                    tp.idpesquisa
                FROM agepnet200.tb_pesquisa tp
                INNER JOIN agepnet200.tb_questionario_pesquisa tqp ON tqp.idpesquisa = tp.idpesquisa
                WHERE tp.situacao = " . Pesquisa_Model_Pesquisa::PUBLICADO;

        if (isset($params['nomquestionario']) && $params['nomquestionario'] != "") {
            $sql .= $this->_db->quoteInto('  AND tqp.nomquestionario ilike ? ', "%" . $params['nomquestionario'] . "%");
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
            throw new Exception($exc->getMessage());
        }
    }

    /**
     * Retorna o enunciado das questoes da pesquisa
     *
     * @param type $params
     * @return type
     * @throws Exception
     */
    public function retornaEnunciadoPesquisaById($params)
    {
        $sql = " SELECT
                    tp.idpesquisa,			                        
                    tqp.nomquestionario,
                    tqp.idquestionariopesquisa,
                    tqfp.idfrasepesquisa,
                    tqfp.numordempergunta,
                    tqfp.obrigatoriedade,
                    tfp.desfrase, 
                    tfp.domtipofrase
                FROM agepnet200.tb_pesquisa tp
                    INNER JOIN agepnet200.tb_questionario_pesquisa tqp ON tqp.idpesquisa = tp.idpesquisa
                    INNER JOIN agepnet200.tb_questionariofrase_pesquisa tqfp ON tqfp.idquestionariopesquisa = tqp.idquestionariopesquisa
                    INNER JOIN agepnet200.tb_frase_pesquisa tfp ON tfp.idfrasepesquisa = tqfp.idfrasepesquisa	    
                WHERE tp.idpesquisa = :idpesquisa";

        try {
            return $this->_db->fetchAll($sql, array('idpesquisa' => $params['idpesquisa']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }
}