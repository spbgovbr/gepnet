<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Evento_Model_Mapper_Eventoavaliacao extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Eventoavaliacao
     */
    public function insert(Evento_Model_Eventoavaliacao $model)
    {

        try {

            $model->ideventoavaliacao = $this->maxVal('ideventoavaliacao');

            $data = array(
                "ideventoavaliacao" => $model->ideventoavaliacao,
                "idevento" => $model->idevento,
                "desdestaqueservidor" => $model->desdestaqueservidor,
                "desobs" => $model->desobs,
                "idavaliador" => $model->idavaliador,
                "idavaliado" => $model->idavaliado,
                "datcadastro" => new Zend_Db_Expr("now()"),
                "numpontualidade" => $model->numpontualidade,
                "numordens" => $model->numordens,
                "numrespeitochefia" => $model->numrespeitochefia,
                "numrespeitocolega" => $model->numrespeitocolega,
                "numurbanidade" => $model->numurbanidade,
                "numequilibrio" => $model->numequilibrio,
                "numcomprometimento" => $model->numcomprometimento,
                "numesforco" => $model->numesforco,
                "numtrabalhoequipe" => $model->numtrabalhoequipe,
                "numauxiliouequipe" => $model->numauxiliouequipe,
                "numaceitousugestao" => $model->numaceitousugestao,
                "numconhecimentonorma" => $model->numconhecimentonorma,
                "numalternativaproblema" => $model->numalternativaproblema,
                "numiniciativa" => $model->numiniciativa,
                "numtarefacomplexa" => $model->numtarefacomplexa,
                "idtipoavaliacao" => $model->idtipoavaliacao,
                "numnotaavaliador" => $model->numnotaavaliador,
                "nummedia" => $model->nummedia,
                "nummediafinal" => $model->nummediafinal,
                "numtotalavaliado" => $model->numtotalavaliado,
            );
            //var_dump($data); exit;
            $retorno = $this->getDbTable()->insert($data);
            return $retorno;

        } catch (exception $e) {
            throw $e;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Eventoavaliacao
     */
    public function update(Evento_Model_Eventoavaliacao $model)
    {
        $data = array(
            "ideventoavaliacao" => $model->ideventoavaliacao,
            "idevento" => $model->idevento,
            "desdestaqueservidor" => $model->desdestaqueservidor,
            "desobs" => $model->desobs,
            "idavaliador" => $model->idavaliador,
            "idavaliado" => $model->idavaliado,
            "numpontualidade" => $model->numpontualidade,
            "numordens" => $model->numordens,
            "numrespeitochefia" => $model->numrespeitochefia,
            "numrespeitocolega" => $model->numrespeitocolega,
            "numurbanidade" => $model->numurbanidade,
            "numequilibrio" => $model->numequilibrio,
            "numcomprometimento" => $model->numcomprometimento,
            "numesforco" => $model->numesforco,
            "numtrabalhoequipe" => $model->numtrabalhoequipe,
            "numauxiliouequipe" => $model->numauxiliouequipe,
            "numaceitousugestao" => $model->numaceitousugestao,
            "numconhecimentonorma" => $model->numconhecimentonorma,
            "numalternativaproblema" => $model->numalternativaproblema,
            "numiniciativa" => $model->numiniciativa,
            "numtarefacomplexa" => $model->numtarefacomplexa,
            "domtipoavaliacao" => $model->domtipoavaliacao,
            "numnotaavaliador" => $model->numnotaavaliador,
            "nummedia" => $model->nummedia,
            "nummediafinal" => $model->nummediafinal,
            "numtotalavaliado" => $model->numtotalavaliado,
            "idtipoavaliacao" => $model->idtipoavaliacao,
        );

        $data = array_filter($data);
        $pks = array(
            "ideventoavaliacao" => $model->ideventoavaliacao
        );
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }


        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getById($params)
    {
        $sql = "SELECT
                      e.nomevento as nomevento,
                      e.idevento as idevento,
                      t.noavaliacao as noavaliacao,
                      a.idavaliado as idavaliado,
                      (select p1.nompessoa from agepnet200.tb_pessoa p1 where p1.idpessoa = a.idavaliado) as nomavaliado,
                      (select p2.nompessoa from agepnet200.tb_pessoa p2 where p2.idpessoa = a.idavaliador) as nomavaliador,
                      a.numnotaavaliador||'('||numtotalavaliado||')' as numnotaavaliador,
                      a.nummedia as nummedia,
                      a.nummediafinal as nummediafinal,
                      a.numtotalavaliado as numtotalavaliado,
                      to_char(a.datcadastro, 'DD/MM/YYYY') as datcadastro,
                      a.ideventoavaliacao as ideventoavaliacao,
                      a.idtipoavaliacao as idtipoavaliacao,
                      a.idavaliador,
                      a.numpontualidade,
                      a.numordens,
                      a.numrespeitochefia,
                      a.numrespeitocolega,
                      a.numurbanidade,
                      a.numequilibrio,
                      a.numcomprometimento,
                      a.numesforco,
                      a.numtrabalhoequipe,
                      a.numauxiliouequipe,
                      a.numaceitousugestao,
                      a.numconhecimentonorma,
                      a.numalternativaproblema,
                      a.numiniciativa,
                      a.numtarefacomplexa,
                      a.numnotaavaliador,
                      a.desdestaqueservidor,
                      a.desobs
                 FROM
                    agepnet200.tb_eventoavaliacao a,
                    agepnet200.tb_tipoavaliacao t,
                    agepnet200.tb_evento e
                 WHERE
                    e.idevento = a.idevento
                    and t.idtipoavaliacao = a.idtipoavaliacao
                    and a.ideventoavaliacao = :ideventoavaliacao
                ";

        $resultado = $this->_db->fetchRow($sql, array(
                'ideventoavaliacao' => $params['ideventoavaliacao']
            )
        );
        $retorno = new Evento_Model_Eventoavaliacao($resultado);
        return $retorno;

    }

    public function pesquisar($params, $paginator = false)
    {
        $sql = "SELECT 
                      e.nomevento as nomevento,
                      t.noavaliacao as noavaliacao,
                      p1.nompessoa as nomavaliado,
                      p2.nompessoa as nomavaliador,
                      a.numnotaavaliador||'('||numtotalavaliado||')' as numnotaavaliador,
                      a.nummedia as nummedia,
                      a.nummediafinal as nummediafinal,
                      to_char(a.datcadastro, 'DD/MM/YYYY') as datcadastro,
                      a.ideventoavaliacao as ideventoavaliacao
                      
                 FROM 
                    agepnet200.tb_eventoavaliacao a,
                    agepnet200.tb_tipoavaliacao t,
                    agepnet200.tb_evento e,
                    agepnet200.tb_pessoa p1,
                    agepnet200.tb_pessoa p2
                 WHERE
                    e.idevento = a.idevento
                    and t.idtipoavaliacao = a.idtipoavaliacao
                    and p1.idpessoa = a.idavaliado
                    and p2.idpessoa = a.idavaliador
                    ";


        $params = array_filter($params);
        if (isset($params['idevento'])) {
            $idevento = strtoupper($params['idevento']);
            $sql .= " AND e.idevento = {$idevento}";
        }
        if (isset($params['idtipoavaliacao'])) {
            $idtipoavaliacao = strtoupper($params['idtipoavaliacao']);
            $sql .= " AND a.idtipoavaliacao = {$idtipoavaliacao}";
        }

        if (isset($params['nomavaliador'])) {
            $nomavaliador = strtoupper($params['nomavaliador']);
            $sql .= " AND upper(p2.nompessoa) LIKE '%{$nomavaliador}%'";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }
        //var_dump($sql); exit;
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

    public function getForm()
    {
        return $this->_getForm(Evento_Form_AvaliacaoServidor);
    }

}

