<?php

class App_View_Helper_Navigation_Twitter extends Zend_View_Helper_Navigation_Menu
{

    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function _getHtmlLabel(Zend_Navigation_Page $page)
    {
        // get label and title for translating
        $label = $page->getLabel();

        // translate label and title?
        if ($this->getUseTranslator() && $t = $this->getTranslator()) {
            if (is_string($label) && !empty($label)) {
                $label = $t->translate($label);
            }
        }

        return $this->view->escape($label);
    }


    private $_AclCheckFunction = null;

    public function setAclCheckFunction($function)
    {
        if (is_callable($function)) {
            $this->_AclCheckFunction = $function;
        }
    }

    public function twitter(Zend_Navigation_Container $container = null)
    {
        if ($container) {
            $this->setContainer($container);
        }
        /**
         * @author Kanstantsin A Kamkou (2ka.by)
         * History:
         *  - 21.08.2012 Bootstrap 2.1.0 corrections
         */
        $html = array('<ul class="nav">');

        foreach ($this->container as $page) {
            // visibility of the page
            if (!$page->isVisible()) {
                continue;
            }

            // dropdown
            $dropdown = !empty($page->pages);

            // header
            $html[] = '<li' . ($dropdown ? ' class="dropdown"' : '') . '>';

            if (!$dropdown) {
                $html[] = '<a href="' . $page->getHref() . '">';
            } else {
                $html[] = '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
            }

            $html[] = $page->getLabel();

            if ($dropdown) {
                $html[] = '<b class="caret"></b>';
            }

            $html[] = '</a>';

            if (!$dropdown) {
                $html[] = '</li>';
                continue;
            }

            $html[] = '<ul class="dropdown-menu">';

            foreach ($page->pages as $subpage) {
                // visibility of the sub-page
                if (!$subpage->isVisible()) {
                    continue;
                }

                $html[] = '<li' . ($subpage->isActive() ? ' class="active"' : '') . '>';
                $html[] = '<a href="' . $subpage->getHref() . '">';

                if ($subpage->get('icon')) {
                    $html[] = '<i class="icon-' . $subpage->get('icon') . '"></i>';
                }

                $html[] = $subpage->getLabel();
                $html[] = "</a>";
                $html[] = "</li>";
            }

            $html[] = "</ul>";
            $html[] = "</li>";
        }

        $html[] = '</ul>';

        return join(PHP_EOL, $html);
    }

}
