<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_Controller
 * @copyright  Copyright (c) 2009 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Sep 5, 2009
 * @version    SVN: $$Id$$
 */


abstract class Zood_Controller_Action_Interceptor_Abstract
{
    /**
     * 
     * @var Zood_Controller_Action
     */
    protected $_action;

    /**
     * Set Action
     *
     * @param Zood_Controller_Action $action
     */
    public function setAction(Zood_Controller_Action $action)
    {
        $this->_action = $action;
    }
    
    /**
     * Get Action
     * @return Zood_Controller_Action
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * Before action runs
     */
    public function before() {}

    /**
     * After action run
     */
    public function after(){}
}
