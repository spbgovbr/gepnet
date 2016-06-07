<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Atividadecronograma extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Atividadecronograma
     */
    public function inserirAtividade(Projeto_Model_Atividadecronograma $model, $inserePredecessora = true)
    {
        try {


            //$model->idatividadecronograma = $this->maxVal("idatividadecronograma");
            $model->idatividadecronograma = $this->maxVal("idatividadecronograma", "idprojeto = :idprojeto", array('idprojeto' => $model->idprojeto));
            //$model->idcadastrador         = 1;
            //$model->idelementodespesa     = 1;
            $data                         = array(
                "idatividadecronograma"  => $model->idatividadecronograma,
                "idprojeto"              => $model->idprojeto,
                "idgrupo"                => $model->idgrupo,
                "numpercentualconcluido" => $model->numpercentualconcluido,
                "nomatividadecronograma" => $model->nomatividadecronograma,
                "datiniciobaseline"      => new Zend_Db_Expr("to_date('" . $model->datiniciobaseline->format('Y-m-d') . "','YYYY-MM-DD')"),
                "datfimbaseline"         => new Zend_Db_Expr("to_date('" . $model->datfimbaseline->format('Y-m-d') . "','YYYY-MM-DD')"),
                "datinicio"              => new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')"),
                "datfim"                 => new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')"),
                "datcadastro"            => new Zend_Db_Expr("now()"),
                "domtipoatividade"       => $model->domtipoatividade,
                //"idresponsavel"          => $model->idresponsavel,
                "idparteinteressada"     => $model->idparteinteressada,
                "flacancelada"           => $model->flacancelada,
                "flaaquisicao"           => $model->flaaquisicao,
                "flainformatica"         => $model->flainformatica,
                "desobs"                 => $model->desobs,
                "idcadastrador"          => $model->idcadastrador,
                "idmarcoanterior"        => $model->idmarcoanterior,
                "numdias"                => $model->numdias,
                "numdiasrealizados"      => $model->numdiasrealizados,
                "numdiasbaseline"        => $model->numdiasbaseline,
                "vlratividadebaseline"   => $model->vlratividadebaseline,
                "vlratividade"           => $model->vlratividade,
                
                //"nomresponsavel"         => $model->nomresponsavel,
                "numfolga"               => $model->numfolga,
                //"descriterioaceitacao"   => $model->descriterioaceitacao,
                "idelementodespesa"      => $model->idelementodespesa,
                //"idpredecessora"         => $model->idpredecessora,
                //"idpredecessora2"        => $model->idpredecessora2,
                //"numseq"                 => $model->numseq,
            );

            $data = array_filter($data);

            $this->getDbTable()->insert($data);

            if($inserePredecessora){
                $predecessoras = $model->retornaPredecessoras();
                $model->limparPredecessoras();
                //Zend_Debug::dump($predecessoras, 'predecessoras');
                if ( $predecessoras ) {
                    $mapperPredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
                    foreach ( $predecessoras as $predecessora )
                    {
                        $predecessora->idatividade = $model->idatividadecronograma;
                        $predecessora->idprojeto = $model->idprojeto;
                        try {
                            $p = $mapperPredecessora->insert($predecessora);
                            $model->adicionarPredecessora($p);
                        } catch ( Exception $exc ) {
                            $this->_log->log($exc, Zend_Log::ERR);
                            throw $exc;
                        }
                    }
                }
            }
            return $model;
        } catch ( Exception $exc ) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function excluir($params)
    {
        try {
            $pks = array(
                "idprojeto" => $params['idprojeto'],
                "idatividadecronograma" => $params['idatividadecronograma'],
            );
            $where   = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch ( Exception $exc ) {
            throw $exc;
        }
    }

    /**
     *
     * @param Projeto_Model_Grupocronograma $model
     * @return Projeto_Model_Grupocronograma
     */
    public function inserirGrupo(Projeto_Model_Grupocronograma $model)
    {
        $model->idatividadecronograma = $this->maxVal("idatividadecronograma", "idprojeto = :idprojeto", array('idprojeto' => $model->idprojeto));

        $data = array(
            "idatividadecronograma"  => $model->idatividadecronograma,
            "idprojeto"              => $model->idprojeto,
            "nomatividadecronograma" => $model->nomatividadecronograma,
            "datcadastro"            => new Zend_Db_Expr("now()"),
            "domtipoatividade"       => $model->domtipoatividade,
            //"flacancelada"         => $model->flacancelada,
            "idcadastrador"          => $model->idcadastrador,
        );

        $data = array_filter($data);
        //Zend_Debug::dump($data);exit;
        $retorno = $this->getDbTable()->insert($data);

        return $model;
    }
    
    /**
     *
     * @param Projeto_Model_Grupocronograma $model
     * @return Projeto_Model_Grupocronograma
     */
    public function atualizarGrupo(Projeto_Model_Grupocronograma $model)
    {
        $data = array(
            "nomatividadecronograma" => $model->nomatividadecronograma,
            "flacancelada"           => $model->flacancelada,
        );
        
        $data = array_filter($data);
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     *
     * @param Projeto_Model_Entregacronograma $model
     * @return Projeto_Model_Entregacronograma
     */
    public function inserirEntrega(Projeto_Model_Entregacronograma $model)
    {
        $model->idatividadecronograma = $this->maxVal("idatividadecronograma", "idprojeto = :idprojeto", array('idprojeto' => $model->idprojeto));
        //$model->idcadastrador = 1;
        // Zend_Debug::dump($model); exit;


        $data = array(
            "idatividadecronograma"  => $model->idatividadecronograma,
            "idprojeto"              => $model->idprojeto,
            "idgrupo"                => $model->idgrupo,
            "nomatividadecronograma" => $model->nomatividadecronograma,
            "domtipoatividade"       => $model->domtipoatividade,
            "numpercentualconcluido" => $model->numpercentualconcluido,
            "datcadastro"            => new Zend_Db_Expr("now()"),
            "domtipoatividade"       => $model->domtipoatividade,
            "flacancelada"           => $model->flacancelada,
            "idcadastrador"          => $model->idcadastrador,
            "desobs"                 => $model->desobs,
            "idparteinteressada"     => $model->idparteinteressada,
            //"nomresponsavel"         => $model->nomresponsavel,
            "descriterioaceitacao"   => $model->descriterioaceitacao,
        );
        $data = array_filter($data);
        $this->getDbTable()->insert($data);
        return $model;
    }
    
     /**
     *
     * @param Projeto_Model_Entregacronograma $model
     * @return Projeto_Model_Entregacronograma
     */
    public function atualizarEntrega(Projeto_Model_Entregacronograma $model)
    {
        $data = array(
            "idgrupo"                => $model->idgrupo,
            "nomatividadecronograma" => $model->nomatividadecronograma,
            "domtipoatividade"       => $model->domtipoatividade,
            "domtipoatividade"       => $model->domtipoatividade,
            "flacancelada"           => $model->flacancelada,
            "desobs"                 => $model->desobs,
            "idparteinteressada"     => $model->idparteinteressada,
            "descriterioaceitacao"   => $model->descriterioaceitacao,
        );
        $data = array_filter($data);
        //Zend_Debug::dump($data);
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            print_r($exc);
            throw $exc;
        }
    }
    
     /**
     *
     * @param Projeto_Model_Atividadecronograma $model
     * @return Projeto_Model_Atividadecronograma
     */
    public function atualizarDatasAtividade(Projeto_Model_Atividadecronograma $model)
    {
        //Zend_Debug::dump($model);exit;

        $data = array(
            "datiniciobaseline" => new Zend_Db_Expr("to_date('" . $model->datiniciobaseline->format('Y-m-d') . "','YYYY-MM-DD')"),
            "datfimbaseline"    => new Zend_Db_Expr("to_date('" . $model->datfimbaseline->format('Y-m-d') . "','YYYY-MM-DD')"),
            "datinicio"         => new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')"),
            "datfim"            => new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')"),
        );
        $data = array_filter($data);
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    /**
     *
     * @param Projeto_Model_Entregacronograma $model
     * @return Projeto_Model_Entregacronograma
     */
    public function atualizarDatasEntrega(Projeto_Model_Entregacronograma $model)
    {
        $data = array(
            "datiniciobaseline" => new Zend_Db_Expr("to_date('" . $model->datiniciobaseline->format('Y-m-d') . "','YYYY-MM-DD')"),
            "datfimbaseline"    => new Zend_Db_Expr("to_date('" . $model->datfimbaseline->format('Y-m-d') . "','YYYY-MM-DD')"),
            "datinicio"         => new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')"),
            "datfim"            => new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')"),
        );
        $data = array_filter($data);
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );

        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            //Zend_Debug::dump($model);exit;
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    /**
     *
     * @param Projeto_Model_Entregacronograma $model
     * @return Projeto_Model_Entregacronograma
     */
    public function atualizarDatasGrupo(Projeto_Model_Grupocronograma $model)
    {
        $data = array(
            "datiniciobaseline" => (!empty($model->datiniciobaseline))? new Zend_Db_Expr("to_date('" . $model->datiniciobaseline->format('Y-m-d') . "','YYYY-MM-DD')") : "",
            "datfimbaseline"    => (!empty($model->datfimbaseline))?    new Zend_Db_Expr("to_date('" . $model->datfimbaseline->format('Y-m-d') . "','YYYY-MM-DD')"): "",
            "datinicio"         => (!empty($model->datinicio))?         new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')"): "",
            "datfim"            => (!empty($model->datfim))?            new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')"): "",
        );

        $data = array_filter($data);
        if(count($data) > 0){
            $pks = array(
                "idprojeto" => $model->idprojeto,
                "idatividadecronograma" => $model->idatividadecronograma
            );
            try {
                $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
                $this->getDbTable()->update($data, $where);
                return $model;
            } catch (Exception $exc) {
                throw $exc;
            }
        }
        return true;
    }
    
     /**
     *
     * @param Projeto_Model_Atividadecronograma $model
     * @return Projeto_Model_Atividadecronograma
     */
    public function atualizarAtividade(Projeto_Model_Atividadecronograma $model)
    {
        
        //Zend_Debug::dump($model, 'model'); exit;
        $data = array(
            "idatividadecronograma"  => $model->idatividadecronograma,
            "idprojeto"              => $model->idprojeto,
            "idgrupo"                => $model->idgrupo,
            "numpercentualconcluido" => $model->numpercentualconcluido,
            "nomatividadecronograma" => $model->nomatividadecronograma,
            "datinicio"              => new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')"),
            "datfim"                 => new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')"),
            //"datcadastro"            => new Zend_Db_Expr("now()"),
            "domtipoatividade"       => $model->domtipoatividade,
            "idparteinteressada"     => $model->idparteinteressada,
            "flacancelada"           => $model->flacancelada,
            "flaaquisicao"           => $model->flaaquisicao,
            "flainformatica"         => $model->flainformatica,
            "desobs"                 => $model->desobs,
            "idcadastrador"          => $model->idcadastrador,
            "idmarcoanterior"        => $model->idmarcoanterior,
            "numdias"                => $model->numdias,
            "numdiasrealizados"      => $model->numdiasrealizados,
            "numdiasbaseline"        => $model->numdiasbaseline,
            
            "vlratividadebaseline"   => $model->vlratividadebaseline,
            "vlratividade"           => $model->vlratividade,
            
            "numfolga"               => $model->numfolga,
            //"descriterioaceitacao"   => $model->descriterioaceitacao,
            "idelementodespesa"      => $model->idelementodespesa,
        );
        
        $data = array_filter($data);
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function retornaGrupoPorProjeto($params)
    {
        $sql = "SELECT
                    idatividadecronograma,
                    idprojeto,
                    idgrupo,
                    datfim as datafinal,
                    nomatividadecronograma,
                    to_char(datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(datfim, 'DD/MM/YYYY') as datfim,
                    to_char(datcadastro, 'DD/MM/YYYY') as datcadastro,
                    domtipoatividade,
                    datiniciobaseline as dtib,
                    datfimbaseline as dtfb,
                    numpercentualconcluido as numpercentualconcluido
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    domtipoatividade = 1
                    and idprojeto = :idprojeto
                ORDER BY datafinal asc, datfim asc";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
        
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Grupocronograma');
        
        foreach ( $resultado as $r )
        {
            $o          = new Projeto_Model_Grupocronograma($r);
            $o->entregas = new App_Model_Relation(
                $this, 'retornaEntrega', array(
                array(
                    'idprojeto'     => $o->idprojeto,
                    'idgrupo'       => $o->idatividadecronograma,
                    'pesquisa'      => $params
                )
                )
            );

            $collection[] = $o;
        }
        return $collection;
    }

    public function retornaEntrega($params)
    {
        if ( isset($params['pesquisa']) ) {
            $parametros = $params['pesquisa'];
        }else{
            $parametros = $params;
        }
        $sql = "SELECT
                    idatividadecronograma,
                    idprojeto,
                    idgrupo,
                    nomatividadecronograma,
                    to_char(datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(datfim, 'DD/MM/YYYY') as datfim,
                    to_char(datcadastro, 'DD/MM/YYYY') as datcadastro,
                    (datfim - datinicio) as qtdreal,
                    domtipoatividade,
                    idparteinteressada,
                    desobs,
                    idcadastrador,
                    descriterioaceitacao,
                    datiniciobaseline as dtib,
                    datfimbaseline as dtfb,
                    numpercentualconcluido as numpercentualconcluido
                FROM agepnet200.tb_atividadecronograma ent
                WHERE
                    idprojeto = :idprojeto
                    and idgrupo = :idgrupo
                    and domtipoatividade = 2";
        
        if (isset($parametros['idresponsavel'])) {
             $idparteinteressada = $parametros['idresponsavel'];
             $sql.= " and idparteinteressada = (select par.idparteinteressada
                                                from agepnet200.tb_parteinteressada par
                                                where par.idpessoainterna = {$idparteinteressada}
                                                and par.idprojeto = :idprojeto)";
         }
         
         $sql .= " ORDER BY dtib asc, dtfb asc, idatividadecronograma asc";
        
        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo'   => $params['idgrupo'],
        ));
        
        
        $mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();
        $mapperPredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
        
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Entregacronograma');

        foreach ( $resultado as $r )
        {
            $o = new Projeto_Model_Entregacronograma($r);
            
            $o->atividades = new App_Model_Relation(
                $this, 'retornaAtividade', array(
                    array(
                        'idprojeto' => $o->idprojeto,
                        'idgrupo'   => $o->idatividadecronograma,
                        'pesquisa'  => $parametros
                    )
                )
            );
            
            $o->parteinteressada = $mapperParteInteressada->retornaPorId(array(
                'idparteinteressada' => $o->idparteinteressada), true);
            
            $o->predecessoras = $mapperPredecessora->retornaPorAtividade(array(
                'idatividadecronograma' => $o->idatividadecronograma,
                'idprojeto'             => $o->idprojeto), false);
           
            $collection[] = $o;
        }

        return $collection;
    }

    public function retornaAtividade($params)
    {
        $sql = "SELECT
                    idatividadecronograma,
                    idprojeto,
                    idgrupo,
                    numpercentualconcluido,
                    datinicio as datainicio,
                    nomatividadecronograma,
                    to_char(datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(datfim, 'DD/MM/YYYY') as datfim,
                    to_char(datcadastro, 'DD/MM/YYYY') as datcadastro,
                    domtipoatividade,
                    idparteinteressada,
                    flacancelada,
                    flaaquisicao,
                    flainformatica,
                    desobs,
                    idcadastrador,
                    idmarcoanterior,
                    numdias,
                    numdiasrealizados,
                    vlratividadebaseline,
                    vlratividade,
                    numfolga,
                    idelementodespesa,
                    datiniciobaseline as dtib,
                    datfimbaseline as dtfb,
                    numpercentualconcluido as numpercentualconcluido
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idprojeto = :idprojeto
                    and idgrupo = :idgrupo
                    and domtipoatividade in (3,4)
                ";

        if (isset($params['pesquisa'])) {
            $parametros = array_filter($params['pesquisa']);
        }else{
            $parametros = array_filter($params);
        }
        if (isset($parametros['idresponsavel'])) {
             $idparteinteressada = $parametros['idresponsavel'];
             $sql.= " and idparteinteressada = (select par.idparteinteressada
                                                from agepnet200.tb_parteinteressada par
                                                where par.idpessoainterna = {$idparteinteressada}
                                                and par.idprojeto = :idprojeto)";
        }
        
        if (isset($parametros['idelementodespesa'])) {
            $idelementodespesa = $parametros['idelementodespesa'];
            $sql.= " and idelementodespesa = {$idelementodespesa}";
        }
        if (isset($parametros['statusatividade'])) {
            $status = $parametros['statusatividade'];
            switch($status):
                case 'C':
                    $sql .= " and flacancelada = 'S' "; break;
                case 100:
                    $sql .= " and numpercentualconcluido = 100 "; break;
                case 50:
                    $sql .= " and numpercentualconcluido < 100 "; break;
                case 'A':
                    $sql .= " and (numpercentualconcluido = 0 and datinicio < CURRENT_DATE ) "; break;
            endswitch;
        }
        if(isset($parametros['inicial_dti'])){
            $sql .= " and datinicio >= to_date('{$parametros['inicial_dti']}','DD/MM/YYYY')";
        }
        if(isset($parametros['inicial_dtf'])){
            $sql .= " and datinicio <= to_date('{$parametros['inicial_dtf']}','DD/MM/YYYY')";
        }
        if(isset($parametros['final_dti'])){
            $sql .= " and datfim >= to_date('{$parametros['final_dti']}','DD/MM/YYYY')";
        }
        if(isset($parametros['final_dtf'])){
            $sql .= " and datfim <= to_date('{$parametros['final_dtf']}','DD/MM/YYYY')";
        }
        $sql .= " ORDER BY datainicio asc";
        
        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo'   => $params['idgrupo'],
        ));

        $mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();
        $mapperPredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
        
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Atividadecronograma');

        foreach ( $resultado as $r )
        {
            $o        = new Projeto_Model_Atividadecronograma($r);
            $o->parteinteressada = $mapperParteInteressada->retornaPorId(array(
                'idparteinteressada' => $o->idparteinteressada), true);
            $o->predecessoras = $mapperPredecessora->retornaPorAtividade(array(
                'idatividadecronograma' => $o->idatividadecronograma,
                'idprojeto'             => $o->idprojeto), false);
            
            $collection[] = $o;
        }
        return $collection;
    }
    
    public function retornaMarco($params)
    {
        /**
         * @todo criar o model para os marcos
         */
        $sql = "SELECT
                    idatividadecronograma,
                    idprojeto,
                    idgrupo,
                    numpercentualconcluido,
                    nomatividadecronograma,
                    to_char(datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(datfim, 'DD/MM/YYYY') as datfim,
                    to_char(datcadastro, 'DD/MM/YYYY') as datcadastro,
                    domtipoatividade,
                    idparteinteressada,
                    flacancelada,
                    flaaquisicao,
                    flainformatica,
                    desobs,
                    idcadastrador,
                    idmarcoanterior,
                    numdias,
                    vlratividadebaseline,
                    vlratividade,
                    numfolga,
                    idelementodespesa
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idprojeto = :idprojeto
                    and idgrupo = :idgrupo
                    and domtipoatividade = 4";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo'   => $params['idgrupo'],
        ));
        //return $resultado;
        
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Atividadecronograma');

        foreach ( $resultado as $r )
        {
            $o            = new Projeto_Model_Atividadecronograma($r);
            $collection[] = $o;
        }

        return $collection;
    }

    public function retornaMarcosPorEntrega($params, $array = false, $collection = false)
    {
        /**
         * @todo criar o model para os marcos
         */
        $idprojeto =$params['idprojeto'];
        $identrega = $params['identrega'];

        $sql = "SELECT
                    idatividadecronograma,
                    idprojeto,
                    idgrupo,
                    numpercentualconcluido,
                    nomatividadecronograma,
                    to_char(datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(datfim, 'DD/MM/YYYY') as datfim,
                    to_char(datcadastro, 'DD/MM/YYYY') as datcadastro,
                    domtipoatividade,
                    idparteinteressada,
                    flacancelada,
                    flaaquisicao,
                    flainformatica,
                    desobs,
                    idcadastrador,
                    idmarcoanterior,
                    numdias,
                    vlratividadebaseline,
                    vlratividade,
                    numfolga,
                    idelementodespesa
                FROM agepnet200.tb_atividadecronograma
                WHERE idprojeto = $idprojeto and idgrupo = $identrega and domtipoatividade = 4";

        $resultado = $this->_db->fetchAll($sql);
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Atividadecronograma');

        foreach ( $resultado as $r )
        {
            $o            = new Projeto_Model_Atividadecronograma($r);
            $collection[] = $o;
        }

        return $collection;
    }




    public function fetchPairsGrupo($params)
    {
        $sql   = "SELECT
                    idatividadecronograma, nomatividadecronograma
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idprojeto = :idprojeto
                    and domtipoatividade = 1";
        
        return $this->_db->fetchPairs($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
    }

    public function fetchPairsEntrega($params)
    {
        $sql   = "SELECT
                    idatividadecronograma, nomatividadecronograma
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idprojeto = :idprojeto
                    and domtipoatividade = 2";

        return $this->_db->fetchPairs($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
    }
    public function fetchPairsMarcoPorEntrega($params)
    {
        $sql   = "SELECT
                    idatividadecronograma, nomatividadecronograma
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idprojeto = :idprojeto
                    and idgrupo = :idgrupo
                    and domtipoatividade = 4";

        return $this->_db->fetchPairs($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo' => $params['identrega']
        ));
    }

    public function fetchPairsAtividade($params)
    {
        $sql   = "SELECT
                    idatividadecronograma, to_char(datinicio, 'DD/MM/YYYY') || ' a ' || to_char(datfim, 'DD/MM/YYYY') || ' - ' || nomatividadecronograma
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idprojeto = :idprojeto
                    and domtipoatividade in(3,4)
                    order by datinicio asc ";

        return $this->_db->fetchPairs($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
    }

    protected function fetchPairs($params, $tipo)
    {
        $sql   = "SELECT
                    idatividadecronograma, nomatividadecronograma
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idprojeto = :idprojeto";
        $binds = array(
            'idprojeto' => $params['idprojeto'],
        );

        switch ( $tipo )
        {
            case Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_GRUPO :
                $sql .= " and domtipoatividade = 1";
                break;
            case Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_ENTREGA :
                //$sql .= " and idgrupo = :idgrupo and domtipoatividade = 2";
                $sql .= " and domtipoatividade = 2";
                //$binds['idgrupo'] = $params['idgrupo'];
                break;
            case Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM :
                //$sql .= " and idgrupo = :idgrupo and domtipoatividade = 3";
                $sql .= " and domtipoatividade = 3";
                //$binds['idgrupo'] = $params['idgrupo'];
                break;
            case Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO :
                $sql .= " and idgrupo = :idgrupo and domtipoatividade = 4";
                $binds['idgrupo'] = $params['idgrupo'];
                break;
            default:
                $sql .= " and idgrupo = :idgrupo and domtipoatividade = 3";
                $binds['idgrupo'] = $params['idgrupo'];
                break;
        }

        return $this->_db->fetchPairs($sql, $binds);
    }

    public function retornaInicioBaseLinePorPredecessoras($params)
    {
        $sql = "select
                    to_char(max(datfim), 'DD/MM/YYYY')
                from
                    agepnet200.tb_atividadecronograma cron
                where
                    cron.idprojeto = :idprojeto
                    and cron.idatividadecronograma in (" . implode(',', $params['predecessora']) . ")";
        //Zend_Debug::dump($sql);exit;

        return $this->_db->fetchOne($sql, array(
            'idprojeto' => $params['idprojeto'],
            //'predecessora' => implode(',', $params['predecessora']),
        ));

    }

    public function retornaInicioRealPorPredecessoras($params)
    {
        //Zend_Debug::dump($params);exit;

        $sql = "select
                    to_char(max(datfim), 'DD/MM/YYYY')
                from
                    agepnet200.tb_atividadecronograma cron
                where
                    cron.idprojeto = :idprojeto
                    and cron.idatividadecronograma in (" . $params['predecessora'] . ")";
        //Zend_Debug::dump($sql);exit;
        return $this->_db->fetchOne($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
    }
    
    public function retornaEntregasEMarcosPorProjeto($params)
    {
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.idprojeto,
                    cron.idgrupo,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    cron.domtipoatividade,
                    cron.desobs,
                    to_char(cron.datcadastro,'DD/MM/YYYY') as datcadastro,
                    cron.idmarcoanterior,
                    cron.numdias,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.descriterioaceitacao,
                    cron.idelementodespesa,
                    cron.idcadastrador,
                    --p1.nomparteinteressada as idparteinteressada,
                    p1.idparteinteressada,
                    to_char(cron.datiniciobaseline,'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline,'DD/MM/YYYY') as datfimbaseline,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flacancelada,
                    to_char(cron.datinicio,'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim,'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                LEFT OUTER JOIN
                    agepnet200.tb_parteinteressada p1 ON cron.idparteinteressada = p1.idparteinteressada
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.domtipoatividade IN (" . $params['domtipoatividade'] . ")"; 
                    /* domtipoatividade
                     * 1 - grupo
                     * 2 - entrega
                     * 3 - atividade
                     * 4 - marco
                     */
        $sql .= " ORDER BY cron.datiniciobaseline ASC,cron.datinicio, cron.idatividadecronograma,cron.idgrupo ";
//        $tipoAtividade = explode(',', $params['domtipoatividade']);
        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
        
        
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Atividadecronograma');

        foreach ( $resultado as $r )
        {
            $o            = new Projeto_Model_Atividadecronograma($r);
            $collection[] = $o;
        }

        return $collection;
    }
    
    public function retornaGrupoPorId($params, $model = false, $collection = false)
    {
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.idprojeto,
                    cron.idgrupo,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    cron.domtipoatividade,
                    cron.desobs,
                    cron.datcadastro,
                    cron.idmarcoanterior,
                    cron.numdias,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.descriterioaceitacao,
                    cron.idelementodespesa,
                    cron.idcadastrador,
                    to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flacancelada,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.idatividadecronograma = :idatividadecronograma
                    and cron.domtipoatividade = 1";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma'],
        ));
        
        if($model){
            $grupo = new Projeto_Model_Grupocronograma($resultado);
            return $grupo;
        }
        
        if($collection){
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Grupocronograma');

            $o          = new Projeto_Model_Grupocronograma($resultado);
            $o->entregas = new App_Model_Relation(
                $this, 'retornaEntrega', array(
                    array(
                        'idprojeto' => $o->idprojeto,
                        'idgrupo'   => $o->idatividadecronograma
                    )
                )
            );
            $collection = $o;
            return $collection;
        }
        
        return $resultado;
    }

    public function retornaEntregaPorId($params, $array = false, $collection = false)
    {
        //Zend_Debug::dump($params);exit;
        $idprojeto = $params['idprojeto'];
        $idatividadecronograma = $params['idatividadecronograma'];

        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.idprojeto,
                    cron.idgrupo,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    cron.domtipoatividade,
                    cron.desobs,
                    cron.datcadastro,
                    cron.idmarcoanterior,
                    cron.numdias,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.descriterioaceitacao,
                    cron.idelementodespesa,
                    cron.idcadastrador,
                    p1.nomparteinteressada as nomparteinteressada,
                    cron.idparteinteressada,
                    to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flacancelada,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM
                    agepnet200.tb_atividadecronograma cron
                LEFT OUTER JOIN
                    agepnet200.tb_parteinteressada p1 ON cron.idparteinteressada = p1.idparteinteressada
                WHERE
                    cron.idprojeto = $idprojeto
                    and cron.idatividadecronograma = $idatividadecronograma
                    and cron.domtipoatividade = 2";
        //Zend_Debug::dump($sql);exit;
        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */

        $resultado = $this->_db->fetchRow($sql);
        
        if($array){
            return $resultado;
        }
        
        if($collection){
            $mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();
            $mapperPredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
            
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Entregacronograma');
        
            $o = new Projeto_Model_Entregacronograma($resultado);
            $o->atividades = new App_Model_Relation(
                $this, 'retornaAtividade', array(
                    array(
                        'idprojeto' => $o->idprojeto,
                        'idgrupo'   => $o->idatividadecronograma
                    )
                )
            );
            
            $o->parteinteressada = $mapperParteInteressada->retornaPorId(array(
                'idparteinteressada' => $o->idparteinteressada), true);
            $o->predecessoras = $mapperPredecessora->retornaPorAtividade(array(
                'idatividadecronograma' => $o->idatividadecronograma,
                'idprojeto'             => $o->idprojeto), false);
            $collection = $o;
            return $collection;
        }

        $entrega = new Projeto_Model_Entregacronograma($resultado);
        return $entrega;
    } 
    
    public function retornaProximoMarco($params)
    {
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.nomatividadecronograma,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                WHERE
                    cron.numpercentualconcluido != 100
                    and cron.idprojeto = :idprojeto
                    and cron.domtipoatividade = 4";
                    /* domtipoatividade
                     * 1 - grupo
                     * 2 - entrega
                     * 3 - atividade
                     * 4 - marco
                     */

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));

        $marco = new Projeto_Model_Atividadecronograma($resultado);
        return $marco;
    }
      public function retornaNumFolgaAtividade($params)
    {
        $sql = "SELECT numfolga 
                FROM 
                    agepnet200.tb_atividadecronograma
                WHERE 
                    idprojeto =:idprojeto 
                    and idatividadecronograma = :idatividadecronograma";

        $resultado = $this->_db->fetchRow($sql, $params);
        return $resultado;
    }
    
    public function retornaAtividadePorId($params, $predecessoras = false)
    {
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.idprojeto,
                    cron.idgrupo,
                    cron.numpercentualconcluido,
                    cron.nomatividadecronograma,
                    cron.domtipoatividade,
                    cron.desobs,
                    cron.datcadastro,
                    cron.idmarcoanterior,
                    cron.numdias,
                    cron.numdiasbaseline,
                    cron.numdiasrealizados,
                    cron.vlratividadebaseline,
                    cron.vlratividade,
                    cron.numfolga,
                    cron.descriterioaceitacao,
                    cron.idparteinteressada,
                    cron.idelementodespesa,
                    cron.idcadastrador,
                    to_char(cron.datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline, 
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline, 
                    cron.flaaquisicao,
                    cron.flainformatica,
                    cron.flacancelada,
                    to_char(cron.datinicio, 'DD/MM/YYYY') as datinicio, 
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.idatividadecronograma = :idatividadecronograma
                    and cron.domtipoatividade in (3,4)";
        
        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma'],
        ));
        
        if($predecessoras){
            $mapperAtividadePredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
            $predecessoras = $mapperAtividadePredecessora->retornaPorAtividade($params);
            $resultado['predecessoras'] = $predecessoras;
        }
        //Zend_Debug::dump($resultado);exit;
        return $resultado;
    }
    
    public function retornaDatasPorEntrega($params)
    {
        $sql = "SELECT
                    to_char(min(cron.datiniciobaseline), 'DD/MM/YYYY') as datiniciobaseline, 
                    to_char(max(cron.datfimbaseline), 'DD/MM/YYYY') as datfimbaseline, 
                    to_char(min(cron.datinicio), 'DD/MM/YYYY') as datinicio,
                    to_char(max(cron.datfim), 'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                WHERE
                    cron.idgrupo = :idgrupo
                    and cron.idprojeto = :idprojeto
                    and cron.flacancelada = 'N'
                    and cron.domtipoatividade IN (3, 4)";
        
        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo'   => $params['idgrupo'],
        ));
        
        return $resultado;
    }
    
    public function retornaDatasPorGrupo($params)
    {
        $sql = "SELECT
                    to_char(min(cron.datiniciobaseline), 'DD/MM/YYYY') as datiniciobaseline, 
                    to_char(max(cron.datfimbaseline), 'DD/MM/YYYY') as datfimbaseline, 
                    to_char(min(cron.datinicio), 'DD/MM/YYYY') as datinicio,
                    to_char(max(cron.datfim), 'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                WHERE
                    cron.idgrupo = :idgrupo
                    and cron.idprojeto = :idprojeto
                    and cron.domtipoatividade = 2";
        
        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo'   => $params['idgrupo'],
        ));
        
        return $resultado;
    }
    
    public function retornaMetaDadosPorProjeto($params)
    {
        $sql = "SELECT
                    to_char(min(cron.datiniciobaseline), 'DD/MM/YYYY') as datiniciobaseline, 
                    to_char(max(cron.datfimbaseline), 'DD/MM/YYYY') as datfimbaseline, 
                    to_char(max(cron.datfim), 'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.flacancelada = 'N'
                    and cron.domtipoatividade = 3";
        
        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
        
        return $resultado;
    }
    
    
    public function retornaAtividadesConcluidasPorPeríodo($params)
    {
        $sql = "SELECT
                    idatividadecronograma,
                    idprojeto,
                    idgrupo,
                    numpercentualconcluido,
                    nomatividadecronograma,
                    to_char(datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(datfim, 'DD/MM/YYYY') as datfim,
                    datfim as datafim,
                    to_char(datcadastro, 'DD/MM/YYYY') as datcadastro,
                    domtipoatividade,
                    idparteinteressada,
                    flacancelada,
                    flaaquisicao,
                    flainformatica,
                    desobs,
                    idcadastrador,
                    idmarcoanterior,
                    numdias,
                    vlratividadebaseline,
                    vlratividade,
                    numfolga,
                    idelementodespesa
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idprojeto = :idprojeto
                    and domtipoatividade = 3
                    and numpercentualconcluido = 100
                    and datfim > to_date(:datainicial,'DD/MM/YYYY') 
                    and datfim <= to_date(:datafinal,'DD/MM/YYYY')
                ORDER BY datafim DESC LIMIT 10 --datiniciobaseline asc, datfimbaseline asc";


        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto'   => $params['idprojeto'],
            'datainicial' => $params['datainicial'],
            'datafinal'   => $params['datafinal']
        ));
        return $resultado;
    }
    
    public function retornaAtividadesEmAndamentoPorPeríodo($params)
    {
        $sql = "SELECT
                    idatividadecronograma,
                    idprojeto,
                    idgrupo,
                    numpercentualconcluido,
                    nomatividadecronograma,
                    to_char(datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(datfim, 'DD/MM/YYYY') as datfim,
                    datfim as datafim,
                    to_char(datcadastro, 'DD/MM/YYYY') as datcadastro,
                    domtipoatividade,
                    idparteinteressada,
                    flacancelada,
                    flaaquisicao,
                    flainformatica,
                    desobs,
                    idcadastrador,
                    idmarcoanterior,
                    numdias,
                    vlratividadebaseline,
                    vlratividade,
                    numfolga,
                    idelementodespesa
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idprojeto = :idprojeto
                    and domtipoatividade = 3
                    and numpercentualconcluido != 100
                    and numpercentualconcluido != 0
                ORDER BY datafim DESC  LIMIT 10 --datiniciobaseline asc, datfimbaseline asc";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto'   => $params['idprojeto'],
        ));
        return $resultado;
    }
    
    /**
     *
     * @param Projeto_Model_Atividadecronograma $model
     * @return Projeto_Model_Atividadecronograma
     */
    public function atividadeAtualizarPercentual(Projeto_Model_Atividadecronograma $model)
    {

        if($model->numpercentualconcluido>0){
            $data = array(
                "numpercentualconcluido" => $model->numpercentualconcluido,
                "datinicio"              => new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')"),
                "datfim"                 => new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')"),
            );
        }else{
            $data = array(
            "datinicio"              => new Zend_Db_Expr("to_date('" . $model->datinicio->format('Y-m-d') . "','YYYY-MM-DD')"),
            "datfim"                 => new Zend_Db_Expr("to_date('" . $model->datfim->format('Y-m-d') . "','YYYY-MM-DD')"),
            );
        }
        $data = array_filter($data);
        if($model->numpercentualconcluido==0){
            $adiconaArray = array("numpercentualconcluido" => $model->numpercentualconcluido);
            $data = array_replace($data, $adiconaArray);
        }

        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );

        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    /**
     *
     * @param Projeto_Model_Atividadecronograma $model
     * @return Projeto_Model_Atividadecronograma
     */
    public function atualizarPercentuaisGrupoEntrega(Projeto_Model_Atividadecronograma $model)
    {
        $data = array(
            "numpercentualconcluido" => $model->numpercentualconcluido,
        );
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    
     /**
     *
     * @param Projeto_Model_Atividadecronograma $model
     * @return Projeto_Model_Atividadecronograma
     */
    public function atualizarTipoAtividade(Projeto_Model_Atividadecronograma $model)
    {
        $data = array(
            "idatividadecronograma"  => $model->idatividadecronograma,
            "idprojeto"              => $model->idprojeto,
            "domtipoatividade"       => $model->domtipoatividade,
        );
        
        $data = array_filter($data);
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    public function fetchPairsMarcosPorProjeto($params)
    {
        $sql = "SELECT
                    cron.idatividadecronograma,
                    (cron.nomatividadecronograma || ' - ' || to_char(cron.datiniciobaseline, 'DD/MM/YYYY') || ' - '||  
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY')) as data
                FROM 
                    agepnet200.tb_atividadecronograma cron
                WHERE
                    cron.idprojeto = :idprojeto
                    and cron.domtipoatividade = 4";

        $resultado = $this->_db->fetchPairs($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));

        return $resultado;
    }
    
    public function pesquisar($params)
    {
        //Zend_Debug::dump($params, 'params');
        $sql = "SELECT
                    idatividadecronograma,
                    idprojeto,
                    idgrupo,
                    domtipoatividade
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idprojeto = {$params['idprojeto_pesq']}";

                    
        $percentualinicio = $params['percentualinicio'];            
        $params = array_filter($params);
        if (isset($params['status'])) {
            if($params['status'] == 50){
                if(isset($params['percentualfim'])){
                    //$percentualinicio = $params['percentualinicio'];
                    $percentualfim = $params['percentualfim'];
                    $sql.= " and numpercentualconcluido >= {$percentualinicio} and numpercentualconcluido <= {$percentualfim}";
                }
            } else {
                $sql.= " and numpercentualconcluido = 100";
            }
        }
        
        if (isset($params['domtipoatividade_pesq'])) {
            $domtipoatividade = $params['domtipoatividade_pesq'];
            $sql.= " and domtipoatividade = {$domtipoatividade}";
        }

        if (isset($params['idparteinteressada_pesq'])) {
            $idparteinteressada = $params['idparteinteressada_pesq'];
            $sql.= " and idparteinteressada = {$idparteinteressada}";
        }
        
        if(isset($params['inicial_dti'])){
            $sql .= " and datinicio >= to_date('{$params['inicial_dti']}','DD/MM/YYYY')";
        }
        
        if(isset($params['inicial_dtf'])){
            $sql .= " and datinicio <= to_date('{$params['inicial_dtf']}','DD/MM/YYYY')";
        }
        
        if(isset($params['final_dti'])){
            $sql .= " and datfim >= to_date('{$params['final_dti']}','DD/MM/YYYY')";
        }
        
        if(isset($params['final_dtf'])){
            $sql .= " and datfim <= to_date('{$params['final_dtf']}','DD/MM/YYYY')";
        }

        
       // Zend_Debug::dump($sql); exit;
        $resultado = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($resultado); exit;
        return $resultado;
    }
       
    public function retornaGrupoPorAtividade($params){
        
        $sql = "SELECT idprojeto,
                       idgrupo
                FROM agepnet200.tb_atividadecronograma
                WHERE idprojeto = :idprojeto
                  and idatividadecronograma = (select ativ.idgrupo 
                                                from agepnet200.tb_atividadecronograma as ativ
                                                where ativ.idatividadecronograma = :idatividadecronograma
                                                and ativ.idprojeto = :idprojeto)
                ORDER BY idatividadecronograma asc";
        
        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idatividadecronograma' => $params['idatividadecronograma'],
        ));
       
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Grupocronograma');
       
            $o          = new Projeto_Model_Grupocronograma($resultado);
            $o->entregas = new App_Model_Relation(
                $this, 'retornaEntrega', array(
                array(
                    'idprojeto' => $o->idprojeto,
                    'idgrupo'   => $o->idgrupo
                )
                )
            );
            $collection = $o;
       
        return $collection;
        
    }
    
    public function retornaUltimoMarco($params) {
        $sql = "SELECT
                    cron.idatividadecronograma,
                    cron.nomatividadecronograma,
                    to_char(cron.datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(cron.datfim, 'DD/MM/YYYY') as datfim
                FROM 
                    agepnet200.tb_atividadecronograma cron
                WHERE
                    cron.numpercentualconcluido != 100
                    and cron.idprojeto = :idprojeto
                    and cron.domtipoatividade = 4";
        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */
        $sql .= "ORDER BY cron.datfimbaseline DESC";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));

        $marco = new Projeto_Model_Atividadecronograma($resultado);
        return $marco;
    }

    public function getById($params) {
        $sql = "SELECT
                    idatividadecronograma,
                    idprojeto,
                    idgrupo,
                    numpercentualconcluido,
                    nomatividadecronograma,
                    to_char(datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(datfim, 'DD/MM/YYYY') as datfim,
                    to_char(datcadastro, 'DD/MM/YYYY') as datcadastro,
                    domtipoatividade,
                    idparteinteressada,
                    flacancelada,
                    flaaquisicao,
                    flainformatica,
                    desobs,
                    idcadastrador,
                    idmarcoanterior,
                    numdias,
                    vlratividadebaseline,
                    vlratividade,
                    numfolga,
                    idelementodespesa
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idatividadecronograma = :idatividadecronograma";

        $resultado = $this->_db->fetchRow($sql, array(
            'idatividadecronograma' => $params['idatividadecronograma'],
        ));

        $retorno = new Projeto_Model_Atividadecronograma($resultado);
        return $retorno;
    }
    
    public function retornaGrupoPorEntrega($params){
        $sql = "SELECT
                    idatividadecronograma,
                    idprojeto,
                    idgrupo,
                    numpercentualconcluido,
                    nomatividadecronograma,
                    to_char(datiniciobaseline, 'DD/MM/YYYY') as datiniciobaseline,
                    to_char(datfimbaseline, 'DD/MM/YYYY') as datfimbaseline,
                    to_char(datinicio, 'DD/MM/YYYY') as datinicio,
                    to_char(datfim, 'DD/MM/YYYY') as datfim,
                    to_char(datcadastro, 'DD/MM/YYYY') as datcadastro,
                    domtipoatividade,
                    idparteinteressada,
                    flacancelada,
                    flaaquisicao,
                    flainformatica,
                    desobs,
                    idcadastrador,
                    idmarcoanterior,
                    numdias,
                    vlratividadebaseline,
                    vlratividade,
                    numfolga,
                    idelementodespesa
                FROM agepnet200.tb_atividadecronograma
                WHERE
                    idatividadecronograma = :idatividadecronograma
                    and idprojeto = :idprojeto";
        
        $resultado = $this->_db->fetchRow($sql, array(
            'idatividadecronograma' => $params['idatividadecronograma'],
            'idprojeto'             => $params['idprojeto']
        ));
        
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Grupocronograma');
       
            $o          = new Projeto_Model_Grupocronograma($resultado);
            $o->entregas = new App_Model_Relation(
                $this, 'retornaEntrega', array(
                    array(
                        'idprojeto' => $o->idprojeto,
                        'idgrupo'   => $o->idgrupo
                    )
                )
            );
            $collection = $o;
       
        return $collection;
        
    }


    public function retornaAtividadeGantt($params) 
    {
        $sql =  "select 
                        nv1.idatividadecronograma as nv1_idatividadecronograma, 
                        nv1.idprojeto as nv1_idprojeto, 
                        nv1.domtipoatividade as nv1_domtipoatividade, 
                        nv1.idgrupo as nv1_idgrupo, 
                        nv1.datinicio as nv1_datinicio, 
                        nv1.datfim as nv1_datfim, 
                        nv1.nomatividadecronograma as nv1_nomatividadecronograma, 
                        nv1.numdias as nv1_numdias,
                        nv2.idatividadecronograma as nv2_idatividadecronograma, 
                        nv2.idprojeto as nv2_idprojeto, 
                        nv2.idgrupo as nv2_idgrupo, 
                        nv2.datinicio as nv2_datinicio, 
                        nv2.datfim as nv2_datfim,
                        nv2.domtipoatividade as nv2_domtipoatividade,
                        nv2.nomatividadecronograma as nv2_nomatividadecronograma, 
                        nv2.numdias as nv2_numdias,
                        nv3.idatividadecronograma as nv3_idatividadecronograma,
                        nv3.idprojeto as nv3_idprojeto,
                        nv3.idgrupo as nv3_idgrupo,
                        nv3.datinicio as nv3_datinicio,
                        nv3.datfim as nv3_datfim,
                        nv3.domtipoatividade as nv3_domtipoatividade,
                        nv3.nomatividadecronograma as nv3_nomatividadecronograma,
                        nv3.numdias as nv3_numdias,
                        nv3.numpercentualconcluido as nv3_numpercentualconcluido
                from agepnet200.tb_atividadecronograma nv1
                        inner join agepnet200.tb_atividadecronograma nv2 on nv2.idgrupo = nv1.idatividadecronograma -- inner trata grupo sem entrega mas com marcor e/ou atividade
                        and nv2.idprojeto = nv1.idprojeto 
                        and nv2.domtipoatividade = ".Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_ENTREGA."
                        left join agepnet200.tb_atividadecronograma nv3 on nv3.idgrupo = nv2.idatividadecronograma 
                        and nv3.idprojeto = nv1.idprojeto	
                        and(
                            nv3.domtipoatividade = ".Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM."
                            or nv3.domtipoatividade = ".Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO."
                            or nv3.domtipoatividade is null
                            )
                where nv1.domtipoatividade = ".Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_GRUPO. " 
                        and nv1.idprojeto = :idprojeto
                        and nv1.datinicio is not null
                        and nv1.datfim is not null
                order by nv1.idatividadecronograma, 
                         nv2.idatividadecronograma, 
                         nv3.idgrupo, 
                         nv3.idatividadecronograma";
        
        $result = $this->_db->fetchAll($sql, $params);
        
        return $result;
    }
    
    
    
   public function atualizarEntregaEap(Projeto_Model_Atividadecronograma $model)
   {
       $data = array(
           "idgrupo"   => $model->idgrupo
       );
        $data = array_filter($data);
        $pks = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma
        );
        
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
   }

    public function verificarAtividadesDesatualizadas($params){

        $hoje = date("Y-m-d");
        $sql = "SELECT
                       *
				   FROM
				       agepnet200.tb_atividadecronograma
				   WHERE
					   idprojeto = :idprojeto
                   AND domtipoatividade != 1
                   AND domtipoatividade != 2
                   AND numpercentualconcluido != 100
                   AND flacancelada != 'S'
                   AND datfim <= '$hoje' order by idatividadecronograma ASC";
                    /* domtipoatividade
                     * 1 - grupo
                     * 2 - entrega
                     * 3 - atividade
                     * 4 - marco
                     */
        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));

        return $resultado;
    }

    public function retornaTendenciaProjeto($params){
        $sql = "SELECT
                  to_char(MAX(datfim), 'DD/MM/YYYY') as datfimprojetotendencia
                FROM
                  agepnet200.tb_atividadecronograma
                WHERE
			      idprojeto = :idprojeto ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));

        return $resultado['datfimprojetotendencia'];
    }

    public function retornaIrregularidades($params){

        $data = null;
        $hoje = date('Y-m-d');
        $sql = "SELECT
                    nomatividadecronograma
		        FROM
		            agepnet200.tb_atividadecronograma
		        WHERE
		            idprojeto = :idprojeto
		            AND  datfimbaseline < datfim
		            AND  datiniciobaseline > '$hoje'
		            AND  numpercentualconcluido != 100 ";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));

//        Zend_Debug::dump($resultado);

        if($resultado){
            foreach($resultado as $r){
                $data .=  $r["nomatividadecronograma"] . "\n";
            }
        }

        return $data;
    }
   
}