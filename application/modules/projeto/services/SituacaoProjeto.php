<?php

/*
 * @ by Danilo
 */

class Projeto_Service_SituacaoProjeto extends App_Service_ServiceAbstract
{

    protected $_mapper;

    // criando construct para mapper
    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_SituacaoProjeto();
    }

    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }

    public function retornaUltimo($params)
    {
        return $this->_mapper->retornaUltimo($params);
    }

    public function retornaStatusDoProjeto($params)
    {
        return $this->_mapper->retornaStatusDoProjeto($params);
    }

    public function retornaNomeSituacaoAtivo()
    {
        $retorna = $this->_mapper->retornaNomeSituacaoAtivo();
        $nomTipo = array(0 => "Selecione");
        foreach ($retorna as $val => $listaItem) {
            if (strtoupper($listaItem['nomtipo']) != "TODOS") {
                $nomTipo[$listaItem['idtipo']] = $listaItem['nomtipo'];
            }
        };
        return $nomTipo;
    }

    public function retornaNomeSituacaoAtivoSt()
    {
        $retorna = $this->_mapper->retornaNomeSituacaoAtivo();
        $nomTipo = array('' => "Selecione");
        foreach ($retorna as $val => $listaItem) {
            if (strtoupper($listaItem['nomtipo']) != "TODOS") {
                $nomTipo[$listaItem['idtipo']] = $listaItem['nomtipo'];
            }
        };
        return $nomTipo;
    }

}
