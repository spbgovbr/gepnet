<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Tratamento extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Tratamento
     */
    public function insert(Projeto_Model_Tratamento $model)
    {
        $data = array(
            "idtratamento" => $model->idtratamento,
            "dstratamento" => $model->dstratamento,
            "idcadastrador" => $model->idcadastrador,
            "dtcadastro" => $model->dtcadastro,
            "idtiporisco" => $model->idtiporisco,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Tratamento
     */
    public function update(Projeto_Model_Tratamento $model)
    {
        $data = array(
            "idtratamento" => $model->idtratamento,
            "dstratamento" => $model->dstratamento,
            "idcadastrador" => $model->idcadastrador,
            "dtcadastro" => $model->dtcadastro,
            "idtiporisco" => $model->idtiporisco,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Projeto_Form_Tratamento);
    }

    public function fetchPairs($param = null)
    {
        $param;
        if (!empty($param) && isset($param)) {
            $sql = " select * from agepnet200.tb_tratamento 
                where idtiporisco in ($param, 3) 
                order by dstratamento";
        } else {
            $sql = " select * from agepnet200.tb_tratamento 
                    order by idtratamento";
        }

        $result = $this->_db->fetchAll($sql);

        return $this->_db->fetchAll($sql);
    }

}
