<?php

/**
 * Newton Carlos
 *
 * Criado em 11-12-2018
 * 12:32
 */
class Diagnostico_Model_Mapper_Sumario extends App_Model_Mapper_MapperAbstract
{

    public function retornaQuantitativoQuestionarioRespondido($params)
    {

        $sql = "SELECT qr.numero
                FROM agepnet200.tb_questionariodiagnostico_respondido qr
                INNER JOIN agepnet200.tb_questionario_diagnostico q
                  ON q.idquestionariodiagnostico=qr.idquestionario AND q.tipo='{$params['tpquestionario']}'
                INNER JOIN agepnet200.tb_resposta_questionariordiagnostico r
                  ON r.iddiagnostico=qr.iddiagnostico
                  AND r.idquestionario=qr.idquestionario
                  AND r.numero=qr.numero
                WHERE qr.iddiagnostico=:iddiagnostico
                GROUP BY qr.numero";

        $retorno = $this->_db->fetchAll($sql, array(
            'iddiagnostico' => $params['iddiagnostico'],
        ));

        return (count($retorno) > 0) ? count($retorno) : 0;
    }

    public function retornaSomatorioEscalaLinkertPorDiagnostico($params)
    {

        $sql = "SELECT rq.iddiagnostico, SUM(o.escala) AS escala
                FROM agepnet200.tb_resposta_questionariordiagnostico rq
                INNER JOIN agepnet200.tb_questionario_diagnostico q
                    ON q.idquestionariodiagnostico=rq.idquestionario
                    AND q.tipo='{$params['tpquestionario']}'
                INNER JOIN agepnet200.tb_resposta_pergunta rp
                    ON rp.id_resposta_pergunta = rq.id_resposta_pergunta
                INNER JOIN agepnet200.tb_opcao_resposta	o
                    ON o.idpergunta=rp.idpergunta
                    AND o.idresposta =rp.idresposta
                INNER JOIN agepnet200.tb_pergunta p
                    ON p.idpergunta = rp.idpergunta
                    AND p.idquestionario=rq.idquestionario
                    AND p.tiporegistro=1
                WHERE rq.iddiagnostico=:iddiagnostico
                GROUP BY rq.iddiagnostico";

        $retorno = $this->_db->fetchRow($sql, array(
            'iddiagnostico' => $params['iddiagnostico'],
        ));

        return ($retorno['escala'] > 0) ? $retorno['escala'] : 0;
    }

    public function retornaSomatorioEscalaLinkertPorResposta($params)
    {

        $sql = "SELECT
                    rq.idquestionario,
                    q.nomquestionario,
                    --rq.numero,
                    s.id_secao,
                    s.ds_item as secao,
                    --p.idpergunta,
                    --p.dstitulo as pergunta,
                    --rp.id_resposta_pergunta,
                    o.desresposta,
                    o.idresposta,
                    SUM(o.escala) AS escala
                FROM agepnet200.tb_resposta_questionariordiagnostico rq
                INNER JOIN agepnet200.tb_questionario_diagnostico q
                    ON q.idquestionariodiagnostico=rq.idquestionario
                    AND q.tipo='{$params['tpquestionario']}'
                INNER JOIN agepnet200.tb_resposta_pergunta rp
                    ON rp.id_resposta_pergunta = rq.id_resposta_pergunta
                INNER JOIN agepnet200.tb_opcao_resposta	o
                    ON o.idpergunta=rp.idpergunta
                    AND o.idresposta =rp.idresposta
                INNER JOIN agepnet200.tb_pergunta p
                    ON p.idpergunta = rp.idpergunta
                    AND p.idquestionario=rq.idquestionario
                    AND p.tiporegistro=1
                INNER JOIN agepnet200.tb_item_secao s
                    ON s.idquestionariodiagnostico=rq.idquestionario
                    AND s.id_secao=p.id_secao
                WHERE rq.iddiagnostico=:iddiagnostico
                GROUP BY
                    rq.idquestionario,
                    q.nomquestionario,
                    --rq.numero,
                    s.id_secao,
                    s.ds_item,
                    --p.idpergunta,
                    --p.dstitulo,
                    --rp.id_resposta_pergunta,
                    desresposta,
                    o.idresposta
                ORDER BY s.id_secao";

        $retorno = $this->_db->fetchAll($sql, array(
            'iddiagnostico' => $params['iddiagnostico'],
        ));

        return $retorno;
    }

}
