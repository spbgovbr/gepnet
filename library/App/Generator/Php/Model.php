<?php

class App_Generator_Php_Model extends App_Generator_Php_Abstract
{
    //public function _create ($class, $tableName, $metadata, $primary, $relations, $dependents, $extends)
    /**
     *
     * @param App_Generator_Php_Config $config
     * @return App_Generator_Php_Config
     */
    public function _create(App_Generator_Php_Config $config)
    {
        $classGen = new App_Generator_Php_Class();
        $classGen->setExtendedClass($config->extends);
        $docblock = new App_Generator_Php_Docblock(array(
            'shortDescription' => 'Automatically generated data model',
            'longDescription' => 'This class has been automatically generated at "' . $config->tableName . '" @ ' . strftime('%d-%m-%Y %H:%M')
        ));
        $classGen->setName($config->className)
            ->setDocblock($docblock);
        /*
        $classGen->setProperty(array(
            'name'         => 'installed',
            'visibility'   => 'public',
           // 'docblock'     => 'Installed flag. Remove to regenerate.',
            'defaultValue' => 1
        ));
        */
        if ($config->isReflection) {
            $classGen = $this->_createFromReflection($config, $classGen);
        }
        if (!$classGen) {
            return false;
        }
        //$validators = '';
        foreach ($config->metadata as $key => $meta) {
            $propertyName = strtolower($key);
            if (!$classGen->hasProperty($propertyName)) {
                $defaultValue = (isset($meta['DEFAULT']) && $meta['DEFAULT'] != '') ?
                    $meta['DEFAULT'] :
                    new Zend_CodeGenerator_Php_Property_DefaultValue("null");

                $classGen->setProperty(array(
                    'name' => $propertyName,
                    'visibility' => 'public',
                    'defaultValue' => $defaultValue
                ));
            }

            //$validators .= $this->generateElement($meta);
        }
        /*
        $classGen
            ->setMethod(array(
                'name' => 'getInputFilter',
                'body' => '
                    return array(' . $validators . ');
                ',
            ));
         * 
         */
        return $classGen;
    }

    public function generateElement($elemento)
    {
        $format = "'%s' => array(
            'required'   => " . (($elemento["NULLABLE"]) ? 'false' : 'true') . ",
            'filters'    => " . $this->getElementFilters($elemento) . ",
            'validators' => array(" . $this->getElementValidators($elemento) . "),
        ),\n";

        return sprintf($format, strtolower($elemento["COLUMN_NAME"]));
    }

    public function getElementValidators($e)
    {
        //Zend_Debug::dump($v);exit;
        $v = array();
        if ($e["NULLABLE"]) {
            $v[] = "'NotEmpty'";
        }
        if ($e['LENGTH'] && in_array($e['DATA_TYPE'], array('string', 'varchar', 'text'))) {
            $v[] = "array('StringLength', false, array(0, " . $e['LENGTH'] . "))";
        }
        return implode(',', $v);
    }

    public function getElementFilters($e)
    {
        return "array('StringTrim','StripTags')";
    }
}