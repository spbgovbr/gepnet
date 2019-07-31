<?php

class App_Generator_Adapter_Oracle extends App_Generator_Adapter_Abstract
{
    public function getReference($table, $schema = '')
    {
        $table = strtoupper($table);
        $relations = array();
        $sql = 'SELECT '
            . 'rcc.table_name AS referenced_table_name, '
            . 'lcc.column_name AS local_column_name, '
            . 'rcc.column_name AS referenced_column_name '
            . 'FROM user_constraints ac '
            . 'JOIN user_cons_columns rcc ON ac.r_constraint_name = rcc.constraint_name '
            . 'JOIN user_cons_columns lcc ON ac.constraint_name = lcc.constraint_name '
            . "WHERE ac.constraint_type = 'R' AND ac.table_name = '$table' ";
        /*
        $sql = 'SELECT *'
             . 'FROM user_constraints ac '
             . 'JOIN user_cons_columns rcc ON ac.r_constraint_name = rcc.constraint_name '
             . 'JOIN user_cons_columns lcc ON ac.constraint_name = lcc.constraint_name '
             . "WHERE ac.constraint_type = 'R' AND ac.table_name = 'TB_DOCUMENTO'";
         */
        $results = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($sql);
        //Zend_Debug::dump($results);exit;
        foreach ($results as $result) {
            $result = array_change_key_case($result, CASE_LOWER);
            $className = $this->camelize($result['referenced_table_name']);
            $relations[$className] = array(
                'refTableClass' => $result['referenced_table_name'],
                'columns' => $result['local_column_name'],
                'refColumns' => $result['referenced_column_name']
            );
        }
        return $relations;
    }

    public function getPrimaryKey($table, $schemaName = null)
    {
        //$db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $metadata = $this->_db->describeTable($table, $schemaName);
        $primary = array();
        foreach ($metadata as $col) {
            if ($col['PRIMARY']) {
                $primary[$col['PRIMARY_POSITION']] = $col['COLUMN_NAME'];
            }
        }
        return $primary;
    }

    public function listTables($schema = '')
    {
        //$db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $sql = "SELECT TABLE_NAME FROM all_tables";
        if ($schema != '') {
            $sql .= " WHERE OWNER = '$schema'";
        }
        return $this->_db->fetchCol($sql);
    }

    public function getDependent($table, $schema = null)
    {
        $table = strtoupper($table);
        $schema = strtoupper($schema);
        $sql = "SELECT TABLE_NAME
                FROM user_constraints
                WHERE R_CONSTRAINT_NAME IN
                ( SELECT CONSTRAINT_NAME
                FROM user_constraints
                WHERE
                OWNER = '$schema' AND
                TABLE_NAME = '$table'  )
                AND STATUS = 'ENABLED'
                ";
        //echo $sql;
        //exit;
        //$results = $db->fetchAll($sql, array('SIOUV',$table));
        $results = $this->_db->fetchAll($sql);
        $relations = array();
        foreach ($results as $result) {
            //$result = array_change_key_case($result, CASE_LOWER);
            $relations[] = $result['TABLE_NAME'];

        }
        return $relations;
    }
}
