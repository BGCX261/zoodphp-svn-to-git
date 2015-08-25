<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_View
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Feb 23, 2009
 * @version    SVN: $Id$
 */


/** Zood_Exception */
require_once 'Zood/Exception.php';

/** Zood_Controller_Dispatcher_Abstract */
require_once 'Zood/View/Abstract.php';


/**
 * Standard View handler -- php script
 *
 * @category   Zood
 * @package    Zood_View
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
class Zood_View_Php extends Zood_View_Abstract
{
	/**
	 * Process result data controller action returned
	 * 
	 * @param array $rs
	 * @param Zood_Controller_Action $action
	 */
	public function process(array $rs, Zood_Controller_Action $action)
	{
		$this->setData($action->getData());

		$scriptPath = $this->getScriptPath();
		if (empty($scriptPath)) {
			/** Zood_Config */
			require_once 'Zood/Config.php';
			$viewConfig = Zood_Config::get('view');
			if (isset($viewConfig['script_path'])) {
				$scriptPath = $viewConfig['script_path'];
				$this->setScriptPath($scriptPath);
			}
		}

		$script = $this->getScript($rs['resource']);
		$this->render($script);
	}
	
	/**
	 * Render spicified script
	 * 
	 * @param $script
	 */
	protected function render($script)
	{
		include $script;
	}
}
?>