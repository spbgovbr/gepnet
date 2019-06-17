<?php

class Projeto_Model_Mapper_Bloqueioprojeto extends App_Model_Mapper_MapperAbstract
{


    protected function _init()
    {

    }

    /**
     * Set the property
     *
     * @param Projeto_Model_Bloqueioprojeto
     * @return Projeto_Model_Bloqueioprojeto
     */
    public function registrarBloqueioProjeto(Projeto_Model_Bloqueioprojeto $model)
    {

        $model->idbloqueioprojeto = $this->maxVal('idbloqueioprojeto');
        $data = array(
            'idbloqueioprojeto' => $model->idbloqueioprojeto,
            'idprojeto' => $model->idprojeto,
            'datbloqueio' => new Zend_Db_Expr('now()'),
        );

        try {
            $this->getDbTable()->insert($data);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param Projeto_Model_Bloqueioprojeto
     * @return Projeto_Model_Bloqueioprojeto
     */
    public function registrarDesbloqueioProjeto(Projeto_Model_Bloqueioprojeto $model)
    {
        $data = array(
            "datdesbloqueio" => new Zend_Db_Expr('now()'),
            "desjustificativa" => $model->desjustificativa,
            'idpessoa' => (int)$model->idpessoa,
            'idprojeto' => (int)$model->idprojeto,
        );

        try {
            $this->getDbTable()->update($data, array("idprojeto = ?" => $data['idprojeto'], 'datdesbloqueio is NULL'));
        } catch (Exception $exc) {
            throw $exc;
        }
    }
}
