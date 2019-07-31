<?php

/**
 * Header view helper
 *
 * @author Marcelo Rodovalho <mfrodovalho[at]gmail.com>
 * @license Free to use - no strings.
 */
class App_View_Helper_Header extends Zend_View_Helper_Abstract
{
    protected $template = '<div id="info-site" class="span12">
            <div id="info-site-orgao">
                %s
            </div>
            <div id="info-site-gestor">
                %s
            </div>
            <div id="info-site-sistema">
                %s
            </div>
            <div id="info-site-versao">
                <a href="%s">Vers&atilde;o %s</a>&nbsp;&nbsp; 
                %s
            </div>
        </div>';
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function header()
    {
        $project = Zend_Registry::get('config')->project;
        $versao = App_Service_SemVer::getSemver();
        return sprintf(
            $this->template,
            $project->orgao,
            $project->gestor,
            $project->sistema,
            $this->view->url(array('module' => 'default', 'controller' => 'versao', 'action' => 'index')),
            $versao,
            $this->labelColor()
        );
    }

    protected function labelColor()
    {
        $baseUrl = explode('/', Zend_Controller_Front::getInstance()->getBaseUrl());
        $arrayLabel = array(
            'projetosdesenv.dpf.gov.br-gepnet-prehom' => '<span class="label label-important">Pr&eacute; Homologaç&atilde;o</span>',
            /** Ambiente pré-homologação */
            'projetoshom.dpf.gov.br-gepnet' => '<span class="label label-success">Homologaç&atilde;o</span>',
            /** Ambiente homologação */
            'projetostreino.dpf.gov.br-gepnet' => '<span class="label label-info">Treinamento</span>',
            /** Ambiente de treinamento */
            'projetosdesenv.dpf.gov.br-gepnet' => '<span class="label label-warning">Desenvolvimento</span>',
            /** Ambiente desenvolvimento */
            'localhost-gepnet' => '<span class="label label-inverse">Local</span>',
            /** Ambiente local */
            'gepnet.local' => '<span class="label label-inverse">Local</span>'
            /** Ambiente local */
        );
        if (!empty($baseUrl[1])) {
            return (isset($arrayLabel[$_SERVER['SERVER_NAME'] . '-' . $baseUrl[1]])) ? $arrayLabel[$_SERVER['SERVER_NAME'] . '-' . $baseUrl[1]] : '';
        } else {
            return (isset($arrayLabel[$_SERVER['SERVER_NAME']])) ? $arrayLabel[$_SERVER['SERVER_NAME']] : '';
        }
    }
}