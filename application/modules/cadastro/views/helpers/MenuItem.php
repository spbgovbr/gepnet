<?php
/**
 * Dijit MenuItem Implementation
 * @author morf
 * @version
 */

/**
 * MenuItem helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_MenuItem extends Zend_Dojo_View_Helper_DijitContainer
{
    /**
     * Dijit being used
     * @var string
     */
    protected $_dijit = 'dijit.MenuBarItem';

    /**
     * Dojo module to use
     * @var string
     */
    protected $_module = 'dijit.MenuBarItem';

    /**
     * dijit.MenuItem
     *
     * @param string $id
     * @param string $content
     * @param array $params Parameters to use for dijit creation
     * @param array $attribs HTML attributes
     * @return string
     */
    public function menuItem($id = null, $content = '', array $params = array(), array $attribs = array())
    {
        if (0 === func_num_args()) {
            return $this;
        }

        return $this->_createLayoutContainer($id, $content, $params, $attribs);
    }
}