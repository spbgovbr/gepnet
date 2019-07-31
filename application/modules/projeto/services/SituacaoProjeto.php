<?php
/*
 * @ by Danilo
 */
class Projeto_Service_SituacaoProjeto extends App_Service_ServiceAbstract {

    protected $_mapper;

    // criando construct para mapper
    public function init() {

        $this->_mapper = new Projeto_Model_Mapper_SituacaoProjeto();
    }

    public function getById($params){
        return $this->_mapper->getById($params);
    }
    public function retornaUltimo($params){
        return $this->_mapper->retornaUltimo($params);
    }
    public function retornaNomeSituacaoAtivo() {
       $retorna = $this->_mapper->retornaNomeSituacaoAtivo();
       foreach ($retorna as $retornas) {
         $nomtipo = $retornas['nomtipo'];
         $nomTipo[0] = "Todos";
         $nomTipo[$retornas['idtipo']] = $nomtipo;
       };
       return $nomTipo;
       // return $this;
    }

}
