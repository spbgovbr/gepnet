<?php

class Projeto_Service_Risco extends App_Service_ServiceAbstract {

    public $_mapper = null;
    protected $_form = null;
    protected $auth = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Projeto_Model_Mapper_Risco();
    }

    public function getFormRisco()
    {
        $this->_form = new Projeto_Form_Risco();
        return $this->_form;
    }

    public function getFormPesquisar()
    {
        $this->_form = new Projeto_Form_RiscoPesquisar();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Retorna riscos por projeto.
     * RN - So deve exibir riscos nao aprovados para perfis determinados <b style="color: red">(Administrador GEPNET, Gerente de Projetos, ...)</b>
     * 
     * @param array $params - parametros do request
     * @return boolean|\App_Service_JqGrid
     */
    public function retornaRiscoByProjeto($params = null)
    {
        //Relacao de perfis que podem ver Riscos ainda nao aprovados pelo GP
        $this->permissaoPerfil($params);
                
        try {
            $dados = $this->_mapper->retornaPorProjetoToGrid($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch ( Exception $exc ) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function getByIdDetalhar($params)
    {
        $risco = $this->_mapper->getByIdDetalhar($params);
        return $risco;
    }

    public function getById($params)
    {
        $risco = $this->_mapper->getById($params);
        return $risco;
    }
    public function matrizRisco($params)
    {
        $risco = $this->_mapper->matrizRisco($params);
        return $risco;
    }

    public function insert($dados)
    {
        $form = $this->getFormRisco();

        if ( $form->isValid($dados) ) {
            $model = new Projeto_Model_Risco();
            $model->setFromArray($form->getValidValues($dados));
            $model->idcadastrador = $this->auth->idpessoa;
            try {
                $model->idrisco = $this->_mapper->insert($model);
            } catch ( Exception $exc ) {
                $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
                return false;
            }
            return $model;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function update($params)
    {
        $form = $this->getFormRisco();
        if ( $form->isValid($params) ) {
            $model = new Projeto_Model_Risco($form->getValidValues($params));
            try {
                $retorno = $this->_mapper->update($model);
                return $retorno;
            } catch (Exception $exc) {
                $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
                return false;
            }
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function excluir($params)
    {
        try {
            return $this->_mapper->delete($params);
        } catch ( Zend_Db_Statement_Exception $exc ) {
            if ( $exc->getCode() == 23503 ) {
                $this->errors = App_Service_ServiceAbstract::ERRO_VIOLACAO_FK_CODE_23503;
            }
        } catch ( Exception $exc ) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function imprimirPorProjeto($params)
    {
        //Relacao de perfis que podem ver Riscos ainda nao aprovados pelo GP
        $this->permissaoPerfil($params);
        try {
            return $this->_mapper->retornaRiscoContramedida($params);
        } catch (Exception $exc) {
             $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
             return false;
        }
    }
    
    /**
     * Relacao de perfis que podem ver Riscos ainda nao aprovados pelo GP
     * 
     * @param array $params - ponteiro para o array de parametros
     * @return void
     */
    public function permissaoPerfil(&$params) 
    {
        //Relacao de perfis que podem ver Riscos ainda nao aprovados pelo GP
        $perfisPermissao = array(
            Default_Model_Perfil::ADMINISTRADOR_GEPNET,
            Default_Model_Perfil::GERENTE_DE_PROJETOS,
        );

        if ( in_array($this->auth->perfilAtivo->idperfil, $perfisPermissao) ) {
            $params['ver_nao_aprovados'] = true;
        }
    }
    
    /**
     * Gera pdf
     * 
     * @param array $templates - codigo html encodados no formato chave => valor
     * @param string $name - nome do documento
     * @example array(1=>$html1, 2=>$html);
     * @see lembrar de desabilitar o layout do tamplate para evitar erro de compatibilidade com adobe reader
     */
    public function gerarPdf($templates = array(), $name = 'document') 
    {
        define('_MPDF_PATH', '../library/MPDF57/');
        include('../library/MPDF57/mpdf.php');

        $this->mpdf = new mPDF('UTF-8', 'A4-L', '', '', 15, 15, 15, 25, 10, 15, '');
        $this->mpdf->AddPage('L', '', '', '', '', 15, 15, 15, 20, 15, 15);
        $this->mpdf->SetHTMLFooter('<div align="center" style="font-size: 12px;">DPF - {DATE d/m/Y H:i} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                                       . 'Página {PAGENO} de {nbpg}</div>');

        $stylesheet = file_get_contents('../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents('../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        $cssBootstrap = file_get_contents('../public/js/library/bootstrap/css/bootstrap.min.css');
        $this->mpdf->WriteHTML($cssBootstrap, 1);

        $cssBootstrapResp = file_get_contents('../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
        $this->mpdf->WriteHTML($cssBootstrapResp, 1);

        $cssForm = file_get_contents(APPLICATION_PATH.'/../public/css/form.css');
        $this->mpdf->WriteHTML($cssForm, 1);

        $cssPortlet = file_get_contents(APPLICATION_PATH.'/../public/css/portlet.css');
        $this->mpdf->WriteHTML($cssPortlet, 1);

        ksort($templates);
        foreach ($templates as $html) {
            $this->mpdf->WriteHTML($html);
        }

        $this->mpdf->Output($name.'.pdf', 'I');
    }

    public function retornaRiscos($params){
        return  $this->_mapper->retornaRiscos($params);
    }
}
