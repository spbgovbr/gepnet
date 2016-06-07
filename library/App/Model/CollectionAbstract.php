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
     * @param  string $class
     * @return void
     */
    public function setDomainClass($class){
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
     * @see    Countable::count()
     * @return integer
     */
    public function count()
    {
        return count($this->_entities);
    }
    
    /**
     * @see    SeekableIterator::seek()
     * @param  integer $index
     * @throws App_Model_OutOfBoundsException When the seek position is invalid
     * @return void
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
     * @see    SeekableIterator::current()
     * @return mixed
     */
    public function current()
    {
        return current($this->_entities);
    }
    
    /**
     * @see    SeekableIterator::next()
     * @return mixed
     */
    public function next()
    {
        return next($this->_entities);
    }
    
    /**
     * @see    SeekableIterator::key()
     * @return mixed
     */
    public function key()
    {
        return key($this->_entities);
    }

    /**
     * @see    SeekableIterator::valid()
     * @return boolean
     */
    public function valid()
    {
        return ($this->current() !== false);
    }
    
    /**
     * @see    SeekableIterator::rewind()
     * @return void
     */
    public function rewind()
    {
        reset($this->_entities);
    }
    
    /**
     * @see    ArrayAccess::offsetExists()
     * @param  mixed $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return array_key_exists($offset, $this->_entities);
    }
 
    /**
     * @see    ArrayAccess::offsetGet()
     * @param  mixed $offset
     * @return Zend_Tag_IItem
     */
    public function offsetGet($offset) {
        return $this->_entities[$offset];
    }
 
    /**
     * @see    ArrayAccess::offsetSet()
     * @param  mixed $offset
     * @param  mixed $entity
     * @throws App_Model_InvalidArgumentException When $entity is no instance of the defined domain class
     * @return void
     */
    public function offsetSet($offset, $entity) {
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
     * @see    ArrayAccess::offsetUnset()
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset($offset) {
        unset($this->_entities[$offset]);
    }
}