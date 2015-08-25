<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_Db
 * @copyright  Copyright (c) 2009 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Feb 23, 2009
 * @version    SVN: $Id$
 */


/**
 * Zend_Db_Table_Abstract
 */
require_once 'Zend/Db/Table/Abstract.php';

/**
 * Class for SQL table interface.
 *
 * @category   Zood
 * @package    Zood_Db
 * @subpackage Table
 * @copyright  Copyright (c) 9 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
abstract class Zood_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
    /**
     * Instances of sub-class
     * @var array
     */
    protected static $_instances = array();
    
    /**
     * Constructor.
     *
     * Supported params for $config are:
     * - db              = user-supplied instance of database connector,
     *                     or key name of registry instance.
     * - name            = table name.
     * - primary         = string or array of primary key(s).
     * - rowClass        = row class name.
     * - rowsetClass     = rowset class name.
     * - referenceMap    = array structure to declare relationship
     *                     to parent tables.
     * - dependentTables = array of child tables.
     * - metadataCache   = cache for information from adapter describeTable().
     *
     * @param  mixed $config Array of user-specified config options, or just the Db Adapter.
     * @return void
     */
    public function __construct($config = array())
    {
        if (is_null(self::getDefaultAdapter())) {
            $config_db = Zood_Config::get('db');
            $config_db_option = $config_db['default'];
            $dbAdapter = Zend_Db::factory($config_db_option['adapter'],$config_db_option);
            self::setDefaultAdapter($dbAdapter);
        }
        
        parent::__construct($config);
    }
    
    /**
     * Get the table name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Counts rows
     * 
     * @param string|array
     * @return integer
     */
    public function countRows($where = null)
    {
        $select = $this->select()->from($this->getName(), array('count_num' => "COUNT(*)"));
        if ($where !== null) {
            $this->_where($select, $where);
        }
        $rows = $select->query()->fetchAll();
        return $rows[0]['count_num'];
    }
    
    /**
     * Singleton
     * 
     * @param string
     * @return Zood_Db_Table_Abstract
     */
    protected static function _getInstance($tablename)
    {
        if (isset(self::$_instances[$tablename]) && is_subclass_of(self::$_instances[$tablename], __CLASS__)) {
            return self::$_instances[$tablename];
        } else {
            self::$_instances[$tablename] = new $tablename();
            return self::$_instances[$tablename];
        }
    }
}
