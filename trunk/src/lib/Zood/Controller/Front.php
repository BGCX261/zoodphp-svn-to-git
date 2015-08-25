<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_Controller
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Feb 23, 2009
 * @version    SVN: $Id$
 */


/**
 * Zood_Exception
 */
require_once 'Zood/Exception.php';

/**
 * Front controller
 * 
 * @category   Zood
 * @package    Zood_Controller
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
class Zood_Controller_Front
{
    /**
     * Base URL
     * @var string
     */
    protected $_baseUrl = null;
    /**
     * Directory|ies where controllers are stored
     *
     * @var string|array
     */
    protected $_controllerDir = null;
    
    /**
     * Instance of Zood_Controller_Dispatcher_Interface
     * @var Zood_Controller_Dispatcher_Interface
     */
    protected $_dispatcher = null;
    
    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var Zood_Controller_Front
     */
    protected static $_instance = null;
    
    /**
     * Array of invocation parameters to use when instantiating action
     * controllers
     * @var array
     */
    protected $_invokeParams = array();
    
    /**
     * Subdirectory within a module containing controllers; defaults to 'controllers'
     * @var string
     */
    protected $_moduleControllerDirectoryName = 'controllers';
    
    /**
     * Instance of Zood_Controller_Router_Interface
     * @var Zood_Controller_Router_Interface
     */
    protected $_router = null;
    
    /**
     * Instance of Zend_Controller_Request_Abstract
     * @var Zood_Controller_Request_Abstract
     */
    protected $_request = null;
    
    /**
     * Instance of Zend_Controller_Response_Abstract
     * @var Zood_Controller_Response_Abstract
     */
    protected $_response = null;
    
    /**
     * Singleton instance
     *
     * @return Zood_Controller_Front
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    /**
     * Dispatch an HTTP request to a controller/action.
     *
     * @param Zood_Controller_Request_Abstract|null $request
     * @param Zood_Controller_Response_Abstract|null $response
     * @return void|Zood_Controller_Response_Abstract Returns response object if returnResponse() is true
     */
    public function dispatch(Zood_Controller_Request_Abstract $request = null, Zood_Controller_Response_Abstract $response = null)
    {
        /**
         * Instantiate default request object (HTTP version) if none provided
         */
        if (null !== $request) {
            $this->setRequest($request);
        } elseif ((null === $request) && (null === ($request = $this->getRequest()))) {
            require_once 'Zood/Controller/Request/Http.php';
            $request = new Zood_Controller_Request_Http();
            $this->setRequest($request);
        }
        
        /**
         * Instantiate default response object (HTTP version) if none provided
         */
        if (null !== $response) {
            $this->setResponse($response);
        } elseif ((null === $this->_response) && (null === ($this->_respondispatchse = $this->getResponse()))) {
            require_once 'Zood/Controller/Response/Http.php';
            $response = new Zood_Controller_Response_Http();
            $this->setResponse($response);
        }
        
        /**
         * Initialize dispatcher
         */
        $dispatcher = $this->getDispatcher();
        $dispatcher->setParams($this->getParams())->setResponse($this->_response);
        
        // Begin dispatch
        try {
            $dispatcher->dispatch($this->_request, $this->_response);
        } catch ( Exception $e ) {
            throw $e;
        }
        
        //$this->_response->sendResponse();
    }
    
    /**
     * Set request class/object
     *
     * Set the request object.  The request holds the request environment.
     *
     * If a class name is provided, it will instantiate it
     *
     * @param string|Zood_Controller_Request_Abstract $request
     * @throws Zood_Controller_Exception if invalid request class
     * @return Zood_Controller_Front
     */
    public function setRequest($request)
    {
        if (is_string($request)) {
            Zood_Loader::loadClass($request);
            $request = new $request();
        }
        if (! $request instanceof Zood_Controller_Request_Abstract) {
            throw new Zood_Controller_Exception('Invalid request class');
        }
        
        $this->_request = $request;
        
        return $this;
    }
    
    /**
     * Return the request object.
     *
     * @return null|Zood_Controller_Request_Abstract
     */
    public function getRequest()
    {
        return $this->_request;
    }
    
    /**
     * Set the dispatcher object.  The dispatcher is responsible for
     * taking a Zood_Controller_Dispatcher_Token object, instantiating the controller, and
     * call the action method of the controller.
     *
     * @param Zood_Controller_Dispatcher_Interface $dispatcher
     * @return Zood_Controller_Front
     */
    public function setDispatcher(Zood_Controller_Dispatcher_Interface $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
        return $this;
    }
    
    /**
     * Return the dispatcher object.
     *
     * @return Zood_Controller_Dispatcher_Interface
     */
    public function getDispatcher()
    {
        /**
         * Instantiate the default dispatcher if one was not set.
         */
        if (! $this->_dispatcher instanceof Zood_Controller_Dispatcher_Abstract) {
            require_once 'Zood/Controller/Dispatcher/Standard.php';
            $this->_dispatcher = new Zood_Controller_Dispatcher_Standard();
        }
        return $this->_dispatcher;
    }
    
    /**
     * Set response class/object
     *
     * Set the response object.  The response is a container for action
     * responses and headers. Usage is optional.
     *
     * If a class name is provided, instantiates a response object.
     *
     * @param string|Zood_Controller_Response_Abstract $response
     * @throws Zood_Controller_Exception if invalid response class
     * @return Zood_Controller_Front
     */
    public function setResponse($response)
    {
        if (is_string($response)) {
            Zood_Loader::loadClass($response);
            $response = new $response();
        }
        if (! $response instanceof Zood_Controller_Response_Abstract) {
            throw new Zood_Controller_Exception('Invalid response class');
        }
        
        $this->_response = $response;
        
        return $this;
    }
    
    /**
     * Return the response object.
     *
     * @return null|Zood_Controller_Response_Abstract
     */
    public function getResponse()
    {
        return $this->_response;
    }
    
    /**
     * Set the base URL used for requests
     *
     * Use to set the base URL segment of the REQUEST_URI to use when
     * determining PATH_INFO, etc. Examples:
     * - /admin
     * - /myapp
     * - /subdir/index.php
     *
     * Note that the URL should not include the full URI. Do not use:
     * - http://example.com/admin
     * - http://example.com/myapp
     * - http://example.com/subdir/index.php
     *
     * If a null value is passed, this can be used as well for autodiscovery (default).
     *
     * @param string $base
     * @return Zood_Controller_Front
     * @throws Zood_Exception for non-string $base
     */
    public function setBaseUrl($base = null)
    {
        if (!is_string($base) && (null !== $base)) {
            require_once 'Zood/Exception.php';
            throw new Zood_Exception('Rewrite base must be a string');
        }

        $this->_baseUrl = $base;

        if ((null !== ($request = $this->getRequest())) && (method_exists($request, 'setBaseUrl'))) {
            $request->setBaseUrl($base);
        }

        return $this;
    }

    /**
     * Retrieve the currently set base URL
     *
     * @return string
     */
    public function getBaseUrl()
    {
        $request = $this->getRequest();
        if ((null !== $request) && method_exists($request, 'getBaseUrl')) {
            return $request->getBaseUrl();
        }

        return $this->_baseUrl;
    }
    
    /**
     * Set controller directory
     *
     * Stores controller directory(ies) in dispatcher. May be an array of
     * directories or a string containing a single directory.
     *
     * @param string|array $directory Path to Zend_Controller_Action controller
     * classes or array of such paths
     * @param  string $module Optional module name to use with string $directory
     * @return Zood_Controller_Front
     */
    public function setControllerDirectory($directory, $module = null)
    {
        $this->getDispatcher()->setControllerDirectory($directory, $module);
        return $this;
    }
    
    /**
     * Retrieve controller directory
     *
     * Retrieves:
     * - Array of all controller directories if no $name passed
     * - String path if $name passed and exists as a key in controller directory array
     * - null if $name passed but does not exist in controller directory keys
     *
     * @param  string $name Default null
     * @return array|string|null
     */
    public function getControllerDirectory($name = null)
    {
        return $this->getDispatcher()->getControllerDirectory($name);
    }
    
    /**
     * Add or modify a parameter to use when instantiating an action controller
     *
     * @param string $name
     * @param mixed $value
     * @return Zood_Controller_Front
     */
    public function setParam($name, $value)
    {
        $name = ( string ) $name;
        $this->_invokeParams[$name] = $value;
        return $this;
    }
    
    /**
     * Set parameters to pass to action controller constructors
     *
     * @param array $params
     * @return Zood_Controller_Front
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
        if (isset($this->_invokeParams[$name])) {
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
     * @return Zood_Controller_Front
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
}
