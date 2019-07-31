<?php

class Diagnostico_Model_Mapper_QuestionarioDiagnosticoRespondido extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     * @param Diagnostico_Model_QuestionarioDiagnosticoRespondido $model
     * @return Diagnostico_Model_QuestionarioDiagnosticoRespondido || boolean
     */
    public function insert($model)
    {

        try {
            $sql = "INSERT INTO agepnet200.tb_questionariodiagnostico_respondido(
                                idquestionario, iddiagnostico, numero, dt_resposta,
                                idpessoaresposta)
                                SELECT " . $model->idquestinario . ", " . $model->iddiagnostico . ",
                                (SELECT COALESCE(MAX(numero), 0::BIGINT) + 1 FROM agepnet200.tb_questionariodiagnostico_respondido
                                 WHERE idquestionario=" . $model->idquestinario . " AND iddiagnostico=" . $model->iddiagnostico . "),
                                 CURRENT_TIMESTAMP::DATE, " . $model->idpessoaresposta . " ";

            $execucao = $this->_db->fetchAll($sql);

            $params = array(
                'idquestionario' => $model->idquestinario,
                'iddiagnostico' => $model->iddiagnostico
            );

            $model->numero = $this->retornaMaxNumero($params);

            return $model;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    public function retornaMaxNumero($params)
    {

        $sql2 = "SELECT COALESCE(MAX(numero), 0::BIGINT) as numero FROM agepnet200.tb_questionariodiagnostico_respondido
                     WHERE idquestionario=" . $params['idquestionario'] . " AND iddiagnostico=" . $params['iddiagnostico'] . " ";

        $resultado = $this->_db->fetchRow($sql2);

        return $resultado['numero'];
    }

}
