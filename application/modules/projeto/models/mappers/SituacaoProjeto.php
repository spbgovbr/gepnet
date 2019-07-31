<?php

/**
 * @By Danilo
 */
class Projeto_Model_Mapper_SituacaoProjeto extends App_Model_Mapper_MapperAbstract
{

    public function retornaNomeSituacaoAtivo()
    {
        $sql = "select idtipo, nomtipo from agepnet200.tb_tiposituacaoprojeto 
                where flatiposituacao = 1";
        $retorno = $this->_db->fetchAll($sql);
        return $retorno;
    }

    public function getById($params)
    {
        $sql = "select * from agepnet200.tb_tiposituacaoprojeto 
                where idtipo = " . $params;
        $return = $this->_db->fetchRow($sql);

        return $return['nomtipo'];
    }

    public function retornaUltimo($params)
    {
        $sql = "select
                MAX(idtipo)AS idtipo,
                tp.nomtipo
                from agepnet200.tb_tiposituacaoprojeto tp
                inner join agepnet200.tb_statusreport st on tp.idtipo = 
                st.domstatusprojeto 
                where st.idprojeto = " . $params . "
                group by tp.nomtipo";

        $return = $this->_db->fetchRow($sql);
        return $return['nomtipo'];
    }

    public function retornaStatusDoProjeto($params)
    {
        $sql = "select idprojeto, domstatusprojeto as idstatusprojeto, 
            CASE
                WHEN domstatusprojeto = 1 THEN 'Proposta'
                WHEN domstatusprojeto = 2 THEN 'Em andamento'
                WHEN domstatusprojeto = 3 THEN 'Concluído'
                WHEN domstatusprojeto = 4 THEN 'Paralisado'
                WHEN domstatusprojeto = 5 THEN 'Cancelado'
                WHEN domstatusprojeto = 8 THEN 'Excluído'
                ELSE 'Selecione'
            END as domstatusprojeto
            from agepnet200.tb_projeto 
            where idprojeto = {$params['idprojeto']}";

        $return = $this->_db->fetchAll($sql);
        return $return;
    }
}
