<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Planejamento_Model_Mapper_Portfolioprograma extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Planejamento_Model_Portfolio
     */
    public function insert(Planejamento_Model_Portfolioprograma $model)
    {

        try {

            $data = array(
                "idportfolio" => $model->idportfolio,
                "idprograma" => $model->idprograma,
            );

            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Planejamento_Model_Portfolioprograma
     */
    public function update(Planejamento_Model_Portfolioprograma $model)
    {

        try {

            $pks = array(
                "idportfolio" => $model->idportfolio,
            );

            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);

            if (count($model->idprograma) > 0) {
                foreach ($model->idprograma as $programa) {
                    $dados = new Planejamento_Model_Portfolioprograma(
                        array(
                            'idportfolio' => $model->idportfolio,
                            'idprograma' => $programa,

                        ));
                    $insere = $this->insert($dados);
                }
            }
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function getProgramaByPortfolio($params)
    {
        $sql = " SELECT idprograma FROM agepnet200.tb_portifolioprograma WHERE idportfolio = :idportfolio ";
        return $this->_db->fetchCol($sql, array('idportfolio' => $params['idportfolio']));
    }

    public function fecthAllProgramasByPortfolio($params)
    {
        $sql = " SELECT port.idprograma,
                        p.nomprograma
                    FROM agepnet200.tb_portifolioprograma port,
                         agepnet200.tb_programa p
                    WHERE port.idportfolio = :idportfolio
                          and p.idprograma = port.idprograma ";

        return $this->_db->fetchAll($sql, array('idportfolio' => $params['idportfolio']));
    }

}

