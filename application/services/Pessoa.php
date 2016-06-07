<?php

class Default_Service_Pessoa extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Pessoa
     */
    protected $_mapper;

    /**
     * @var array 
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Default_Model_Mapper_Pessoa();
    }

    /**
     * @return Default_Form_Documento
     */
    public function getForm()
    {
        return $this->_getForm('Default_Form_Pessoa');
    }

    /**
     * @return Default_Form_Documento
     */
    public function getFormPesquisar()
    {
        return $this->_getForm('Default_Form_PessoaPesquisar');
    }

    /**
     * @return Default_Form_DocumentoEditar
     */
    public function getFormEditar()
    {
        $form = $this->_getForm('Default_Form_PessoaEditar');
        return $form;
    }

    //put your code here
    public function inserir($dados)
    {
        $form = $this->getFormEditar();
        if ( $form->isValid($dados) ) {
            $dados['token'] = md5($dados['token']);
            $model   = new Default_Model_Pessoa($form->getValues());
            $retorno = $this->_mapper->insert($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    /**
     * 
     * @param array $dados
     * @return boolean | array
     */
    public function update($dados)
    {
        $form = $this->getFormEditar();
        if ( $form->isValid($dados) ) {
            $pessoa = $this->getById(array('idpessoa' => $form->getValue('idpessoa')));
            if ( $pessoa->desemail == $form->getValue('desemail') and $pessoa->idpessoa != $form->getValue('idpessoa') ) {
                   $this->errors[] = "Um registro com o email {$form->getValue('desmail')} já existe no banco de dados.";
                $model->desemail     = null;
                $model->numcpf       = null;
                $model->nummatricula = null;
                return false;
            }
                $model   = new Default_Model_Pessoa($form->getValues());
                $retorno = $this->_mapper->update($model);
                return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    /**
     * 
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            //$model = new Default_Model_Documento($dados);
            return $this->_mapper->excluir($dados);
        } catch ( Exception $exc ) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function retornaPorId($dados)
    {
        return $this->_mapper->retornaPorId($dados);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /*
      public function retornaMensagemNumeroDoc($params)
      {

      $mprTipoDocumento = new Default_Model_Mapper_Tipodoc($params);
      $descricaoTipoDoc = $mprTipoDocumento->findById($model->tipodoc_cd_tipodoc);
      $msg     = "USAR {$descricaoTipoDoc} Nº: <strong>{$model->nr_documento}</strong>";
      }
     */

    /**
     * 
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params, $paginator);
        if ( $paginator ) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function pesquisarSemUnidade($params, $paginator)
    {
        $dados = $this->_mapper->pesquisarSemUnidade($params, $paginator);
        if ( $paginator ) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    public function buscar($params, $paginator)
    {
        if ( $params['tipo'] == 0 ) {
            $paginator = $this->buscarServidor($params, $paginator);
        } else {
            $paginator = $this->buscarColaborador($params, $paginator);
        }
        $service = new App_Service_JqGrid();
        $service->setPaginator($paginator);
        //$service->toJqgrid($paginator);
        return $service;
    }

    public function importar($params)
    {
        if ( $params['tipo'] == 0 ) {
            return $this->importarServidor($params);
        } else {
            return $this->importarColaborador($params);
        }
    }

    /**
     * 
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function buscarServidor($params, $paginator)
    {
        return $this->_mapper->buscarServidor($params, $paginator);
        /*
          $paginador         = $this->_mapper->buscarServidor($params, $paginator);
          $response          = array();
          $response['total'] = $paginador->getTotalItemCount();
          foreach ( $paginador as $d )
          {
          $a                     = new stdClass();
          $a->id                 = $d['id'];
          $a->text               = $d['text'];
          $response['pessoas'][] = $a;
          }
          //var_dump($response);
          //exit;
          return $response;
         */
    }

    public function buscarColaborador($params, $paginator)
    {
        return $this->_mapper->buscarColaborador($params, $paginator);
        /*
          $paginador         = $this->_mapper->buscarColaborador($params, $paginator);
          $response          = array();
          $response['total'] = $paginador->getTotalItemCount();
          foreach ( $paginador as $d )
          {
          $a                     = new stdClass();
          $a->id                 = $d['ID'];
          $a->text               = $d['TEXT'];
          $response['pessoas'][] = $a;
          }
          //var_dump($response);
          //exit;
          return $response;
         */
    }

    /**
     * Retorna um registro da view pessoa do owner comum
     * @param array $params
     * @return array
     */
    public function importarServidor($params)
    {
        $pessoa            = $this->_mapper->getServidorById($params);
        $id_servidor       = array('id_servidor' => $pessoa->id_servidor);
        $response          = new stdClass();
        $response->dados   = null;
        $response->success = false;
        $date              = date('d/m/Y H:i:s');
        $response->dados   = $pessoa->formPopulate();
        $response->msg     = "Usuario importado: {$pessoa->nompessoa} - {$pessoa->getNumcpfMascarado()} em: {$date}.";
        $response->success = true;
        return $response;
        /*
          if ( $this->_mapper->existeServidor($id_servidor) ) {
          $response->msg   = "Usuario: {$pessoa->nompessoa} - {$pessoa->getNumcpfMascarado()} já está cadastrado.";
          return $response;
          }
         */
    }

    /**
     * Retorna um registro da view pessoa do owner comum
     * @param array $params
     * @return array
     */
    public function importarColaborador($params)
    {
        $pessoa            = $this->_mapper->getColaboradorById($params);
        $response          = new stdClass();
        $response->dados   = null;
        $response->success = false;
        $date              = date('d/m/Y H:i:s');
        $response->dados   = $pessoa->formPopulate();
        $response->msg     = "Usuario importado: {$pessoa->nompessoa} - {$pessoa->getNumcpfMascarado()} em: {$date}.";
        $response->success = true;
        return $response;
        /*
          if ( $this->_mapper->existeServidor($id_servidor) ) {
          $response->msg   = "Usuario: {$pessoa->nompessoa} - {$pessoa->getNumcpfMascarado()} já está cadastrado.";
          return $response;
          }
         */
    }

    public function delete($id)
    {
        return $this->_mapper->delete($id);
    }

    public function fetchPairs()
    {
        return $this->_mapper->fetchPairs();
    }
    /*
    public function fetchPairsPorProjeto($params, $selecione = true)
    {
        $resultado = $this->_mapper->fetchPairsPorProjeto($params);
        $retorno = array();

        if ( $selecione ) {
            $retorno[''] = 'Selecione';
        }
        
        $retorno[0]   = '** OUTRO (Não vinculado ao Órgão)';

        foreach ( $resultado as $key => $value )
        {
            $retorno[$key] = $value;
        }
        return $retorno;
    }
    */
    public function getByCpf($dados)
    {
        return $this->_mapper->getByCpf($dados);
    }
    
    // Retorna pessoa por email
    public function getByEmail($dados)
    {
        return $this->_mapper->getByEmail($dados);
    }
    public function retornaPessoaProjeto($dados)
    {
        return $this->_mapper->retornaPessoaProjeto($dados);
    }

}

?>
