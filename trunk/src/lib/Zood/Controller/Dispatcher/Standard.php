<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_Controller_Dispatcher
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Feb 23, 2009
 * @version    SVN: $Id$
 */

/** Zood_Exception */
require_once 'Zood/Exception.php';

/** Zood_Controller_Dispatcher_Abstract */
require_once 'Zood/Controller/Dispatcher/Abstract.php';

/**
 * Standard Dispatcher
 *
 * @category   Zood
 * @package    Zood_Controller_Dispatcher
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
class Zood_Controller_Dispatcher_Standard extends Zood_Controller_Dispatcher_Abstract
{
	/**
	 * Default action
	 * @var string
	 */
	protected $_defaultAction = 'index';

	/**
	 * Default controller
	 * @var string
	 */
	protected $_defaultController = 'index';

	/**
	 * Default module
	 * @var string
	 */
	protected $_defaultModule = 'index';

	/**
	 * Front Controller instance
	 * @var Zood_Controller_Front
	 */
	protected $_frontController;

	/**
	 *
	 */
	protected $_controllerDirectory;

	/**
	 * Array of invocation parameters to use when instantiating action
	 * controllers
	 * @var array
	 */
	protected $_invokeParams = array();



	/**
	 * Dispatch to a controller/action
	 *
	 * By default, if a controller is not dispatchable, dispatch() will throw
	 * an exception. If you wish to use the default controller instead, set the
	 * param 'useDefaultControllerAlways' via {@link setParam()}.
	 *
	 * @param Zood_Controller_Request_Abstract $request
	 * @param Zood_Controller_Response_Abstract $response
	 * @return void
	 * @throws Zood_Exception
	 */
	public function dispatch($request, $response)
	{
        $this->setResponse($response);
        
		$controllerClass = $this->loadControllerClass($request);
		$controller = new $controllerClass($request, $this->getResponse(), $this->getParams());
		$controller->dispatch($request,$response);
	}
	
	/**
	 * Load a controller class
	 *
	 * Attempts to load the controller class file from
	 * {@link getControllerDirectory()}.  If the controller belongs to a
	 * module, looks for the module prefix to the controller class.
	 *
	 * @param Zood_Controller_Request_Abstract $request
	 * @return string Class name loaded
	 * @throws Zood_Exception if class not loaded
	 */
	public function loadControllerClass(Zood_Controller_Request_Abstract $request)
	{
		$moduleName = $request->getModuleName();
		$controllerName = $request->getControllerName();
		if (empty($moduleName)) {
			$moduleName = $this->_defaultModule;
		}
		if (empty($controllerName)) {
			$controllerName = $this->_defaultController;
		}

		$finalClassName = ucfirst($controllerName).'Controller';
		
		if (class_exists($finalClassName, false)) {
			return $finalClassName;
		}

		$loadFile = $this->_controllerDirectory.'/'.$moduleName.'/'.ucfirst($controllerName).'Controller.php';

		if (!@include_once $loadFile) {
			require_once 'Zood/Exception.php';
			throw new Zood_Exception('Cannot load controller class "' . $finalClassName . '" from file "' . $loadFile . "'", 404);
		}

		if (!class_exists($finalClassName, false)) {
		    $finalClassName = ucfirst($moduleName) . '_' . ucfirst($controllerName) . 'Controller';
            if (!class_exists($finalClassName, false)) {
    			require_once 'Zood/Exception.php';
    			throw new Zood_Exception('Invalid controller class ("' . $finalClassName . '")', 404);
		    }
		}

		return $finalClassName;
	}


	/**
	 * Set controller directory
	 *
	 * @param array|string $directory
	 * @return Zend_Controller_Dispatcher_Standard
	 */
	public function setControllerDirectory($directory)
	{
		if (is_string($directory)) {
			$this->_controllerDirectory = $directory;
		} else {
			require_once 'Zood/Exception.php';
			throw new Zood_Exception('Controller directory spec must be a string');
		}

		return $this;
	}

	/**
	 * Return the currently set directories for Zend_Controller_Action class
	 * lookup
	 *
	 * If a module is specified, returns just that directory.
	 *
	 * @param  string $module Module name
	 * @return array|string Returns array of all directories by default, single
	 * module directory if module argument provided
	 */
	public function getControllerDirectory($module = null)
	{
		return $this->_controllerDirectory;
	}

	/**
	 * Add or modify a parameter to use when instantiating an action controller
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return Zood_Controller_Dispatcher_Abstract
	 */
	public function setParam($name, $value)
	{
		$name = (string) $name;
		$this->_invokeParams[$name] = $value;
		return $this;
	}

	/**
	 * Set parameters to pass to action controller constructors
	 *
	 * @param array $params
	 * @return Zood_Controller_Dispatcher_Abstract
	 */
	public function setParams(array $params)
	{
		$this->_invokeParams = array_merge($this->_invokeParams, $params);
		return $this;
	}

	/**
	 * Retrieve a single parameter from the controller parameter stack
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getParam($name)
	{
		if(isset($this->_invokeParams[$name])) {
			return $this->_invokeParams[$name];
		}

		return null;
	}

	/**
	 * Retrieve action controller instantiation parameters
	 *
	 * @return array
	 */
	public function getParams()
	{
		return $this->_invokeParams;
	}

	/**
	 * Clear the controller parameter stack
	 *
	 * By default, clears all parameters. If a parameter name is given, clears
	 * only that parameter; if an array of parameter names is provided, clears
	 * each.
	 *
	 * @param null|string|array single key or array of keys for params to clear
	 * @return Zood_Controller_Dispatcher_Abstract
	 */
	public function clearParams($name = null)
	{
		if (null === $name) {
			$this->_invokeParams = array();
		} elseif (is_string($name) && isset($this->_invokeParams[$name])) {
			unset($this->_invokeParams[$name]);
		} elseif (is_array($name)) {
			foreach ($name as $key) {
				if (is_string($key) && isset($this->_invokeParams[$key])) {
					unset($this->_invokeParams[$key]);
				}
			}
		}

		return $this;
	}

	/**
	 * Set response object to pass to action controllers
	 *
	 * @param Zood_Controller_Response_Abstract|null $response
	 * @return Zood_Controller_Dispatcher_Abstract
	 */
	public function setResponse(Zood_Controller_Response_Abstract $response = null)
	{
		$this->_response = $response;
		return $this;
	}

	/**
	 * Return the registered response object
	 *
	 * @return Zood_Controller_Response_Abstract|null
	 */
	public function getResponse()
	{
		return $this->_response;
	}

}

// End ^ LF ^ UTF-8
