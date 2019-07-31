<?php

class Projeto_Service_Risco extends App_Service_ServiceAbstract
{
    /**
     * @var $mpdf App_Service_MPDF
     */
    private $mpdf;
    public $_mapper = null;
    protected $_form = null;
    protected $auth = null;
    protected $_dependencies = array(
        'db'
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

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Projeto_Model_Mapper_Risco();
    }

    public function getFormRisco($request)
    {
        $arrPartesInteressadas = new Projeto_Model_Mapper_Parteinteressada();
        $contramedida = new Projeto_Model_Mapper_Contramedida();
        $statusReport = new Projeto_Model_Mapper_Statusreport();
        $etapa = new Projeto_Model_Mapper_Etapa();
        $this->_form = new Projeto_Form_Risco();
        $params['idrisco'] = $request->idrisco;
        $arrStatusContramedida = $contramedida->statusContramendida();
        $params['idprojeto'] = $request->idprojeto;
        $patesInter = array();
        $etapaPg = array();

        foreach ($arrPartesInteressadas->getById($params) as $pi) {
            $patesInter[$pi['nomparteinteressada']] = $pi['nomparteinteressada'];
        }

        foreach ($etapa->getEtapaPgp($request->idprojeto) as $e) {
            $etapaPg[$e['idetapa']] = $e['dsetapa'];
            if ($statusReport->getPgpAssinado($request->idprojeto) == $e['pgpassinado']) {
                $assinado = $e['idetapa'];
            }
        }
        $this->_form->getElement('desresponsavel')->addMultiOptions($patesInter);
        $this->_form->getElement('domcorprobabilidade')->addMultiOptions($this->_mapper->probabilidade());
        $this->_form->getElement('domcorimpacto')->addMultiOptions($this->_mapper->impacto());
        $this->_form->getElement('domstatuscontramedida')->addMultiOptions($arrStatusContramedida);
        $this->_form->getElement('flariscoativo')->setValue('1');
        $this->_form->getElement('datinatividade')->setValue(Zend_Date::now());
        $this->_form->getElement('datdeteccao')->setValue(Zend_Date::now());
        $this->_form->getElement('idetapa')->addMultiOptions($etapaPg)->setValue($assinado);
        $this->_form->getElement('idprojeto')->setValue($request->idprojeto);
        $this->_form->getElement('idrisco')->setValue($request->idrisco);
        $this->_form->getElement('idcontramedida')->setValue($request->idcontramedida);
        $this->_form->getElement('trat')->setValue(
            $contramedida->getContramedidaPorRisco($params)->idtipocontramedida);
        $this->_form->getElement('pgpass')->setValue($statusReport->getPgpAssinado($request->idprojeto));
        $this->_form->getElement('contramedidaefetiva')->setValue(
            $contramedida->getContramedidaPorRisco($params)->flacontramedidaefetiva);
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
        } catch (Exception $exc) {
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
        $risco = $this->_mapper->getByRiscoContramedida($params);
        return $risco;
    }

    public function matrizRisco($params)
    {
        $risco = $this->_mapper->matrizRisco($params);
        return $risco;
    }

    public function insert($dados)
    {
        $model = new stdClass();
        $modelRisco = new Projeto_Model_Risco();
        $modelContramedida = new Projeto_Model_Contramedida();
        $mapperContramedida = new Projeto_Model_Mapper_Contramedida();
        $modelRisco->setFromArray($dados);
        $modelContramedida->setFromArray($dados);
        $modelRisco->idcadastrador = $this->auth->idpessoa;
        /**
         * Ficou aprovado gravar o valor 1 como padrão, tendo em vista que o campo fou excluído do formulário.
         * @var  flaaprovado
         */
        $modelRisco->domtratamento = $dados['tratamento'];
        $modelRisco->flaaprovado = 1;
        $modelContramedida->idcadastrador = $this->auth->idpessoa;
        $db = $this->_db;
        $db->beginTransaction();
        try {
            /** Insere na tabela de risco. */
            $model->idrisco = $this->_mapper->insert($modelRisco);
            /** Insere na tabela de contramedida. */
            $modelContramedida->idrisco = $model->idrisco;
            //$modelContramedida->descontramedida = $dados['descontramedida'];
            $modelContramedida->idrisco = $model->idrisco;
            if (isset($dados['flacontramedidaefetiva'])) {
                $flacontramedidaefetiva = $dados['flacontramedidaefetiva'] == "" ? null : $dados['flacontramedidaefetiva'];
            } else {
                $flacontramedidaefetiva = null;
            }
            $modelContramedida->flacontramedidaefetiva = $flacontramedidaefetiva;
            if (isset($dados['tratamento'])) {
                $tratamento = $dados['tratamento'] == "" ? null : $dados['tratamento'];
            } else {
                $tratamento = null;
            }
            $modelContramedida->idtipocontramedida = $tratamento;
            $model->idcontramedida = $mapperContramedida->insert($modelContramedida);
            $db->commit();
            return $model;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            $db->rollBack();
            return false;
        }
    }

    public function update($params)
    {
        $model = new stdClass();
        $modelRisco = new Projeto_Model_Risco();
        $modelContramedida = new Projeto_Model_Contramedida();
        $mapperContramedida = new Projeto_Model_Mapper_Contramedida();
        $modelRisco->setFromArray($params);
        $modelRisco->idcadastrador = $this->auth->idpessoa;
        $modelRisco->flaaprovado = 1;

        $modelContramedida->setFromArray($params);
        $db = $this->_db;
        $db->beginTransaction();
        try {
            $model->idrisco = $this->_mapper->update($modelRisco);
            $model->idcontramedida = $mapperContramedida->update($modelContramedida);
            $db->commit();
            return $model;
        } catch (Exception $exc) {
            $message = $exc->getMessage();
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            $db->rollBack();
            return false;
        }
    }

    public function excluir($params)
    {
        try {
            return $this->_mapper->delete($params);
        } catch (Zend_Db_Statement_Exception $exc) {
            if ($exc->getCode() == 23503) {
                $this->errors = App_Service_ServiceAbstract::ERRO_VIOLACAO_FK_CODE_23503;
            }
        } catch (Exception $exc) {
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

        if (in_array($this->auth->perfilAtivo->idperfil, $perfisPermissao)) {
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
        $project = Zend_Registry::get('config')->project;

        $this->mpdf = new App_Service_MPDF('UTF-8', 'A4-L', '', '', 15, 15, 15, 25, 10, 15, '');
        $this->mpdf->AddPage('L', '', '', '', '', 15, 15, 15, 20, 15, 15);
        $this->mpdf->SetHTMLFooter('<div align="center" style="font-size: 12px;">' . $project->sigla . ' - {DATE d/m/Y H:i} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
            . 'Página {PAGENO} de {nbpg}</div>');

        $stylesheet = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        $cssBootstrap = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap.min.css');
        $this->mpdf->WriteHTML($cssBootstrap, 1);

        $cssBootstrapResp = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
        $this->mpdf->WriteHTML($cssBootstrapResp, 1);

        $cssForm = file_get_contents(APPLICATION_PATH . '/../public/css/form.css');
        $this->mpdf->WriteHTML($cssForm, 1);

        $cssPortlet = file_get_contents(APPLICATION_PATH . '/../public/css/portlet.css');
        $this->mpdf->WriteHTML($cssPortlet, 1);

        ksort($templates);
        foreach ($templates as $html) {
            $this->mpdf->WriteHTML($html);
        }

        $this->mpdf->Output($name . '.pdf', 'I');
    }

    public function retornaRiscos($params)
    {
        return $this->_mapper->retornaRiscos($params);
    }
}
