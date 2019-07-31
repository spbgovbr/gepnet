<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Aceite extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Aceite
     */
    public function insert(Projeto_Model_Aceite $model)
    {
        try {

            $model->idaceite = $this->maxVal('idaceite');

            $data = array(
                "idaceite" => $model->idaceite,
                "desprodutoservico" => $model->desprodutoservico,
                "desparecerfinal" => $model->desparecerfinal,
                "idcadastrador" => $model->idcadastrador,
                "datcadastro" => new Zend_Db_Expr("now()"),
            );
            //Zend_Debug::dump($data);exit;
            $retorno = $this->getDbTable()->insert($data);

            //Zend_Debug::dump($model);exit;
            return $model;

        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param Projeto_Model_Aceite
     * @return
     */
    public function update(Projeto_Model_Aceite $model)
    {
        $data = array(
            "idaceite" => $model->idaceite,
            "desprodutoservico" => $model->desprodutoservico,
            "desparecerfinal" => $model->desparecerfinal,
        );

        try {
            $pks = array(
                "idaceite" => $model->idaceite,
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
        try {
            $pks = array(
                "idaceite" => $params['idaceite'],
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            //var_dump($retorno); //exit;
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
            //exit;
        }
    }

    public function getForm()
    {
        return $this->_getForm(Projeto_Form_Aceite);
    }

    public function getById($params)
    {
        $idaceite = $params['idaceite'];
        $idprojeto = $params['idprojeto'];
        $sql = "SELECT
                    aac.idaceite,
                    aac.identrega,
                    aac.idprojeto,
                    a.desprodutoservico,
                    a.desparecerfinal,
                    a.idcadastrador,
                    a.datcadastro,
                    aac.aceito,
                    ac.nomatividadecronograma,
                    ac.descriterioaceitacao,
                    pi.nomparteinteressada as nomresponsavel,
                    CASE
                      WHEN aac.aceito = 'S' THEN 'Sim'
                      WHEN aac.aceito = 'N' THEN 'Não'
                      ELSE ''
                    END as desflaaceite,
                    ac.desobs,
                    acm.nomatividadecronograma as nomarco,
                    (SELECT
                        atc.nomatividadecronograma
                        FROM agepnet200.tb_atividadecronograma atc
                        INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = atc.idprojeto and tbp.idtipoiniciativa = 1
                        WHERE
                            atc.idprojeto = :idprojeto
                            and atc.domtipoatividade = 1
                            and idatividadecronograma = ac.idgrupo) as grupo,
                    (SELECT p1.nomparteinteressada FROM agepnet200.tb_atividadecronograma cron
                    INNER JOIN agepnet200.tb_projeto tbp on tbp.idprojeto = cron.idprojeto
                          and tbp.idtipoiniciativa = 1 LEFT OUTER JOIN
                        agepnet200.tb_parteinteressada p1 ON cron.idresponsavel = p1.idparteinteressada
                    WHERE
                        p1.idparteinteressada = cron.idresponsavel
                        and cron.idprojeto = aac.idprojeto
                        and idatividadecronograma = aac.identrega
                        ) as nomparteinteressadaentrega FROM agepnet200.tb_aceiteatividadecronograma aac
                     INNER JOIN agepnet200.tb_aceite a on a.idaceite = aac.idaceite
                     INNER JOIN agepnet200.tb_atividadecronograma ac on ac.idprojeto = aac.idprojeto
                                and ac.idatividadecronograma = aac.identrega and ac.domtipoatividade = 2
                     LEFT JOIN agepnet200.tb_atividadecronograma acm on ac.idprojeto = aac.idprojeto
                                and ac.idatividadecronograma = aac.identrega and ac.domtipoatividade = 4
                     LEFT  JOIN agepnet200.tb_parteinteressada pi on pi.idparteinteressada=ac.idparteinteressada
                WHERE aac.idaceite = :idaceite and aac.idprojeto = :idprojeto ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idaceite' => $idaceite,
            'idprojeto' => $idprojeto,
        ));

        return new Projeto_Model_Aceite($resultado);
    }

    public function retornaPorProjeto($params, $paginator = false)
    {
        $idprojeto = $params['idprojeto'];

        $sql = "SELECT ac.nomatividadecronograma,
                      ac.descriterioaceitacao,
                      pi.nomparteinteressada as nomresponsavel,
                      a.desprodutoservico,
                      a.desparecerfinal,
                      CASE
                        WHEN aac.aceito = 'S' THEN 'Sim'
                        WHEN aac.aceito = 'N' THEN 'Não'
                        ELSE ''
                       END as flaaceite,
                      a.idaceite,
                      aac.identrega,
                      aac.idprojeto,
                      aac.idmarco,
                      a.idcadastrador,
                      a.datcadastro,
                      ac.desobs,
                      acm.nomatividadecronograma AS nomarco,
                      (SELECT atc.nomatividadecronograma
                         FROM agepnet200.tb_atividadecronograma atc
                         JOIN agepnet200.tb_projeto tbp 
                           ON tbp.idprojeto = atc.idprojeto
                          AND tbp.idtipoiniciativa = 1 
                        WHERE atc.idprojeto = aac.idprojeto
                          AND atc.domtipoatividade = 1
                          AND atc.idatividadecronograma = atc.idgrupo) AS grupo,
                      (SELECT p1.nomparteinteressada 
                         FROM agepnet200.tb_atividadecronograma cron 
                         JOIN agepnet200.tb_projeto tbp 
                           ON tbp.idprojeto = cron.idprojeto 
                          AND tbp.idtipoiniciativa = 1
                         LEFT OUTER JOIN agepnet200.tb_parteinteressada p1 
                           ON cron.idresponsavel = p1.idparteinteressada
                        WHERE p1.idparteinteressada = cron.idresponsavel
                          AND cron.idprojeto = aac.idprojeto 
                          AND cron.idatividadecronograma = aac.identrega) AS nomparteinteressadaentrega
                  FROM agepnet200.tb_aceiteatividadecronograma aac 
                  JOIN agepnet200.tb_aceite a 
                    ON a.idaceite = aac.idaceite 
                  JOIN agepnet200.tb_atividadecronograma ac 
                    ON ac.idatividadecronograma = aac.identrega 
                   AND ac.idprojeto = aac.idprojeto 
                   AND ac.domtipoatividade = 2 
                  LEFT JOIN agepnet200.tb_atividadecronograma acm 
                    ON acm.idatividadecronograma = aac.idmarco 
                   AND acm.idprojeto = aac.idprojeto 
                  LEFT JOIN  agepnet200.tb_parteinteressada pi 
                    ON pi.idparteinteressada = ac.idparteinteressada 
                 WHERE aac.idprojeto = {$idprojeto}";

        if ((@trim($params['idaceite']) != "") && (@trim($params['identrega']) != "")) {
            $identrega = $params['identrega'];
            $idaceite = $params['idaceite'];
            $sql .= " AND aac.identrega = {$identrega} 
                      AND aac.idaceite = {$idaceite}";
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
}

