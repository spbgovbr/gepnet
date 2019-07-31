<?php

class App_Generator_Adapter_Pgsql extends App_Generator_Adapter_Abstract
{
    public function getReference($table, $schema = '')
    {
        $relations = array();
        $sql = "SELECT a.attname as \"column\", nf.nspname as reference_schema, clf.relname as reference_table, af.attname as \"reference_column\"
                FROM pg_catalog.pg_attribute a
                JOIN pg_class cl ON (a.attrelid = cl.oid AND cl.relkind = 'r')
                JOIN pg_namespace n ON (n.oid = cl.relnamespace)
                JOIN pg_constraint ct ON (a.attrelid = ct.conrelid AND ct.confrelid != 0 AND ct.conkey[1] = a.attnum)
                JOIN pg_class clf ON (ct.confrelid = clf.oid AND clf.relkind = 'r')
                JOIN pg_namespace nf ON (nf.oid = clf.relnamespace)
                JOIN pg_namespace nfi ON (nfi.oid = cl.relnamespace)
                JOIN pg_attribute af ON (af.attrelid = ct.confrelid AND af.attnum = ct.confkey[1])
                WHERE  cl.relname = '$table' AND n.nspname = '$schema'
                ORDER BY a.attname";
        $results = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($sql);
        //Zend_Debug::dump($results);exit;
        foreach ($results as $result) {
            $result = array_change_key_case($result, CASE_LOWER);
            $className = $this->camelize($this->sanitizeTableName($result['reference_table']));
            $relations[$className] = array(
                'refTableClass' => $result['reference_table'],
                'columns' => $result['column'],
                'refColumns' => $result['reference_column']
            );
        }
        return $relations;
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

    public function listTables()
    {
        return $this->_db->listTables();
    }

    public function getDependent($table, $schema = '', $field = 'id')
    {
        $sql = "SELECT a.attname as \"column\",
                        n.nspname as \"schema\",
                        cl.relname as \"table\"
                FROM pg_catalog.pg_attribute a
                JOIN pg_class cl ON (a.attrelid = cl.oid AND cl.relkind = 'r')
                JOIN pg_namespace n ON (n.oid = cl.relnamespace)
                JOIN pg_constraint ct ON (a.attrelid = ct.conrelid AND ct.confrelid != 0 AND ct.conkey[1] = a.attnum)
                JOIN pg_class clf ON (ct.confrelid = clf.oid AND clf.relkind = 'r')
                JOIN pg_namespace nf ON (nf.oid = clf.relnamespace)
                JOIN pg_namespace nfi ON (nfi.oid = cl.relnamespace)
                JOIN pg_attribute af ON (af.attrelid = ct.confrelid AND af.attnum = ct.confkey[1])
                WHERE clf.relname = '$table' AND nf.nspname = '$schema' AND af.attname = '$field'
                ORDER BY cl.relname";

        $result = $this->_db->fetchAll($sql);
        return $result;
    }
}