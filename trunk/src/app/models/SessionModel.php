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

require_once dirname(__FILE__) .'/Table/SessionTable.php';

class SessionModel
{
    public static function addSession(array $data)
    {
        if (empty($data['ctime'])) {
            $data['ctime'] = date('Y-m-d H:i:s');
        }
        if (empty($data['active_time'])) {
            $data['active_time'] = date('Y-m-d H:i:s');
        }
        return SessionTable::instance()->addSession($data);
    }

    public static function getSessionBySessionid($sessionid)
    {
        return SessionTable::instance()->getSessionBySessionid($sessionid);
    }

    public static function updateSession(array $data, $sessionid)
    {
        if (empty($data['active_time'])) {
            $data['active_time'] = date('Y-m-d H:i:s');
        }
        return SessionTable::instance()->updateSession($data, $sessionid);
    }
    
    public static function deleteSession($sessionid)
    {
        return SessionTable::instance()->deleteSession($sessionid);
    }
}
