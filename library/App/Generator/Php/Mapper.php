<?php

class App_Generator_Php_Mapper extends App_Generator_Php_Abstract
{

    //public function _create($className, $modelName, $tableName, $metadata, $pks, $relations, $dependents, $extends = null) 
    /**
     * @param App_Generator_Php_Config $config
     * @return App_Generator_Php_Class
     */
    public function _create(App_Generator_Php_Config $config)
    {
        if ($config->isReflection) {
            return false;
        }

        $data = '';
        foreach ($config->metadata as $key => $meta) {
            //$data .= "\t" . '"' . $key . '" => $model->' . $this->camelize($key) . ',' . "\n";
            $data .= "\t" . '"' . $key . '" => $model->' . strtolower($key) . ',' . "\n";
        }
        $data = 'array(' . "\n" . $data . ');';

        $formName = str_replace("_Model_Mapper_", "_Form_", $config->className);

        $classGen = new Zend_CodeGenerator_Php_Class();
        $classGen->setExtendedClass($config->extends);

        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
            'shortDescription' => 'Automatically generated data model',
            'longDescription' => 'This class has been automatically generated based on the dbTable "' . $config->tableName . '" @ ' . strftime('%d-%m-%Y %H:%M')
        ));

        $classGen
            ->setName($config->className)
            ->setDocblock($docblock)
            ->setMethod(array(
                'name' => 'insert',
                'parameters' => array(
                    array(
                        'name' => 'model',
                        'type' => $config->modelName
                    )
                ),
                'body' => '
                    $data = ' . $data . '
                    $this->getDbTable()->insert($data);
                ',
                'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
                    'shortDescription' => 'Set the property',
                    'tags' => array(
                        new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                            'paramName' => 'value',
                            'datatype' => 'string'
                        )),
                        new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                            'datatype' => $config->modelName
                        ))
                    )
                ))
            ))
            ->setMethod(array(
                'name' => 'update',
                'parameters' => array(
                    array(
                        'name' => 'model',
                        'type' => $config->modelName
                    )
                ),
                'body' => '
                  $data = ' . $data . '
                       // $this->getDbTable()->update($data, array("id = ?" => $id));
                ',
                'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
                    'shortDescription' => 'Set the property',
                    'tags' => array(
                        new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                            'paramName' => 'value',
                            'datatype' => 'string'
                        )),
                        new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                            'datatype' => $config->modelName
                        ))
                    )
                ))
            ))
            ->setMethod(array(
                'name' => 'getForm',
                'body' => '
                    return $this->_getForm(' . $formName . ');
                ',
            ));

        return $classGen;
    }
}