<?php

/**
 * Newton Carlos
 *
 * Criado em 14-11-2018
 * 16:07
 */
class Diagnostico_Model_Mapper_Partediagnostico extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     *
     * @param string $value
     * @return Diagnostico_Model_Partediagnostico
     */
    public function insert(Diagnostico_Model_Partediagnostico $model)
    {
        $model->idpartediagnostico = $this->maxVal('idpartediagnostico');

        $data = array(
            "idpartediagnostico" => $model->idpartediagnostico,
            "iddiagnostico" => $model->iddiagnostico,
            "qualificacao" => $model->qualificacao,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "idpessoa" => $model->idpessoa,
            "tppermissao" => $model->tppermissao
        );
        $retorno = $this->getDbTable()->insert($data);
        return $model;
    }

    public function deletePartes($data)
    {
        try {
            $sql = "
                DELETE FROM agepnet200.tb_partediagnostico
                WHERE iddiagnostico = :iddiagnostico ";

            $resultado = $this->_db->fetchAll($sql, array(
                'iddiagnostico' => $data['iddiagnostico']
            ));
            return $resultado;

        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function isPessoaParteInteressadaByDiagnostico($params)
    {

        $sql = "select idpartediagnostico, iddiagnostico, idpessoa, tppermissao, qualificacao
                from agepnet200.tb_partediagnostico
                where 1=1 ";


        if (!empty($params['iddiagnostico'])) {
            $sql .= "AND iddiagnostico IN({$params['iddiagnostico']}) ";
        }

        if (!empty($params['idpessoa'])) {
            $sql .= "AND idpessoa IN({$params['idpessoa']}) ";
        }

        $resultado = $this->_db->fetchRow($sql);

        if ($resultado) {
            return (count($resultado) > 0) ? true : false;
        }
        return false;
    }


    public function retornarParteByIdPessoa($params, $parte = false, $array = false)
    {

        $sql = "select idpartediagnostico, iddiagnostico, idpessoa, tppermissao, qualificacao::INTEGER
                from agepnet200.tb_partediagnostico
                where 1=1 ";

        if (!empty($params['iddiagnostico'])) {
            $sql .= "AND iddiagnostico IN({$params['iddiagnostico']}) ";
        }

        if (!empty($params['idpessoa'])) {
            $sql .= "AND idpessoa IN({$params['idpessoa']}) ";
        }

        $resultado = $this->_db->fetchRow($sql);

        if ($array) {
            return $resultado;
        } else {
            return new Diagnostico_Model_Partediagnostico($resultado);
        }
    }

}
