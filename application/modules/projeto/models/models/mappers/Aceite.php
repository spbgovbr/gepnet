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
                "idaceite"          => $model->idaceite,
                "desprodutoservico" => $model->desprodutoservico,
                "desparecerfinal"   => $model->desparecerfinal,
                "idcadastrador"     => $model->idcadastrador,
                "datcadastro"       => new Zend_Db_Expr("now()"),
            );
            //Zend_Debug::dump($data);exit;
            $retorno = $this->getDbTable()->insert($data);

            //Zend_Debug::dump($model);exit;
            return $model;

        }  catch (Exception $exc){
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param  Projeto_Model_Aceite
     * @return 
     */
    public function update(Projeto_Model_Aceite $model)
    {
        $data = array(
            "idaceite"          => $model->idaceite,
            "desprodutoservico" => $model->desprodutoservico,
            "desparecerfinal"   => $model->desparecerfinal,
        );
        
        try {
        	$pks     = array(
        			"idaceite" => $model->idaceite,
        	);
        	$where   = $this->_generateRestrictionsFromPrimaryKeys($pks);
        	$retorno = $this->getDbTable()->update($data, $where);
        	return $retorno;
        } catch ( Exception $exc ) {
        	throw $exc;
        }
    }

    public function delete($params){
        try {
            $pks = array(
                "idaceite" => $params['idaceite'],
            );
            $where   = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            //var_dump($retorno); //exit;
            return $retorno;
        } catch ( Exception $exc ) {
            throw $exc;
            //exit;
        }
    }    
    
    public function getForm()
    {
        return $this->_getForm(Projeto_Form_Aceite);
    }

    public function getById($params) {
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
                    acm.nomatividadecronograma as nomarco
                FROM agepnet200.tb_aceiteatividadecronograma aac
                     INNER JOIN agepnet200.tb_aceite a on a.idaceite = aac.idaceite
                     INNER JOIN agepnet200.tb_atividadecronograma ac on ac.idprojeto = aac.idprojeto
                                and ac.idatividadecronograma = aac.identrega and ac.domtipoatividade = 2
                     LEFT JOIN agepnet200.tb_atividadecronograma acm on ac.idprojeto = aac.idprojeto
                                and ac.idatividadecronograma = aac.identrega and ac.domtipoatividade = 4
                     LEFT  JOIN agepnet200.tb_parteinteressada pi on pi.idparteinteressada=ac.idparteinteressada
                WHERE aac.idaceite = $idaceite and aac.idprojeto = $idprojeto ";

        $resultado = $this->_db->fetchRow($sql);

        return new Projeto_Model_Aceite($resultado);
    }

    public function retornaPorProjeto($params, $paginator = false) {

        $idprojeto = $params['idprojeto'];

        $sql = "SELECT
                          ac.nomatividadecronograma,
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
                          ac.desobs
                        FROM agepnet200.tb_aceiteatividadecronograma aac
                             INNER JOIN agepnet200.tb_aceite a on a.idaceite=aac.idaceite
                             INNER JOIN agepnet200.tb_atividadecronograma ac on ac.idatividadecronograma=aac.identrega
                                        and ac.idprojeto=aac.idprojeto and ac.domtipoatividade = 2
                             LEFT JOIN  agepnet200.tb_parteinteressada pi on pi.idparteinteressada=ac.idparteinteressada
                        WHERE aac.idprojeto = ".$idprojeto."";

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }
        //, array('idprojeto' => $idprojeto)
        $resultado = $this->_db->fetchAll($sql);


        return $resultado;
        
        
    }
}

