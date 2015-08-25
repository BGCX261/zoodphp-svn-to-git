<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_View
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Sep 12, 2010
 * @version    SVN: $Id$
 */


/** Zood_Exception */
require_once 'Zood/Exception.php';

/** Zood_Controller_Dispatcher_Abstract */
require_once 'Zood/View/Abstract.php';


/**
 * Json View handler
 *
 * @category   Zood
 * @package    Zood_View
 * @copyright  Copyright (c) 2009-2010 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
class Zood_View_Json extends Zood_View_Abstract
{
	/**
	 * Process result data controller action returned
	 * 
	 * @param array $rs
	 * @param Zood_Controller_Action $action
	 */
	public function process(array $rs, Zood_Controller_Action $action)
	{
		echo json_encode($action->getData());
	}
}
?>