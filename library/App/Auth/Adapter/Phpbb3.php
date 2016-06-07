<?php
/**
 * Zend Framework Brasil
 *
 * @category  Zfb
 * @package   Zfb_Validate
 * @version   $Id$
 */

/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once 'Zend/Auth/Adapter/Interface.php';

/**
 * @category   Zfb
 * @package    Zfb_Auth
 * @subpackage Zfb_Auth_Adapter
 */
class Zfb_Auth_Adapter_Phpbb3 implements Zend_Auth_Adapter_Interface {

	/**
	 * path for phpbb3 files
	 *
	 * @var string
	 */
	private $_phpbbRoot;

	/**
	 * Sets username and password for authentication
	 *
	 * @return void
	 */
	public function __construct($phpbbRootPath)
	{
		$this->_phpbbRoot = $phpbbRootPath;
	}

	/**
	 * Performs an authentication attempt
	 *
	 * @throws Zend_Auth_Adapter_Exception If authentication cannot
	 *                                     be performed
	 * @return Zend_Auth_Result
	 */
	public function authenticate()
	{
		define('PHPBB_MSG_HANDLER', 'zfb_auth_adapter_phpbb3_error');

		try {
			defined('IN_PHPBB') || define('IN_PHPBB', true);

			global $db, $template, $config, $auth, $phpEx, $phpbb_root_path, $cache, $user, $phpbb_hook;

			$phpEx = substr(strrchr(__FILE__, '.'), 1);
			$phpbb_root_path = $this->_phpbbRoot;
			include ($phpbb_root_path . 'common.' . $phpEx);

			// Start session management
			$user->session_begin();
			$auth->acl($user->data);
			$user->setup();
			restore_error_handler();

		} catch (Exception $e) {
			restore_error_handler();
			throw $e;
		}

		return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user->data);
	}
}

function zfb_auth_adapter_phpbb3_error($errno, $errstr, $errfile, $errline) {
	require_once 'Zend/Auth/Adapter/Exception.php';
	throw new Zend_Auth_Adapter_Exception($errstr);
}