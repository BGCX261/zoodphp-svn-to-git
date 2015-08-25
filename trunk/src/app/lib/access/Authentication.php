<?php
/**
 * Zood Framework
 *
 * @category   app
 * @package    lib
 * @subpackage access
 * @copyright  Copyright (c) 2009 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Aug 11, 2009
 * @version    SVN: $$Id$$
 */

session_start();

/**
 *
 */
class Authentication implements Zood_Authentication_Interface
{
    protected static $_instance;

    protected $_isLoggedIn;
    protected $_userid;
    protected $_username;
    protected $_user;

    /**
     * Check if the current user is logged in
     * 
     * @return boolean|integer
     */
    public function isLoggedIn()
    {
        if ($this->_isLoggedIn !== null) {
            return $this->_isLoggedIn;
        }

        if (!isset($_SESSION['ZOODSID']) || !$_SESSION['ZOODSID']) {
            return $this->_isLoggedIn = false;
        } else {
            require_once ZOODPP_APP . '/models/SessionModel.php';
            $sessionid = $_SESSION['ZOODSID'];
            $session = SessionModel::getSessionBySessionid($sessionid);
            if ($session) {
                $this->_isLoggedIn = $session['userid'];
                $this->_userid = $session['userid'];
                $this->_username = $session['username'];
            } else {
                $this->_isLoggedIn = false;
            }
            return $this->_isLoggedIn;
        }
    }

    /**
     * Get id of current user
     * 
     * @return integer
     * @see src/lib/Zood/Authentication/Zood_Authentication_Interface#getUserid()
     */
    public function getUserid()
    {
        if ($this->isLoggedIn()) {
            return $this->_userid;
        } else {
            return 0;
        }
    }

    /**
     * Get username of current user
     * 
     * @return string Return null if not logged in
     */
    public function getUsername()
    {
        if ($this->isLoggedIn()) {
            return $this->_username;
        } else {
            return null;
        }
    }

    /**
     * Get detail information of current user
     * 
     * @return array Return null if not logged in
     * @see src/lib/Zood/Authentication/Zood_Authentication_Interface#getUser()
     */
    public function getUser() {
        if ($this->_user !== null) {
            return $this->_user;
        }
        if ($this->isLoggedIn()) {
            $userid = $this->_userid;
            require_once ZOODPP_APP . '/models/UserModel.php';
            return $this->_user = UserModel::getUserByUserid($userid);
        } else {
            return null;
        }
    }

    /**
     * Singleton
     * 
     * @return Authentication
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Authentication();
        }

        return self::$_instance;
    }
}
