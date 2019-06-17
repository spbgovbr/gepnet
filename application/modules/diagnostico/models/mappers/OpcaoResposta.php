<?php

/**
 * Newton Carlos
 *
 * Criado em 11-12-2018
 * 12:32
 */
class Diagnostico_Model_Mapper_OpcaoResposta extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     *
     * @param string $value
     * @return Diagnostico_Model_RespostaQuestionario
     */
    public function insert(Diagnostico_Model_OpcaoResposta $model)
    {
        try {
            $model->idresposta = $this->maxVal('idresposta');

            $data = array(
                "idresposta" => $model->idresposta,
                "idpergunta" => $model->idpergunta,
                "idquestionario" => $model->idquestionario,
                "desresposta" => $model->desresposta,
                "escala" => $model->escala,
                "ordenacao" => $model->ordenacao
            );

            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    public function insertCopiaOpResp($dados, $tipos, $idQuest)
    {
        try {
            $arrTipo = array();
            foreach ($tipos as $t) {
                $arrTipo[] = $t;
            }
            $retorno = false;
            $arrDados = array();
            $id = $this->getMaxId();
            foreach ($dados as $d) {

                for ($i = 0; $i < count($arrTipo); $i++) {
                    if ($d['tipopergunta'] === $arrTipo[$i]["tipoperguntanova"]) {
                        $d['idpergunta'] = $arrTipo[$i]["idperguntanova"];
                    }
                }

                unset($d['tipopergunta']);
                $data['idresposta'] = $id;
                $data['idpergunta'] = $d['idpergunta'];
                $data['desresposta'] = $d['desresposta'];
                $data['escala'] = $d['escala'];
                $data['ordenacao'] = $d['ordenacao'];
                $data['idquestionario'] = $idQuest;
                $id++;

                $resposta = $this->getDbTable()->insert($data);
                $retorno = true;
            }
            return $retorno;

        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    public function getMaxId()
    {
        $sql = "SELECT MAX(idresposta)+1 AS idresposta 
                FROM agepnet200.tb_opcao_resposta";
        $resultado = $this->_db->fetchOne($sql);
        return $resultado;
    }

    public function getByIdOpResp($params)
    {
        $sql = "SELECT 
                p.tipopergunta AS tipoperguntanova, p.idpergunta AS idperguntanova, p.idquestionario
                FROM agepnet200.tb_pergunta p
                LEFT JOIN agepnet200.tb_opcao_resposta o 
                    ON o.idpergunta = p.idpergunta
                WHERE p.idquestionario = {$params}
                AND p.tipopergunta <> 1
                ORDER BY p.tipopergunta ";

        return $this->_db->fetchAll($sql);
    }

    public function getByIdOpRespAnterior($params)
    {

        $sql = "SELECT o.desresposta, o.escala, o.ordenacao,
                p.tipopergunta, o.idpergunta, p.idquestionario
                FROM agepnet200.tb_pergunta p
                INNER JOIN agepnet200.tb_opcao_resposta o 
                    ON o.idpergunta = p.idpergunta
                    AND o.idquestionario = p.idquestionario
                WHERE p.idquestionario = {$params}
                ORDER BY p.tipopergunta";

        return $this->_db->fetchAll($sql);
    }


    /**
     * Set the property
     *
     * @param Diagnostico_Model_OpcaoResposta $model
     * @return Diagnostico_Model_OpcaoResposta
     */
    public function update($model)
    {
        try {
            $data = array(
                "desresposta" => $model->desresposta,
                "escala" => $model->escala,
                "ordenacao" => $model->ordenacao,
            );

            $pks = array(
                "idresposta" => $model->idresposta,
            );

            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    public function delete($params = array())
    {
        $pks = array("idresposta" => $params['idresposta']);
        $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
        $retorno = $this->getDbTable()->delete($where);

        return $retorno;
    }

    /**
     * Função que remover a opçao de resposta
     * @param array $params
     * @return int
     */
    public function deleteResposta($params)
    {
        try {
            $pks = array(
                "idresposta" => (int)$params['idresposta'],
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function retornaTodasRespostas($params)
    {

        $sql = "SELECT ordenacao, desresposta, escala, idresposta, idpergunta
                FROM agepnet200.tb_opcao_resposta
                WHERE idpergunta =:idpergunta
                AND idquestionario=:idquestionario ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idpergunta' => (int)$params['idpergunta'],
            'idquestionario' => (int)$params['idquestionario'],
        ));

        return $resultado;
    }

    public function retornaTodasOpcoesRespostasPorPergunta($params, $paginator = false)
    {

        $sql = "SELECT idresposta, ordenacao, desresposta, escala, idpergunta
                FROM agepnet200.tb_opcao_resposta
                WHERE idpergunta = " . (int)$params['idpergunta'] . "
                AND idquestionario = " . (int)$params['idquestionario'];

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

        try {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            //Zend_Debug::dump($paginator);die;
            return $paginator;
        } catch (Exception $exc) {
            throw new Exception($exc->code());
        }
    }

    public function deleteResPergunta($params = array())
    {
        try {
            $sql = "DELETE FROM agepnet200.tb_opcao_resposta WHERE idpergunta = :idpergunta AND idquestionario=:idquestionario";

            $resultado = $this->_db->fetchAll($sql, array(
                'idpergunta' => $params['idpergunta'],
                'idquestionario' => $params['idquestionario']
            ));
            return $resultado;

        } catch (Exception $exc) {
            throw $exc;
        }
    }

}
