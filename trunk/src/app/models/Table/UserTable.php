<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood
 * @copyright  Copyright (c) 2009 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Sep 13, 2009
 * @version    SVN: $$Id$$
 */


Zood_Loader::loadClass('Zood_Db_Table_Abstract');

class UserTable extends Zood_Db_Table_Abstract
{
    /** Table name */
    protected $_name    = 'user';

    protected $_primary = 'userid';

    /**
     * Get a single user by userid
     * @param $userid
     * @return Array
     */
    public function getUserByUserid($userid)
    {
        $select = $this->select()->where('userid = ?', $userid)->order('userid');
        $row = $this->fetchRow($select);

        return is_null($row) ? NULL : $row->toArray();
    }

    /**
     * Get a single user by username
     * @param $userid
     * @return Array
     */
    public function getUserByUsername($username)
    {
        $select = $this->select()->where('username = ?', $username)->order('userid');
        $row = $this->fetchRow($select);

        return is_null($row) ? NULL : $row->toArray();
    }
    
    /**
     * Singleton
     * 
     * @return UserTable
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
