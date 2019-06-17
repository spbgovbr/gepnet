<?php
/**
 * Dijit Menu Implementation
 * @author morf
 * @version
 */

/**
 * Menu helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_Menu extends Zend_Dojo_View_Helper_DijitContainer
{
    /**
     * Dijit being used
     * @var string
     */
    protected $_dijit = 'dijit.MenuBar';

    /**
     * Dojo module to use
     * @var string
     */
    protected $_module = 'dijit.MenuBar';

    /**
     * dijit.Menu
     *
     * @param string $id
     * @param string $content
     * @param array $params Parameters to use for dijit creation
     * @param array $attribs HTML attributes
     * @return string
     */
    public function menu($id = null, $content = '', array $params = array(), array $attribs = array())
    {
        if (0 === func_num_args()) {
            return $this;
        }

        return $this->_createLayoutContainer($id, $content, $params, $attribs);
    }
}