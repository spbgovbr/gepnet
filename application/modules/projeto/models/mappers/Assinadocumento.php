<?php

class Projeto_Model_Mapper_Assinadocumento extends App_Model_Mapper_MapperAbstract
{
    /**
     * Cadastra os documentos assinados
     * @param array $params
     * @return array
     */
    public function inserir($params)
    {
        try {
            $params['id'] = $this->maxVal('id');

            $data = array(
                "id" => $params['id'],
                "idprojeto" => $params['idprojeto'],
                "idpessoa" => $params['idpessoa'],
                "nomfuncao" => $params['nomfuncao'],
                "assinado" => new Zend_Db_Expr("now()"),
                "tipodoc" => (int)$params['tipodoc'],
                "hashdoc" => $params['hashdoc'],
                "idaceite" => (isset($params['idaceite'])) ? $params['idaceite'] : null,
                "situacao" => 'A',
            );
            $data = array_filter($data);
            $retorno = $this->getDbTable()->insert($data);
            //var_dump($retorno);die;
            return $retorno;
        } catch (Exception $exc) {
            $this->_log->err($exc);
            throw $exc;
        }
    }

    /**
     * Retorna os documentos assinados por projeto
     * @param array $params
     * @return string
     */
    public function retornaAssinaturaPorProjeto($params)
    {

        $sql = "SELECT ad.id, ad.idprojeto, pes.idpessoa, pes.nompessoa as nomparteinteressada, ad.nomfuncao,
                to_char(ad.assinado,'DD/MM/YYYY HH24:MI:SS') as assinado,
                CASE ad.tipodoc
                   WHEN 1 THEN 'Termo de Abertura'
                   WHEN 2 THEN 'Plano de Projeto'
                   WHEN 3 THEN 'Termo de Aceite'
                   WHEN 4 THEN 'Termo de encerramento do Projeto'
                END AS tipo, trim(ad.hashdoc) as hashdoc
                FROM agepnet200.tb_assinadocumento ad
                inner join agepnet200.tb_projeto p on p.idprojeto= ad.idprojeto
                inner join agepnet200.tb_pessoa pes on pes.idpessoa=ad.idpessoa
                WHERE ad.idprojeto = :idprojeto and ad.situacao in('A')";


        return $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));
    }

    /**
     * Retorna todos os aceites assinados assinados por projeto
     * @param array $params
     * @return string
     */
    public function retornaTodosAceitesAssinadosPorProjeto($params)
    {

        $sql = "SELECT ad.id, ad.idprojeto, pes.idpessoa, pes.nompessoa as nomparteinteressada, ad.nomfuncao,
                to_char(ad.assinado,'DD/MM/YYYY HH24:MI:SS') as assinado,
                CASE ad.tipodoc
                   WHEN 1 THEN 'Termo de Abertura'
                   WHEN 2 THEN 'Plano de Projeto'
                   WHEN 3 THEN 'Termo de Aceite'
                   WHEN 4 THEN 'Termo de encerramento do Projeto'
                END AS tipo, trim(ad.hashdoc) as hashdoc, ad.idaceite
                FROM agepnet200.tb_assinadocumento ad
                inner join agepnet200.tb_projeto p on p.idprojeto= ad.idprojeto
                inner join agepnet200.tb_pessoa pes on pes.idpessoa=ad.idpessoa
                INNER JOIN agepnet200.tb_aceite a on a.idaceite = ad.idaceite
                WHERE ad.idprojeto = :idprojeto and situacao in('A')";

        return $this->_db->fetchAll($sql, array('idprojeto' => (int)$params['idprojeto']));
    }

    /**
     * Retorna o Termo de aceite assinados por projeto
     * @param array $params
     * @return string
     */
    public function retornaAceiteAssinado($params)
    {

//        var_dump($params);die;

        $sql = "SELECT ad.id, ad.idprojeto, pes.idpessoa, pes.nompessoa as nomparteinteressada, ad.nomfuncao,
                to_char(ad.assinado,'DD/MM/YYYY HH24:MI:SS') as assinado,
                --CASE ad.tipodoc
                  -- WHEN 1 THEN 'Termo de Abertura'
                   --WHEN 2 THEN 'Plano de Projeto'
                   --WHEN 3 THEN 'Termo de Aceite'
                  -- WHEN 4 THEN 'Termo de encerramento do Projeto'
                --END AS tipo,
                trim(ad.hashdoc) as hashdoc
                FROM agepnet200.tb_assinadocumento ad
                inner join agepnet200.tb_projeto p on p.idprojeto= ad.idprojeto
                inner join agepnet200.tb_pessoa pes on pes.idpessoa=ad.idpessoa
                INNER JOIN agepnet200.tb_aceite a on a.idaceite = ad.idaceite
                WHERE ad.idprojeto = :idprojeto AND ad.idaceite= :idaceite and situacao in('A')";

        return $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idaceite' => (int)$params['idaceite']
        ));
    }

    /**
     * Retorna os documentos assinados por tipo e projeto
     * @param array $params
     * @return string
     */
    public function retornaAssinaturaPorTipoEProjeto($params)
    {

        $sql = "SELECT ad.id, ad.idprojeto, pes.idpessoa, pes.nompessoa as nomparteinteressada, ad.nomfuncao,
                to_char(ad.assinado,'DD/MM/YYYY HH24:MI:SS') as assinado,
                CASE ad.tipodoc
                   WHEN 1 THEN 'Termo de Abertura'
                   WHEN 2 THEN 'Plano de Projeto'
                   WHEN 3 THEN 'Termo de Aceite'
                   WHEN 4 THEN 'Termo de encerramento do Projeto'
                END AS tipo, trim(ad.hashdoc) as hashdoc
                FROM agepnet200.tb_assinadocumento ad
                inner join agepnet200.tb_projeto p on p.idprojeto= ad.idprojeto
                inner join agepnet200.tb_pessoa pes on pes.idpessoa=ad.idpessoa
                WHERE ad.idprojeto = :idprojeto and tipodoc in(:tipodoc) and situacao in('A')";

        return $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'tipodoc' => (int)$params['tipodoc']
        ));
    }

    /**
     * Retorna os documentos assinados por hash validado
     * @param array $params
     * @return array|bool
     */
    public function validaCodigo($params)
    {

        $sql = "SELECT pes.nompessoa as nomparteinteressada, pes.nummatricula,
                to_char(ad.assinado,'DD/MM/YYYY HH24:MI:SS') as assinado,
                CASE ad.tipodoc
                   WHEN 1 THEN 'Termo de Abertura'
                   WHEN 2 THEN 'Plano de Projeto'
                   WHEN 3 THEN 'Termo de Aceite'
                   WHEN 4 THEN 'Termo de encerramento do Projeto'
                END AS tipo
                FROM agepnet200.tb_assinadocumento ad
                inner join agepnet200.tb_projeto p on p.idprojeto= ad.idprojeto
                inner join agepnet200.tb_pessoa pes on pes.idpessoa=ad.idpessoa
                WHERE  ad.hashdoc in(:hashdoc) and situacao in('A')";
        $resposta = $this->_db->fetchAll($sql, array('hashdoc' => $params['hashdoc']));

        if (count($resposta) <= 0) {
            return false;
        }
        return $resposta;
    }


    public function inativaAssinatura($params)
    {
        $data = array(
            "situacao" => "I",
        );
        $pks = array(
            "id" => $params['id']
        );
        try {
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            //var_dump($data);die;
            $this->getDbTable()->update($data, $where);
            return true;
        } catch (Exception $exc) {
            throw $exc;
            return false;
        }
    }

    public function isAssinouAceite($params)
    {
        $sql = "SELECT id
                FROM agepnet200.tb_assinadocumento
                WHERE idprojeto = :idprojeto
                and tipodoc = :tipo
                and idpessoa = :idpessoa
                and idaceite = :idaceite
                and situacao  in('A')";

        $result = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'tipo' => (int)$params['tipodoc'],
            'idpessoa' => $params['idpessoa'],
            'idaceite' => (int)$params['idaceite']
        ));

        return $result;
    }


    public function isAssinouDocumento($params)
    {

        $sql = "SELECT id
                FROM agepnet200.tb_assinadocumento
                WHERE idprojeto = :idprojeto
                and tipodoc = :tipo
                and idpessoa = :idpessoa
                and situacao in('A')";

        $result = $this->_db->fetchRow($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'tipo' => (int)$params['tipodoc'],
            'idpessoa' => $params['idpessoa']
        ));
        //var_dump($result);die;
        return $result;
    }


    /**
     * Retorna os documentos assinados por tipo e projeto
     * @param array $params
     * @return string
     */
    public function retornaAssinaturaPorProjetoETipo($params)
    {

        $sql = "SELECT ad.id, ad.idprojeto, pes.idpessoa, pes.nompessoa as nomparteinteressada, ad.nomfuncao,
                to_char(ad.assinado,'DD/MM/YYYY HH24:MI:SS'),
                CASE ad.tipodoc
                   WHEN 1 THEN 'Termo de Abertura'
                   WHEN 2 THEN 'Plano de Projeto'
                   WHEN 3 THEN 'Termo de Aceite'
                   WHEN 4 THEN 'Termo de encerramento do Projeto'
                END AS tipo
                FROM agepnet200.tb_assinadocumento ad
                inner join agepnet200.tb_projeto p on p.idprojeto= ad.idprojeto
                inner join agepnet200.tb_pessoa pes on pes.idpessoa=ad.idpessoa
                WHERE ad.idprojeto=:idprojeto
                and ad.tipodoc in(:tipo)
                and situacao in('A')";

        return $this->_db->fetchOne($sql, array(
            'idprojeto' => $params['idprojeto'],
            'tipo' => $params['tipo']
        ));
    }

    /**
     * Retorna os documentos assinados por tipo e projeto
     * @param array $params
     * @return boolean
     */
    public function validaHashDocumento($params)
    {
        $retorno = false;
        $sql = "SELECT
                    count(id)
                FROM agepnet200.tb_assinadocumento ad
                WHERE ad.hashdoc = :hashdoc ";

        $retorno = $this->_db->fetchOne($sql, array(
            'hashdoc' => $params['hashdoc']
        ));

        return ($retorno <= 0) ? false : true;
    }


}