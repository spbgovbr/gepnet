<?php

use Default_Service_Log as Log;

/**
 * Newton Carlos
 *
 * Criado em 07-12-2018
 * 15:58
 */
class Diagnostico_Model_Mapper_Pergunta extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     *
     * @param string $value
     * @return Diagnostico_Model_Pergunta
     */
    public function insert(Diagnostico_Model_Pergunta $model)
    {
        try {
            unset($model->idpergunta);
            $model->idpergunta = $this->maxVal('idpergunta');

            $data = array(
                'idpergunta' => $model->idpergunta,
                'dspergunta' => $model->dspergunta,
                'tipopergunta' => (int)$model->tipopergunta,
                'ativa' => ($model->ativa == 't') ? true : false,
                'idquestionario' => (int)$model->idquestionario,
                'posicao' => $model->posicao,
                'id_secao' => (int)$model->id_secao,
                'tiporegistro' => (int)$model->tiporegistro,
                'dstitulo' => $model->dstitulo,
            );
            $data = array_filter($data);
            $retorno = $this->getDbTable()->insert($data);
            //Zend_Debug::dump($data);die;
            return $retorno;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }


    public function insertCopia($dados)
    {
        try {
            $dadosId = $this->maxVal('idpergunta');

            $data = array(
                'idpergunta' => $dadosId,
                'dspergunta' => $dados['dspergunta'],
                'tipopergunta' => $dados['tipopergunta'],
                'ativa' => $dados['ativa'],
                'idquestionario' => $dados['idquestionario'],
                'posicao' => $dados['posicao'],
                'id_secao' => $dados['id_secao'],
                'tiporegistro' => $dados['tiporegistro'],
                'dstitulo' => $dados['dstitulo'],
            );

            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }


    /**
     * Set the property
     *
     * @param Diagnostico_Model_Pergunta $model
     * @return Diagnostico_Model_Pergunta
     */
    public function update($model)
    {
        $data = array(
            'dspergunta' => $model->dspergunta,
            'tipopergunta' => (int)$model->tipopergunta,
            'ativa' => ($model->ativa == 't') ? 1 : 0,
            'posicao' => (int)$model->posicao,
            'id_secao' => (int)$model->id_secao,
            'tiporegistro' => (int)$model->tiporegistro,
            'dstitulo' => $model->dstitulo,
        );
        $data = array_filter($data);

        $data['ativa'] = ($model->ativa == 't') ? 1 : 0;
        $data['posicao'] = ($model->posicao == null) ? null : $model->posicao;

        try {
            $pks = array(
                "idpergunta" => $model->idpergunta
            );

            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

    public function getForm()
    {
        return $this->_getForm(Diagnostico_Form_Pergunta);
    }

    public function getById($params, $model = false)
    {
        $sql = "SELECT idpergunta, tipopergunta, id_secao, tiporegistro, 
                    dstitulo, posicao, 
                    CASE 
                        WHEN ativa = '1' THEN '1'
                        WHEN ativa = '0' THEN '0'
                    END AS ativa,  
                    tipopergunta, dspergunta
                  FROM agepnet200.tb_pergunta
                  WHERE idquestionario = :idquestionario ";

        if (isset($params ["idpergunta"])) {
            $sql .= " AND idpergunta = {$params ["idpergunta"]} ";
        }

        $resultado = $this->_db->fetchRow($sql, array(
                'idquestionario' => $params['idquestionario']
            )
        );
        if ($model) {
            $pergunta = new Diagnostico_Model_Pergunta($resultado);
            return $pergunta;
        }
        return $resultado;
    }

    public function buscaTodasPerguntasPorQuestionario($params)
    {

        $sql = "SELECT
                    p.posicao,
                    s.ds_secao,
                    p.dspergunta,
                    p.dstitulo,
                    CASE
                      WHEN p.ativa=true THEN 'SIM'
                      ELSE 'NÃO'
                    END AS obrigatoriedade,
                    CASE
                      WHEN p.tipopergunta = 1 THEN 'Descritiva'
                      WHEN p.tipopergunta = 2 THEN 'Multipla Escolha'
                      ELSE 'Única Escolha'
                    END AS ds_pergunta,
                    p.tipopergunta as tp_pergunta,
                    p.tiporegistro as tp_registro,
                    p.idpergunta
                FROM agepnet200.tb_pergunta p
                INNER JOIN agepnet200.tb_secao s
                    ON s.id_secao=p.id_secao AND s.ativa in(true)
                WHERE idquestionario = " . (int)$params['idquestionario'];

        $sql .= ' order by ' . $params['sidx'] . ' ' . $params['sord'];

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


    public function retornaPerguntas($params)
    {
        $sql = "SELECT idpergunta, idquestionario, dstitulo, posicao, dspergunta
                FROM agepnet200.tb_pergunta
                WHERE idquestionario = {$params['idquestionario']}
                ORDER BY posicao";

        return $this->_db->fetchAll($sql);
    }


    public function getByIdCloneItens($params)
    {
        $sql = "SELECT * 
                FROM agepnet200.tb_pergunta
                WHERE idquestionario = {$params}
                ORDER BY posicao";

        return $this->_db->fetchAll($sql);
    }


    public function getPosicao($params = null)
    {
        if (isset($params['posicao']) && !empty($params['posicao'])) {
            $sql = "SELECT (SELECT COUNT(posicao) 
                FROM agepnet200.tb_pergunta 
                WHERE idquestionario = {$params['idquestionariodiagnostico']} 
                AND posicao = {$params['posicao']}) AS posicao";
        } else {
            $sql = "SELECT (SELECT COUNT(posicao) 
                FROM agepnet200.tb_pergunta) ";
        }
        return $this->_db->fetchRow($sql);
    }

    public function getPosicaoUpdate($params = null)
    {
        if (isset($params['posicao']) && !empty($params['posicao'])) {
            $sql = "SELECT (SELECT COUNT(posicao) 
                FROM agepnet200.tb_pergunta 
                WHERE idquestionario = {$params['idquestionariodiagnostico']} ";
            if (isset($params['idpergunta']) && !empty($params['idpergunta'])) {
                $sql .= " AND idpergunta = {$params['idpergunta']} ";
            }
            $sql .= " AND posicao = {$params['posicao']}) AS posicaoup ";
        } else {
            $sql = "SELECT (SELECT COUNT(posicao) 
                FROM agepnet200.tb_pergunta) ";
        }
        return $this->_db->fetchRow($sql);
    }

    /**
     * Lista todas as perguntas da tabela.
     * @param int $iddiagnostico
     * @param array $params
     * return array
     */
    public function listar($params = array())
    {
        $params = array_filter($params);

        $sql = "SELECT qd.nomquestionario, 
                    CASE 
                    WHEN (qd.tipo = '1') THEN 'Servidor'
                    ELSE 'Cidadão'
                    END AS tipo,
                    td.dsdiagnostico, to_char(qd.dtcadastro, 'DD/MM/YYYY'), qd.idquestionariodiagnostico 
                FROM agepnet200.tb_questionario_diagnostico qd
                LEFT JOIN agepnet200.tb_vincula_questionario vq
                ON vq.idquestionario = qd.idquestionariodiagnostico
                LEFT JOIN agepnet200.tb_diagnostico td
		          ON td.iddiagnostico = vq.iddiagnostico
                WHERE 1 = 1 ";
        $params = array_filter($params);
        if (isset($params['nomquestionario']) && (!empty($params['nomquestionario']))) {
            $nomquestionario = mb_strtoupper($params['nomquestionario']);
            $sql .= " AND upper(qd.nomquestionario) LIKE '%{$nomquestionario}%' ";
        }
        if (isset($params['tipo']) && (!empty($params['tipo']))) {
            $sql .= " AND qd.tipo = '{$params['tipo']}'";
        }
        if (isset($params['dtcadastro']) && (!empty($params['dtcadastro']))) {
            $sql .= " AND to_char(qd.dtcadastro, 'DD/MM/YYYY') = '{$params['dtcadastro']}' ";
        }
        if (isset($params['idunidadeprincipal']) && (!empty($params['idunidadeprincipal']))) {
            $sql .= " AND qd.idunidadeprincipal = {$params['idunidadeprincipal']} ";
        }
        if (isset($params['iddiagnostico']) && (!empty($params['iddiagnostico']))) {
            $sql .= " AND vq.iddiagnostico = {$params['iddiagnostico']} ";
        }
        $sql .= " ORDER BY qd.dtcadastro DESC ";
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

    public function delete($params = array())
    {
        $pks = array("idquestionario" => $params['idquestionario']);
        $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
        $retorno = $this->getDbTable()->delete($where);
        return $retorno;
    }

    public function deletePergunta($params = array())
    {
        try {
            $pks = array(
                "idpergunta" => $params['idpergunta'],
                "idquestionario" => $params['idquestionario'],
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    public function retornaPerguntaObrigatoria($params)
    {
        $sql = "SELECT ARRAY_AGG(p.idpergunta) AS pergunta
                FROM agepnet200.tb_pergunta p
                WHERE p.idquestionario = :idquestionario AND p.ativa=TRUE
                GROUP BY p.idquestionario";
        $retorno = $this->_db->fetchRow($sql, array(
            'idquestionario' => $params['idquestionariodiagnostico'],
        ));
        return $retorno;
    }

    public function retornaPerguntaQuestionario($params)
    {

        $params['tpquestionario'] = ($params['tpquestionario'] == 1) ? 'S' : 'C';

        $sql = "SELECT p.idpergunta,
			p.dstitulo,
			p.tipopergunta,
			p.posicao,
			p.ativa,
			p.tiporegistro,
			o.idresposta,
			o.desresposta AS resposta,
			s.ds_secao,
			s.id_secao,
			p.idpergunta,
			o.idresposta,
			p.posicao,
			o.ordenacao
		   FROM agepnet200.tb_pergunta p
		   JOIN agepnet200.tb_secao s
		     ON s.id_secao = p.id_secao
		    AND s.tp_questionario IN(:tpquestionario)
		   LEFT JOIN agepnet200.tb_opcao_resposta o
		     ON o.idpergunta = p.idpergunta
		     AND o.idquestionario=p.idquestionario
		  WHERE p.idquestionario = :idquestionariodiagnostico
		  ORDER BY s.id_secao, p.posicao, o.ordenacao";

        $retorno = $this->_db->fetchAll($sql, array(
            'idquestionariodiagnostico' => $params['idquestionariodiagnostico'],
            'tpquestionario' => $params['tpquestionario']
        ));

        if ($retorno) {
            return $retorno;
        }
        return false;
    }

}
