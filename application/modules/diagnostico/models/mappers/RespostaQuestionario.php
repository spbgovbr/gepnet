<?php

/**
 * Newton Carlos
 *
 * Criado em 11-12-2018
 * 12:32
 */
class Diagnostico_Model_Mapper_RespostaQuestionario extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     *
     * @param string $value
     * @return boolean
     */
    public function insert(Diagnostico_Model_RespostaQuestionario $model)
    {
        try {
            $model->id_resposta_pergunta = $this->maxVal('id_resposta_pergunta');

            $data = array(
                "id_resposta_pergunta" => $model->id_resposta_pergunta,
                "ds_resposta_descritiva" => $model->ds_resposta_descritiva,
                "idresposta" => $model->idresposta,
                "idpergunta" => $model->idpergunta,
                "idquestionario" => $model->idquestionariodiagnostico,
                "iddiagnostico" => $model->iddiagnostico,
                "nrquestionario" => $model->nrquestionario,
            );

            $data = array_filter($data);
            $retorno = $this->getDbTable()->insert($data);
            return $model;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    public function delete($params = array())
    {
        $pks = array("idquestionario" => $params['idquestionario']);
        $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
        $retorno = $this->getDbTable()->delete($where);
        return $retorno;
    }

}
