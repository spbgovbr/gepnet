<?php

/**
 * Abstract collection
 */
abstract class App_Model_CollectionAbstract
    implements Countable, SeekableIterator, ArrayAccess
{
    /**
     * Allowed domain class in this collection
     *
     * @var string
     */
    protected $_domainClass = 'App_Model_ModelAbstract';

    /**
     * Entities in this collection
     *
     * @var array
     */
    protected $_entities = array();

    /**
     * @param string $class
     * @return void
     */
    public function setDomainClass($class)
    {
        $this->_domainClass = $class;
    }

    /**
     * Clear all entities
     *
     * @return void
     */
    public function clear()
    {
        $this->_entities = array();
    }

    /**
     * @return integer
     * @see    Countable::count()
     */
    public function count()
    {
        return count($this->_entities);
    }

    /**
     * @param integer $index
     * @return void
     * @throws App_Model_OutOfBoundsException When the seek position is invalid
     * @see    SeekableIterator::seek()
     */
    public function seek($index)
    {
        $this->rewind();
        $position = 0;

        while ($position < $index && $this->valid()) {
            $this->next();
            $position++;
        }

        if (!$this->valid()) {
            throw new Exception('Invalid seek position');
        }
    }

    /**
     * @return mixed
     * @see    SeekableIterator::current()
     */
    public function current()
    {
        return current($this->_entities);
    }

    /**
     * @return mixed
     * @see    SeekableIterator::next()
     */
    public function next()
    {
        return next($this->_entities);
    }

    /**
     * @return mixed
     * @see    SeekableIterator::key()
     */
    public function key()
    {
        return key($this->_entities);
    }

    /**
     * @return boolean
     * @see    SeekableIterator::valid()
     */
    public function valid()
    {
        return ($this->current() !== false);
    }

    /**
     * @return void
     * @see    SeekableIterator::rewind()
     */
    public function rewind()
    {
        reset($this->_entities);
    }

    /**
     * @param mixed $offset
     * @return boolean
     * @see    ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_entities);
    }

    /**
     * @param mixed $offset
     * @return Zend_Tag_IItem
     * @see    ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->_entities[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $entity
     * @return void
     * @throws App_Model_InvalidArgumentException When $entity is no instance of the defined domain class
     * @see    ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $entity)
    {
        // We need to make that check here, as the method signature must be
        // compatible with ArrayAccess::offsetSet()
        //Zend_Debug::dump($entity->distribuicao->getIterator());exit;

        if (!($entity instanceof $this->_domainClass)) {
            throw new Exception('Entity is no instance of ' . $this->_domainClass);
        }

        if ($offset === null) {
            $this->_entities[] = $entity;
        } else {
            $this->_entities[$offset] = $entity;
        }
    }

    /**
     * @param mixed $offset
     * @return void
     * @see    ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->_entities[$offset]);
    }
}