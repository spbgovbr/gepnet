<?php

/**
 * Created by PhpStorm.
 * User: Wendell
 * Date: 05/10/2018
 * Time: 13:33
 */
class Projeto_Service_Comentario extends App_Service_ServiceAbstract
{
    /** @var Projeto_Model_Mapper_Comentario */
    protected $_mapper;

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;


    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Comentario();
    }


    /**
     * Retorna formulario coment�rio.
     * @return Projeto_Form_Comentario
     * @throws Exception
     */
    public function getForm()
    {
        $form = $this->_getForm('Projeto_Form_Comentario');
        return $form;
    }

    /**
     * Adiciona comentarios
     * @param $params
     * @return bool
     */
    public function inserir($params)
    {
        try {
            $modelComentario = new Projeto_Model_Comentario($params);
            $resultado = $this->_mapper->insert($modelComentario);
            return $resultado;
        } catch (Exception $exc) {
            $this->errors = array('msg' => 'Nao foi possivel inserir o comentario.');
            return false;
        }
    }

    /**
     * @param $dados
     * @return int
     * @throws Exception
     */
    public function excluir($dados)
    {
        return $this->_mapper->delete($dados);
    }

    /**
     * Retorna a quantidade de comentarios por projeto e atividade.
     * @param $params
     * @return int
     */
    public function retornaQtdComentarioPorIdAtvCronograma($params)
    {
        return $this->_mapper->retornaQtdComentarioPorIdAtvCronograma($params);
    }


    /**
     * Retorna listagem de comentarios.
     * @param $params
     * @return array
     */
    public function listarComentarios($params)
    {
        $lista = array();
        $lista = $this->_mapper->listaComentarios($params);
        return $lista;
    }
}