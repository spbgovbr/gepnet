<?php

class Projeto_Service_PermissaoProjeto extends App_Service_ServiceAbstract
{

    protected $_form;
    //protected $_isAuthorized;

    /**
     *
     * @var Projeto_Model_Mapper_Permissaoprojeto
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     *
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger;
    /**
     * @var array
     */
    protected $_forbiddenRoute = array(
        'controller' => 'error',
        'action' => 'forbidden',
        'module' => 'projeto'
    );


    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     * @var array
     */
    public $errors = array();
    private $arrRecursos = array();
    private $recursos = array();
    private $recursosCadastrados = array();
    private $permissoesAtual = array();

    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Permissaoprojeto();
        $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getForm()
    {
        $form = new Projeto_Form_Permissaoprojeto();
        return $form;
    }

    public function getFormPerfil()
    {
        $form = new Projeto_Form_PerfilPermissao();
        return $form;
    }

    /**
     * @return Projeto_Model_Mapper_Permissaoprojeto
     */
    public function inserir($params)
    {
        try {
            return $this->_mapper->insert($params);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function editar($params)
    {
        try {
            return $this->_mapper->update($params);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function excluir($params)
    {
        try {
            return $this->_mapper->excluir($params);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }


    /**
     *
     * @param array $param
     * @return array
     */
    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }

    public function getByIdPermissaoParteInteressada($params)
    {
        return $this->_mapper->getByIdParteInteressada($params);
    }

    public function getByIdParteInteressadaByPermissao($params)
    {
        return $this->_mapper->getByIdParteInteressadaByPermissao($params);
    }

    public function updatePermissaoParteInteressada($params)
    {
        return $this->_mapper->updatePermissaoParteInteressada($params);
    }

    public function excluirPermissaoByParteInteressada($params)
    {
        return $this->_mapper->excluirPermissaoByParteInteressada($params);
    }

    /**
     *
     * @param array $param
     * @return  Projeto_Model_Mapper_Permissaoprojeto
     */
    public function getPermissaoProjetoById()
    {
        return $this->_mapper->getPermissaoProjetoById();
    }

    public function verificaPermissaoByParteInteressadaAndProjeto($params)
    {
        return $this->_mapper->verificaPermissaoByParteInteressadaAndProjeto($params);
    }

    public function excluirPermissoesProjetoByParteInteressada($params)
    {
        $retorno = false;

        //buscar o serviço de permissão projeto        
        $countPermissao = $this->verificaPermissaoByParteInteressadaAndProjeto($params);
        if ($countPermissao['count'] != 0) {
            $resultado = $this->_mapper->excluir($params);
            $retorno = true;
        }
        return $retorno;
    }


    /**
     *
     * @param array $param
     * @return Projeto_Model_Mapper_Permissaoprojeto
     */
    public function getPermissaoPorParte($params)
    {
        return $this->_mapper->getPermissaoPorParte($params);
    }

    public function fetchPairs($params)
    {
        return $this->_mapper->fetchPairs($params);
    }

    /**
     * Funcão de atribuir ou negar permissões para parte interessada no projeto.
     *
     * @param Projeto_Model_Parteinteressada $parte
     * @return boolean
     */
    public function permissaoNoProjeto($parte)
    {
        $arrayParte = array(
            'idprojeto' => $parte->idprojeto,
            'idparteinteressada' => $parte->idparteinteressada
        );

        try {
            //Verifica se existe permissões para a parte interessada
            $permissoes = $this->getByIdPermissaoParteInteressada($arrayParte);
            if (count($permissoes) > 0) {
                //Exclui as permissoes da parte interessada
                $retorno = $this->excluir($arrayParte);
            }
            $servicePermissao = new Default_Service_Permissao();
            if (((int)$parte->tppermissao) === 1) {//caso o tipo de permissao seja editar
                //Busca todas as permissões do tipo 'E' - Especifica.
                $arrayPermissao = $servicePermissao->retornaRecursoEPermissaoPorTipo('todos');
                //Adiciona as permissões.
                $this->ajustarPermissoes($arrayPermissao, $parte);
            } else {
                if (((int)$parte->tppermissao) === 2) {//caso o tipo de permissao seja visualizar
                    //Busca todas as permissões do tipo 'G' - Gerais.
                    $arrayPermissao = $servicePermissao->retornaRecursoEPermissaoPorTipo('G');
                    //Adiciona as permissões.
                    $this->ajustarPermissoes($arrayPermissao, $parte);
                }
            }
            return true;
        } catch (Exception $e) {
            $e->getMessage();
        }

        return false;
    }

    private function ajustarPermissoes($permissoes, $parte)
    {
        $serviceLogin = new Default_Service_Login();
        $usuario = $serviceLogin->retornaUsuarioLogado();

        if (count($permissoes) > 0) {
            foreach ($permissoes as $permissao) {
                $data = array(
                    "idpermissao" => $permissao['idpermissao'],
                    "idprojeto" => (int)$parte->idprojeto,
                    "idparteinteressada" => (int)$parte->idparteinteressada,
                    "idpessoa" => (int)$usuario->idpessoa,
                    "idrecurso" => $permissao['idrecurso'],
                );
                $retorno = $this->inserir($data);
                $retorno = '';
                $data = array();
            }
        }
    }

    public function atualizaPermissaoParteProjetoId($params)
    {
        return $this->_mapper->fetchPairs($params);
    }

    /**
     *
     * @param array $param
     * @return Projeto_Model_Mapper_Permissaoprojeto
     */
    public function getPermissaoProjeto($params)
    {
        $idperfilativo = Zend_Auth::getInstance()->getIdentity()->perfilAtivo->idperfil;
        if ($idperfilativo != 1) {
            return $this->_mapper->getPermissaoAcaoProjetoPorParte($params);
        }
        return true;
    }

    /**
     *
     * @param array $param
     * @return  Projeto_Model_Mapper_Permissaoprojeto
     */
    public function getPermissaoPorProjeto($params)
    {
        return $this->_mapper->getPermissaoPorProjeto($params);
    }

}
