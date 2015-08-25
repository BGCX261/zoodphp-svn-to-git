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
 * Controller Action
 *
 * @category   Zood
 * @package    Zood_Controller
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @todo Add access interceptor
 */
abstract class Zood_Controller_Action
{
    const RESULT_INPUT   = 'input';
    const RESULT_SUCCESS = 'success';

    /**
     * Result 配置信息
     *
     * @var $_result
     */
    protected $_result;

    /**
     * 待处理的数据
     *
     * @var Array
     */
    private $_data;

    /**
     * Front controller instance
     * @var Zood_Controller_Front
     */
    protected $_frontController;

    /**
     * Zood_Controller_Request_Abstract object wrapping the request environment
     * @var Zood_Controller_Request_Abstract
     */
    protected $_request = null;

    /**
     * Zood_Controller_Response_Abstract object wrapping the response
     * @var Zood_Controller_Response_Abstract
     */
    protected $_response = null;

    protected $_classMethods = null;

    /**
     * Skip Session Start
     * @var boolean
     */
    protected $_skipSessionStart = false;

    /**
     * Constructor
     * 
     * @param Zood_Controller_Request_Abstract $request
     * @param Zood_Controller_Response_Abstract $response
     * @param array $invokeArgs Any additional invocation arguments
     * @return void
     */
    public function __construct($request, $response, $invokeArgs = array())
    {
        $this->setRequest($request)->setResponse($response);
        
        if (!$this->_skipSessionStart) {
            Zood_Session::start();
        }
    }

    /**
     * Proxy for undefined methods.  Default behavior is to throw an
     * exception on undefined methods, however this function can be
     * overridden to implement magic (dynamic) actions, or provide run-time
     * dispatching.
     *
     * @param  string $methodName
     * @param  array $args
     * @return void
     * @throws Zood_Controller_Action_Exception
     */
    public function __call($methodName, $args)
    {
        require_once 'Zood/Exception.php';

        throw new Zood_Exception(sprintf('Method "%s" does not exist and was not trapped in __call()', $methodName), 404);
    }

    /**
     * Dispatch the requested action
     *
     * @param string $action Method name of action
     * @return void
     */
    public function run($action)
    {
        if (null === $this->_classMethods) {
            $this->_classMethods = get_class_methods($this);
        }

        if (in_array($action, $this->_classMethods)) {
            return $this->$action();
        } else {
            return $this->__call($action, array());
        }
    }

    /**
     * Call the action specified in the request object, and return a response
     *
     * Not used in the Action Controller implementation, but left for usage in
     * Page Controller implementations. Dispatches a method based on the
     * request.
     *
     * Returns a Zood_Controller_Response_Abstract object, instantiating one
     * prior to execution if none exists in the controller.
     *
     * @param null|Zood_Controller_Request_Abstract $request Optional request
     * object to use
     * @param null|Zood_Controller_Response_Abstract $response Optional response
     * object to use
     * @return Zood_Controller_Response_Abstract
     */
    public function dispatch(Zood_Controller_Request_Abstract $request = null, Zood_Controller_Response_Abstract $response = null)
    {
        if (null !== $request) {
            $this->setRequest($request);
        } else {
            $request = $this->getRequest();
        }

        if (null !== $response) {
            $this->setResponse($response);
        }

        $action = $request->getActionName();
        if (empty($action)) {
            $action = 'indexAction';
        } else {
            $action .= 'Action';
        }

        if ( $ifRunInterceptors = (is_array(self::$_interceptors) && count(self::$_interceptors) > 0) ) {
            foreach (self::$_interceptors as $interceptor) {
                $interceptor->setAction($this);
                $interceptor->before();
            }
        }
        $result = $this->run($action);
        if ($ifRunInterceptors) {
            foreach (self::$_interceptors as $interceptor) {
                $interceptor->after();
            }
        }

        if (null !== $result) {
            $this->_view($result);
        }

        return $this->getResponse();
    }

    /**
     * Generate View
     *
     * @param $result
     * @return null
     */
    protected function _view($result)
    {
        $rs = $this->getResult($result);

        $rsType = ucfirst($rs['type']);
        require_once "Zood/View/" . $rsType . ".php";
        $viewClass = "Zood_View_" . $rsType;

        $view = new $viewClass();
        $view->process($rs,$this);
    }

    /**
     * Return the Request object
     *
     * @return Zood_Controller_Request_Abstract
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Set the Request object
     *
     * @param Zood_Controller_Request_Abstract $request
     * @return Zood_Controller_Action
     */
    public function setRequest(Zood_Controller_Request_Abstract $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     * Return the Response object
     *
     * @return Zood_Controller_Response_Abstract
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * Set the Response object
     *
     * @param Zood_Controller_Response_Abstract $response
     * @return Zood_Controller_Action
     */
    public function setResponse(Zood_Controller_Response_Abstract $response)
    {
        $this->_response = $response;
        return $this;
    }

    /**
     * 给 $_data 赋
     *
     * @param Array|String|Mix $data
     * @return Void
     */
    public function setData($dataOrKey,$value = null)
    {
        $data = $dataOrKey;
        if (is_array($data)) {
            $this->_data = (array)$this->_data + $data;
        } else if (is_string($data))
        {
            $this->_data[$data] = $value;
        }

        return $this;
    }

    /**
     * 获取 $_data 值
     *
     * @return Array
     */
    public function getData($key = null, $default = null)
    {
        if (null !== $key) {
            $key = (string) $key;
            if (isset($this->_data[$key])) {
                return $this->_data[$key];
            } else {
                return $default;
            }
        } else {
            return $this->_data;
        }
    }

    /**
     * 获取ResultType的配置信息
     *
     * @return Array
     */
    public function getResult($key = null, $default = null)
    {
        if (null !== $key) {
            $key = (string) $key;
            if (isset($this->_result[$key])) {
                return $this->_result[$key];
            } else {
                return $default;
            }
        } else {
            return $this->_result;
        }
    }

    /**
     * 为 Action 添加 Resul
     *
     * @param String $result     返回字串
     * @param String $resultType ResultType 类型
     * @param String $resource   资源文件
     */
    public function addResult($result, $resultType = null, $resource = null)
    {
        $_tmp = array();
        if(!is_null($resultType)){
            $_tmp['type'] = $resultType;
            if(!is_null($resource)){
                $_tmp['resource'] = $resource;
            }
        }
        $result = strtolower($result);
        $this->_result[$result] = $_tmp;
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
        return $this->getRequest()->getParam($key,$default);
    }

    /**
     * Retrieve only user params (i.e, any param specific to the object and not the environment)
     *
     * @return array
     */
    public function getUserParams()
    {
        return $this->getRequest()->getUserParams();
    }

    /**
     *
     * @var Array
     */
    protected static $_interceptors;

    public static function addInterceptor($interceptor,$key = NULL)
    {
        if (is_null($key)) {
            self::$_interceptors[] = $interceptor;
        } else {
            self::$_interceptors[$key] = $interceptor;
        }
    }

    public static function removeInterceptor($key)
    {
        if (isset(self::$_interceptors[$key])) {
            unset(self::$_interceptors[$key]);
        }
    }

    public static function clearInterceptors()
    {
        self::$_interceptors = NULL;
    }

    public static function getInterceptors()
    {
        return self::$_interceptors;
    }
}


// End ^ LF ^ UTF-8
