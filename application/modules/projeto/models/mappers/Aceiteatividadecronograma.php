<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 18-09-2014
 * 14:01
 */
class Projeto_Model_Mapper_Aceiteatividadecronograma extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Aceiteatividadecronograma
     */
    public function inserir(Projeto_Model_Aceiteatividadecronograma $model)
    {
        try {

            $model->idaceiteativcronograma = $this->maxVal('idaceiteativcronograma');

            $data = array(
                "idaceiteativcronograma" => $model->idaceiteativcronograma,
                "identrega" => $model->identrega,
                "idprojeto" => $model->idprojeto,
                "idaceite" => $model->idaceite,
                "idmarco" => $model->idmarco,
                "aceito" => $model->aceito
            );

            $data = array_filter($data);

            //Zend_Debug::dump($data);exit;

            $retorno = $this->getDbTable()->insert($data);
            return $retorno;

        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param Projeto_Model_Aceiteatividadecronograma
     * @return
     */
    public function update(Projeto_Model_Aceiteatividadecronograma $model)
    {
        $data = array(
            //"idaceiteativcronograma"    => $model->idaceiteativcronograma,
            "identrega" => $model->identrega,
            //"idprojeto"                 => $model->idprojeto,
            "idaceite" => $model->idaceite,
            "idmarco" => $model->idmarco,
            "aceito" => $model->aceito,
            "idpesaceitou" => "",
            "dataceitacao" => ""
        );
        if (($model->aceito == "S") && (isset($model->idpesaceitou))) {
            $data["idpesaceitou"] = $model->idpesaceitou;
            $data["dataceitacao"] = new Zend_Db_Expr("now()");
        }
        $data = array_filter($data);
        try {
            $pks = array(
                "idaceiteativcronograma" => $model->idaceiteativcronograma,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function delete($params)
    {
        try {
            $pks = array(
                "idaceiteativcronograma" => $params['idaceiteativcronograma'],
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
            //exit;
        }
    }

    public function getById($params)
    {
        $idaceite = $params['idaceite'];
        $idprojeto = $params['idprojeto'];
        $sql = "SELECT
                    aac.idaceiteativcronograma,
                    aac.idaceite,
                    aac.identrega,
                    aac.idprojeto,
                    aac.idmarco,
                    aac.aceito,
                    CASE
                      WHEN aac.aceito = 'S' THEN 'Sim'
                      WHEN aac.aceito = 'N' THEN 'Não'
                      ELSE ''
                    END as desAceito,
                    aac.idpesaceitou,
                    p.nompessoa,
                    to_char(aac.dataceitacao,'DD/MM/YYYY') as dataaceitacao,
                    acm.nomatividadecronograma as nomarco
                FROM agepnet200.tb_aceiteatividadecronograma aac
                     LEFT JOIN agepnet200.tb_pessoa p on p.idpessoa=aac.idpesaceitou
                     LEFT JOIN agepnet200.tb_atividadecronograma acm on acm.idatividadecronograma = aac.idmarco
                               and acm.idprojeto = aac.idprojeto
                WHERE aac.idaceite = $idaceite and aac.idprojeto = $idprojeto ";

        $resultado = $this->_db->fetchRow($sql);

        return new Projeto_Model_Aceiteatividadecronograma($resultado);
    }

}

