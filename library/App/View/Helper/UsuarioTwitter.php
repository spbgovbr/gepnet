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

            $sg = explode('/', $user->SG_LOTACAO);
            $sigla = $sg[0] . '-' . $user->SG_UF;

            //Zend_Debug::dump($user);
            return sprintf($this->template, $user->DS_LOTACAO, $sigla, $user->DS_USUARIO,
                $this->view->url(array('module' => 'default', 'controller' => 'log', 'action' => 'out')));
        }

    }

    /*
  ["CD_PESSOA"] => string(5) "20662"
  ["NR_NIVEL"] => string(1) "3"
  ["CD_LOTACAO"] => string(2) "41"
  ["DS_USUARIO"] => string(5) "admin"
  ["DS_LOTACAO"] => string(44) "SERVIÇO DISCIPLINAR > SEDIS/CODIS/COGER/DPF"
  ["SG_LOTACAO"] => string(21) "SEDIS/CODIS/COGER/DPF"
  ["SG_UF"] => string(2) "DF"
     */
}