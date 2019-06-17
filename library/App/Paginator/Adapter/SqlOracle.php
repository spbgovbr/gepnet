<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Paginator
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Array.php 20096 2010-01-06 02:05:09Z bkarwin $
 */
/**
 * @see Zend_Paginator_Adapter_Interface
 */
require_once 'Zend/Paginator/Adapter/Interface.php';

/**
 * @category   Zend
 * @package    Zend_Paginator
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class App_Paginator_Adapter_Oracle implements Zend_Paginator_Adapter_Interface
{

    /**
     * Array
     *
     * @var array
     */
    protected $_sql = null;
    /**
     * Item count
     *
     * @var integer
     */
    protected $_count = null;

    protected $_db = null;
    protected $_rowCount = null;

    /**
     * Constructor.
     *
     * @param string $sql
     */
    //public function __construct(array $array) {
    public function __construct(string $sql)
    {
        $this->_db = Zend_Db_Table::getDefaultAdapter();
        $this->_sql = $sql;
    }

    /**
     * Returns an array of items for a page.
     *
     * @param integer $offset Page offset
     * @param integer $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        //return array_slice($this->_array, $offset, $itemCountPerPage);
        $sql = "SELECT
                    *
                 FROM
                    (
                        SELECT
                            r.*, ROWNUM as numero_linha
                        FROM
                            ( " . $this->_sql . " ) r
                        WHERE
                            ROWNUM <= ?
                    )
                 WHERE 
                    ? <= numero_linha";

        $limit_sql = "SELECT z2.*
            FROM (
                SELECT z1.*, ROWNUM AS \"zend_db_rownum\"
                FROM (
                    " . $this->_sql . "
                ) z1
            ) z2
            WHERE z2.\"zend_db_rownum\" BETWEEN " . ($offset + 1) . " AND " . ($offset + $count);

        $stmt = $this->_db->query($sql, array($itemCountPerPage, $offset));
        return $stmt->fetchAll();
        /*
        $sql = "SELECT
                    *
                 FROM
                    (
                        SELECT
                            r.*, ROWNUM as numero_linha
                        FROM
                            ( " . $this->_sql . " ) r
                        WHERE
                            ROWNUM <= :item_count_per_page
                    )
                 WHERE 
                    :offset <= numero_linha";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':offset', $offset);
        //Calcula o número da última linha na pagina
        $item_count_per_page = $offset + $itemCountPerPage - 1;
        oci_bind_by_name($stmt, ':item_count_per_page', $item_count_per_page);
        oci_execute($stmt);
        */


        /*
        $sql = "SELECT / *+ FIRST_ROWS * / s.* FROM".
        "(SELECT r.*, rownum as adodb_rownum, $fields FROM".
        " ($sql) r WHERE rownum <= :adodb_nrows".
        ") s 
        WHERE adodb_rownum >= :adodb_offset";         
        */
    }

    /**
     * Returns the total number of rows in the array.
     *
     * @return integer
     */
    public function count()
    {
        if ($this->_rowCount === null) {
            $this->setRowCount(
                $this->getCountSelect()
            );
        }

        return $this->_rowCount;
    }

    public function getCountSelect()
    {
        $sql = "SELECT COUNT(*) AS zend_paginator_row_count FROM($this->_sql)";
        return $sql;
    }

    public function setRowCount($sql)
    {
        $rowCount = $this->_db->fetchOne($sql);
        $this->_rowCount = $rowCount;
    }

}