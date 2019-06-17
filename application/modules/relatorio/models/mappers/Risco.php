<?php

class Relatorio_Model_Mapper_Risco extends App_Model_Mapper_MapperAbstract
{

    public function relatorioRiscos($params)
    {
        $params = array_filter($params);

        $sql = "SELECT distinct
                    tr.idrisco,
                    to_char(tr.datdeteccao, 'DD/MM/YYYY') as datdeteccao,
                    tor.desorigemrisco, 
                    te.dsetapa, 
                    ttr.dstiporisco, 
                    CASE 
                        WHEN tr.domcorrisco=1 THEN '<span class=\"badge badge-important\">Vermelho</span>'
                        WHEN tr.domcorrisco=2 THEN '<span class=\"badge badge-warning\">Amarelo</span>'
                        WHEN tr.domcorrisco=3 THEN '<span class=\"badge badge-success\">Verde</span>'
                        ELSE '<span class=\"badge\"> - </span>'
                   END as domcorrisco,
                   CASE 
                        WHEN tr.domtratamento=1 THEN 'Conviver'
                        WHEN tr.domtratamento=2 THEN 'Mitigar'
                        WHEN tr.domtratamento=3 THEN 'Neutralizar'
                        WHEN tr.domtratamento=4 THEN 'Potencializar'
                        WHEN tr.domtratamento=5 THEN 'Transferir'
                        ELSE '-'
                   END as domtratamento,
                   tr.desrisco,
                   tr.descausa,
                   tr.desconsequencia,
                   tc.descontramedida,
                   CASE 
                        WHEN tc.flacontramedidaefetiva =1 THEN 'Sim'
                        WHEN tc.flacontramedidaefetiva =2 THEN 'Não'
                        ELSE '-'
                   END as flacontramedidaefetiva,
                   tr.idprojeto,
                   tp.nomprojeto,
                   tp.nomcodigo,
                   tn.nomnatureza,
		           tr.datdeteccao as dtdeteccao
                FROM agepnet200.tb_risco tr
                    inner join agepnet200.tb_origemrisco tor on tor.idorigemrisco = tr.idorigemrisco
                    inner join agepnet200.tb_etapa te on te.idetapa = tr.idetapa
                    inner join agepnet200.tb_tiporisco ttr on ttr.idtiporisco = tr.idtiporisco
                    left join  agepnet200.tb_contramedida tc on tc.idrisco = tr.idrisco
                    inner join agepnet200.tb_projeto tp on tp.idprojeto = tr.idprojeto
                    inner join agepnet200.tb_escritorio tes on tes.idescritorio =  tp.idescritorio 
                    left join agepnet200.tb_natureza tn on tn.idnatureza =  tp.idnatureza
                WHERE 1 = 1";

        if (isset($params['idprojeto']) && trim($params['idprojeto']) != "") {
            $sql .= " AND tr.idprojeto = " . (int)$params['idprojeto'];
        }
        if (isset($params['idescritorio']) && trim($params['idescritorio']) != "") {
            $sql .= " AND tes.idescritorio = " . (int)$params['idescritorio'];
        }
        if (isset($params['idportfolio']) && trim($params['idportfolio']) != "") {
            $sql .= " AND tp.idportfolio = " . (int)$params['idportfolio'];
        }
        if (isset($params['idprograma']) && trim($params['idprograma']) != "") {
            $sql .= " AND tp.idprograma = " . (int)$params['idprograma'];
        }
        if (isset($params['idnatureza']) && trim($params['idnatureza']) != "") {
            $sql .= " AND tn.idnatureza = " . (int)$params['idnatureza'];
        }
        if ((isset($params['datdeteccao']) && trim($params['datdeteccao']) != "") ||
            (isset($params['datdeteccaofim']) && trim($params['datdeteccaofim']) != "")
        ) {
            if ((isset($params['datdeteccao']) && trim($params['datdeteccao']) != "") &&
                (isset($params['datdeteccaofim']) && trim($params['datdeteccaofim']) != "")
            ) {
                $sql .= " AND tr.datdeteccao BETWEEN "
                    . " to_date('{$params['datdeteccao']}','DD/MM/YYYY') AND "
                    . " to_date('{$params['datdeteccaofim']}','DD/MM/YYYY') ";
            } else {
                if (isset($params['datdeteccao']) && trim($params['datdeteccao']) != "") {
                    $sql .= " AND tr.datdeteccao = to_date('{$params['datdeteccao']}','DD/MM/YYYY') ";
                } else {
                    $sql .= " AND tr.datdeteccao = to_date('{$params['datdeteccaofim']}','DD/MM/YYYY') ";
                }
            }
        }

        if (isset($params['norisco']) && trim($params['norisco']) != "") {
            $sql .= " AND tr.norisco ilike'%{$params['norisco']}%'";
        }
        if (isset($params['idorigemrisco']) && trim($params['idorigemrisco']) != "") {
            $sql .= " AND tr.idorigemrisco = '{$params['idorigemrisco']}'";
        }
        if (isset($params['idetapa']) && trim($params['idetapa']) != "") {
            $sql .= " AND tr.idetapa = '{$params['idetapa']}'";
        }
        if (isset($params['idtiporisco']) && trim($params['idtiporisco']) != "") {
            $sql .= " AND tr.idtiporisco = '{$params['idtiporisco']}'";
        }
        if (isset($params['domcorprobabilidade']) && trim($params['domcorprobabilidade']) != "") {
            $sql .= " AND tr.domcorprobabilidade = '{$params['domcorprobabilidade']}'";
        }
        if (isset($params['domcorimpacto']) && trim($params['domcorimpacto']) != "") {
            $sql .= " AND tr.domcorimpacto = '{$params['domcorimpacto']}'";
        }
        if (isset($params['domtratamento']) && trim($params['domtratamento']) != "") {
            $sql .= " AND tr.domtratamento = '{$params['domtratamento']}'";
        }
        if (isset($params['flariscoativo']) && trim($params['flariscoativo']) != "") {
            $sql .= " AND tr.flariscoativo = '{$params['flariscoativo']}'";
        }
        if ((isset($params['datencerramentorisco']) && trim($params['datencerramentorisco']) != "") ||
            (isset($params['datencerramentoriscofim']) && trim($params['datencerramentoriscofim']) != "")
        ) {
            if ((isset($params['datencerramentorisco']) && trim($params['datencerramentorisco']) != "") &&
                (isset($params['datencerramentoriscofim']) && trim($params['datencerramentoriscofim']) != "")
            ) {
                $sql .= " AND tr.datencerramentorisco BETWEEN "
                    . " to_date('{$params['datencerramentorisco']}','DD/MM/YYYY') AND "
                    . " to_date('{$params['datencerramentoriscofim']}','DD/MM/YYYY') ";
            } else {
                if (isset($params['datencerramentorisco']) && trim($params['datencerramentorisco']) != "") {
                    $sql .= " AND tr.datencerramentorisco = to_date('{$params['datencerramentorisco']}','DD/MM/YYYY') ";
                } else {
                    $sql .= " AND tr.datencerramentorisco = to_date('{$params['datencerramentoriscofim']}','DD/MM/YYYY') ";
                }
            }
        }

        //if (isset($params['datencerramentorisco']) && $params['datencerramentorisco'] != "") {
        //    $sql .= " AND tr.datencerramentorisco = to_date('{$params['datencerramentorisco']}','DD/MM/YYYY') ";
        //}

        $sql .= " order by tr.idprojeto, tr.idrisco, dtdeteccao ";

        $resultado = $this->_db->fetchAll($sql);

        return $resultado;
    }

}
