<?php

/**
 * Automatically generated data model
 *
 */
class Diagnostico_Model_Mapper_SugestaoMelhoria extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Risco
     */
    public function insert(Diagnostico_Model_SugestaoMelhoria $model)
    {
//        Zend_Debug::dump($model); exit;
        $data = array(
            "idmelhoria" => $model->idmelhoria,
            "datmelhoria" => $model->datmelhoria == "" ? null :
                new Zend_Db_Expr("to_date('" . $model->datmelhoria . "','DD-MM-YYYY')"),
            "idunidadeprincipal" => $model->idunidadeprincipal,
            "matriculaproponente" => $model->matriculaproponente == "" ? null : $model->matriculaproponente,
            "desmelhoria" => $model->desmelhoria,
            "idmacroprocessotrabalho" => $model->idmacroprocessotrabalho,
            "idmacroprocessomelhorar" => $model->idmacroprocessomelhorar,
            "idunidaderesponsavelproposta" => $model->idunidaderesponsavelproposta,
            "flaabrangencia" => $model->flaabrangencia,
            "idunidaderesponsavelimplantacao" => $model->idunidaderesponsavelimplantacao,
            "idobjetivoinstitucional" => $model->idobjetivoinstitucional,
            "idacaoestrategica" => $model->idacaoestrategica == "" ? null : $model->idacaoestrategica,
            "idareamelhoria" => $model->idareamelhoria == "" ? null : $model->idareamelhoria,
            "idsituacao" => $model->idsituacao,
            "iddiagnostico" => $model->iddiagnostico,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Diagnostico_Model_SugestaoMelhoria
     */
    public function update(Diagnostico_Model_SugestaoMelhoria $model)
    {
        $data = array(
            "idmelhoria" => $model->idmelhoria,
            "datmelhoria" => $model->datmelhoria == "" ? null :
                new Zend_Db_Expr("to_date('" . $model->datmelhoria . "','DD-MM-YYYY')"),
            "idunidadeprincipal" => $model->idunidadeprincipal,
            "matriculaproponente" => $model->matriculaproponente == "" ? null : $model->matriculaproponente,
            "desmelhoria" => $model->desmelhoria,
            "idmacroprocessotrabalho" => $model->idmacroprocessotrabalho,
            "idmacroprocessomelhorar" => $model->idmacroprocessomelhorar,
            "idunidaderesponsavelproposta" => $model->idunidaderesponsavelproposta == "" ? null : $model->idunidaderesponsavelproposta,
            "flaabrangencia" => $model->flaabrangencia,
            "idunidaderesponsavelimplantacao" => $model->idunidaderesponsavelimplantacao,
            "idobjetivoinstitucional" => $model->idobjetivoinstitucional,
            "idacaoestrategica" => $model->idacaoestrategica == "" ? null : $model->idacaoestrategica,
            "idareamelhoria" => $model->idareamelhoria == "" ? null : $model->idareamelhoria,
            "idsituacao" => $model->idsituacao,
            "iddiagnostico" => $model->iddiagnostico
        );

        $ret = $this->getDbTable()->update($data, array("idmelhoria = ?" => $model->idmelhoria));
        return $ret;
    }

    public function delete($params)
    {
        $sql = "
            DELETE FROM agepnet200.tb_questionariodiagnosticomelhoria
            WHERE idmelhoria = :idmelhoria ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idmelhoria' => $params['idmelhoria']
        ));
        return $resultado;
    }


    public function getForm()
    {
        return $this->_getForm(Projeto_Form_SugestaoMelhoria);
    }

    public function getByIdDetalhar($params)
    {
        $sql = "select  
                    tq.idmelhoria, 
                    to_char(tq.datmelhoria,  'DD/MM/YYYY') as datmelhoria,
                    (select p.nomprocesso from agepnet200.tb_processo p where p.idprocesso = tq.idmacroprocessotrabalho) as macroprocessotrabalho, 
                    (select p.nomprocesso from agepnet200.tb_processo p where p.idprocesso = tq.idmacroprocessomelhorar) as macroprocessomelhorar, 
                    (SELECT un.sigla FROM vw_comum_unidade un WHERE un.id_unidade = tq.idunidadeprincipal) as idunidadeprincipal,
                    tq.matriculaproponente, 
                    tq.desmelhoria, 
                    (SELECT un.nome FROM vw_comum_unidade un WHERE un.id_unidade = tq.idunidaderesponsavelproposta) as idunidaderesponsavelproposta,
                    CASE 
		      WHEN tq.flaabrangencia = '1' THEN 'Local'
		      WHEN tq.flaabrangencia = '2' THEN 'Nacional'
                    END AS flaabrangencia,
                    (SELECT un.nome FROM vw_comum_unidade un WHERE un.id_unidade = tq.idunidaderesponsavelimplantacao) as idunidaderesponsavelimplantacao, 
                    (select ta.nomacao from agepnet200.tb_acao ta where ta.idacao = tq.idobjetivoinstitucional) idobjetivoinstitucional, 
                    (select ta.nomacao from agepnet200.tb_acao ta where ta.idacao = tq.idacaoestrategica) idacaoestrategica, 
                    CASE              
                      WHEN tq.idareamelhoria = '1' THEN 'Simplificação'
                      WHEN tq.idareamelhoria = '2' THEN 'Normatização'
                      WHEN tq.idareamelhoria = '3' THEN 'Gerenciamento'
                      WHEN tq.idareamelhoria = '4' THEN 'Automação'
                      WHEN tq.idareamelhoria = '5' THEN 'Capacitação'
                      WHEN tq.idareamelhoria = '6' THEN 'Interfaces'
                      WHEN tq.idareamelhoria = '7' THEN 'Estrutura'
                      WHEN tq.idareamelhoria = '8' THEN 'Inovação'
                    END AS idareamelhoria,
                    CASE
                      WHEN tq.idsituacao = '1' THEN 'Registrada'
                      WHEN tq.idsituacao = '2' THEN 'Validada'
                      WHEN tq.idsituacao = '3' THEN 'Priorizada'
                      WHEN tq.idsituacao = '4' THEN 'Implantada'
                      WHEN tq.idsituacao = '5' THEN 'Suspensa'
                      WHEN tq.idsituacao = '6' THEN 'Agrupada'
                    END AS idsituacao,
                    tq.iddiagnostico,
                    tq.idmacroprocessotrabalho, 
                    tq.idmacroprocessomelhorar,
                    qdp.desrevisada,
                    CASE 
		      WHEN qdp.idprazo = '1' THEN 'Baixo' 
		      WHEN qdp.idprazo = '2' THEN 'Médio' 
		      WHEN qdp.idprazo = '3' THEN 'Alto'
                      WHEN qdp.idprazo = '4' THEN 'Até 6 meses'
                    END AS idprazo,
		    CASE 
		      WHEN qdp.idimpacto = '1' THEN 'Baixo' 
		      WHEN qdp.idimpacto = '2' THEN 'Médio' 
		      WHEN qdp.idimpacto = '3' THEN 'Alto'
                    END AS idimpacto,
                    CASE 
		      WHEN qdp.idesforco = '4' THEN 'Alto' 
		      WHEN qdp.idesforco = '3' THEN 'Médio' 
		      WHEN qdp.idesforco = '2' THEN 'Baixo'
		      WHEN qdp.idesforco = '1' THEN 'Irrelevante'
                    END AS idesforco,
                    qdp.numpontuacao,
                    qdp.numincidencia,
                    qdp.numvotacao,
                    CASE 
		      WHEN qdp.flaagrupadora = '1' THEN 'Sim'
		      WHEN qdp.flaagrupadora = '0' THEN 'Não'
                    END AS flaagrupadora,
                    qdp.destitulogrupo,
                    (SELECT pa.destitulogrupo
                      FROM agepnet200.tb_questdiagnosticopadronizamelhoria pa
                      where pa.idmelhoria = (SELECT de.desmelhoriaagrupadora
                      FROM agepnet200.tb_questdiagnosticopadronizamelhoria de
                      where de.idpadronizacaomelhoria = qdp.idpadronizacaomelhoria)) as desmelhoriaagrupadora,
                    qdp.desinformacoescomplementares
            FROM agepnet200.tb_questionariodiagnosticomelhoria tq
            LEFT JOIN agepnet200.tb_questdiagnosticopadronizamelhoria qdp
		ON qdp.idmelhoria = tq.idmelhoria
            WHERE tq.idmelhoria = :idmelhoria";
        $resultado = $this->_db->fetchRow($sql, array('idmelhoria' => $params['idmelhoria']));
        return $resultado;
    }

    public function retornaPorIdMelhoria($params)
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
                    tq.idmacroprocessomelhorar,
                    qdp.desrevisada,
                    qdp.idprazo,
                    qdp.idimpacto,
                    qdp.idesforco,
                    qdp.numpontuacao,
                    qdp.numincidencia,
                    qdp.numvotacao,
                    CASE 
		      WHEN qdp.flaagrupadora = true THEN '1' 
		      WHEN qdp.flaagrupadora = false THEN '0'
                    END AS flaagrupadora,
                    qdp.destitulogrupo,
                    qdp.desmelhoriaagrupadora,
                    qdp.desinformacoescomplementares,
                    qdp.idpadronizacaomelhoria
            FROM agepnet200.tb_questionariodiagnosticomelhoria tq
            LEFT JOIN agepnet200.tb_questdiagnosticopadronizamelhoria qdp
		ON qdp.idmelhoria = tq.idmelhoria
            WHERE tq.idmelhoria = :idmelhoria";
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

    public function getAllDelegacia()
    {
        $sql = "SELECT * FROM vw_comum_unidade 
                WHERE id_tipo_organizacional in(2400) 
                ORDER BY nome";
        $arrayDel = array();
        foreach ($this->_db->fetchAll($sql) as $d) {
            $arrayDel[$d['id_unidade']] = $d['nome'];
        }
        return $arrayDel;
    }

    public function getMelhoriaAgrupadora()
    {
        $sql = "select idmelhoria, destitulogrupo
            from agepnet200.tb_questdiagnosticopadronizamelhoria 
            where flaagrupadora != false
            and desmelhoriaagrupadora is not null
            order by destitulogrupo";
        return $this->_db->fetchPairs($sql);
    }

    public function getAreaMelhoria()
    {
        return array(
            1 => 'Simplificação',
            2 => 'Normatização',
            3 => 'Gerenciamento',
            4 => 'Automação',
            5 => 'Capacitação',
            6 => 'Interfaces',
            7 => 'Estrutura',
            8 => 'Inovação'
        );
    }

    public function getSituacao()
    {
        return array(
            1 => 'Registrada',
            2 => 'Validada',
            3 => 'Priorizada',
            4 => 'Implantada',
            5 => 'Suspensa',
            6 => 'Agrupada'
        );
    }

    public function getAbrangencia()
    {
        return array(
            1 => 'Local',
            2 => 'Nacional'
        );
    }

    public function getPrazo()
    {
        return array(
            1 => 'Baixo',
            2 => 'Médio',
            3 => 'Alto',
            4 => 'Até 6 meses'
        );
    }

    public function getImpacto()
    {
        return array(
            1 => 'Baixo',
            2 => 'Médio',
            3 => 'Alto'
        );
    }

    public function getEsforco()
    {
        return array(
            4 => 'Alto',
            3 => 'Médio',
            2 => 'Baixo',
            1 => 'Irrelevante'
        );
    }

    public function getAgrupadora()
    {
        return array(
            1 => 'Sim',
            0 => 'Não'
        );
    }

    public function getUnidadePrincipal($idDiagnostico)
    {
        $sql = "select * from agepnet200.tb_diagnostico
                   Where iddiagnostico = $idDiagnostico";
        return $this->_db->fetchRow($sql);
    }

    public function getIdMelhoria()
    {
        $sql = "SELECT nextval('agepnet200.sq_melhoria')";
        return $this->_db->fetchRow($sql);
    }

    public function getMelhoriaToDiagnostico($params)
    {
        $sql = "select  
                    count(tq.iddiagnostico) quantidade
            FROM agepnet200.tb_questionariodiagnosticomelhoria tq
            LEFT JOIN agepnet200.tb_questdiagnosticopadronizamelhoria qdp
		ON qdp.idmelhoria = tq.idmelhoria
            WHERE tq.iddiagnostico = :iddiagnostico";
        $resultado = $this->_db->fetchRow($sql, array('iddiagnostico' => $params['iddiagnostico']));
        return $resultado;
    }
}
