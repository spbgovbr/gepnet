<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 28-06-2013
 * 10:07
 */
class Default_Model_Mapper_Permissao extends App_Model_Mapper_MapperAbstract
{

    protected $_dependencies = array('log');

    /**
     *
     * @var Zend_Log
     */
    protected $_log;

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Permissao
     */
    public function insert(Default_Model_Permissao $model)
    {
        try {
            $model->idpermissao = $this->maxVal('idpermissao');

            $data = array(
                "idpermissao" => $model->idpermissao,
                "idrecurso" => $model->idrecurso,
                "ds_permissao" => $model->ds_permissao,
                //"idperfil"   => $model->idperfil,
                "no_permissao" => $model->no_permissao,
                "visualizar" => $model->visualizar,
                "tipo" => $model->tipo,
            );

            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
            //Zend_Debug::dump($retorno);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     *
     * @param array $params
     * @return array of Default_Model_Permissao
     */
    public function retornaPorRecurso($params)
    {
        $sql = "select idpermissao, no_permissao as no_permissao, idrecurso, ds_permissao
                from agepnet200.tb_permissao
                where 1 = 1";

        if (isset($params['idrecurso'])) {
            $sql .= " and idrecurso = {$params['idrecurso']}";
        }

        $retorno = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($params); exit;
        //$this->_log->log('retornaPorRecurso: ' . $params['idrecurso'], Zend_Log::ALERT);

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Default_Model_Permissao');

        foreach ($retorno as $r) {
            $permissao = new Default_Model_Permissao($r);
            $collection[] = $permissao;
        }

        return $collection;
    }

    /**
     *
     * @param array $params
     * @return Default_Model_Permissao
     */
    public function retornaPorId($params)
    {
        return new Default_Model_Permissao($this->getById($params));
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "SELECT per.idpermissao, 
                       per.no_permissao, 
                       per.idrecurso, 
                       per.ds_permissao, 
                       rec.ds_recurso,
                       per.visualizar,
                       per.tipo,
                       CASE 
                              WHEN per.visualizar = '0' THEN 'False' 
                              WHEN per.visualizar = '1' THEN 'Verdadeiro' 
                       END AS detalhevisualizar, 
                       CASE 
                              WHEN tipo = 'G' THEN 'Geral' 
                              WHEN tipo = 'E' THEN 'Específica' 
                       END AS detalhetipo 
                FROM   agepnet200.tb_permissao AS per, 
                       agepnet200.tb_recurso   AS rec 
                WHERE  per.idrecurso = rec.idrecurso 
                        AND    per.idpermissao = :idpermissao";

        return $this->_db->fetchRow($sql, array(
            'idpermissao' => $params['idpermissao']
        ));
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getRecursosProjeto()
    {
        $sql = "select r.idrecurso, r.ds_recurso,
                replace(replace(replace(replace(replace(replace(upper(r.ds_recurso),'PROJETO:','')
                ,'ATAREUNIAO','ATA'),'LICAO','LICOES'),'RISCO','RISCOS'),'TERMOACEITE','ACEITE')
                ,'SOLICITACAOMUDANCA','MUDANCAS')
                txrecurso,
                CASE r.idrecurso
                  WHEN 28 THEN 8   WHEN 37 THEN 6 WHEN 21 THEN 3  WHEN 40 THEN 10
                  WHEN 41 THEN 2   WHEN 42 THEN 4 WHEN 60 THEN 15 WHEN 43 THEN 9
                  WHEN 35 THEN 13  WHEN 38 THEN 5 WHEN 44 THEN 7  WHEN 49 THEN 12
                  WHEN 30 THEN 11  WHEN 16 THEN 1 WHEN 58 THEN 16 WHEN 29 THEN 14
                  ELSE 99
                END as lista
                from
                agepnet200.tb_recurso r,
                agepnet200.tb_permissao p
                where
                r.idrecurso   = p.idrecurso and
                (
                   r.idrecurso IN( /* RECURSOS DE PROJETO */
                    16,21,28,29,30,35,37,38,40,41,42,43,44,49,58,60 )
                )
                group by r.idrecurso, r.ds_recurso
                order by lista asc ";

        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getRecursosProjetoPorId($params)
    {
        $sql = "select r.idrecurso, upper(r.ds_recurso),
		replace(replace(replace(upper(r.ds_recurso),'PROJETO:',''),'ATAREUNIAO','ATA'),'LICAO','LICOES')
                txrecurso, p.idpermissao, p.no_permissao
                from
                agepnet200.tb_recurso r,
                agepnet200.tb_permissao p
                where
                r.idrecurso = p.idrecurso and
                r.idrecurso = :idrecurso and
                r.idrecurso IN( /* RECURSOS DE PROJETO */
                    16,21,28,29,30,35,37,38,40,41,42,43,44,49,58,60
                )
                and visualizar='true'
                order by p.idpermissao, p.no_permissao ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idrecurso' => $params['idrecurso']
        ));
        return $resultado;

    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getRecursosProjetoPorParte($params)
    {
        $sql = "select r.idrecurso, upper(r.ds_recurso) ds_recurso,
                replace(replace(replace(upper(r.ds_recurso),'PROJETO:',''),'ATAREUNIAO','ATA'),'LICAO','LICOES') txrecurso,
                p.idpermissao, p.no_permissao, p.ds_permissao,
                (
                    SELECT count(c.idparteinteressada) ct FROM agepnet200.tb_permissaoprojeto c
                    where c.idparteinteressada = :idparteinteressada and
                          c.idprojeto          = :idprojeto and c.idrecurso=:idrecurso and
                          c.idpermissao        = p.idpermissao and c.ativo='S'
                ) ctpermissao
                from
                agepnet200.tb_recurso r,
                agepnet200.tb_permissao p
                where
                r.idrecurso = p.idrecurso and
                r.idrecurso = :idrecurso and
                r.idrecurso IN( /* RECURSOS DE PROJETO */
                    16,21,28,29,30,35,37,38,40,41,42,43,44,49,58,60
                )
                and visualizar='true'
                order by p.ds_permissao, p.no_permissao, p.idpermissao ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idrecurso' => $params['idrecurso'],
            'idparteinteressada' => $params['idparteinteressada'],
            'idprojeto' => $params['idprojeto']
        ));
        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getRecursosProjetoRecursoPorParte($params)
    {
        $sql = "select r.idrecurso, upper(r.ds_recurso),
                replace(replace(replace(upper(r.ds_recurso),'PROJETO:',''),'ATAREUNIAO','ATA'),'LICAO','LICOES') txrecurso,
                p.idpermissao, p.no_permissao,
                (
                    SELECT count(c.idparteinteressada) ct FROM agepnet200.tb_permissaoprojeto c
                    where c.idparteinteressada = :idparteinteressada and
                          c.idprojeto          = :idprojeto and c.idrecurso=r.idrecurso and
                          c.idpermissao        = p.idpermissao and c.ativo='S'
                ) ctpermissao
                from
                agepnet200.tb_recurso r,
                agepnet200.tb_permissao p
                where
                r.idrecurso = p.idrecurso and
                r.idrecurso IN( /* RECURSOS DE PROJETO */
                    16,21,28,29,30,35,37,38,40,41,42,43,44,49,58,60
                )
                and visualizar='true'
                order by r.idrecurso, p.no_permissao, p.idpermissao ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idparteinteressada' => $params['idparteinteressada'],
            'idprojeto' => $params['idprojeto']
        ));
        return $resultado;
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Permissao
     */
    public function update(Default_Model_Permissao $model)
    {
        $data = array(
            "ds_permissao" => $model->ds_permissao,
            "visualizar" => $model->visualizar,
            "tipo" => $model->tipo,
        );
        try {
            $pks = array(
                "idpermissao" => $model->idpermissao,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            return $this->getDbTable()->update($data, $where);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $paginator = false)
    {
        $sql = "select
                    rec.ds_recurso,
                    per.no_permissao,
                    per.ds_permissao,
                    1 as allow,
                    per.idpermissao
               from
                    agepnet200.tb_recurso rec,
                    agepnet200.tb_permissao per
               where
                    rec.idrecurso = per.idrecurso
                and  rec.ds_recurso <> 'default:error' ";
        $params = array_filter($params);
        if (isset($params['ds_recurso'])) {
            $ds_recurso = strtoupper($params['ds_recurso']);
            $sql .= " AND lower(rec.ds_recurso) LIKE '%{$ds_recurso}%'";
        }

        if (isset($params['no_pemissao'])) {
            $sql .= " AND lower(per.no_permissao) LIKE  '%{$params['no_permissao']}%'";
        }

        if (isset($params['ds_permissao'])) {
            $sql .= " AND lower(per.ds_permissao) LIKE  '%{$params['ds_permissao']}%'";
        }

        if (isset($params['idrecurso'])) {
            $sql .= " AND per.idrecurso =  {$params['idrecurso']}";
        }

        if (isset($params['idpermissao'])) {
            $sql .= " AND per.idpermissao =  {$params['idpermissao']}";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    /**
     *
     * @param int $perfil
     * @return array
     */
    public function retornaPorPerfil($perfil)
    {
        $sql = "select per.idpermissao, rec.idrecurso, per.no_permissao, rec.ds_recurso
                from
                    agepnet200.tb_permissaoperfil ppe,
                    agepnet200.tb_permissao per,
                    agepnet200.tb_recurso rec
                where
                    per.idpermissao = ppe.idpermissao
                    and rec.idrecurso = per.idrecurso
                    and ppe.idperfil = :perfil
                order by rec.ds_recurso asc, per.no_permissao asc";
        return $this->_db->fetchAll($sql, array('perfil' => $perfil));
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function fetchPairs()
    {
        $sql = "select idpermissao, no_permissao
                from agepnet200.tb_permissao";
        return $this->_db->fetchPairs($sql);
    }


    public function retornaRecursoEPermissaoPorTipo($tipo)
    {

        $sql = "select idrecurso, idpermissao from agepnet200.tb_permissao where visualizar = true ";

        if (!empty($tipo) && $tipo == 'G') {
            $sql .= "and tipo in('G') ";
        }

        $retorno = $this->_db->fetchAll($sql);

        return $retorno;
    }

    public function retornaRecursoEPermissaoDiagnosticoPorTipo($tipo)
    {

        $sql = "select p.idrecurso, p.idpermissao
                from agepnet200.tb_permissao p
                inner join agepnet200.tb_recurso r on r.idrecurso=p.idrecurso
                and r.ds_recurso like 'diagnostico:%'
                where 1=1 ";

        if (!empty($tipo) && $tipo == 'G') {
            $sql .= "and tipo in('G') ";
        }

        $retorno = $this->_db->fetchAll($sql);

        return $retorno;
    }
}