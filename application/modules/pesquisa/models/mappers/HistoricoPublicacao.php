<?php

class Pesquisa_Model_Mapper_HistoricoPublicacao extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_HistoricoPublicacao
     */
    public function insert(Pesquisa_Model_HistoricoPublicacao $model)
    {
        $data = array(
            "idhistoricopublicacao" => $this->maxVal('idhistoricopublicacao'),
            "idpesquisa" => $model->idpesquisa,
            "datpublicacao" => $model->datpublicacao,
            "datencerramento" => $model->datencerramento,
            "idpespublicou" => $model->idpespublicou,
            "idpesencerrou" => $model->idpesencerrou,
        );
        return $this->getDbTable()->insert($data);
    }

    public function getHistoricoPublicacao($params)
    {
        $sql = "SELECT                     
                    tqp.nomquestionario,
                    CASE 		
                        WHEN (th.datpublicacao > th.datencerramento) OR (th.datencerramento IS NULL and th.datpublicacao IS NOT NULL) THEN 'Publicada'
                        WHEN th.datpublicacao < th.datencerramento THEN 'Encerrada'
                    END AS situacao,
                    to_char(th.datpublicacao, 'DD/MM/YYYY HH24:MI:SS') AS datpublicacao,
                    to_char(th.datencerramento, 'DD/MM/YYYY HH24:MI:SS') AS datencerramento,
                    tpp.nompessoa AS nome_publicou,
                    vwp.sigla as unidade_pub,
                    tpe.nompessoa AS nome_encerrou,
                    vwe.sigla as unidade_enc,
                    th.idhistoricopublicacao,
                    th.idpesquisa
            FROM agepnet200.tb_hst_publicacao th
                INNER JOIN agepnet200.tb_pesquisa tp ON tp.idpesquisa = th.idpesquisa
                INNER JOIN agepnet200.tb_questionario_pesquisa tqp ON tqp.idpesquisa = tp.idpesquisa
                INNER JOIN agepnet200.tb_pessoa tpp ON tpp.idpessoa = th.idpespublicou
                LEFT JOIN agepnet200.tb_pessoa tpe ON tpe.idpessoa =  th.idpesencerrou
                LEFT JOIN  public.vw_comum_unidade vwp ON vwp.id_unidade = tpp.id_unidade
                LEFT JOIN  public.vw_comum_unidade vwe ON vwe.id_unidade = tpe.id_unidade
            WHERE 1 = 1
            ";

        if (isset($params['nomquestionario']) && $params['nomquestionario'] != "") {
            $sql .= $this->_db->quoteInto(' AND tqp.nomquestionario ilike ? ', "%" . $params['nomquestionario'] . "%");
        }
        if (isset($params['nome_publicou']) && $params['nome_publicou'] != "") {
            $sql .= $this->_db->quoteInto(' AND tpp.nompessoa ilike ? ', "%" . $params['nome_publicou'] . "%");
        }
        if (isset($params['nome_encerrou']) && $params['nome_encerrou'] != "") {
            $sql .= $this->_db->quoteInto(' AND tpe.nompessoa ilike ? ', "%" . $params['nome_encerrou'] . "%");
        }
        //data de encerramento
        if (isset($params['datencerramento']) && $params['datencerramento'] != "") {
            $sql .= $this->_db->quoteInto(" AND (
                                                    th.datencerramento <= to_timestamp(?, 'dd-mm-yyyy HH24:MI:SS')
                                                 OR 
                                                    (th.datencerramento IS NULL AND th.datpublicacao <= to_timestamp(?||' 23:59:59' , 'dd-mm-yyyy HH24:MI:SS'))
                                                 ) ", $params['datencerramento'] . ' 23:59:59');
        }
        //data de publicacao
        if (isset($params['datpublicacao']) && $params['datpublicacao'] != "") {
            $sql .= $this->_db->quoteInto(" AND th.datpublicacao >= to_timestamp(?, 'dd-mm-yyyy HH24:MI:SS') ",
                $params['datpublicacao'] . ' 00:00:00');
        }
        //Publicada
        if (isset($params['situacao']) && $params['situacao'] == Pesquisa_Model_Pesquisa::PUBLICADO) {
            $sql .= " AND ((th.datpublicacao > th.datencerramento) OR (th.datencerramento IS NULL and th.datpublicacao IS NOT NULL)) ";
        }
        //encerrada
        if (isset($params['situacao']) && $params['situacao'] == Pesquisa_Model_Pesquisa::ENCERRADO) {
            $sql .= " AND th.datpublicacao < th.datencerramento ";
        }
        $sql .= " ORDER BY th.idpesquisa, th.idhistoricopublicacao ";

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

}
