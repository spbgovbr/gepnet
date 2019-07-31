<?php

/**
 * Created by PhpStorm.
 * User: wendell.wlfl
 * Date: 03/05/2016
 * Time: 09:14
 */
class Projeto_Model_Configura implements Zend_Acl_Role_Interface
{
    private $_userName;
    private $_roleId;
    private $_fullName;

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->_userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->_userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getRoleId()
    {
        return $this->_roleId;
    }

    /**
     * @param mixed $roleId
     */
    public function setRoleId($roleId)
    {
        $this->_roleId = $roleId;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->_fullName;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName)
    {
        $this->_fullName = $fullName;
    }

}