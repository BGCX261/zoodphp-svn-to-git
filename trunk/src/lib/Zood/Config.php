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
 * Zood_Exception
 */
require_once 'Zood/Exception.php';

/**
 * Zood_Util
 */
require_once 'Zood/Util.php';

/**
 * Class for get configuration.
 *
 * @category   Zood
 * @package    Zood
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
class Zood_Config
{
    /**
     * @var String
     */
    protected static $_configDirectory = array();

    /**
     * @var array
     */
    protected static $_config = null;

    /**
     * Get configuration
     *
     * @param   mixed   $index          The key of configuration array
     * @param   String  $config_file    Configuration file
     * @return  mixed
     */
    public static function get($index, $config_file = null)
    {
        if (strstr($index, '/')) {
            $hierarchical = explode('/', $index);
            $indexgroup = array_shift($hierarchical);
        } else {
            $indexgroup = $index;
        }
        
        if (! is_array(self::$_config) || ! array_key_exists($indexgroup, self::$_config)) {
            try {
                self::_loadConfigFile($indexgroup, $config_file);
            } catch ( Zood_Exception $e ) {
                return self::$_config[$index] = null;
            }
        }
        
        $return = self::$_config[$indexgroup];
        if (isset($hierarchical) && count($hierarchical) > 0) {
            foreach ($hierarchical as $h) {
                if (isset($return[$h])) {
                    $return = $return[$h];
                } else {
                    return null;
                }
            }
        }
        
        return $return;
    }

    /**
     * Add dir of configuration files
     *
     * @param $dir
     */
    public static function addConfigDirectory($dir)
    {
        self::$_configDirectory[] = $dir;
    }

    /**
     * Get dir of configuration files
     */
    public static function getConfigDirectory()
    {
        if (empty(self::$_configDirectory) && defined('ZOODPP_APP')) {
            $defaultDir = ZOODPP_APP . '/config';
            if (is_dir($defaultDir)) {
                self::addConfigDirectory($defaultDir);
            }
        }
        return self::$_configDirectory;
    }

    /**
     * Load configuration file
     *
     * @param   mixed   $index          The key of configuration array
     * @param   String  $config_file    Configuration file
     * @throws  Zood_Exception
     */
    private static function _loadConfigFile($index, $config_file = null)
    {
        //Get config file
        if ($config_file == null) {
            $configDirectory = self::getConfigDirectory();
            if (count($configDirectory) > 0) {
                foreach ($configDirectory as $cd) {
                    if (is_file($cd . '/config.' . $index . '.php')) {
                        $config_file = $cd . '/config.' . $index . '.php';
                        break;
                    }
                }
                if ($config_file == null) {
                    throw new Zood_Exception('Config file not found.');
                }
            } else {
                throw new Zood_Exception('Config file dir not set.');
            }
        }

        //load config file
        if (is_readable($config_file)) {
            $CONF = include $config_file;
            self::$_config[$index] = $CONF;
        } else {
            throw new Zood_Exception('Could not load config file: ' . $config_file . '.');
        }
    }
}
