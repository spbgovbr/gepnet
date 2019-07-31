<?php

/**
 * Created by PhpStorm.
 * User: Wendell
 * Date: 05/10/2018
 * Time: 12:24
 */
class Projeto_Model_Mapper_Comentario extends App_Model_Mapper_MapperAbstract
{
    /**
     * Adiciona coment�rio para atividades.
     * @param Projeto_Model_Comentario $model
     * @return Projeto_Model_Comentario
     * @throws Exception
     */
    public function insert(Projeto_Model_Comentario $model)
    {
        try {
            $model->idcomentario = $this->maxVal('idcomentario');
            $data = array(
                "idcomentario" => $model->idcomentario,
                "idprojeto" => $model->idprojeto,
                "idatividadecronograma" => $model->idatividadecronograma,
                "idpessoa" => $model->idpessoa,
                "dtcomentario" => new Zend_Db_Expr("now()"),
                "dscomentario" => $model->dscomentario
            );
            $data = array_filter($data);
            $retorno = $this->getDbTable()->insert($data);
            return $model;

        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @param $params
     * @return int
     * @throws Exception
     */
    public function delete($params)
    {
        try {
            $pks = array(
                "idcomentario" => (int)$params['idcomentario'],
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Retorna a listagem de comentario por projeto e atividade.
     * @param $params
     * @return array
     */
    public function listaComentarios($params)
    {
        $sql = "SELECT
                    cmt.idcomentario,
                    p.nompessoa,
                    cmt.dscomentario,
                    to_char(cmt.dtcomentario,'dd/mm/yyyy HH24:mm:ss') as dtcomentario
                FROM agepnet200.tb_comentario cmt
                INNER JOIN agepnet200.tb_atividadecronograma ac
                                on ac.idatividadecronograma = cmt.idatividadecronograma
                                and ac.idprojeto = cmt.idprojeto
                INNER JOIN agepnet200.tb_pessoa p on p.idpessoa = cmt.idpessoa
                WHERE cmt.idatividadecronograma in(:idatividadecronograma) and cmt.idprojeto in(:idprojeto)
                order by cmt.idcomentario DESC ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));

        return $resultado;
    }

    /**
     * Retorna a quantidade de comentarios por projeto e atividade.
     * @param $params
     * @return int
     */
    public function retornaQtdComentarioPorIdAtvCronograma($params)
    {
        $sql = "SELECT count(cmt.idcomentario)
                FROM agepnet200.tb_comentario cmt
                WHERE cmt.idatividadecronograma in(:idatividadecronograma) and cmt.idprojeto in(:idprojeto)";

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojeto' => (int)$params['idprojeto'],
            'idatividadecronograma' => (int)$params['idatividadecronograma'],
        ));

        return $resultado;
    }

}