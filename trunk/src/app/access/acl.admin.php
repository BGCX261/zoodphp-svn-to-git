<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood
 * @copyright  Copyright (c) 2009 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Sep 17, 2009
 * @version    SVN: $$Id$$
 */


/**
 * 定义使用的权限域
 */
$ACL_SCHEMA['Admin'] = array('1');

/**
 * 模块访问权限
 */
$ACL['Admin'] = array('1');

/**
 * 忽略权限判断的资源
 */
$ACL_IGNORE['Admin'] = array('Admin_Login');

/**
 * 登录即可访问的资源
 */
$ACL_LOGIN['Admin'] = array('Admin_Test_Index');

/**
 * 详细权限
 */
$ACL['Admin_Index'] = array('2');
$ACL['Admin_Load_Index'] = array('2');
