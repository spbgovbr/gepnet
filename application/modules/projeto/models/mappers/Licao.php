<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Licao extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Licao
     */
    public function insert(Projeto_Model_Licao $model)
    {

        try {
            $model->idlicao = $this->maxVal('idlicao');
            $data = array(
                "idlicao" => $model->idlicao,
                "idprojeto" => $model->idprojeto,
                "identrega" => $model->identrega,
                "desresultadosobtidos" => $model->desresultadosobtidos,
                "despontosfortes" => $model->despontosfortes,
                "despontosfracos" => $model->despontosfracos,
                "dessugestoes" => $model->dessugestoes,
                "datcadastro" => new Zend_Db_Expr("now()"),
                "idassociada" => $model->idassociada,
            );
            $retorno = $this->getDbTable()->insert($data);
            return $retorno;

        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param Projeto_Model_Licao
     * @return
     */
    public function update(Projeto_Model_Licao $model)
    {
        if ($model->idassociada == 2) {
            $model->identrega = null;
        }

        $data = array(
            "identrega" => (int)$model->identrega,
            "desresultadosobtidos" => $model->desresultadosobtidos,
            "despontosfortes" => $model->despontosfortes,
            "despontosfracos" => $model->despontosfracos,
            "dessugestoes" => $model->dessugestoes,
            "idassociada" => $model->idassociada
        );

        try {
            $pks = array(
                "idlicao" => $model->idlicao,
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

        $pks = array(
            "idlicao" => $params['idlicao'],
        );
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function getForm()
    {
        return $this->_getForm(Projeto_Form_Licao);
    }

    public function getById($params)
    {

        // var_dump($params['idlicao']);
        $sql = "SELECT
                      ac.nomatividadecronograma,
                      l.desresultadosobtidos,
                      l.despontosfortes,
                      l.despontosfracos,
                      l.dessugestoes,
                      l.idlicao,
                      l.idprojeto,
                      CASE
                        WHEN idassociada = '1' THEN 'Entrega'
                        WHEN idassociada = '2' THEN 'Projeto'
                        END
                        AS nomassociada,
                      l.identrega,
                      l.idassociada,
                      proj.desobjetivo,
                      proj.desprojeto
                      from agepnet200.tb_licao l
left join agepnet200.tb_atividadecronograma ac on ac.idprojeto = l.idprojeto
and l.identrega = ac.idatividadecronograma
left join agepnet200.tb_projeto proj on proj.idprojeto = l.idprojeto
                WHERE idlicao = :idlicao
                  ";
        //var_dump($sql); exit;
        $resultado = $this->_db->fetchRow($sql, array(':idlicao' => $params['idlicao']));
        return new Projeto_Model_Licao($resultado);
    }

    public function retornaLicaoPorProjeto($idprojeto)
    {


        //$idprojeto = $params['idprojeto'];
        $sql = "
                SELECT
                    CASE
                        WHEN idassociada = '1' THEN 'Entrega'
                        WHEN idassociada = '2' THEN 'Projeto'
                        END
                    AS nomassociada,
                      ac.nomatividadecronograma,
                      l.desresultadosobtidos,
                      l.despontosfortes,
                      l.despontosfracos,
                      l.dessugestoes,
                      l.idlicao,
                      l.idprojeto,
                      l.identrega,
                      l.idassociada,
                      proj.desobjetivo,
                      proj.desprojeto     
                FROM  agepnet200.tb_licao l
                LEFT JOIN  agepnet200.tb_atividadecronograma ac on ac.idprojeto = l.idprojeto
                  AND l.identrega = ac.idatividadecronograma and (ac.domtipoatividade = " . Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_ENTREGA . ")
                LEFT JOIN  agepnet200.tb_projeto proj on proj.idprojeto = l.idprojeto
                WHERE l.idprojeto = {$idprojeto}";
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function retornaPorProjeto($params, $paginator = false)
    {

        $idprojeto = $params['idprojeto'];
        $sql = "
                SELECT
                    CASE
                        WHEN idassociada = '1' THEN 'Entrega'
                        WHEN idassociada = '2' THEN 'Projeto'
                        END
                    AS nomassociada,
                      ac.nomatividadecronograma,
                      l.desresultadosobtidos,
                      l.despontosfortes,
                      l.despontosfracos,
                      l.dessugestoes,
                      l.idlicao,
                      l.idprojeto,
                      l.identrega     
                FROM  agepnet200.tb_licao l
                LEFT JOIN  agepnet200.tb_atividadecronograma ac on ac.idprojeto = l.idprojeto
                and l.identrega = ac.idatividadecronograma and (ac.domtipoatividade = " . Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_ENTREGA . ")
                WHERE l.idprojeto = {$idprojeto}";

        $params = array_filter($params);

        if (isset($params['identrega'])) {
            $identrega = $params['identrega'];
            $sql .= " and l.identrega = {$identrega} ";
        }

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function retornTipoassociada($params)
    {
        $idprojeto = $params['idprojeto'];
        $idlicao = $params['idlicao'];
        $sql = "SELECT idassociada
                FROM agepnet200.tb_licao 
                where idprojeto = {$idprojeto} 
                and idlicao = {$idlicao}";

        $resultado = $this->_db->fetchOne($sql);
        return $resultado;
    }

}