<?php

/**
 * Abstract relation
 */
class App_Model_Relation implements Countable, IteratorAggregate
{
    /**
     * Inner iterator
     *
     * @var CollectionAbstract
     */
    protected $_iterator;

    /**
     * Mapper to call
     *
     * @var App_Mapper_MapperAbstract
     */
    protected $_mapper;

    /**
     * Method to call
     *
     * @var string
     */
    protected $_method;

    /**
     * Arguments to pass to the mapper's method
     *
     * @var array
     */
    protected $_arguments;

    /**
     * Create a relation with a mapper, method to call and it's arguments
     *
     * @param App_Model_Mapper_MapperAbstract $mapper
     * @param string $method
     * @param array $arguments
     */
    public function __construct(App_Model_Mapper_MapperAbstract $mapper, $method, array $arguments)
    {
        $this->_mapper = $mapper;
        $this->_method = $method;
        $this->_arguments = $arguments;
    }

    /**
     * @return integer
     * @see    Countable::count()
     */
    public function count()
    {
        return count($this->getIterator());
    }

    /**
     * @return Traversable
     * @see    IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        if ($this->_iterator === null) {
            $this->_iterator = call_user_func_array(array($this->_mapper, $this->_method), $this->_arguments);
        }

        return $this->_iterator;
    }
    /*
    public function __get($name) {
        return $this->getIterator();
    }
    */
    /**
     * Route method calls to the iterator
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        return call_user_func_array(array($this->getIterator(), $name), $arguments);
    }
}