<?php

/**
 * Newton Carlos
 *
 * Criado em 11-12-2018
 * 12:32
 */
class Diagnostico_Model_Mapper_QuestionarioRespondido extends App_Model_Mapper_MapperAbstract
{
    /**
     * Lista todas os questionários respondidos.
     * @param array $params
     * return Zend_Paginator
     */
    public function listarQuestionariosRespondidos($params)
    {

        $sql = "SELECT
                    d.nomquestionario,
                    qr.numero,
                    TO_CHAR(qr.dt_resposta,'DD/MM/YYYY') AS dt_resposta,
                    qr.idquestionario
                FROM agepnet200.tb_questionario_diagnostico d
                INNER JOIN agepnet200.tb_questionariodiagnostico_respondido qr
                      ON qr.idquestionario = d.idquestionariodiagnostico
                      AND qr.iddiagnostico = {$params['iddiagnostico']}  ";

        if (!empty($params['numero'])) {
            $sql .= "AND qr.numero = " . (int)$params['numero'] . " ";
        }
        if (!empty($params['dt_resposta'])) {
            $arrData = explode("/", $params['dt_resposta']);
            $novaData = $arrData[2] . "-" . $arrData[1] . "-" . $arrData[0];
            $sql .= "AND qr.dt_resposta = '" . $novaData . "' ";
        }
        if (!empty($params['nomquestionario'])) {
            $sql .= "AND d.nomquestionario like ('%" . $params['nomquestionario'] . "%') ";
        }

        $sql .= "INNER JOIN agepnet200.tb_resposta_questionariordiagnostico rq
                    ON rq.iddiagnostico=qr.iddiagnostico and rq.idquestionario=qr.idquestionario
                    AND rq.id_resposta_pergunta IS NOT NULL AND qr.numero= rq.numero
                WHERE d.tipo = '{$params['tpquestionario']}'
                GROUP BY d.nomquestionario, qr.numero, TO_CHAR(qr.dt_resposta,'DD/MM/YYYY'), qr.idquestionario ";

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

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

    public function retornaQuestionarioRespondido($params)
    {

        $tpQuestionario = (!empty($params['tpquestionario']) && $params['tpquestionario'] == '1') ? 'S' : 'C';

//        $sql = "SELECT
//                       p.idpergunta,
//                       p.dstitulo,
//                       p.tipopergunta,
//                       p.ativa,
//                       p.tiporegistro,
//                       p.posicao,
//                       s.ds_secao,
//                       s.id_secao,
//                       o.idresposta,
//                       o.desresposta AS resposta,
//                       o.ordenacao,
//                       COALESCE(resp.idresposta, NULL::BIGINT) AS resp_numerica,
//                       COALESCE(resp_desc.ds_resposta_descritiva, NULL::TEXT) as resp_descritiva
//                FROM agepnet200.tb_questionariodiagnostico_respondido qr
//                INNER JOIN agepnet200.tb_questionario_diagnostico q
//                    ON q.idquestionariodiagnostico = qr.idquestionario
//                    AND q.tipo in('{$params['tpquestionario']}')
//                INNER JOIN agepnet200.tb_pergunta p
//                    ON p.idquestionario = qr.idquestionario
//                JOIN agepnet200.tb_secao s
//                    ON s.id_secao = p.id_secao
//                    AND s.tp_questionario IN('{$tpQuestionario}')
//                LEFT JOIN agepnet200.tb_opcao_resposta o
//                    ON o.idpergunta = p.idpergunta
//                    AND o.idquestionario = p.idquestionario
//                LEFT JOIN agepnet200.tb_resposta_pergunta resp
//                    ON resp.idpergunta = p.idpergunta
//                    AND resp.idresposta = o.idresposta
//                    AND resp.nrquestionario = qr.numero
//                LEFT JOIN agepnet200.tb_resposta_pergunta resp_desc
//                    ON resp_desc.idpergunta = p.idpergunta
//                    AND resp_desc.idresposta IS NULL
//                    AND resp_desc.nrquestionario = qr.numero
//                WHERE qr.idquestionario = :idquestionario
//                AND qr.iddiagnostico = :iddiagnostico
//                AND qr.numero = :numero
//                GROUP BY p.idpergunta, p.dstitulo, p.tipopergunta, p.ativa, p.tiporegistro, p.posicao,
//                       s.ds_secao, s.id_secao, o.idresposta, o.desresposta, o.ordenacao, resp.idresposta,
//                       resp_desc.ds_resposta_descritiva
//                ORDER BY s.id_secao, p.posicao, o.ordenacao";

        $sql = "SELECT
                    p.idpergunta,
                    p.dstitulo,
                    p.tipopergunta,
                    p.ativa,
                    p.tiporegistro,
                    p.posicao,
                    s.ds_secao,
                    s.id_secao,
                    o.idresposta,
                    o.desresposta AS resposta,
                    o.ordenacao,
                    CASE
                       WHEN (
                        SELECT count(a.*)
                          FROM
                            (SELECT rq.idquestionario, ARRAY_AGG(rp.idresposta) AS ar_resposta
                            FROM agepnet200.tb_resposta_questionariordiagnostico rq
                            INNER JOIN agepnet200.tb_resposta_pergunta rp
                               ON rp.id_resposta_pergunta = rq.id_resposta_pergunta
                               AND rp.idresposta IS NOT NULL
                            WHERE rq.idquestionario= p.idquestionario
                             AND  rq.iddiagnostico = :iddiagnostico
                             AND rq.numero = :numero
                            GROUP BY rq.idquestionario ) a
                        WHERE o.idresposta = ANY(ar_resposta)) > 0 THEN TRUE
                       ELSE FALSE
                    END as checked,
                    (
                        SELECT a.descricao
                          FROM
                            (SELECT rq.idquestionario, ARRAY_AGG(rp.idpergunta) AS ar_resposta, ds_resposta_descritiva as descricao
                            FROM agepnet200.tb_resposta_questionariordiagnostico rq
                            INNER JOIN agepnet200.tb_resposta_pergunta rp
                               ON rp.id_resposta_pergunta = rq.id_resposta_pergunta
                               AND rp.idresposta IS NULL
                            WHERE rq.idquestionario = :idquestionario
                             AND  rq.iddiagnostico = :iddiagnostico
                             AND rq.numero = :numero
                            GROUP BY rq.idquestionario, ds_resposta_descritiva ) a
                          WHERE p.idpergunta = ANY(ar_resposta)
                    ) as descricao
                FROM agepnet200.tb_questionario_diagnostico q
                INNER JOIN agepnet200.tb_pergunta p
	                ON p.idquestionario = q.idquestionariodiagnostico
                INNER JOIN agepnet200.tb_secao s
                    ON s.id_secao = p.id_secao
                    AND s.tp_questionario IN('{$tpQuestionario}')
                LEFT JOIN agepnet200.tb_opcao_resposta o
                    ON o.idpergunta = p.idpergunta
                    AND o.idquestionario=p.idquestionario
                WHERE p.idquestionario = :idquestionario
                AND q.tipo in('{$params['tpquestionario']}')
                ORDER BY s.id_secao, p.posicao, o.ordenacao";

        $retorno = $this->_db->fetchAll($sql, array(
            'idquestionario' => (int)$params['idquestionariodiagnostico'],
            'iddiagnostico' => (int)$params['iddiagnostico'],
            'numero' => (int)$params['numero']
        ));

        if ($retorno) {
            return $retorno;
        }
        return false;
    }


}
