<?php

class App_Generator_Adapter_Mysql extends App_Generator_Adapter_Abstract
{
    public function getReference($table, $schema = '')
    {
        $data = array();
        //$db     = Zend_Db_Table_Abstract::getDefaultAdapter();
        $result = $this->_db->fetchRow("SHOW CREATE TABLE `$table`");
        $sql = $result["Create Table"];
        $values = explode("CONSTRAINT", $sql);

        if (count($values) > 1) {
            for ($i = 1; $i < count($values); $i++) {
                $foreign = explode("FOREIGN KEY", $values[$i]);
                $foreignData = explode("`", $foreign[1]);
                $references = explode("REFERENCES", $foreign[1]);
                $referenceData = explode("`", $references[1]);

                $class = ucfirst($referenceData[1]);

                $data[$class] = array(
                    'reference_schema' => null,
                    'refTableClass' => ucfirst($referenceData[1]),
                    'refColumns' => $referenceData[3],
                    'columns' => $foreignData[1]
                );
            }
        }
        return $data;
    }

    public function listTables()
    {
        //$db = Zend_Db_Table_Abstract::getDefaultAdapter();
        return $this->_db->listTables();
    }

    public function getPrimaryKey($table)
    {
        $metadata = $this->_db->describeTable($table);
        $primary = array();
        foreach ($metadata as $col) {
            if ($col['PRIMARY']) {
                $primary[$col['PRIMARY_POSITION']] = $col['COLUMN_NAME'];
            }
        }
        return $primary;
    }

    public function getDependent($table, $schema = '', $field = 'id')
    {
        $data = array();
        //$db     = Zend_Db_Table_Abstract::getDefaultAdapter();
        $tables = $this->_db->listTables();

        foreach ($tables as $value) {
            $result = $this->_db->fetchRow("SHOW CREATE TABLE `$value`");
            $sql = $result["Create Table"];
            $values = explode("CONSTRAINT", $sql);

            if (count($values) > 1) {
                for ($i = 1; $i < count($values); $i++) {
                    $foreign = explode("FOREIGN KEY", $values[$i]);
                    $foreignData = explode("`", $foreign[1]);
                    $references = explode("REFERENCES", $foreign[1]);
                    $referenceData = explode("`", $references[1]);

                    if ($referenceData[1] == $table && $referenceData[3] == $field) {
                        $data[] = array(
                            'table' => $value,
                            'column' => $foreignData[1],
                            'schema' => null
                        );
                    }
                }
            }
        }
        return $data;
    }
}