<?php

class Pesquisa_Model_Mapper_QuestionarioPesquisa extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_QuestionarioPesquisa
     */
    public function insert(Pesquisa_Model_QuestionarioPesquisa $model)
    {
        $data = array(
            "idquestionariopesquisa" => $this->maxVal('idquestionariopesquisa'),
            "idpesquisa" => $model->idpesquisa,
            "nomquestionario" => $model->nomquestionario,
            "desobservacao" => $model->desobservacao,
            "tipoquestionario" => $model->tipoquestionario,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "idescritorio" => $model->idescritorio,
        );
        return $this->getDbTable()->insert($data);
    }

    public function retornaQuestionarioByPesquisa($params)
    {
        $sql = " SELECT * FROM agepnet200.tb_questionario_pesquisa WHERE idpesquisa = :idpesquisa ";

        try {
            return $this->_db->fetchRow($sql, array('idpesquisa' => (int)$params['idpesquisa']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }

    }

    public function retornaPesquisaMesmoNome($params)
    {
        $params = array_filter($params);
        $sql = " SELECT 
                        tqp.nomquestionario 
                 FROM agepnet200.tb_questionario_pesquisa tqp 
                 INNER JOIN agepnet200.tb_questionario tq ON tq.nomquestionario = tqp.nomquestionario 
                 WHERE tq.idquestionario = :idquestionario";

        try {
            return $this->_db->fetchRow($sql, array('idquestionario' => (int)$params['id']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }

    }

    public function retornaQuestionarioById($params)
    {
        $sql = "SELECT * FROM agepnet200.tb_questionario_pesquisa WHERE idquestionariopesquisa = :idquestionariopesquisa";

        try {
            return $this->_db->fetchRow($sql, array('idquestionariopesquisa' => $params['idquestionariopesquisa']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }

    }
}
