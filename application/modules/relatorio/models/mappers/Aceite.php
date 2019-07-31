<?php

class Relatorio_Model_Mapper_Aceite extends App_Model_Mapper_MapperAbstract
{

    public function relatorioAceite($params)
    {

        $params = array_filter($params);

        $sql = "SELECT distinct
			aac.idaceite,
			to_char(aac.dataceitacao, 'DD/MM/YYYY') as dataceitacao,
			aac.identrega,
			aac.idprojeto,
			a.desprodutoservico,
			a.desparecerfinal,
			a.idcadastrador,
			a.datcadastro,
			aac.aceito,
			ac.nomatividadecronograma,
			ac.descriterioaceitacao,
			pi.nomparteinteressada as nomresponsavel,
			CASE
			  WHEN aac.aceito = 'S' THEN 'Sim'
			  WHEN aac.aceito = 'N' THEN 'Não'
			  ELSE ''
			END as desflaaceite,
			ac.desobs,
			ac.nomatividadecronograma as nomarco,
                tp.nomprojeto,
                tp.nomcodigo,
                aac.dataceitacao as dtaceitacao
		FROM 
		agepnet200.tb_aceiteatividadecronograma aac, 
		agepnet200.tb_aceite a, 
		agepnet200.tb_atividadecronograma ac,
		agepnet200.tb_parteinteressada pi,
		agepnet200.tb_projeto tp,
		agepnet200.tb_escritorio tes
		WHERE 
		aac.idaceite = a.idaceite and 
		aac.idprojeto = ac.idprojeto and ac.domtipoatividade in(2,4) and
		aac.identrega = ac.idatividadecronograma and 
		ac.idparteinteressada = pi.idparteinteressada and
		aac.idprojeto = tp.idprojeto
        ";

        if (isset($params['idprojeto']) && trim($params['idprojeto']) != "") {
            $sql .= " AND aac.idprojeto = " . (int)$params['idprojeto'];
        }
        if (isset($params['idescritorio']) && trim($params['idescritorio']) != "") {
            $sql .= " AND tp.idescritorio = " . (int)$params['idescritorio'];
        }
        if (isset($params['idnatureza']) && trim($params['idnatureza']) != "") {
            $sql .= " AND tp.idnatureza = " . (int)$params['idnatureza'];
        }
        if (isset($params['flagaceito']) && trim($params['flagaceito']) != "") {
            $sql .= " AND aac.aceito = '{$params['flagaceito']}'";
        }
        if (isset($params['noatividade']) && trim($params['noatividade']) != "") {
            $sql .= " AND ac.nomatividadecronograma ilike'%{$params['noatividade']}%'";
        }
        if (isset($params['idaceite']) && trim($params['idaceite']) != "") {
            $sql .= " AND aac.idaceite = " . (int)$params['idaceite'];
        }
        if (isset($params['identrega']) && trim($params['identrega']) != "") {
            $sql .= " AND aac.identrega = " . (int)$params['identrega'];
        }
        if ((isset($params['dataceitacao']) && trim($params['dataceitacao']) != "") ||
            (isset($params['dataceitacaofim']) && trim($params['dataceitacaofim']) != "")
        ) {
            if ((isset($params['dataceitacao']) && trim($params['dataceitacao']) != "") &&
                (isset($params['dataceitacaofim']) && trim($params['dataceitacaofim']) != "")
            ) {
                $sql .= " AND aac.dataceitacao BETWEEN "
                    . " to_date('{$params['dataceitacao']}','DD/MM/YYYY') AND "
                    . " to_date('{$params['dataceitacaofim']}','DD/MM/YYYY') ";
            } else {
                if (isset($params['dataceitacao']) && trim($params['dataceitacao']) != "") {
                    $sql .= " AND aac.dataceitacao = to_date('{$params['dataceitacao']}','DD/MM/YYYY') ";
                } else {
                    $sql .= " AND aac.dataceitacao = to_date('{$params['dataceitacaofim']}','DD/MM/YYYY') ";
                }
            }
        }
        $sql .= " order by aac.idaceite, aac.idprojeto, dtaceitacao ";

        $resultado = $this->_db->fetchAll($sql);

        return $resultado;
    }

}
