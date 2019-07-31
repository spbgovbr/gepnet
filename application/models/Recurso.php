<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 28-06-2013 10:07
 */
class Default_Model_Recurso extends App_Model_ModelAbstract
{

    public $idrecurso = null;

    public $ds_recurso = null;

    public $permissions = array();

    /**
     *
     * @param Default_Model_Permissao $permissao
     */
    public function adicionarPermissao(Default_Model_Permissao $permissao)
    {
        $index = $this->ds_recurso . '_' . $permissao->no_permissao;
        $this->permissions[$index] = $permissao;
        return $this;
    }

    /**
     *
     * @return boolean | array
     */
    public function retornaPermissoes()
    {
        if (count($this->permissions) > 0) {
            return $this->permissions;
        }
        return false;
    }
}

