<?php

class App_Generator_Php_Class extends Zend_CodeGenerator_Php_Class
{
    /**
     * hasProperty()
     *
     * @param string $propertyName
     * @return bool
     */
    public function hasProperty($propertyName)
    {
        return isset($this->_properties[$propertyName]);
    }

    /**
     * setProperty()
     *
     * @param array|Zend_CodeGenerator_Php_Property $property
     * @return Zend_CodeGenerator_Php_Class
     */
    public function setProperty($property)
    {
        if (is_array($property)) {
            $property = new Zend_CodeGenerator_Php_Property($property);
            $propertyName = $property->getName();
        } elseif ($property instanceof Zend_CodeGenerator_Php_Property) {
            $propertyName = $property->getName();
        } else {
            #require_once 'Zend/CodeGenerator/Php/Exception.php';
            throw new Zend_CodeGenerator_Php_Exception('setProperty() expects either an array of property options or an instance of Zend_CodeGenerator_Php_Property');
        }

        $this->_properties[$propertyName] = $property;
        return $this;
    }

    /**
     * hasMethod()
     *
     * @param string $methodName
     * @return bool
     */
    public function hasMethod($methodName)
    {
        //Zend_Debug::dump($methodName);exit;
        return isset($this->_methods[$methodName]);
    }


    /**
     * hasConstant()
     *
     * @param string $constName
     * @return bool
     */
    public function hasConstant($constName)
    {
        return isset($this->_constants[$constName]);
    }


}