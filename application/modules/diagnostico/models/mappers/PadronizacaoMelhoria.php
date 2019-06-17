<?php

/**
 * Automatically generated data model
 *
 */
class Diagnostico_Model_Mapper_PadronizacaoMelhoria extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_PadronizacaoMelhoria
     */
    public function insert(Diagnostico_Model_PadronizacaoMelhoria $model)
    {
        $data = array(
            'idpadronizacaomelhoria' => $this->maxVal('idpadronizacaomelhoria'),
            'idmelhoria' => $model->idmelhoria,
            'desrevisada' => $model->desrevisada,
            'idprazo' => $model->idprazo,
            'idimpacto' => $model->idimpacto,
            'idesforco' => $model->idesforco,
            'numpontuacao' => $model->numpontuacao == "" ? 0 : $model->numpontuacao,
            'numincidencia' => $model->numincidencia,
            'numvotacao' => $model->numvotacao,
            'flaagrupadora' => $model->flaagrupadora == "" ? 0 : $model->flaagrupadora,
            'destitulogrupo' => $model->destitulogrupo,
            'desmelhoriaagrupadora' => $model->desmelhoriaagrupadora == "" ? 0 : $model->desmelhoriaagrupadora,
            'desinformacoescomplementares' => $model->desinformacoescomplementares,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Diagnostico_Model_PadronizacaoMelhoria
     */
    public function update(Diagnostico_Model_PadronizacaoMelhoria $model)
    {
        $data = array(
            'idpadronizacaomelhoria' => $model->idpadronizacaomelhoria,
            'idmelhoria' => $model->idmelhoria,
            'desrevisada' => $model->desrevisada,
            'idprazo' => $model->idprazo,
            'idimpacto' => $model->idimpacto,
            'idesforco' => $model->idesforco,
            'numpontuacao' => $model->numpontuacao == "" ? 0 : $model->numpontuacao,
            'numincidencia' => $model->numincidencia,
            'numvotacao' => $model->numvotacao,
            'flaagrupadora' => $model->flaagrupadora == "" ? 0 : $model->flaagrupadora,
            'destitulogrupo' => $model->destitulogrupo,
            'desmelhoriaagrupadora' => $model->desmelhoriaagrupadora == "" ? 0 : $model->desmelhoriaagrupadora,
            'desinformacoescomplementares' => $model->desinformacoescomplementares,
        );
//        Zend_Debug::dump($data); exit;
        if ($model->idpadronizacaomelhoria == "") {
            $ret = $this->insert($model);
        } else {
            $ret = $this->getDbTable()->update($data, array("idmelhoria = ?" => $model->idmelhoria));
        }
        return $ret;
    }

    public function delete($params)
    {
        $where = $this->quoteInto('idpadronizacaomelhoria = ?', (int)$params['idpadronizacaomelhoria']);
        $result = $this->getDbTable()->delete($where);
        return $result;
    }

    public function deletePadronizacao($params)
    {
        $where = $this->quoteInto('idmelhoria = ?', (int)$params['idmelhoria']);
        $result = $this->getDbTable()->delete($where);
        return $result;
    }

    public function getForm()
    {
        return $this->_getForm(Projeto_Form_PadronizacaoMelhoria);
    }

    public function getByIdDetalhar($params)
    {
        $sql = "select  
                    tq.idmelhoria, 
                    to_char(tq.datmelhoria,  'DD/MM/YYYY') as datmelhoria,
                    (select p.nomprocesso from agepnet200.tb_processo p where p.idprocesso = tq.idmacroprocessotrabalho) as macroprocessotrabalho, 
                    (select p.nomprocesso from agepnet200.tb_processo p where p.idprocesso = tq.idmacroprocessomelhorar) as macroprocessomelhorar, 
                    tq.idunidadeprincipal, 
                    tq.matriculaproponente, 
                    tq.desmelhoria, 
                    tq.idunidaderesponsavelproposta, 
                    tq.flaabrangencia, 
                    tq.idunidaderesponsavelimplantacao, 
                    tq.idobjetivoinstitucional, 
                    tq.idacaoestrategica, 
                    tq.idareamelhoria, 
                    tq.idsituacao, 
                    tq.iddiagnostico,
                    tq.idmacroprocessotrabalho, 
                    tq.idmacroprocessomelhorar
            FROM agepnet200.tb_questionariodiagnosticomelhoria tq
            WHERE tq.idmelhoria = :idmelhoria";
        $resultado = $this->_db->fetchRow($sql, array('idmelhoria' => $params['idmelhoria']));
        return $resultado;
    }

    public function getByIdPadronizacao($params)
    {
        $sql = "SELECT count(idmelhoria) 
                FROM agepnet200.tb_questdiagnosticopadronizamelhoria
                WHERE idmelhoria = :idmelhoria";
        $resultado = $this->_db->fetchRow($sql, array('idmelhoria' => $params['idmelhoria']));
        return $resultado;
    }

    public function getByPontuacao($params)
    {
        $sql = "SELECT idprazo, idimpacto, idesforco   
                FROM agepnet200.tb_questdiagnosticopadronizamelhoria
                WHERE idmelhoria = :idmelhoria";
        $resultado = $this->_db->fetchRow($sql, array('idmelhoria' => $params['idmelhoria']));
        return $resultado;
    }

    public function retornaPorDiagnosticoToGrid($params)
    {
        $params = array_filter($params);
        $sql = "select  
                    tq.idmelhoria, 
                    to_char(tq.datmelhoria,  'DD/MM/YYYY') as datmelhoria,
                    (select p.nomprocesso from agepnet200.tb_processo p where p.idprocesso = tq.idmacroprocessotrabalho) as macroprocessotrabalho, 
                    (select p.nomprocesso from agepnet200.tb_processo p where p.idprocesso = tq.idmacroprocessomelhorar) as macroprocessomelhorar, 
                    tq.idunidadeprincipal, 
                    tq.matriculaproponente, 
                    tq.desmelhoria, 
                    tq.idunidaderesponsavelproposta, 
                    tq.flaabrangencia, 
                    tq.idunidaderesponsavelimplantacao, 
                    tq.idobjetivoinstitucional, 
                    tq.idacaoestrategica, 
                    tq.idareamelhoria, 
                    tq.idsituacao, 
                    tq.iddiagnostico,
                    tq.idmacroprocessotrabalho, 
                    tq.idmacroprocessomelhorar
            FROM agepnet200.tb_questionariodiagnosticomelhoria tq
            WHERE tq.iddiagnostico = " . (int)$params['iddiagnostico'];

        if (isset($params['datmelhoria']) && $params['datmelhoria'] == true) {
            $sql .= " AND to_char(tq.datmelhoria,  'DD/MM/YYYY') = '" . $params['datmelhoria'] . "'";
        }
        if (isset($params['idmacroprocessotrabalho']) && $params['idmacroprocessotrabalho'] == true) {
            $sql .= " AND tq.idmacroprocessotrabalho = " . $params['idmacroprocessotrabalho'];
        }
        if (isset($params['idmacroprocessomelhorar']) && $params['idmacroprocessomelhorar'] == true) {
            $sql .= " AND tq.idmacroprocessomelhorar = " . $params['idmacroprocessomelhorar'];
        }

        $sql .= ' order by ' . $params['sidx'] . ' ' . $params['sord'];
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
        return;
    }

    public function probabilidade()
    {
        return array(
            '' => 'Selecione',
            '1' => 'Alto',
            '2' => 'Médio',
            '3' => 'Baixo',
        );
    }

    public function impacto()
    {
        return array(
            '' => 'Selecione',
            '1' => 'Alto',
            '2' => 'Médio',
            '3' => 'Baixo',
        );
    }

    public function quantidadeMelhoriaAgrupadora($params)
    {
        $sql = "select count(desmelhoriaagrupadora) quantidade 
            from agepnet200.tb_questdiagnosticopadronizamelhoria 
            where desmelhoriaagrupadora = :desmelhoriaagrupadora";
        $resultado = $this->_db->fetchRow($sql, array('desmelhoriaagrupadora' => $params['desmelhoriaagrupadora']));
        return $resultado;
    }

}
