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
class App_Paginator_Adapter_Sql_Pgsql implements Zend_Paginator_Adapter_Interface
{

    /**
     * Array
     *
     * @var array
     */
    protected $_sql = null;
    protected $_db = null;

    /**
     * Item count
     *
     * @var integer
     */
    protected $_rowCount = null;

    /**
     * Constructor.
     *
     * @param string $sql
     */
    //public function __construct(array $array) {
    public function __construct($sql)
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
        $sql = $this->_sql;
        $sql .= " LIMIT $itemCountPerPage";
        if ($offset > 0) {
            $sql .= " OFFSET $offset";
        }

        //$stmt = $this->_db->query( $sql, array($itemCountPerPage, $offset) );
        $stmt = $this->_db->query($sql);
        $retorno = $stmt->fetchAll();
//		Zend_Debug::dump($retorno, ' getItens ');

        return $retorno;
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
//		Zend_Debug::dump($this->_rowCount, ' _rowCount ');

        return $this->_rowCount;
    }

    public function getCountSelect()
    {
        $sql = "SELECT COUNT(1) AS zend_paginator_row_count FROM ($this->_sql) c";
//			Zend_Debug::dump($sql, ' sql count ');
        return $sql;
    }

    public function setRowCount($sql)
    {
        $rowCount = $this->_db->fetchOne($sql);
        $this->_rowCount = $rowCount;
    }

}