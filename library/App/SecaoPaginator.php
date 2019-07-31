<?php

class App_SecaoPaginator
{
    protected $ns;
    protected $params = array();
    protected $defaults = array(
        'ordem' => 0,
        'page' => 0,
        'itemsperpage' => 20,
        'direcao' => 'ASC'
    );
    protected $sessionVar = array();
    private $page;
    private $ordem;
    private $direcao;
    private $itemsperpage;

    public function __construct($ns, $defaults)
    {
        Zend_Wildfire_Plugin_FirePhp::send($this->defaults, 'defaults', Zend_Wildfire_Plugin_FirePhp::DUMP);
        $this->defaults = array_merge($this->defaults, $defaults);
        $this->ns = new Zend_Session_Namespace($ns);
        $this->params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        $this->params = array_filter($this->params, array($this, 'paginatorKey'));
        $this->params = array_filter($this->params);
        $this->params = array_merge($this->defaults, $this->params);
        $this->sessionVar = $this->ns->getIterator();
        Zend_Wildfire_Plugin_FirePhp::send($this->sessionVar, 'session', Zend_Wildfire_Plugin_FirePhp::DUMP);
        $this->inicializar();
        Zend_Wildfire_Plugin_FirePhp::send($this->sessionVar, 'session com params', Zend_Wildfire_Plugin_FirePhp::DUMP);
    }

    public function paginatorKey($key)
    {
        return (bool)array_key_exists($key, $this->defaults);
    }

    public function inicializar()
    {
        //validar
        foreach ($this->params as $key => $value) {
            if (array_key_exists($key, $this->defaults)) {
                $this->$key = $value;
            }
            //$this->ns->$key = $value;
        }
        $this->popularSessao();
    }

    public function popularSessao()
    {
        foreach ($this->params as $key => $value) {
            if (array_key_exists($key, $this->defaults)) {
                $this->ns->$key = $value;
            }
        }
        $this->sessionVar = $this->ns->getIterator();
    }

    /**
     * @return the $page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return the $ordem
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * @return the $direcao
     */
    public function getDirecao()
    {
        return $this->direcao;
    }

    /**
     * @return the $itemsperpage
     */
    public function getItemsperpage()
    {
        return $this->itemsperpage;
    }

    /**
     * @param field_type $page
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @param field_type $ordem
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
        return $this;
    }

    /**
     * @param field_type $direcao
     */
    public function setDirecao($direcao)
    {
        if (!in_array(strtolower($direcao), array('ASC', 'DESC'))) {
            throw new Exception('[' . __CLASS__ . '] direcao invalida');
        }
        $this->direcao = $direcao;
        return $this;
    }

    /**
     * @param field_type $itemsperpage
     */
    public function setItemsperpage($itemsperpage)
    {
        if (!in_array($itemsperpage, array(15, 30, 60, 120, 240))) {
            throw new Exception('[' . __CLASS__ . '] Itens por pagina fora dos limites[15 - 240]');
        }
        $this->itemsperpage = $itemsperpage;
        return $this;
    }

    public function __set($name, $value)
    {
        $metodo = 'set' . ucfirst($name);
        if (method_exists($this, $metodo)) {
            $metodo($value);
        }

        $this->$name = $value;
        return $this;
    }
}

?>