<?php

class Relatorio_Model_Mapper_Risco extends App_Model_Mapper_MapperAbstract {

    public function relatorioRiscos($params)
    {
        $params = array_filter($params);
        
        $sql = "SELECT 
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
                   tn.nomnatureza
                FROM agepnet200.tb_risco tr
                    inner join agepnet200.tb_origemrisco tor on tor.idorigemrisco = tr.idorigemrisco
                    inner join agepnet200.tb_etapa te on te.idetapa = tr.idetapa
                    inner join agepnet200.tb_tiporisco ttr on ttr.idtiporisco = tr.idtiporisco
                    left join  agepnet200.tb_contramedida tc on tc.idrisco = tr.idrisco
                    inner join agepnet200.tb_projeto tp on tp.idprojeto = tr.idprojeto
                    inner join agepnet200.tb_escritorio tes on tes.idescritorio =  tp.idescritorio 
                    left join agepnet200.tb_natureza tn on tn.idnatureza =  tp.idnatureza
                WHERE 1 = 1";
                    
        if(isset($params['idprojeto']) && $params['idprojeto'] != "") {
            $sql .= " AND tr.idprojeto = ".(int)$params['idprojeto'];
        }
        if(isset($params['idescritorio']) && $params['idescritorio'] != "") {
            $sql .= " AND tes.idescritorio = ".(int)$params['idescritorio'];
        }
        if(isset($params['idnatureza']) && $params['idnatureza'] != "") {
            $sql .= " AND tn.idnatureza = ".(int)$params['idnatureza'];
        }
        
        $sql .= "order by tr.idrisco, tr.idprojeto, tr.datdeteccao";
        
        $resultado = $this->_db->fetchAll($sql);
        
        return $resultado;
    }

}
