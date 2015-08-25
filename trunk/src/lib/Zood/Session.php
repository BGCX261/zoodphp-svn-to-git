<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_Session
 * @copyright  Copyright (c) 2005-2010 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Dec 30, 2010
 * @version    SVN: $Id$
 */

/**
 * Session management
 *
 * @category   Zood
 * @package    Zood_Session
 * @copyright  Copyright (c) 2005-2010 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
class Zood_Session
{
    /**
     * Check whether or not the session was started
     *
     * @var bool
     */
    private static $_sessionStarted = false;

    /**
     * Whether or not the session id has been regenerated this request.
     *
     * Id regeneration state
     * <0 - regenerate requested when session is started
     * 0  - do nothing
     * >0 - already called session_regenerate_id()
     *
     * @var int
     */
    private static $_regenerateIdState = 0;

    /**
     * Whether or not session id cookie has been deleted
     *
     * @var bool
     */
    private static $_sessionCookieDeleted = false;

    /**
     * Whether or not session has been destroyed via session_destroy()
     *
     * @var bool
     */
    private static $_destroyed = false;

    /**
     * regenerateId() - Regenerate the session id.  Best practice is to call this after
     * session is started.  If called prior to session starting, session id will be regenerated
     * at start time.
     *
     * @throws Zood_Exception
     * @return void
     */
    public static function regenerateId()
    {
        if (headers_sent($filename, $linenum)) {
            throw new Zood_Exception("You must call " . __CLASS__ . '::' . __FUNCTION__ . "() before any output has been sent to the browser; output started in {$filename}/{$linenum}");
        }

        if (self::$_sessionStarted && self::$_regenerateIdState <= 0) {
            session_regenerate_id(true);
            self::$_regenerateIdState = 1;
        } else {
            self::$_regenerateIdState = - 1;
        }
    }

    /**
     * start() - Start the session.
     *
     * @param bool|array $options  OPTIONAL Either user supplied options, or flag indicating if start initiated automatically
     * @throws Zend_Session_Exception
     * @return void
     */
    public static function start($options = false)
    {
        if (self::$_sessionStarted && self::$_destroyed) {
            throw new Zood_Exception('The session was explicitly destroyed during this request, attempting to re-start is not allowed.');
        }

        if (self::$_sessionStarted) {
            return; // already started
        }

        $filename = $linenum = null;
        if (headers_sent($filename, $linenum)) {
            throw new Zood_Exception("Session must be started before any output has been sent to the browser;" . " output started in {$filename}/{$linenum}");
        }

        // See http://www.php.net/manual/en/ref.session.php for explanation
        if (defined('SID')) {
            throw new Zood_Exception('session has already been started by session.auto-start or session_start()');
        }

        $startedCleanly = session_start();

        self::$_sessionStarted = true;

        if (self::$_regenerateIdState === - 1) {
            self::regenerateId();
        }
    }

    /**
     * destroy() - This is used to destroy session data, and optionally, the session cookie itself
     *
     * @param bool $remove_cookie - OPTIONAL remove session id cookie, defaults to true (remove cookie)
     * @param bool $readonly - OPTIONAL remove write access (i.e. throw error if Zend_Session's attempt writes)
     * @return void
     */
    public static function destroy($remove_cookie = true, $readonly = true)
    {
        if (self::$_destroyed) {
            return;
        }

        session_destroy();
        self::$_destroyed = true;

        if ($remove_cookie) {
            self::expireSessionCookie();
        }
    }

    /**
     * expireSessionCookie() - Sends an expired session id cookie, causing the client to delete the session cookie
     *
     * @return void
     */
    public static function expireSessionCookie()
    {
        if (self::$_sessionCookieDeleted) {
            return;
        }

        self::$_sessionCookieDeleted = true;

        if (isset($_COOKIE[session_name()])) {
            $cookie_params = session_get_cookie_params();

            setcookie(
                session_name(),
                false,
                315554400, // strtotime('1980-01-01'),
                $cookie_params['path'],
                $cookie_params['domain'],
                $cookie_params['secure']
                );
        }
    }
}
