<?php

class Pesquisa_Model_Mapper_Resposta extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_Resposta
     */
    public function insert(Pesquisa_Model_Resposta $model)
    {
        $data = array(
            "idresposta" => $this->maxVal('idresposta'),
            "numordem" => $model->numordem,
            "flaativo" => $model->flaativo,
            "flaativo" => $model->flaativo,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "desresposta" => $model->desresposta,
            "idcadastrador" => $model->idcadastrador,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_Resposta
     */
    public function update(Pesquisa_Model_Resposta $model)
    {
        $data = array(
            "desresposta" => $model->desresposta,
            "numordem" => $model->numordem,
            "flaativo" => $model->flaativo,
        );
        return $this->getDbTable()->update($data, array("idresposta = ?" => $model->idresposta));
    }

//    public function delete($params)
//    {
//       $where =  $this->quoteInto('idresposta = ?', (int)$params['idresposta']);        
//       $result =  $this->getDbTable()->delete($where);
//       return $result;
//    }

    public function getForm()
    {
        return $this->_getForm(Pesquisa_Form_Resposta);
    }

    public function retornaRespostasToGrid($params)
    {
        $params = array_filter($params);
        $sql = "SELECT
                    tr.desresposta,
                    tr.numordem,
                     CASE 
                        WHEN tr.flaativo = 'S' THEN '<span class=\"badge badge-success\">Ativo</span>'
                        WHEN tr.flaativo = 'N' THEN '<span class=\"badge badge-important\">Inativo</span>'
                     END as flaativo,
                    tr.idresposta 
                FROM agepnet200.tb_resposta tr
                INNER JOIN agepnet200.tb_respostafrase trf ON trf.idresposta =  tr.idresposta 
                WHERE trf.idfrase = " . (int)$params['idfrase'];

        if (isset($params['desresposta']) && $params['desresposta'] != "") {
            $sql .= " AND tr.desresposta ilike'%{$params['desresposta']}%'";
        }
        if (isset($params['numordem']) && $params['numordem'] != "") {
            $sql .= " AND tr.numordem  = {$params['numordem']}";
        }
        if (isset($params['flaativo']) && $params['flaativo'] != "") {
            $sql .= " AND tr.flaativo  = '{$params['flaativo']}'";
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
        $sql = "SELECT 
                    tr.*,
                    tf.desfrase
                FROM agepnet200.tb_resposta tr
                INNER JOIN agepnet200.tb_respostafrase trf ON trf.idresposta =  tr.idresposta
                INNER JOIN agepnet200.tb_frase tf ON tf.idfrase = trf.idfrase
                WHERE tr.idresposta =  :idresposta";

        return $this->_db->fetchRow($sql, array('idresposta' => (int)$params['idresposta']));
    }


    public function getByIdDetalhar($params)
    {
        $params = array_filter($params);

        $sql = "SELECT
                    tr.desresposta, 
                    tr.numordem,
                     CASE 
                        WHEN tr.flaativo = 'S' THEN '<span class=\"badge badge-success\">Ativo</span>'
                        WHEN tr.flaativo = 'N' THEN '<span class=\"badge badge-important\">Inativo</span>'
                     END as flaativo,
                    tr.idresposta 
                FROM agepnet200.tb_resposta tr
                WHERE tr.idresposta =  :idresposta";

        return $this->_db->fetchRow($sql, array('idresposta' => $params['idresposta']));
    }

    public function getByIdPerguntaDetalhar($params)
    {
        $sql = "SELECT
                    tr.desresposta,
                    CASE 
                        WHEN tr.flaativo = 'S' THEN '<span class=\"badge badge-success\">Ativo</span>'
                        WHEN tr.flaativo = 'N' THEN '<span class=\"badge badge-important\">Inativo</span>'
                     END as flaativoresposta,
                    tr.numordem,
                    tf.domtipofrase
                FROM agepnet200.tb_respostafrase trf
                INNER JOIN agepnet200.tb_frase tf on tf.idfrase = trf.idfrase
                LEFT JOIN agepnet200.tb_resposta tr ON tr.idresposta = trf.idresposta
                WHERE tf.idfrase =  :idfrase
                AND tf.domtipofrase IN(1,2,7)";
        return $this->_db->fetchAll($sql, array('idfrase' => $params['idfrase']));
    }
}