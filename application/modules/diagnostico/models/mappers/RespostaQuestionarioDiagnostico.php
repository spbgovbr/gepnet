<?php

class Diagnostico_Model_Mapper_RespostaQuestionarioDiagnostico extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     * @param array $dados
     * @return boolean
     */
    public function insert($dados)
    {
        try {
            $data = array(
                'id_resposta_pergunta' => (int)$dados['id_resposta_pergunta'],
                'idquestionario' => (int)$dados['idquestionario'],
                'iddiagnostico' => (int)$dados['iddiagnostico'],
                'numero' => (int)$dados['numero'],
            );
            $data = array_filter($data);
            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

}
