<?php

class App_View_Helper_DojoMenu extends Zend_View_Helper_Navigation_Menu
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }


    /**
     * View helper entry point:
     * Retrieves helper and optionally sets container to operate on
     *
     * @param Zend_Navigation_Container $container [optional] container to
     *                                               operate on
     * @return Zend_View_Helper_Navigation_Menu      fluent interface,
     *                                               returns self
     */
    public function dojoMenu(Zend_Navigation_Container $container = null)
    {
        if (null !== $container) {
            $this->setContainer($container);
        }

        return $this;
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

    protected function getDojoProgrammaticMenuparts($container, $nl, $counter_prefix)
    {
        $id = "menu_" . $counter_prefix;
        $ret = $nl . "var $id = new dijit.Menu({});";
        $nl .= "  ";

        $element_count = 0;

        $id_counter = 0;
        foreach ($container as $page) {
            // get label and title for translating
            $label = $this->_getHtmlLabel($page);
            $url = $page->getHref();
            if ($page->hasChildren()) {
                $id_counter++;
                $item = $this->getDojoProgrammaticMenuparts($page->getPages(), $nl . "  ",
                    $counter_prefix . "_" . $id_counter);
                if ($item) {
                    $ret .= $nl . $item["code"];
                    $ret .= $nl . "$id.addChild(";
                    $ret .= $nl . "  new dijit.PopupMenuItem({";
                    $ret .= $nl . "    label: \"$label\",";
                    $ret .= $nl . "    popup:" . $item["id"];
                    $ret .= $nl . "  })";
                    $ret .= $nl . ");";
                    $element_count++;
                } else {
                    $id_counter--;
                }
            } else {
                if ($this->accept($page, true)) {
                    $ret .= $nl . "$id.addChild(";
                    $ret .= $nl . "  new dijit.MenuItem({";
                    $ret .= $nl . "    label: '" . addslashes($label) . "',";
                    $ret .= $nl . "    onClick: function () { window.location = \"" . $url . "\"; }";
                    if ($page->isActive()) {
                        $ret .= "," . $nl . "    disabled: true";
                    }
                    $ret .= $nl . "  })";
                    $ret .= $nl . ");";
                    $element_count++;
                }
            }
        }

        if ($element_count > 0) {
            return array("id" => $id, "code" => $ret);
        } else {
            return false;
        }
    }

    private $_AclCheckFunction = null;

    public function setAclCheckFunction($function)
    {
        if (is_callable($function)) {
            $this->_AclCheckFunction = $function;
        }
    }

    /**
     * Overrides the parent function
     * @param Zend_Navigation_Page $page page to check
     * @param bool $recursive [optional] if true, page will not
     *                                         be accepted if it is the
     *                                         descendant of a page that is not
     *                                         accepted. Default is true.
     * @return bool                            whether page should be accepted
     */
    public function accept(Zend_Navigation_Page $page, $recursive = true)
    {
        if ($this->_AclCheckFunction != null && is_callable($this->_AclCheckFunction)) {
            return call_user_func($this->_AclCheckFunction, $page);
        } else {
            return parent::accept($page, $recursive);
        }
    }

    protected function _renderMenu(Zend_Navigation_Container $container, $linkability_function = false)
    {

        $menubar_div_id = "My_Great_MenuBar_" . uniqid();
        $menubar_script = '';
        $menubar_script .= 'var pMenuBar;';
        $menubar_script .= "\npMenuBar = new dijit.MenuBar({});";
        //$iterator = new ArrayIterator($container->getPages());
        $id_counter = 0;
        if (count($container->getPages()) == 1) {
            $all_pages = $container->getPages();
            $all_pages = array_pop($all_pages)->getPages();
        } else {
            $all_pages = $container->getPages();
        }
        foreach ($all_pages as $page) {
            $id_counter++;
            $menu_item = $this->getDojoProgrammaticMenuparts($page, "\n", $id_counter);
            if ($menu_item) {
                $menubar_script .= "\n" . $menu_item["code"];
                $menubar_script .= "\n\npMenuBar.addChild( new dijit.PopupMenuBarItem({";
                $menubar_script .= "\n    label: \"" . $this->_getHtmlLabel($page) . "\",";
                $menubar_script .= "\n    popup: " . $menu_item["id"];
                $menubar_script .= "\n}));";
            } else {
                $id_counter--;
            }
        }

        $menubar_script .= "\npMenuBar.placeAt(\"$menubar_div_id\");";
        $menubar_script .= "\npMenuBar.startup();";
        $this->view->dojo()->enable();
        $this->view->dojo()->addOnLoad("function() {\n" . $menubar_script . "\n}");
        $this->view->dojo()->requireModule('dijit.MenuBar');
        $this->view->dojo()->requireModule("dijit.MenuBarItem");
        $this->view->dojo()->requireModule("dijit.PopupMenuBarItem");
        $this->view->dojo()->requireModule("dijit.Menu");
        $this->view->dojo()->requireModule("dijit.MenuItem");
        $this->view->dojo()->requireModule("dijit.PopupMenuItem");
        $this->view->inlineScript()->captureStart();
        echo 'dojo.addOnLoad(function () { ', $menubar_script, '});';
        $this->view->inlineScript()->captureEnd();

        $html = "<div id=\"$menubar_div_id\"></div>";

        return $html;
    }

    public function renderMenu(Zend_Navigation_Container $container = null, array $options = array())
    {
        if (null === $container) {
            $container = $this->getContainer();
        }

        $options = $this->_normalizeOptions($options);

        $html = $this->_renderMenu($container,
            $options['ulClass'],
            $options['indent'],
            $options['minDepth'],
            $options['maxDepth'],
            $options['onlyActiveBranch']);

        return $html;
    }

    public function render(Zend_Navigation_Container $container = null)
    {
        return $this->renderMenu($container);
    }

}
