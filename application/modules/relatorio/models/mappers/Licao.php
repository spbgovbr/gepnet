<?php

class Relatorio_Model_Mapper_Licao extends App_Model_Mapper_MapperAbstract
{

    public function relatorioLicao($params)
    {

        $params = array_filter($params);

        $sql = "SELECT distinct ac.nomatividadecronograma, tl.idlicao, tl.idprojeto,
                      tl.identrega, tl.desresultadosobtidos, tl.despontosfortes,
                      tl.despontosfracos, tl.dessugestoes,
                      to_char(tl.datcadastro, 'DD/MM/YYYY') as datcadastro,
                      proj.desobjetivo, proj.desprojeto,
                      tl.datcadastro dtcadastro,
                      proj.nomprojeto,
                      proj.nomcodigo
                FROM  agepnet200.tb_licao tl,
                      agepnet200.tb_atividadecronograma ac,
                      agepnet200.tb_projeto proj
                WHERE tl.idprojeto = ac.idprojeto
                  and tl.idprojeto = proj.idprojeto
                  and tl.identrega = ac.idatividadecronograma
                ";

        if (isset($params['idprojeto']) && trim($params['idprojeto']) != "") {
            $sql .= " AND proj.idprojeto = " . (int)$params['idprojeto'];
        }
        if (isset($params['idescritorio']) && trim($params['idescritorio']) != "") {
            $sql .= " AND proj.idescritorio = " . (int)$params['idescritorio'];
        }
        if (isset($params['idportfolio']) && trim($params['idportfolio']) != "") {
            $sql .= " AND proj.idportfolio = " . (int)$params['idportfolio'];
        }
        if (isset($params['idprograma']) && trim($params['idprograma']) != "") {
            $sql .= " AND proj.idprograma = " . (int)$params['idprograma'];
        }
        if (isset($params['idnatureza']) && trim($params['idnatureza']) != "") {
            $sql .= " AND proj.idnatureza = " . (int)$params['idnatureza'];
        }
        if (isset($params['identrega']) && trim($params['identrega']) != "") {
            $sql .= " AND tl.identrega = " . (int)$params['identrega'];
        }
        if (isset($params['desresultadosobtidos']) && trim($params['desresultadosobtidos']) != "") {
            $sql .= " AND tl.desresultadosobtidos ilike'%{$params['desresultadosobtidos']}%'";
        }
        if (isset($params['despontosfortes']) && trim($params['despontosfortes']) != "") {
            $sql .= " AND tl.despontosfortes ilike'%{$params['despontosfortes']}%'";
        }
        if (isset($params['despontosfracos']) && trim($params['despontosfracos']) != "") {
            $sql .= " AND tl.despontosfracos ilike'%{$params['despontosfracos']}%'";
        }
        if (isset($params['dessugestoes']) && trim($params['dessugestoes']) != "") {
            $sql .= " AND tl.dessugestoes ilike'%{$params['dessugestoes']}%'";
        }
        if ((isset($params['datcadastro']) && trim($params['datcadastro']) != "") ||
            (isset($params['datcadastrofim']) && trim($params['datcadastrofim']) != "")
        ) {
            if ((isset($params['datcadastro']) && trim($params['datcadastro']) != "") &&
                (isset($params['datcadastrofim']) && trim($params['datcadastrofim']) != "")
            ) {
                $sql .= " AND tl.datcadastro BETWEEN "
                    . " to_date('{$params['datcadastro']}','DD/MM/YYYY') AND "
                    . " to_date('{$params['datcadastrofim']}','DD/MM/YYYY') ";
            } else {
                if (isset($params['datcadastro']) && trim($params['datcadastro']) != "") {
                    $sql .= " AND tl.datcadastro = to_date('{$params['datcadastro']}','DD/MM/YYYY') ";
                } else {
                    $sql .= " AND tl.datcadastro = to_date('{$params['datcadastrofim']}','DD/MM/YYYY') ";
                }
            }
        }

        $sql .= " order by tl.idlicao, tl.idprojeto, dtcadastro ";

        $resultado = $this->_db->fetchAll($sql);

        return $resultado;
    }


}
