<?php

/**
 * FlashMessages view helper
 * application/modules/admin/views/helpers/FlashMessages.php
 *
 * This helper creates an easy method to return groupings of
 * flash messages by status.
 *
 * @author Aaron Bach <bachya1208[at]googlemail.com
 * @license Free to use - no strings.
 */
class App_View_Helper_Usuario extends Zend_View_Helper_Abstract
{

    //protected $template = '<span id="user_name">%s</span> | <a href="%s">sair</a>';
    /*
      protected $template = '<ul class="nav pull-right">
      <li class="divider-vertical"></li>
      <li class="dropdown"><p class="navbar-text"><a data-toggle="tooltip" title="%s" href="#">%s</a></p></li>
      <li class="divider-vertical"></li>
      <li class="dropdown"><p class="navbar-text">%s</p></li>
      <li class="dropdown">
      <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-user"></i><b class="caret"></b></a>
      <ul class="dropdown-menu">
      <li><a href="#"><i class="icon-edit"></i> Alterar senha</a></li>
      <li class="divider"></li>
      <li><a href="%s"><i class="icon-off"></i> Sair</a></li>
      </ul>
      </li>
      </ul>';
     */
    protected $template = '<div id="info-logado">
                               <div id="info-logado-nome">&nbsp;%s</div>
                               <!--<div id="info-logado-unidade"><strong>Unidade:</strong>&nbsp;%s</div>-->
                               <div id="info-logado-perfil"><strong>Perfil:</strong>&nbsp;<a href="%s" title="Mudar perfil" class="link_perfil">%s</a></div>
                               <div id="info-ultimo-acesso">
                                  <!--<strong>Último Acesso:</strong>&nbsp;18/04/2013 14:47         - -->
                                  <a href="%s" title="Sair do sistema" class="link_sair">Sair</a>
                               </div>
                           </div>';

    protected $templateSemPerfil = '<div id="info-logado">
                               <div id="info-logado-nome">&nbsp;%s</div>
                               <div id="info-ultimo-acesso">
                                  <!--<strong>Último Acesso:</strong>&nbsp;18/04/2013 14:47         - -->
                                  <a href="%s" title="Sair do sistema" class="link_sair">Sair</a>
                               </div>
                           </div>';
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function usuario()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            /*
              $sg = explode('/',$user->SG_LOTACAO);
              $sigla = $sg[0] . '-' . $user->SG_UF;
             */
            if ($user->perfilAtivo) {
                return sprintf(
                    $this->template,
                    $user->nompessoa,
                    $user->sigla,
                    $this->view->url(array('module' => 'default', 'controller' => 'index', 'action' => 'perfil')),
                    $user->perfilAtivo->nomperfil,
                    $this->view->url(array(
                        'module' => 'default',
                        'controller' => 'log',
                        'action' => 'out'
                    )));
            }
            return sprintf(
                $this->templateSemPerfil,
                $user->nompessoa,
                $user->sigla,
                $this->view->url(array(
                    'module' => 'default',
                    'controller' => 'log',
                    'action' => 'out'
                )));
        }
    }

}