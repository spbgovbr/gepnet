<?php

class App_Generator_Php_Table extends App_Generator_Php_Abstract
{

    //public function _create($className, $tableName, $primary, $relations, $dependents)
    /**
     *
     * @param App_Generator_Php_Config $config
     * @return App_Generator_Php_Class
     */
    public function _create(App_Generator_Php_Config $config)
    {
        $classGen = new Zend_CodeGenerator_Php_Class();

        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
            'shortDescription' => 'Automatically generated data model',
            'longDescription' => 'This class has been automatically generated based on the dbTable "' . $config->tableName . '" @ ' . strftime('%d-%m-%Y %H:%M')
        ));

        $classGen
            ->setName($config->className)
            ->setExtendedClass($config->extends)
            ->setDocblock($docblock)
            ->setProperty(array(
                'name' => '_name',
                'visibility' => 'protected',
                'defaultValue' => $config->tableName,
            ))
            ->setProperty(array(
                'name' => '_primary',
                'visibility' => 'protected',
                'defaultValue' => $config->primary,
            ))
            ->setProperty(array(
                'name' => '_dependentTables',
                'visibility' => 'protected',
                'defaultValue' => $config->dependents,
            ))
            ->setProperty(array(
                'name' => '_referenceMap',
                'visibility' => 'protected',
                'defaultValue' => $config->relations,
            ));
        /*
            ->setProperty(array(
                'name' => '_metadata',
                'visibility' => 'protected',
                'defaultValue' => $config->metadata,
            ));
        */
        if ($config->isReflection) {
            $classGen = $this->_createFromReflection($config, $classGen);
        }
        return $classGen;
    }
}