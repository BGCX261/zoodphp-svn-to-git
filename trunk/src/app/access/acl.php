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
$ACL_SCHEMA['Login'] = array('1');

/**
 * 模块访问权限
 */
$ACL['Login'] = array();

/**
 * 忽略权限判断的资源
 */
$ACL_IGNORE['Login'] = array('Login');

/**
 * 登录即可访问的资源
 */
$ACL_LOGIN['Login'] = array();

