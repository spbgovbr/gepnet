<?php

class Diagnostico_Model_Mapper_Estatistica extends App_Model_Mapper_MapperAbstract
{
    /**
     * Função de retorno quantidade de unidades principais
     * @param array $params
     * @return array
     */
    public function retornaQuantidadeUnidadePrincipalPorDiagnostico($params)
    {
        $retorno = 0;
        if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos'
            || (!empty($params['iddiagnostico'])) && $params['iddiagnostico'] == 'Todos') {

            $sql = "SELECT id_unidadeprincipal, COUNT(id_unidadeprincipal) AS total_unidade_principal
               FROM agepnet200.tb_unidade_vinculada
               WHERE 1=1 ";

            if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos') {
                $sql .= "AND iddiagnostico = {$params['iddiagnostico']}";
            }
            $sql .= "GROUP BY id_unidadeprincipal";

            $retorno = $this->_db->fetchAll($sql);
        }
        return $retorno;
    }

    /**
     * Função de retorno quantidade de unidades subordinadas as unidades principais.
     * @param array $params
     * @return array
     */
    public function retornaQuantidadeUnidadeSubordinadaPorDiagnostico($params)
    {
        $retorno = 0;
        if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos'
            || (!empty($params['iddiagnostico'])) && $params['iddiagnostico'] == 'Todos') {

            $sql = "SELECT idunidade, COUNT(idunidade) AS total_unidade
                FROM agepnet200.tb_unidade_vinculada
                WHERE 1=1 ";

            if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos') {
                $sql .= "AND iddiagnostico = {$params['iddiagnostico']} ";
            }

            if (!empty($params['idunidadeprincipal'])) {
                $sql .= "AND id_unidadeprincipal = {$params['idunidadeprincipal']} ";
            }

            $sql .= "GROUP BY idunidade";

            $retorno = $this->_db->fetchAll($sql);
        }
        return $retorno;
    }

    /**
     * funcão que retorna a quantidade de questionarios respondidos
     * @param array $params
     * @return int
     */
    public function retornaQuantidadeQuestionarioRespondido($params)
    {
        $retorno = 0;

        if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos'
            || (!empty($params['iddiagnostico'])) && $params['iddiagnostico'] == 'Todos') {

            $sql = "SELECT
                        d.idquestionariodiagnostico,
                        qr.numero
                    FROM agepnet200.tb_questionario_diagnostico d
                    INNER JOIN agepnet200.tb_questionariodiagnostico_respondido qr
                        ON qr.idquestionario = d.idquestionariodiagnostico
                    INNER JOIN agepnet200.tb_resposta_questionariordiagnostico rq
                        ON rq.iddiagnostico=qr.iddiagnostico and rq.idquestionario=qr.idquestionario
                        AND rq.id_resposta_pergunta IS NOT NULL AND qr.numero= rq.numero
                    WHERE 1=1 ";

            if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos') {
                $sql .= "AND qr.iddiagnostico = {$params['iddiagnostico']} ";
            }

            if (!empty($params['tpquestionario'])) {
                $sql .= "AND d.tipo IN('{$params['tpquestionario']}') ";
            }

            $sql .= "GROUP BY d.idquestionariodiagnostico, qr.numero
                     ORDER BY d.idquestionariodiagnostico, qr.numero";

            $resultado = $this->_db->fetchAll($sql);

            $retorno = (count($resultado) > 0) ? count($resultado) : 0;
        }
        return $retorno;
    }

    /**
     * Função que retorna o quantitativo de pessoas entrevistadas por cargo
     * @param array $params
     * @return array || int
     */
    public function retornaQuantidadePessoaEntrevistadaPorCargo($params)
    {
        $retorno = 0;
        if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos'
            || (!empty($params['iddiagnostico'])) && $params['iddiagnostico'] == 'Todos') {

            $sql = "SELECT
                        o.desresposta as cargo,
                        count(o.idresposta) as total
                    FROM agepnet200.tb_resposta_questionariordiagnostico rq
                    INNER JOIN agepnet200.tb_questionario_diagnostico q
                        ON q.idquestionariodiagnostico=rq.idquestionario
                        AND q.tipo='1'
                    INNER JOIN agepnet200.tb_resposta_pergunta rp
                        ON rp.id_resposta_pergunta = rq.id_resposta_pergunta
                    INNER JOIN agepnet200.tb_opcao_resposta	o
                        ON o.idpergunta=rp.idpergunta
                        AND o.idresposta =rp.idresposta
                    INNER JOIN agepnet200.tb_pergunta p
                        ON p.idpergunta = rp.idpergunta
                        AND p.idquestionario=rq.idquestionario
                        AND p.tiporegistro=1 and p.id_secao=1
                    WHERE 1=1 ";

            if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos') {
                $sql .= "AND rq.iddiagnostico = {$params['iddiagnostico']} ";
            }

            $sql .= "GROUP BY p.id_secao, o.desresposta ";
            $sql .= "ORDER BY o.desresposta";

            $resultado = $this->_db->fetchAll($sql);

            if (count($resultado) > 0) {
                $retorno = $resultado;
            }
        }
        return $retorno;
    }

    public function retornaSomatorioEscalaLinkertPorTipo($params)
    {

        $retorno = 0;
        if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos'
            || (!empty($params['iddiagnostico'])) && $params['iddiagnostico'] == 'Todos') {

            $sql = "SELECT rq.iddiagnostico, SUM(o.escala) AS escala
                    FROM agepnet200.tb_resposta_questionariordiagnostico rq
                    INNER JOIN agepnet200.tb_questionario_diagnostico q
                        ON q.idquestionariodiagnostico=rq.idquestionario
                        AND q.tipo='{$params['tipo']}'
                    INNER JOIN agepnet200.tb_resposta_pergunta rp
                        ON rp.id_resposta_pergunta = rq.id_resposta_pergunta
                    INNER JOIN agepnet200.tb_opcao_resposta	o
                        ON o.idpergunta=rp.idpergunta
                        AND o.idresposta =rp.idresposta
                    INNER JOIN agepnet200.tb_pergunta p
                        ON p.idpergunta = rp.idpergunta
                        AND p.idquestionario=rq.idquestionario
                        AND p.tiporegistro=1
                    WHERE 1=1 ";

            if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos') {
                $sql .= "AND rq.iddiagnostico= {$params['iddiagnostico']} ";
            }

            $sql .= "GROUP BY rq.iddiagnostico";

            $resultado = $this->_db->fetchRow($sql);

            $retorno = ($resultado['escala'] > 0) ? $resultado['escala'] : 0;

        }

        return $retorno;
    }


    public function retornaSomatorioEscalaLinkertPorTipoESecao($params)
    {

        $retorno = null;

        if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos'
            || (!empty($params['iddiagnostico'])) && $params['iddiagnostico'] == 'Todos') {

            $tpSecao = ($params['tipo'] == '1') ? 'S' : 'C';

            $sql = "SELECT s.ds_secao as secao, SUM(o.escala) AS valor
                    FROM agepnet200.tb_resposta_questionariordiagnostico rq
                    INNER JOIN agepnet200.tb_questionario_diagnostico q
                        ON q.idquestionariodiagnostico=rq.idquestionario
                        AND q.tipo='{$params['tipo']}'
                    INNER JOIN agepnet200.tb_resposta_pergunta rp
                        ON rp.id_resposta_pergunta = rq.id_resposta_pergunta
                    INNER JOIN agepnet200.tb_opcao_resposta	o
                        ON o.idpergunta=rp.idpergunta
                        AND o.idresposta =rp.idresposta
                    INNER JOIN agepnet200.tb_pergunta p
                        ON p.idpergunta = rp.idpergunta
                        AND p.idquestionario=rq.idquestionario
                        AND p.tiporegistro=1
                    INNER JOIN agepnet200.tb_secao s
                        ON s.id_secao = p.id_secao
                        AND s.tp_questionario='{$tpSecao}'
                    WHERE 1=1 ";

            if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos') {
                $sql .= "AND rq.iddiagnostico= {$params['iddiagnostico']} ";
            }

            $sql .= "GROUP BY s.id_secao, s.ds_secao ";
            $sql .= "ORDER BY s.ds_secao";

            $retorno = $this->_db->fetchAll($sql);

        }

        return $retorno;
    }

    public function retornaSomatorioEscalaLinkertPorSecaoMacroprocesso($params)
    {

        $retorno = null;

        if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos'
            || (!empty($params['iddiagnostico'])) && $params['iddiagnostico'] == 'Todos') {

            $tpSecao = ($params['tipo'] == '1') ? 'S' : 'C';

            $sql = "SELECT s.ds_secao as macroprocesso, SUM(o.escala) AS valor
                    FROM agepnet200.tb_resposta_questionariordiagnostico rq
                    INNER JOIN agepnet200.tb_questionario_diagnostico q
                        ON q.idquestionariodiagnostico=rq.idquestionario
                        AND q.tipo='{$params['tipo']}'
                    INNER JOIN agepnet200.tb_resposta_pergunta rp
                        ON rp.id_resposta_pergunta = rq.id_resposta_pergunta
                    INNER JOIN agepnet200.tb_opcao_resposta	o
                        ON o.idpergunta=rp.idpergunta
                        AND o.idresposta =rp.idresposta
                    INNER JOIN agepnet200.tb_pergunta p
                        ON p.idpergunta = rp.idpergunta
                        AND p.idquestionario=rq.idquestionario
                        AND p.tiporegistro=1
                    INNER JOIN agepnet200.tb_secao s
                        ON s.id_secao = p.id_secao AND s.macroprocesso=TRUE
                        AND s.tp_questionario='{$tpSecao}'
                    WHERE 1=1 ";

            if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos') {
                $sql .= "AND rq.iddiagnostico= {$params['iddiagnostico']} ";
            }

            $sql .= "GROUP BY s.id_secao, s.ds_secao ";
            $sql .= "ORDER BY s.ds_secao";

            $retorno = $this->_db->fetchAll($sql);
        }
        return $retorno;
    }


    public function retornaSomatorioEscalaLinkertPorCargo($params)
    {

        $retorno = null;

        if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos'
            || (!empty($params['iddiagnostico'])) && $params['iddiagnostico'] == 'Todos') {

            $tpSecao = ($params['tipo'] == '1') ? 'S' : 'C';

            $sql = "SELECT o.desresposta as cargo, SUM(o.escala) AS valor
                    FROM agepnet200.tb_resposta_questionariordiagnostico rq
                    INNER JOIN agepnet200.tb_questionario_diagnostico q
                        ON q.idquestionariodiagnostico=rq.idquestionario
                        AND q.tipo='{$params['tipo']}'
                    INNER JOIN agepnet200.tb_resposta_pergunta rp
                        ON rp.id_resposta_pergunta = rq.id_resposta_pergunta
                    INNER JOIN agepnet200.tb_opcao_resposta	o
                        ON o.idpergunta=rp.idpergunta
                        AND o.idresposta =rp.idresposta
                    INNER JOIN agepnet200.tb_pergunta p
                        ON p.idpergunta = rp.idpergunta
                        AND p.idquestionario=rq.idquestionario
                        AND p.tiporegistro=1
                        AND p.id_secao=1
                    WHERE 1=1 ";

            if (!empty($params['iddiagnostico']) && $params['iddiagnostico'] != 'Todos') {
                $sql .= "AND rq.iddiagnostico= {$params['iddiagnostico']} ";
            }

            $sql .= "GROUP BY p.id_secao, o.desresposta ";
            $sql .= " ORDER BY o.desresposta";

            $retorno = $this->_db->fetchAll($sql);
        }
        return $retorno;
    }


}