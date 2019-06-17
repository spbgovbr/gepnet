<?php

/**
 * Newton Carlos
 *
 * Criado em 14-11-2018
 * 16:07
 */
class Diagnostico_Model_Mapper_UnidadeVinculada extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     *
     * @param string $value
     * @return Diagnostico_Model_UnidadeVinculada
     */
    public function insert(Diagnostico_Model_UnidadeVinculada $model)
    {
        $data = array(
            "idunidade" => $model->idunidade,
            "id_unidadeprincipal" => $model->idunidadeprincipal,
            "iddiagnostico" => $model->iddiagnostico,
        );
        $retorno = $this->getDbTable()->insert($data);
        return $retorno;
    }

    public function retornaUnidadeVinculadaByIdDiagonosticoAndUnidadePrincial($params)
    {
        $sql = "select ARRAY_TO_STRING(ARRAY_AGG(u.sigla), ', ')
                from agepnet200.tb_unidade_vinculada uv
                inner join vw_comum_unidade u on u.id_unidade=uv.idunidade
                where uv.iddiagnostico=:iddiagnostico and uv.id_unidadeprincipal=:idunidadeprincipal ";
        $retorno = $this->_db->fetchAll($sql, array(
            'iddiagnostico' => $params['iddiagnostico'],
            'idunidadeprincipal' => $params['idunidadeprincipal']
        ));
    }

    public function retornaUnidadeVinculadaByIdDiagonostico($params)
    {
        $sql = "select ARRAY_TO_STRING(ARRAY_AGG(u.sigla), ', ')
                from agepnet200.tb_unidade_vinculada uv
                inner join vw_comum_unidade u on u.id_unidade=uv.idunidade
                where uv.iddiagnostico=:iddiagnostico  ";
        $retorno = $this->_db->fetchAll($sql, array(
            'iddiagnostico' => $params['iddiagnostico']
        ));
    }

    public function retornaUnidadeSubordinada($params)
    {
        $sql = "
                WITH RECURSIVE unidade(id_unidade,
                      unidade_responsavel,
                      sigla,
                      pai,
                      ordenacao,
                      nivel) AS (
                        SELECT
                            u.id_unidade,
                            u.unidade_responsavel,
                            u.sigla,
                            u.id_unidade AS pai,
                            ARRAY[u.id_unidade] AS ordenacao,
                            1 AS nivel
                        FROM vw_comum_unidade u
                        WHERE u.id_unidade=:idunidadeprincipal

                        UNION ALL

                        SELECT
                            u.id_unidade,
                            u.unidade_responsavel,
                            u.sigla,
                            u.id_unidade AS pai,
                            up.ordenacao || u.id_unidade AS ordenacao,
                            up.nivel + 1 AS nivel
                        FROM vw_comum_unidade u
                        JOIN unidade up on up.id_unidade=u.unidade_responsavel
                     )
                SELECT u.* FROM unidade u ORDER BY u.ordenacao ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idunidadeprincipal' => $params['idunidadeprincipal']
        ));

        return $resultado;
    }


    public function deletar($params)
    {
        try {
            $sql = "
                DELETE FROM agepnet200.tb_unidade_vinculada
                WHERE iddiagnostico = :iddiagnostico ";

            $resultado = $this->_db->fetchAll($sql, array(
                'iddiagnostico' => $params['iddiagnostico']
            ));
            return $resultado;

        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function retornaUnidadeSelecionada($params)
    {
        $sql = "SELECT sigla
                FROM vw_comum_unidade         
                WHERE id_unidade =: id_unidade  ";
        $retorno = $this->_db->fetchRow($sql, array(
            'id_unidade' => $params['id_unidade']
        ));
    }

}