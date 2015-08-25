<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_Cache
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Apr 20, 2011
 * @version    SVN: $Id$
 */


/**
 * Zood_Exception
 */
require_once 'Zood/Exception.php';

/**
 * @package    Zood_Cache
 */
class Zood_Cache extends Zend_Cache
{
    /**
     * @var array
     */
    private static $_instances = null;
    
    /**
     * Get instance
     * 
     * @param string $name
     * @param array $config
     * @return Zend_Cache_Core|Zend_Cache_Frontend
     */
    public static function getInstance($name = 'default', $config = NULL)
    {
        if (isset(self::$_instances[$name])) {
            return self::$_instances[$name];
        }
        
        if (! is_null($config)) {
            $config = array_merge(Zood_Config::get('cache/' . $name), $config);
        } else {
            $config = Zood_Config::get('cache/' . $name);
        }
        
        self::$_instances[$name] = self::factory($config['frontend'], $config['backend'], $config['frontendOption'], $config['backendOption']);
        return self::$_instances[$name];
    }
}
