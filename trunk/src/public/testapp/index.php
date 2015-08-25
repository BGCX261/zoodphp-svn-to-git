<?php
/**
 * This is an Example Bootstrap
 */

/**
 * Boot start time
 * Just for test
 * @var Float
 */
$bootTime = microtime(true);

error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('Asia/Shanghai');

define('ZOODPP_ROOT', realpath(dirname(__FILE__). '/../..')); //Root dir of project
define('ZOODPP_APP', ZOODPP_ROOT . '/app'); //Dir of applications

/**
 * Add framework dir to php include path
 */
set_include_path(ZOODPP_ROOT . '/lib' . PATH_SEPARATOR . get_include_path());

require_once 'Zood/Loader.php';

/**
 * Register auto load
 */
Zood_Loader::registerAutoload();
//require_once 'Zend/Registry.php';
//require_once 'Zood/Util.php';
//require_once 'Zood/Controller/Front.php';

/**
 * Set default configuration dir
 */
//Zood_Loader::loadClass('Zood_Config');
Zood_Config::getInstance()->addConfigDirectory(ZOODPP_APP . '/config');

/**
 * Set default db adapter
 */
//Zood_Loader::loadClass('Zend_Db');
//Zood_Loader::loadClass('Zood_Db_Table_Abstract');
$config_db = Zood_Config::getInstance()->get('db');
$config_db_option = $config_db['master'];
unset($config_db_option['adapter'],$config_db_option['prefix'],$config_db_option['charset'],$config_db_option['showdebug']);
$dbAdapter = Zend_Db::factory($config_db['master']['adapter'],$config_db_option);
$dbAdapter->query('SET NAMES "UTF8";');
Zood_Db_Table_Abstract::setDefaultAdapter($dbAdapter);

//Access authentication
require_once ZOODPP_ROOT . '/app/lib/access/ActionAccessInterceptor.php';
Zood_Controller_Action::addInterceptor(new ActionAccessInterceptor(), 'access');

/**
 * Get front controller instance, set controller dir and dispatch
 */
try {
	Zood_Controller_Front::getInstance()->setBaseUrl()->setControllerDirectory(ZOODPP_APP . '/controllers')->dispatch();
} catch(Exception $e)
{
	Zood_Util::print_r($e->getMessage(),'Exception!');
}

/**
 * Execution end time
 * @var Float
 */
$endTime = microtime(true);
Zood_Util::print_r($endTime-$bootTime, 'Full execution time(sec)');
Zood_Util::print_r(memory_get_usage(),'memory_get_usage');
Zood_Util::print_r($rf = get_required_files(), 'All included files (' . count($rf) . ')');
// End ^ LF ^ UTF-8
