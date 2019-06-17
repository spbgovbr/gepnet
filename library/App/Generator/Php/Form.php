<?php

class App_Generator_Php_Form extends App_Generator_Php_Abstract
{
    protected $_columnTypes = array(
        'long' => 'text',
        'number' => 'text',
        'numeric' => 'text',
        'integer' => 'text',
        'int' => 'text',
        'int4' => 'text',
        'int2' => 'text',
        'int8' => 'text',
        'year' => 'text',
        'smallint' => 'text',
        'decimal' => 'text',
        'float' => 'text',
        'string' => 'text',
        'varchar' => 'text',
        'varchar2' => 'text',
        'char' => 'text',
        'bpchar' => 'text',
        'tinytext' => 'textarea',
        'mediumtext' => 'textarea',
        'longtext' => 'textarea',
        'text' => 'textarea',
        'clob' => 'textarea',
        'blob' => 'file',
        'boolean' => 'text',
        'timestamp' => 'text',
        'timestamptz' => 'text',
        'timestamp(6)' => 'text',
        'time' => 'text',
        'date' => 'text',
        'enum' => 'select'
    );

    protected $translate;
    protected $_addcode = array();

    /**
     * @param App_Generator_Php_Config $config
     * @return App_Generator_Php_Config
     */
    public function _create(App_Generator_Php_Config $config)
    {
        $this->_addcode = array();
        // Zend_Debug::dump($metadata);exit;
        $classGen = new Zend_CodeGenerator_Php_Class();
        $classGen->setExtendedClass('App_Form_FormAbstract');
        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
            'shortDescription' => 'Automatically generated data model',
            'longDescription' => 'This class has been automatically generated' . strftime('%d-%m-%Y %H:%M')
        ));

        $classGen->setName($config->className)
            ->setDocblock($docblock);
        $elements = '';
        foreach ($config->metadata as $key => $meta) {
            $elements .= $this->generateElement($meta, $config);
        }
        //exit;
        $classGen
            ->setMethod(array(
                'name' => 'init',
                'body' => '
                    ' . implode("\n", $this->_addcode) . '
                    $this
                        ->setOptions(array(
                            "method"   => "post",
                            "elements" => array(
                                ' . $elements . '
                            )
                        ));
                ',
            ));

        return $classGen;
    }

    public function generateElement($elemento, $config)
    {
        $inputType = 'text';
        $dataType = $elemento['DATA_TYPE'];
        //Zend_Debug::dump($elemento);
        if (in_array(strtolower($dataType), $this->_columnTypes)) {
            $inputType = $this->_columnTypes[strtolower($dataType)];
        }

        $prop = new App_Generator_Php_Form_Element_Property();
        $prop->fieldName = strtolower($elemento["COLUMN_NAME"]);
        $prop->fieldType = $inputType;
        $prop->required = ($elemento["NULLABLE"]) ? 'false' : 'true';

        if ($elemento['PRIMARY'] === true) {
            $prop->fieldType = 'hidden';
        }
        $retorno = $this->getClassRef($elemento, $config);
        //Zend_Debug::dump($retorno);
        if ($retorno) {
            $prop->fieldType = 'select';
            $mapperName = '$mapper' . ucfirst($retorno['baseName']);
            $this->_addcode[$mapperName] = $mapperName . ' = new ' . $retorno['class'] . '();';
            $prop->multiOptions = $mapperName . "->fetchPairs()";
        }
        $prop->validators = $this->getElementValidators($elemento);
        $prop->filters = $this->getElementFilters($elemento);
        $generator = 'App_Generator_Php_Form_Element_' . ucfirst($prop->fieldType);
        //Zend_Debug::dump($elemento);
        //Zend_Debug::dump($prop);
        return new $generator($prop, $config);
    }

    public function getClassRef($elemento, $config)
    {
        foreach ($config->relations as $r) {
            //Zend_Debug::dump($r);
            $refTabclass = $this->camelize($r['refTableClass']);
            if ($r['columns'] == $elemento["COLUMN_NAME"]) {
                return array(
                    'class' => sprintf($config->prefix['mapper'], $config->moduleName, $refTabclass),
                    'baseName' => $refTabclass,
                );
            }
        }
        return false;
    }

    public function getElementFilters($elemento)
    {
        return "'StringTrim','StripTags'";
    }

    public function getElementValidators($elemento)
    {
        //Zend_Debug::dump($elemento);
        $dataType = strtolower($elemento['DATA_TYPE']);
        $v = array();
        if ($elemento["NULLABLE"]) {
            $v[] = "'NotEmpty'";
        }
        if ($elemento['LENGTH'] && in_array($dataType, array(
                'string',
                'varchar',
                'varchar2',
                'clob',
                'text',
                'number',
                'date',
                'char',
                'timestamp',
                'timestamp(6)'
            ))) {
            $v[] = "array('StringLength', false, array(0, " . $elemento['LENGTH'] . "))";
        }
        if ('email' === strtolower($elemento['COLUMN_NAME']) || 'emailaddress' === strtolower($elemento['COLUMN_NAME'])) {
            $v[] = "'EmailAddress'";
        }
        return implode(',', $v);
    }

    public function getElementConfiguration($field)
    {
        switch ($field['type']) {
            case 'set':
            case 'enum':
                /**
                 * Por exemplo, ENUM('Masculino', 'Feminino') será convertido para
                 *
                 * ->setMultiOptions(array("Masculino" => "Masculino", "Feminino" => "Feminino"))
                 */
                $numericOptions = eval("return array({$field['type_arguments']});");
                $assocOptions = array();
                foreach ($numericOptions as $option) {
                    $option = str_replace("'", "\'", $option);
                    $assocOptions[] = "'$option' => '$option'";
                }
                $array = 'array(' . implode(',', $assocOptions) . ')';
                $fieldType = 'radio';
                $fieldConfigs[] = '->setMultiOptions(' . $array . ')';
                $validators[] = "new Zend_Validate_InArray(array('haystack' => $array))";
                $fieldConfigs[] = '->setSeparator(" ")';
                break;
            case 'tinytext':
            case 'mediumtext':
            case 'text':
            case 'longtext':
                $fieldType = 'textarea';
                $filters[] = 'new Zend_Filter_StringTrim()';
                break;
            case 'tinyint':
            case 'mediumint':
            case 'int':
            case 'year':
                $fieldType = 'text';
                $filters[] = 'new Zend_Filter_StringTrim()';
                $validators[] = 'new Zend_Validate_Int()';
                break;
            case 'decimal':
            case 'float':
            case 'double':
            case 'bigint':
                $fieldType = 'text';
                $filters[] = 'new Zend_Filter_StringTrim()';
                $validators[] = 'new Zend_Validate_Float()';
                break;
            case 'varchar':
            case 'char':
                $validators[] = 'new Zend_Validate_StringLength(array("max" => ' . $field['type_arguments'] . '))';
                $fieldType = 'password' == $field['type'] ? 'password' : 'text';
                $filters[] = 'new Zend_Filter_StringTrim()';
                $fieldConfigs[] = '->setAttrib("maxlength", ' . $field['type_arguments'] . ')';

                if ('email' === strtolower($field['name']) || 'emailaddress' === strtolower($field['name'])) {
                    $validators[] = 'new Zend_Validate_EmailAddress()';
                }
                break;
            case 'bit':
            case 'date':
            case 'datetime':
            case 'time':
            case 'timestamp':
            default:
                $fieldType = 'text';
                $filters[] = 'new Zend_Filter_StringTrim()';

                if ('datetime' == $field['type'] || 'timestamp' == $field['type']) {
                    $fieldConfigs[] = '->setValue(date("Y-m-d H:i:s"))';
                } elseif ('date' == $field['type']) {
                    $fieldConfigs[] = '->setValue(date("Y-m-d"))';
                } elseif ('time' == $field['type']) {
                    $fieldConfigs[] = '->setValue(date("H:i:s"))';
                }
                break;
        }
    }

}