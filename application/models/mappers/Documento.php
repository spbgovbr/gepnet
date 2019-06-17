<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Documento extends App_Model_Mapper_MapperAbstract
{

    protected $_dependencies = array('log');

    /**
     *
     * @var Zend_Log
     */
    protected $_log;

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Documento
     */
    public function insert(Default_Model_Documento $model)
    {
        try {
            $model->iddocumento = $this->maxVal('iddocumento');
            $data = array(
                "iddocumento" => $model->iddocumento,
                "idescritorio" => $model->idescritorio,
                "nomdocumento" => $model->nomdocumento,
                "idtipodocumento" => $model->idtipodocumento,
//                "descaminho"      => 'documento.pdf',
                "descaminho" => $model->descaminho,
                "datdocumento" => new Zend_Db_Expr("now()"),
                "desobs" => $model->desobs,
                "idcadastrador" => $model->idcadastrador,
                "datcadastro" => new Zend_Db_Expr("now()"),
                "flaativo" => $model->flaativo,
            );
            $id = $this->getDbTable()->insert($data);
            return $id;
        } catch (Exception $exc) {
            $this->_log->err($exc);
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Documento
     */
    public function update(Default_Model_Documento $model)
    {
        $data = array(
            "nomdocumento" => $model->nomdocumento,
            "idtipodocumento" => $model->idtipodocumento,
            // "datcadastro"     => new Zend_Db_Expr("now()"),
            "descaminho" => $model->descaminho,
            "desobs" => $model->desobs,
            "flaativo" => $model->flaativo,
        );

        $data = array_filter($data);
        try {
            $pks = array(
                "iddocumento" => $model->iddocumento,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            //$where = $this->_db->quoteInto('idpessoa = ?', $model->idpessoa);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Documento
     */
    public function delete($params)
    {
        try {
            $pks = array(
                "iddocumento" => $params['iddocumento']
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            //$where = $this->_db->quoteInto('idpessoa = ?', $model->idpessoa);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "SELECT 
                    doc.iddocumento, doc.idescritorio, doc.nomdocumento, doc.idtipodocumento,
                    doc.descaminho, to_char(doc.datdocumento,'DD/MM/YYYY') AS datdocumento, doc.desobs, doc.idcadastrador, doc.datcadastro,
                    doc.flaativo, tip.nomtipodocumento
                FROM agepnet200.tb_documento doc, agepnet200.tb_tipodocumento tip
                WHERE 
                    doc.idtipodocumento = tip.idtipodocumento
                    and doc.iddocumento = :iddocumento";

        $resultado = $this->_db->fetchRow($sql, array('iddocumento' => $params['iddocumento']));
//        Zend_Debug::dump($resultado); exit;
        $documento = new Default_Model_Documento($resultado);
        $tipoDocumento = new Default_Model_Tipodocumento($resultado);
        $documento->tipodocumento = $tipoDocumento;
        return $documento;
    }

    /**
     *
     * @param array $params
     * @return \Default_Model_Documento
     */
    public function retornarPorId($params)
    {
        $resultadoDocumento = $this->getById($params);
        $documento = new Default_Model_Documento($resultadoDocumento);
        $documento->tipodocumento = new Default_Model_Tipodocumento($resultadoDocumento);
        return $documento;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $paginator = false)
    {
        // 'Cargo','Nome','Matrícula','CPF','Lotação','Operações'

        $sql = "SELECT 
                    tip.nomtipodocumento,doc.nomdocumento,
                    to_char(doc.datdocumento,'DD/MM/YYYY') AS datdocumento, doc.iddocumento
                FROM agepnet200.tb_documento doc, agepnet200.tb_tipodocumento tip
                WHERE 
                    doc.idtipodocumento = tip.idtipodocumento";
        $params = array_filter($params);
        if (isset($params['nomdocumento'])) {
            $nomdocumento = strtoupper($params['nomdocumento']);
            $sql .= " AND upper(doc.nomdocumento) LIKE '%{$nomdocumento}%'";
        }

        if (isset($params['tipodocumento'])) {
            $sql .= " AND doc.idtipodocumento =  {$params['tipodocumento']}";
        }

        if (isset($params['data_cadastro_inicial']) && isset($params['data_cadastro_final'])) {
            $dtci = new Zend_Date($params['data_cadastro_inicial'], 'dd/MM/yyyy');
            $dtcf = new Zend_Date($params['data_cadastro_final'], 'dd/MM/yyyy');
            /**
             * Caso as datas inicial e final sejam iguais, adicionamos
             * um dia na data final para garantir o retorno
             */
            if ($dtci->equals($dtcf)) {
                $dtcf->addDay(1);
            }
            $sql .= " and doc.datcadastro BETWEEN TO_DATE('" . $dtci->toString('d/m/Y') . "','DD/MM/YYYY') 
                      AND TO_DATE('" . $dtcf->toString('d/m/Y') . "','DD/MM/YYYY')";
        } else {
            if (isset($params['data_cadastro_inicial'])) {
                $sql .= " and doc.datcadastro >= TO_DATE('" . $params['data_cadastro_inicial'] . "','DD/MM/YYYY')";
            }

            if (isset($params['data_cadastro_final'])) {
                $sql .= " and doc.datcadastro <= TO_DATE('" . $params['data_cadastro_final'] . "','DD/MM/YYYY')";
            }
        }

        if (isset($params['flaativo'])) {
            $sql .= " and doc.flaativo = '" . $params['flaativo'] . "'";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }
        //Zend_Debug::dump($sql); exit;

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($id);exit;
        return $resultado;
    }

}

