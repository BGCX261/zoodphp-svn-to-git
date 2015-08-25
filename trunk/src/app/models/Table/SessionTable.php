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

class SessionTable extends Zood_Db_Table_Abstract
{
    /** Table name */
    protected $_name    = 'session';

    /** Primary */
    protected $_primary = 'sessionid';

    public function addSession(array $data)
    {
        return $this->insert($data);
    }
    /**
     * Get a single session by sessionid
     * @param $userid
     * @return Array
     */
    public function getSessionBySessionid($sessionid)
    {
        $select = $this->select()->where('sessionid = ?', $sessionid)->order('sessionid');
        $row = $this->fetchRow($select);

        return is_null($row) ? NULL : $row->toArray();
    }

    public function updateSession(array $data, $sessionid)
    {
        $where = $this->getAdapter()->quoteInto('sessionid = ?', $sessionid);

        return $this->update($data, $where);
    }
    
    public function deleteSession($sessionid)
    {
        $where = $this->getAdapter()->quoteInto('sessionid = ?', $sessionid);
        return $this->delete($where);
    }

    /**
     * Singleton
     *
     * @return SessionTable
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
