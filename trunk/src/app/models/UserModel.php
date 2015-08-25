<?php

require_once dirname(__FILE__) .'/Table/UserTable.php';

class UserModel
{
    public static function getUserByUserid($userid)
    {
        return UserTable::instance()->getUserByUserid($userid);
    }
    
    public static function getUserByUsername($username)
    {
        return UserTable::instance()->getUserByUsername($username);
    }
}