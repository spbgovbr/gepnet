<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 08-07-2013
 * 13:45
 */
class Default_Model_Mapper_Permissaoperfil extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Permissaoperfil
     */
    public function insert(Default_Model_Permissaoperfil $model)
    {
        $model->idpermissaoperfil = $this->maxVal('idpermissaoperfil');
        $data = array(
            "idpermissaoperfil" => $model->idpermissaoperfil,
            "idperfil" => $model->idperfil,
            "idpermissao" => $model->idpermissao,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Permissaoperfil
     */
    public function update(Default_Model_Permissaoperfil $model)
    {
        $data = array(
            "idpermissaoperfil" => $model->idpermissaoperfil,
            "idperfil" => $model->idperfil,
            "idpermissao" => $model->idpermissao,
        );
    }

    public function delete($params)
    {
        try {
            $pks = array(
                "idpermissao" => $params['idpermissao'],
                "idperfil" => $params['idperfil'],
            );
            $where[] = $this->_db->quoteInto('idpermissao = ?', $params['idpermissao']);
            $where[] = $this->_db->quoteInto('idperfil = ?', $params['idperfil']);
            //Zend_Debug::dump($pks); exit;

            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

}

