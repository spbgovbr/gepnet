<?php

class Pesquisa_Model_Mapper_ResultadoPesquisa extends App_Model_Mapper_MapperAbstract
{

    public function insert(Pesquisa_Model_ResultadoPesquisa $model)
    {
        $data = array(
            "id" => $this->maxVal('id'),
            "idresultado" => $model->idresultado,
            "idfrasepesquisa" => $model->idfrasepesquisa,
            "idquestionariopesquisa" => $model->idquestionariopesquisa,
            "desresposta" => $model->desresposta,
            "cpf" => $model->cpf,
            "datcadastro" => $model->datcadastro,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Seleciona o maior idresultado referente ao questionario da pesquisa
     *
     * @param type $params
     * @return type
     * @throws Exception
     */
    public function maxIdResultado($params)
    {
        try {
            $where = 'idquestionariopesquisa = :idquestionariopesquisa';
            $bind = array('idquestionariopesquisa' => $params['idquestionariopesquisa']);
            return $this->maxVal('idresultado', $where, $bind);
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function retornaResultadoPesquisaGrid($params)
    {
        $params = array_filter($params);

        $sql = "SELECT DISTINCT
                    trp.idresultado, 
                    coalesce(tp.nompessoa, vwcp.nome) as nompessoa,
                    to_char(trp.datcadastro, 'dd-mm-yyyy HH24:MI:SS') AS datcadastro,
                    tpq.idpesquisa,
                    trp.cpf,
                    trp.idquestionariopesquisa
                FROM agepnet200.tb_resultado_pesquisa trp
                    LEFT JOIN agepnet200.tb_pessoa tp ON tp.numcpf::varchar = trp.cpf
                    LEFT JOIN public.vw_comum_pessoa vwcp on vwcp.cpf_cnpj::varchar = trp.cpf
                    INNER JOIN agepnet200.tb_questionario_pesquisa tqp on tqp.idquestionariopesquisa = trp.idquestionariopesquisa
                    INNER JOIN agepnet200.tb_pesquisa tpq on tpq.idpesquisa = tqp.idpesquisa
                WHERE 1 = 1 ";

        $sql .= $this->_db->quoteInto('  AND tpq.idpesquisa = ? ', $params['idpesquisa']);

        if (isset($params['nompessoa']) && $params['nompessoa'] != "") {
            $sql .= $this->_db->quoteInto('  AND tp.nompessoa ilike ? ', "%" . $params['nompessoa'] . "%");
        }

        if (isset($params['idresultado']) && $params['idresultado'] != "") {
            $sql .= $this->_db->quoteInto('  AND trp.idresultado  = ? ', (int)$params['idresultado']);
        }

        if (isset($params['datcadastroinicio']) && $params['datcadastroinicio'] != "") {
            $sql .= $this->_db->quoteInto("  AND trp.datcadastro >= to_timestamp(?, 'dd-mm-yyyy HH24:MI:SS') ",
                $params['datcadastroinicio'] . ' 00:00:00');
        }

        if (isset($params['datcadastrofim']) && $params['datcadastrofim'] != "") {
            $sql .= $this->_db->quoteInto("  AND trp.datcadastro <= to_timestamp(?, 'dd-mm-yyyy HH24:MI:SS') ",
                $params['datcadastrofim'] . ' 23:59:59');
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
            throw new Exception($exc->getMessage());
        }
    }

    public function retornaResultadoByPessoa($params)
    {
        try {
            $sql = 'SELECT 
                        tp.idpesquisa,
                        tqp.idquestionariopesquisa,
                        tqp.nomquestionario,
                        tqfp.idfrasepesquisa,
                        tqfp.numordempergunta,
                        tqfp.obrigatoriedade,
                        tfp.desfrase, 
                        tfp.domtipofrase,
                        trp.desresposta,
                        trtp.desresposta as resultado_resposta,
                        trtp.cpf,
                        trtp.idresultado,
                        coalesce(tpes.nompessoa, vwcp.nome) as nompessoa
                FROM agepnet200.tb_pesquisa tp
                    INNER JOIN agepnet200.tb_questionario_pesquisa tqp ON tqp.idpesquisa = tp.idpesquisa
                    INNER JOIN agepnet200.tb_questionariofrase_pesquisa tqfp ON tqfp.idquestionariopesquisa = tqp.idquestionariopesquisa
                    INNER JOIN agepnet200.tb_frase_pesquisa tfp ON tfp.idfrasepesquisa = tqfp.idfrasepesquisa
                    LEFT JOIN agepnet200.tb_respostafrase_pesquisa trfp ON trfp.idfrasepesquisa = tfp.idfrasepesquisa
                    LEFT JOIN agepnet200.tb_resposta_pesquisa trp ON trp.idrespostapesquisa = trfp.idrespostapesquisa
                    LEFT JOIN agepnet200.tb_resultado_pesquisa trtp ON trtp.idfrasepesquisa = tfp.idfrasepesquisa 
                                    AND trtp.idquestionariopesquisa =  tqp.idquestionariopesquisa
                                     AND (
                                            (
                                                (tfp.domtipofrase = ' . Pesquisa_Model_Frase::UMA_ESCOLHA . '
                                                 OR tfp.domtipofrase = ' . Pesquisa_Model_Frase::MULTIPLA_ESCOLHA . '
                                                 OR tfp.domtipofrase = ' . Pesquisa_Model_Frase::UF . '
                                                 ) AND trp.idrespostapesquisa::text = trtp.desresposta
                                            )
                                            OR
                                            ( 
                                                tfp.domtipofrase <> ' . Pesquisa_Model_Frase::UMA_ESCOLHA . '
                                                AND tfp.domtipofrase <> ' . Pesquisa_Model_Frase::MULTIPLA_ESCOLHA . '
                                                AND tfp.domtipofrase <> ' . Pesquisa_Model_Frase::UF . ' 
                                                AND trtp.desresposta IS NOT NULL)
                                            )                                    
                                    AND trtp.idresultado = :idresultado
                    LEFT JOIN agepnet200.tb_pessoa tpes ON tpes.numcpf = :cpf 
                    LEFT JOIN public.vw_comum_pessoa vwcp on vwcp.cpf_cnpj = :cpf
                WHERE tp.idpesquisa = :idpesquisa
                ORDER BY tqfp.numordempergunta, tqfp.idfrasepesquisa, trp.numordem ';

            $bind = array(
                'cpf' => $params['cpf'] == 'null' ? null : $params['cpf'],
                'idpesquisa' => $params['idpesquisa'],
                'idresultado' => $params['idresultado']
            );

            return $this->_db->fetchAll($sql, $bind);
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    /**
     * Retorna a pesquisa e o total de resposta de cada item da pergunta
     *
     * @param type $params
     * @return type
     * @throws Exception
     */
    public function retornaTotalRespostaQuestoesByPesquisa($params)
    {
        $params = array_filter($params);

        try {
            $sql = "SELECT
                        tp.idpesquisa,			                        
                        tqp.nomquestionario,
                        tqp.idquestionariopesquisa,
                        tqfp.idfrasepesquisa,
                        tqfp.numordempergunta,
                        tqfp.obrigatoriedade,
                        tfp.desfrase, 
                        tfp.domtipofrase, 
                        trp.idrespostapesquisa,
                        trp.desresposta,
                        COALESCE(r1.total_respostas, r2.total_respostas, 0) as total_respostas
                    FROM agepnet200.tb_pesquisa tp
                        INNER JOIN agepnet200.tb_questionario_pesquisa tqp ON tqp.idpesquisa = tp.idpesquisa
                        INNER JOIN agepnet200.tb_questionariofrase_pesquisa tqfp ON tqfp.idquestionariopesquisa = tqp.idquestionariopesquisa
                        INNER JOIN agepnet200.tb_frase_pesquisa tfp ON tfp.idfrasepesquisa = tqfp.idfrasepesquisa
                        LEFT JOIN agepnet200.tb_respostafrase_pesquisa trfp ON trfp.idfrasepesquisa = tfp.idfrasepesquisa
                        LEFT JOIN agepnet200.tb_resposta_pesquisa trp ON trp.idrespostapesquisa = trfp.idrespostapesquisa

                        /*------ BUSCA O TOTAL DE RESPOSTAS PARA QUESTOES TIPO: 'UMA-ESCOLHA', 'MULTIPLA-ESCOLHA' E 'UF' -----*/
                        LEFT JOIN (SELECT 
                                            rp.idfrasepesquisa, 
                                            count(rp.idfrasepesquisa)as total_respostas , 
                                            rp.desresposta 
                                    FROM agepnet200.tb_resultado_pesquisa rp
                                        INNER JOIN agepnet200.tb_frase_pesquisa fr ON fr.idfrasepesquisa = rp.idfrasepesquisa
                                    WHERE rp.idquestionariopesquisa = :idpesquisa 
                                        AND fr.domtipofrase in(" . Pesquisa_Model_Frase::UMA_ESCOLHA . ", " . Pesquisa_Model_Frase::MULTIPLA_ESCOLHA . ", " . Pesquisa_Model_Frase::UF . ")					
                                    GROUP BY rp.idfrasepesquisa, rp.desresposta				
                                    ) r1 ON r1.idfrasepesquisa = tqfp.idfrasepesquisa AND trp.idrespostapesquisa::text = r1.desresposta	

                       /*----- BUSCA O TOTAL DE RESPOSTAS PARA QUESTOES TIPO: TEXTO, DESCRITIVO, NUMERO E DATA  ------*/	
                       LEFT JOIN (SELECT 
                                        trp.idfrasepesquisa, 
                                        count(trp.idfrasepesquisa) AS total_respostas 
                                    FROM agepnet200.tb_resultado_pesquisa trp
                                        INNER JOIN agepnet200.tb_frase_pesquisa tfp ON tfp.idfrasepesquisa = trp.idfrasepesquisa
                                    WHERE trp.idquestionariopesquisa = :idpesquisa 
                                          AND tfp.domtipofrase NOT IN(" . Pesquisa_Model_Frase::UMA_ESCOLHA . ", " . Pesquisa_Model_Frase::MULTIPLA_ESCOLHA . "," . Pesquisa_Model_Frase::UF . ")
                                    GROUP BY trp.idfrasepesquisa, tfp.domtipofrase				
                                    ) r2 ON r2.idfrasepesquisa = tqfp.idfrasepesquisa
                    WHERE tp.idpesquisa = :idpesquisa		
                    ORDER BY tqfp.numordempergunta, tqfp.idfrasepesquisa, trp.numordem";
            return $this->_db->fetchAll($sql, array('idpesquisa' => $params['idpesquisa']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    /**
     * Retorna o total de pesquisas respondidas por idpesquisa
     *
     * @param array $params
     * @return dataset
     * @throws Exception
     */
    public function totalPesquisasRespondidasByIdpesquisa($params)
    {
        $params = array_filter($params);
        try {
            $sql = "SELECT 
                        COUNT(distinct idresultado) as total
                    FROM agepnet200.tb_resultado_pesquisa trp
                        INNER JOIN agepnet200.tb_questionario_pesquisa tp ON tp.idquestionariopesquisa =  trp.idquestionariopesquisa
                    WHERE idpesquisa = :idpesquisa ";
            return $this->_db->fetchRow($sql, array('idpesquisa' => $params['idpesquisa']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    /**
     * Retorna todas a pesquisa e todas a suas respostas por idpesquisa
     *
     * @param array $params
     * @return type
     * @throws Exception
     */
    public function retornaPesquisasRespondidasByPesquisa($params)
    {
        $sql = "SELECT DISTINCT
			a1.idpesquisa,			
			a1.nomquestionario,
			a1.idquestionariopesquisa,
			a1.idfrasepesquisa,
			a1.numordempergunta,
			a1.obrigatoriedade,
			a1.desfrase, 
			a1.domtipofrase,                     
			a1.idresultado,			
			CASE
				WHEN a1.opcoes <> '' THEN a1.opcoes			 
				ELSE a1.desresposta
			END as resposta,
			a1.nome
		FROM ( 
                        SELECT
                            tp.idpesquisa,			
                            tqp.nomquestionario,
                            tqp.idquestionariopesquisa,
                            tqfp.idfrasepesquisa,
                            tqfp.numordempergunta,
                            tqfp.obrigatoriedade,
                            tfp.desfrase, 
                            tfp.domtipofrase,                     
                            trtp.idresultado,
                            trtp.desresposta,
                            coalesce(tbp.nompessoa, vwp.nome) as nome,                                                     
                            /*Retorna as repostas das questoes multipla escolha em uma linha*/                                       
                           (SELECT array_to_string(array(
                                SELECT 
                                        tb4.desresposta
                                FROM agepnet200.tb_resultado_pesquisa tb1
                                        INNER JOIN agepnet200.tb_frase_pesquisa tb2 ON tb2.idfrasepesquisa = tb1.idfrasepesquisa
                                        INNER JOIN agepnet200.tb_respostafrase_pesquisa tb3 ON tb3.idfrasepesquisa = tb1.idfrasepesquisa AND tb1.desresposta = tb3.idrespostapesquisa::text
                                        INNER JOIN agepnet200.tb_resposta_pesquisa tb4 ON tb4.idrespostapesquisa = tb3.idrespostapesquisa 
                                WHERE tb1.idquestionariopesquisa = tqp.idquestionariopesquisa
                                        AND tb1.idfrasepesquisa = tfp.idfrasepesquisa
                                        AND tb1.idresultado = trtp.idresultado		
                                ORDER BY tb1.idfrasepesquisa, tb1.idresultado),', ')) AS opcoes

                        FROM agepnet200.tb_pesquisa tp
                            INNER JOIN agepnet200.tb_questionario_pesquisa tqp ON tqp.idpesquisa = tp.idpesquisa
                            INNER JOIN agepnet200.tb_questionariofrase_pesquisa tqfp ON tqfp.idquestionariopesquisa = tqp.idquestionariopesquisa
                            INNER JOIN agepnet200.tb_frase_pesquisa tfp ON tfp.idfrasepesquisa = tqfp.idfrasepesquisa                   
                            LEFT JOIN agepnet200.tb_resultado_pesquisa trtp ON trtp.idfrasepesquisa = tfp.idfrasepesquisa
                                    AND trtp.idquestionariopesquisa = tqp.idquestionariopesquisa
                            LEFT JOIN public.vw_comum_pessoa vwp ON vwp.cpf_cnpj::text = trtp.cpf
                            LEFT JOIN agepnet200.tb_pessoa tbp ON tbp.numcpf::text = trtp.cpf			
                        WHERE tp.idpesquisa = :idpesquisa
                            ORDER BY trtp.idresultado, tqfp.numordempergunta 
                    ) AS a1                    
                    ORDER BY idresultado, numordempergunta";

        try {
            return $this->_db->fetchAll($sql, array('idpesquisa' => $params['idpesquisa']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function existsRespostaPesquisaByCpf($params)
    {
        try {
            $sql = "SELECT 
                        * 
                   FROM agepnet200.tb_pesquisa tp
                        INNER JOIN agepnet200.tb_questionario_pesquisa tqp ON tqp.idpesquisa =  tp.idpesquisa
                        INNER JOIN agepnet200.tb_resultado_pesquisa trp ON trp.idquestionariopesquisa = tqp.idquestionariopesquisa
                   AND tp.idpesquisa = :idpesquisa
                   AND trp.cpf = :cpf ";

            $bind = array(
                'cpf' => $params['cpf'],
                'idpesquisa' => $params['idpesquisa'],
            );

            return $this->_db->fetchAll($sql, $bind);
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }
}