<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Sep 12, 2010
 * @version    SVN: $Id$
 */

/**
 * A special null
 *
 * @category   Zood
 * @package    Zood_Entity
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
final class Zood_Entity_Null
{
    /**
     * Singleton: use getInstance() to get an instance
     */
    private function __construct()
    {
        //do nothing
    }
    
    /**
     * Instance
     * @var Zood_Entity_Null
     */
    private static $_instance;
    
    /**
     * Singleton: get an instance
     * 
     * @return Zood_Entity_Null
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    /**
     * Return empty string
     * 
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}