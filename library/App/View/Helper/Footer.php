<?php

/**
 * Footer view helper
 *
 * @author Marcelo Rodovalho <mfrodovalho[at]gmail.com>
 * @license Free to use - no strings.
 */
class App_View_Helper_Footer extends Zend_View_Helper_Abstract
{
    protected $template = '<div class="ui-layout-south">
    <div id="rodape">
        <div id="rodape_copy">
            <div class="rodape_content">
                &copy; Copyright  Polícia Federal - PF, %s
            </div>
        </div>
    </div><!--rodape-->
</div>';

    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function footer()
    {
        $project = Zend_Registry::get('config')->project;
        $gestor = explode(' - ', $project->gestor);
        $gestor = array_reverse($gestor);
        $gestor = implode(' - ', $gestor);
        return sprintf(
            $this->template,
            $gestor
        );
    }
}
