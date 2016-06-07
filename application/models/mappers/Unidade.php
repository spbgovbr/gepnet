<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Unidade extends App_Model_Mapper_MapperAbstract
{

    public function getById($params)
    {
        $sql = "SELECT 
                    id_unidade, domcargo sigla, nome, unidade_responsavel, tipo
                FROM vw_comum_unidade
                WHERE id_unidade = :id_unidade";
        return $this->_db->fetchOne($sql,array('id_unidade'=>$params['id_unidade']));
    }
    /**
     * @todo Desenvolver o método retornando o json filtrado para o componente select2
     * @param type $params
     * @return type
     */
    public function fetchPairs()
    {
        $sql = "SELECT 
                    id_unidade, domcargo AS sigla
                FROM vw_comum_unidade
                --WHERE ativo = true
                ORDER BY sigla";
        return $this->_db->fetchPairs($sql);
    }

}

