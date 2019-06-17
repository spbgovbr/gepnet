<?php

class Relatorio_Model_Mapper_Diariobordo extends App_Model_Mapper_MapperAbstract
{

    public function relatorioDiariobordo($params)
    {

        $params = array_filter($params);

        $sql = "SELECT distinct
                    to_char(db.datdiariobordo, 'DD/MM/YYYY') as datdiariobordo,
                    db.domreferencia,
                    CASE
                        WHEN db.domsemafaro=1 THEN '<span class=\"badge badge-important\">Vermelho</span>'
                        WHEN db.domsemafaro=2 THEN '<span class=\"badge badge-warning\">Amarelo</span>'
                        ELSE '<span class=\"badge badge-success\">Verde</span>'
                    END as domsemafarodecode,
                    pes.nompessoa,
                    db.domsemafaro,
                    db.desdiariobordo,
                    db.iddiariobordo,
                    db.idprojeto,
                    tp.nomprojeto,
                    tp.nomcodigo,
                    db.datdiariobordo as dtdiariobordo
                FROM agepnet200.tb_diariobordo db,
                     agepnet200.tb_pessoa pes,
		             agepnet200.tb_projeto tp
                WHERE db.idcadastrador = pes.idpessoa and
		            db.idprojeto = tp.idprojeto
                ";

        if (isset($params['idprojeto']) && trim($params['idprojeto']) != "") {
            $sql .= " AND db.idprojeto = " . (int)$params['idprojeto'];
        }
        if (isset($params['idescritorio']) && trim($params['idescritorio']) != "") {
            $sql .= " AND tp.idescritorio = " . (int)$params['idescritorio'];
        }
        if (isset($params['idnatureza']) && trim($params['idnatureza']) != "") {
            $sql .= " AND tp.idnatureza = " . (int)$params['idnatureza'];
        }
        if (isset($params['domsemafaro']) && trim($params['domsemafaro']) != "") {
            $sql .= " AND db.domsemafaro = " . (int)$params['domsemafaro'];
        }
        if (isset($params['domreferencia']) && trim($params['domreferencia']) != "") {
            $sql .= " AND db.domreferencia ilike'%{$params['domreferencia']}%'";
        }
        if (isset($params['desdiariobordo']) && trim($params['desdiariobordo']) != "") {
            $sql .= " AND db.desdiariobordo ilike'%{$params['desdiariobordo']}%'";
        }
        if ((isset($params['datdiariobordo']) && trim($params['datdiariobordo']) != "") ||
            (isset($params['datdiariobordofim']) && trim($params['datdiariobordofim']) != "")
        ) {
            if ((isset($params['datdiariobordo']) && trim($params['datdiariobordo']) != "") &&
                (isset($params['datdiariobordofim']) && trim($params['datdiariobordofim']) != "")
            ) {
                $sql .= " AND db.datdiariobordo BETWEEN "
                    . " to_date('{$params['datdiariobordo']}','DD/MM/YYYY') AND "
                    . " to_date('{$params['datdiariobordofim']}','DD/MM/YYYY') ";
            } else {
                if (isset($params['datdiariobordo']) && trim($params['datdiariobordo']) != "") {
                    $sql .= " AND db.datdiariobordo = to_date('{$params['datdiariobordo']}','DD/MM/YYYY') ";
                } else {
                    $sql .= " AND db.datdiariobordo = to_date('{$params['datdiariobordofim']}','DD/MM/YYYY') ";
                }
            }
        }
        $sql .= " order by db.iddiariobordo, db.idprojeto, dtdiariobordo ";

        $resultado = $this->_db->fetchAll($sql);

        return $resultado;
    }

}
