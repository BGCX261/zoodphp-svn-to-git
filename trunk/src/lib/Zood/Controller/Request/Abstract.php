<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_Controller_Request
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Feb 23, 2009
 * @version    SVN: $Id$
 */


/**
 * Abstract of Request
 *
 * @category   Zood
 * @package    Zood_Controller_Request
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
abstract class Zood_Controller_Request_Abstract
{
	/**
	 * Module
	 * @var string
	 */
	protected $_module;

	/**
	 * Module key for retrieving module from params
	 * @var string
	 */
	protected $_moduleKey = 'm';

	/**
	 * Controller
	 * @var string
	 */
	protected $_controller;

	/**
	 * Controller key for retrieving controller from params
	 * @var string
	 */
	protected $_controllerKey = 'c';

	/**
	 * Action
	 * @var string
	 */
	protected $_action;

	/**
	 * Action key for retrieving action from params
	 * @var string
	 */
	protected $_actionKey = 'a';

	/**
	 * Request parameters
	 * @var array
	 */
	protected $_params = array();
	/**
	 * Retrieve the module name
	 *
	 * @return string
	 */
	public function getModuleName()
	{
		if (null === $this->_module) {
			$this->_module = $this->getParam($this->getModuleKey());
			if ($this->_module == '') {
			    $this->_module = 'index';
			}
		}

		return $this->_module;
	}

	/**
	 * Set the module name to use
	 *
	 * @param string $value
	 * @return Zend_Controller_Request_Abstract
	 */
	public function setModuleName($value)
	{
		$this->_module = $value;
		return $this;
	}

	/**
	 * Retrieve the controller name
	 *
	 * @return string
	 */
	public function getControllerName()
	{
		if (null === $this->_controller) {
			$this->_controller = $this->getParam($this->getControllerKey());
            if ($this->_controller == '') {
                $this->_controller = 'index';
            }
		}

		return $this->_controller;
	}

	/**
	 * Set the controller name to use
	 *
	 * @param string $value
	 * @return Zend_Controller_Request_Abstract
	 */
	public function setControllerName($value)
	{
		$this->_controller = $value;
		return $this;
	}

	/**
	 * Retrieve the action name
	 *
	 * @return string
	 */
	public function getActionName()
	{
		if (null === $this->_action) {
			$this->_action = $this->getParam($this->getActionKey());
            if ($this->_action == '') {
                $this->_action = 'index';
            }
		}

		return $this->_action;
	}

	/**
	 * Set the action name
	 *
	 * @param string $value
	 * @return Zend_Controller_Request_Abstract
	 */
	public function setActionName($value)
	{
		$this->_action = $value;
		/**
		 * @see ZF-3465
		 */
		if (null === $value) {
			$this->setParam($this->getActionKey(), $value);
		}
		return $this;
	}

	/**
	 * Retrieve the module key
	 *
	 * @return string
	 */
	public function getModuleKey()
	{
		return $this->_moduleKey;
	}

	/**
	 * Set the module key
	 *
	 * @param string $key
	 * @return Zend_Controller_Request_Abstract
	 */
	public function setModuleKey($key)
	{
		$this->_moduleKey = (string) $key;
		return $this;
	}

	/**
	 * Retrieve the controller key
	 *
	 * @return string
	 */
	public function getControllerKey()
	{
		return $this->_controllerKey;
	}

	/**
	 * Set the controller key
	 *
	 * @param string $key
	 * @return Zend_Controller_Request_Abstract
	 */
	public function setControllerKey($key)
	{
		$this->_controllerKey = (string) $key;
		return $this;
	}

	/**
	 * Retrieve the action key
	 *
	 * @return string
	 */
	public function getActionKey()
	{
		return $this->_actionKey;
	}

	/**
	 * Set the action key
	 *
	 * @param string $key
	 * @return Zend_Controller_Request_Abstract
	 */
	public function setActionKey($key)
	{
		$this->_actionKey = (string) $key;
		return $this;
	}

	/**
	 * Get an action parameter
	 *
	 * @param string $key
	 * @param mixed $default Default value to use if key not found
	 * @return mixed
	 */
	public function getParam($key, $default = null)
	{
		$key = (string) $key;
		if (isset($this->_params[$key])) {
			return $this->_params[$key];
		}

		return $default;
	}

	/**
	 * Retrieve only user params (i.e, any param specific to the object and not the environment)
	 *
	 * @return array
	 */
	public function getUserParams()
	{
		return $this->_params;
	}

	/**
	 * Retrieve a single user param (i.e, a param specific to the object and not the environment)
	 *
	 * @param string $key
	 * @param string $default Default value to use if key not found
	 * @return mixed
	 */
	public function getUserParam($key, $default = null)
	{
		if (isset($this->_params[$key])) {
			return $this->_params[$key];
		}

		return $default;
	}

	/**
	 * Set an action parameter
	 *
	 * A $value of null will unset the $key if it exists
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return Zood_Controller_Request_Abstract
	 */
	public function setParam($key, $value)
	{
		$key = (string) $key;

		if ((null === $value) && isset($this->_params[$key])) {
			unset($this->_params[$key]);
		} elseif (null !== $value) {
			$this->_params[$key] = $value;
		}

		return $this;
	}

	/**
	 * Get all action parameters
	 *
	 * @return array
	 */
	public function getParams()
	{
		return $this->_params;
	}

	/**
	 * Set action parameters en masse; does not overwrite
	 *
	 * Null values will unset the associated key.
	 *
	 * @param array $array
	 * @return Zood_Controller_Request_Abstract
	 */
	public function setParams(array $array)
	{
		$this->_params = $this->_params + (array) $array;

		foreach ($this->_params as $key => $value) {
			if (null === $value) {
				unset($this->_params[$key]);
			}
		}

		return $this;
	}
}


// End ^ LF ^ UTF-8
