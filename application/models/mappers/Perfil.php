<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Perfil extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Perfil
     */
    public function insert(Default_Model_Perfil $model)
    {
        $data = array(
            "idperfil" => $model->idperfil,
            "nomperfil" => $model->nomperfil,
            "flaativo" => $model->flaativo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Perfil
     */
    public function update(Default_Model_Perfil $model)
    {
        $data = array(
            "idperfil" => $model->idperfil,
            "nomperfil" => $model->nomperfil,
            "flaativo" => $model->flaativo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Perfil);
    }

    public function retornaPorPessoa($params)
    {

        $sql = "select
                    per.idperfil || '-' || esc.idescritorio as ID, 
                    per.nomperfil || ' (' || esc.nomescritorio || ') ' as TEXT
                from
                    agepnet200.tb_perfilpessoa ppe
                inner join agepnet200.tb_perfil per     on per.idperfil = ppe.idperfil
                inner join agepnet200.tb_escritorio esc on esc.idescritorio = ppe.idescritorio
                where ppe.idpessoa = :idpessoa
                and ppe.flaativo = 'S'
                order by per.nomperfil";

        return $this->_db->fetchPairs($sql, array(
            'idpessoa' => $params['idpessoa'],
        ));
    }

    public function retornaPorIdEPessoa($params)
    {
        $prefilEEscritorio = explode('-', $params['idperfil']);

        $sql = "select
                    per.idperfil, per.nomperfil || ' (' || esc.nomescritorio || ') ' as nomperfil,
                    esc.idescritorio, esc.nomescritorio as nomescritorio, trim(per.nomperfil) as dsperfil 
                from agepnet200.tb_perfilpessoa pp   
                inner join agepnet200.tb_perfil per on pp.idperfil = per.idperfil 
                inner join agepnet200.tb_escritorio esc on esc.idescritorio = pp.idescritorio 
                where 1=1 ";

        if (empty($params['idpessoa'])) {
            $sql .= "and pp.idperfil = :idperfil ";
            $sql .= "and pp.idescritorio = :idescritorio ";
            $sql .= "group by per.idperfil,per.nomperfil,esc.idescritorio, esc.nomescritorio ";
            $sql .= "order by per.nomperfil";

            $resultado = $this->_db->fetchRow($sql, array(
                'idperfil' => (int)$prefilEEscritorio[0],
                'idescritorio' => $prefilEEscritorio[1],
            ));
        } else {
            if (!empty($params['idpessoa'])) {
                $sql .= "and pp.idpessoa = :idpessoa and pp.idperfil = :idperfil ";
                $sql .= "and pp.idescritorio = :idescritorio ";
                $sql .= "group by per.idperfil,per.nomperfil,esc.idescritorio, esc.nomescritorio ";
                $sql .= "order by per.nomperfil";
                $resultado = $this->_db->fetchRow($sql, array(
                    'idperfil' => $prefilEEscritorio[0],
                    'idpessoa' => $params['idpessoa'],
                    'idescritorio' => $prefilEEscritorio[1],
                ));

            }
        }
        return $resultado;
    }

    public function getById($params)
    {
        $sql = "select
                    per.idperfil, 
                    per.nomperfil,
                    per.flaativo,
                    per.idcadastrador,
                    per.datcadastro
                from
                    agepnet200.tb_perfil per
                where 
                    per.idperfil = :idperfil
                order by per.nomperfil";

        return $this->_db->fetchRow($sql, array(
            'idperfil' => $params['idperfil'],
        ));
    }

    public function retornaPorId($params)
    {
        $perfil = $this->getById($params);
        return new Default_Model_Perfil($perfil);
    }

    public function fetchPairs()
    {
        $sql = "select per.idperfil, per.nomperfil
                from agepnet200.tb_perfil per
                where per.flaativo = 'S'
                order by idperfil asc";

        return $this->_db->fetchPairs($sql);
    }

    public function authfetchPairs($identiti)
    {
        $sql = "select per.idperfil, per.nomperfil
                from agepnet200.tb_perfil per
                where per.flaativo = 'S'";

        if (isset($identiti) && $identiti <> 1) {
            $sql .= " and per.idperfil <> 1 "
                . "order by idperfil asc";
        }
        //Zend_Debug::dump($sql);die;
        return $this->_db->fetchPairs($sql);
    }

}

