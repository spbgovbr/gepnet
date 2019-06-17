<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Tep extends App_Model_Mapper_MapperAbstract
{


    protected function _init()
    {
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Tep
     */
    public function update(Projeto_Model_Tep $model)
    {

        if (@trim($model->desconsideracaofinal) != "") {
            $data = array(
                "desconsideracaofinal" => $model->desconsideracaofinal,
            );
        } else {
            return true;
        }
        $data = array_filter($data);

//        Zend_Debug::dump($data); exit;

        try {
            $pks = array("idprojeto" => $model->idprojeto);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }
}
