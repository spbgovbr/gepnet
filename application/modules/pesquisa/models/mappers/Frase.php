<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Pesquisa_Model_Mapper_Frase extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_Frase
     */
    public function insert(Pesquisa_Model_Frase $model)
    {
        $data = array(
            "idfrase" => $this->maxVal('idfrase'),
            "desfrase" => $model->desfrase,
            "domtipofrase" => $model->domtipofrase,
            "flaativo" => $model->flaativo,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "idescritorio" => $model->idescritorio,
            "idcadastrador" => $model->idcadastrador,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_Frase
     */
    public function update(Pesquisa_Model_Frase $model)
    {
        $data = array(
            "desfrase" => $model->desfrase,
            "domtipofrase" => $model->domtipofrase,
            "flaativo" => $model->flaativo,
        );
        return $this->getDbTable()->update($data, array("idfrase = ?" => $model->idfrase));
    }

//    public function delete($params)
//    {
//       $where =  $this->quoteInto('idfrase = ?', (int)$params['idfrase']);        
//       $result =  $this->getDbTable()->delete($where);
//       return $result;
//    }

    public function getForm()
    {
        return $this->_getForm(Pesquisa_Form_Frase);
    }

    public function retornaPerguntasToGrid($params)
    {
        $params = array_filter($params);
        $sql = "SELECT
                    desfrase, 
                    CASE 
                        WHEN domtipofrase = 1 THEN 'Uma-escolha'
                        WHEN domtipofrase = 2 THEN 'Multipla-escolha'
                        WHEN domtipofrase = 3 THEN 'Descritivo (em várias linhas)'
                        WHEN domtipofrase = 4 THEN 'Texto (em uma linha)'
                        WHEN domtipofrase = 5 THEN 'Número'
                        WHEN domtipofrase = 6 THEN 'Data'
                        WHEN domtipofrase = 7 THEN 'UF'
                     END as domtipofrase,
                     CASE 
                        WHEN flaativo = 'S' THEN '<span class=\"badge badge-success\">Ativo</span>'
                        WHEN flaativo = 'N' THEN '<span class=\"badge badge-important\">Inativo</span>'
                     END as flaativo,
                    idfrase,
                    domtipofrase as inttipo
                FROM agepnet200.tb_frase 
                WHERE 1 = 1 ";

        if (isset($params['desfrase']) && $params['desfrase'] != "") {
            $sql .= " AND desfrase ilike'%{$params['desfrase']}%'";
        }
        if (isset($params['domtipofrase']) && $params['domtipofrase'] != "") {
            $sql .= " AND domtipofrase  = {$params['domtipofrase']}";
        }
        if (isset($params['flaativo']) && $params['flaativo'] != "") {
            $sql .= " AND flaativo  = '{$params['flaativo']}'";
        }

        $sql .= 'order by ' . $params['sidx'] . ' ' . $params['sord'];
        try {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        } catch (Exception $exc) {
            throw new Exception($exc->code());
        }

    }

    public function getById($params)
    {
        $sql = "SELECT * FROM agepnet200.tb_frase where idfrase =  :idfrase ";

        return $this->_db->fetchRow($sql, array('idfrase' => (int)$params['idfrase']));
    }


    public function getByIdDetalhar($params)
    {
        $params = array_filter($params);

        $sql = "SELECT
                    desfrase, 
                    CASE 
                        WHEN domtipofrase = 1 THEN 'Uma-escolha'
                        WHEN domtipofrase = 2 THEN 'Multipla-escolha'
                        WHEN domtipofrase = 3 THEN 'Descritivo (em várias linhas)'
                        WHEN domtipofrase = 4 THEN 'Texto (em uma linha)'
                        WHEN domtipofrase = 5 THEN 'Número'
                        WHEN domtipofrase = 6 THEN 'Data'
                        WHEN domtipofrase = 7 THEN 'UF'
                     END as domtipofrase,
                     CASE 
                        WHEN flaativo = 'S' THEN '<span class=\"badge badge-success\">Ativo</span>'
                        WHEN flaativo = 'N' THEN '<span class=\"badge badge-important\">Inativo</span>'
                     END as flaativo,
                    idfrase,
                    domtipofrase as inttipo
                FROM agepnet200.tb_frase 
                WHERE idfrase =  :idfrase";

        return $this->_db->fetchRow($sql, array('idfrase' => $params['idfrase']));
    }
}