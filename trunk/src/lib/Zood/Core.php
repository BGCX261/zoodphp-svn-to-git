<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Feb 23, 2009
 * @version    SVN: $Id$
 */


/**
 * Generate URL
 * 
 * @param $module
 * @param $controller
 * @param $action
 * @param $vars
 * @return String
 */
function zood_url($module='index',$controller='index',$action='index',$vars=null)
{
	return 'index.php?m='.$module.'&c='.$controller.'&a='.$action.$vars;
}