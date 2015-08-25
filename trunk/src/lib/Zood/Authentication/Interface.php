<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_Authentication
 * @copyright  Copyright (c) 2009 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Aug 11, 2009
 * @version    SVN: $$Id$$
 */


/**
 * @category   Zend
 * @package    Zend_Authentication
 * @copyright  Copyright (c) 2009 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
interface Zood_Authentication_Interface
{
    /**
     * Check if current user is logged in or not
     *
     * @return Boolean
     */
    public function isLoggedIn();

    /**
     * Get information of user logged in
     *
     * @return Array
     */
    public function getUser();

    /**
     * Get ID of user logged in
     *
     * @return Integer
     */
    public function getUserid();
}