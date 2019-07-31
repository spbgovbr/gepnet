<?php

/**
 * Automatically generated data model
 *
 */
class Projeto_Model_Mapper_Linhatempo extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Linhatempo
     */
    public function insert(Projeto_Model_Linhatempo $model)
    {
        $model->id = $this->maxVal('id');
        $data = array(
            "id" => $model->id,
            "idpessoa" => $model->idpessoa,
            "dsfuncaoprojeto" => $model->dsfuncaoprojeto,
            "idrecurso" => $model->idrecurso,
            "tpacao" => $model->tpacao,
            "dtacao" => $dados['dtacao'] = new Zend_Db_Expr('now()'),
            "idprojeto" => $model->idprojeto,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Lista o recurso.
     *
     * @param string $controller Nome do controller.
     * @param string $module Nome do módulo.
     * @return array
     */
    public function getRecurso($controller, $module = 'projeto')
    {
        $sql = "select * from agepnet200.tb_recurso
        where ds_recurso = '" . $module . ":" . $controller . "'";
        $result = $this->_db->fetchAll($sql)[0];
        return $result;
    }

    /**
     * Lista toda a linha do tempo (auditoria) do usuário.
     * @param int $idPessoa
     * @param array $params
     * return array
     */
    public function listar($params = array())
    {
        $params = array_filter($params);
        $sql = "select tp.nompessoa, lt.dsfuncaoprojeto, rc.descricao, 
                    (CASE WHEN lt.tpacao = 'N' THEN 'Novo'
                    WHEN lt.tpacao = 'E' THEN 'Exclusão'
                    ELSE 'Alteração' END) tipo,
                    to_char(lt.dtacao, 'DD/MM/YYYY') dtacao, 
                    to_char(lt.dtacao, 'HH24:MI:SS') hracao
                from agepnet200.tb_linhatempo lt
                inner join agepnet200.tb_recurso rc
                on rc.idrecurso = lt.idrecurso
                inner join agepnet200.tb_pessoa tp
                on tp.idpessoa = lt.idpessoa
                where lt.idprojeto = " . $params['idprojeto'];
        if (isset($params['nompessoa'])) {
            $sql .= $params['nompessoa'] != "" ? " and upper(tp.nompessoa) like upper('%" . $params['nompessoa'] . "%') " : "";
        }
        if (isset($params['dsfuncaoprojeto'])) {
            $sql .= $params['dsfuncaoprojeto'] != "" ? " and upper(lt.dsfuncaoprojeto) like upper('%" . $params['dsfuncaoprojeto'] . "%') " : "";
        }
        if (isset($params['descricao'])) {
            $sql .= $params['descricao'] != "" ? " and upper(rc.descricao) like upper('%" . $params['descricao'] . "%') " : "";
        }
        if (isset($params['dtacao'])) {
            $sql .= $params['dtacao'] != "" ? " and to_char(lt.dtacao, 'DD/MM/YYYY') =  '" . $params['dtacao'] . "'" : "";
        }
        if (isset($params['dtacaoinicial']) && isset($params['dtacaofinal'])) {
            if ($params['dtacaoinicial'] != "" && $params['dtacaofinal'] != "") {
                $sql .= " and (lt.dtacao BETWEEN '" . $this->formataDataAmericano($params['dtacaoinicial']) . " 00:00:00' AND '" .
                    $this->formataDataAmericano($params['dtacaofinal']) . " 23:59:59') ";
            }
        }
        $sql .= ' order by lt.' . $params['sidx'] . ' ' . $params['sord'];
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

    private function formataDataAmericano($date)
    {
        $f = explode('/', $date);
        return "$f[2]-$f[1]-$f[0]";
    }

}