<?php

class ZFDebug_Controller_Plugin_Debug_Plugin_Debug implements ZFDebug_Controller_Plugin_Debug_Plugin_Interface
{
    /**
     * @var string
     */
    protected $_tab = '';

    /**
     * @var string
     */
    protected $_panel = '';

    /**
     * Contains plugin identifier name
     *
     * @var string
     */
    protected $_identifier = 'text';

    /**
     * Create ZFDebug_Controller_Plugin_Debug_Plugin_Debug
     *
     * @paran array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (isset($options['tab'])) {
            $this->setTab($options['tab']);
        }
        if (isset($options['panel'])) {
            $this->setPanel($options['panel']);
        }
    }

    /**
     * Gets identifier for this plugin
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Sets identifier for this plugin
     *
     * @param string $name
     * @return ZFDebug_Controller_Plugin_Debug_Plugin_Debug Provides a fluent interface
     */
    public function setIdentifier($name)
    {
        $this->_identifier = $name;
        return $this;
    }

    /**
     * Gets menu tab for the Debugbar
     *
     * @return string
     */
    public function getTab()
    {
        return $this->_tab;
    }

    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
        return $this->_panel;
    }

    /**
     * Sets tab content
     *
     * @param string $tab
     * @return ZFDebug_Controller_Plugin_Debug_Plugin_Debug Provides a fluent interface
     */
    public function setTab($tab)
    {
        $this->_tab = $tab;
        return $this;
    }

    /**
     * Sets panel content
     *
     * @param string $panel
     * @return ZFDebug_Controller_Plugin_Debug_Plugin_Debug Provides a fluent interface
     */
    public function setPanel($panel)
    {
        $this->_panel = $panel;
        return $this;
    }
}