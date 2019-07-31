<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Acordocooperacao_Model_Mapper_Acordoentidadeexterna extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Acordoentidadeexterna
     */
    public function insert(Default_Model_Acordoentidadeexterna $model)
    {
        $data = array(
            "idacordo" => $model->idacordo,
            "identidadeexterna" => $model->identidadeexterna,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Acordoentidadeexterna
     */
    public function update(Default_Model_Acordoentidadeexterna $model)
    {
        $data = array(
            "idacordo" => $model->idacordo,
            "identidadeexterna" => $model->identidadeexterna,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Acordoentidadeexterna);
    }

    public function cadastrar($idacordo, $entidade)
    {
        $data = array(
            "idacordo" => $idacordo,
            "identidadeexterna" => $entidade,
        );
        $this->getDbTable()->insert($data);
    }

    public function excluirEntidades($params)
    {

//        Zend_Debug::dump($params); exit;
        try {
            $pks = array(
                "idacordo" => $params['idacordo'],
            );
//            $where   = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $where = $this->_db->quoteInto('idacordo = ?', $params['idacordo']);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }
}

