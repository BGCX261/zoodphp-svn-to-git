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
 * @category   Zood
 * @package    Zood
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
class Zood_Util
{
    /**
     * 获取密码杂质，可用于密码储存算法
     *
     * @param integer $length
     * @return string
     */
    public static function generateSalt($length = 4)
    {
        $salt = '';
        for ($i = 0; $i < $length; $i ++) {
            $salt .= chr(rand(33, 126));
        }
        return $salt;
    }

    /**
     * 创建多级目录,如果某级目录不存在则依次创建
     *
     * @param string $path
     * @param string $mod
     */
    public static function mkpath($path, $mode = 0777)
    {
        umask(0);
        $path = str_replace("\\", "|", $path);
        $path = str_replace("/", "|", $path);
        $path = str_replace("||", "|", $path);
        $dirs = explode("|", $path);
        $path = $dirs[0];
        $is_ok = true;
        for($i = 1; $i < count($dirs); $i ++) {
            $path .= "/" . $dirs[$i];
            if (@! is_dir($path)) {
                if (@! mkdir($path, $mode)) {
                    $is_ok = false;
                }
            }
        }
        if ($is_ok) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 彩色Print_r函数
     *
     * @param Array|String|Mixed $var
     * @param String $memo
     */
    public static function print_r($var, $memo = null)
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            if (! is_null($memo)) {
                $prefix = "<FIELDSET style=\"font-size:12px;font-family:Courier New;\"><LEGEND>" . $memo . "</LEGEND>";
                $postfix = "</FIELDSET>";
            } else {
                $prefix = "";
                $postfix = "";
            }
            $color_bg = "RGB(" . rand(100, 255) . "," . rand(100, 255) . "," . rand(100, 255) . ")";
            echo $prefix . "<pre style=\"font-size:12px;padding:5px;border-bottom:5px solid #0066cc;font-family:Courier New,Simsun;color:black;line-height:130%;text-align:left;background-color:" . $color_bg . "\">";
            print_r($var);
            echo "</pre>" . $postfix;
        } else {
            if (! is_null($memo)) {
                echo $memo . " - - - - -\n";
            }
            print_r($var);
            echo "\n";
        }
    }
    
    /**
     * Get URL of referer page
     *
     * @return String |null
     */
    public static function getReferer()
    {
        if ($_SERVER['HTTP_REFERER']) {
            $returnUrl = $_SERVER['HTTP_REFERER'];
        } elseif ($_SERVER['REDIRECT_URL'] != '') {
            $returnUrl = $_SERVER['REDIRECT_URL'];
        } else {
            $returnUrl = null;
        }
        return $returnUrl;
    }
    
    /**
     * Setcookie 加强版
     *
     * @param String $name
     * @param String $ |Integer $value
     * @param Intefer $timeout 小甜饼超时时间,单位为秒
     */
    public static function setcookie($name, $value = "", $timeout = 0)
    {
        $expire = time() + $timeout;
        if ($_SERVER['SERVER_PORT'] == "443") { // Using SSL
            $secure = 1;
        } else {
            $secure = 0;
        }
        setcookie($name, $value, $expire, '/', '', $secure);
    }
    
    /**
     *
     * @return String (IP)
     */
    public static function clientIP()
    {
        $addrs = array();
        
        if (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            foreach (array_reverse(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])) as $x_f) {
                $x_f = trim($x_f);
                
                if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $x_f)) {
                    $addrs[] = $x_f;
                }
            }
            
            $addrs[] = $_SERVER['HTTP_CLIENT_IP'];
            $addrs[] = $_SERVER['HTTP_PROXY_USER'];
        }
        
        $addrs[] = $_SERVER['REMOTE_ADDR'];
        // -----------------------------------------
        // Do we have one yet?
        // -----------------------------------------
        foreach ($addrs as $ip) {
            if ($ip) {
                $match = array();
                preg_match('/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/', $ip, $match);
                
                $ip_address = $match[1] . '.' . $match[2] . '.' . $match[3] . '.' . $match[4];
                
                if ($ip_address and $ip_address != '...') {
                    return $ip_address;
                }
            }
        }
        
        return null;
    }

    /**
     * 获取两个时间的时间差,传入参数若是datetime格式
     * 会转成timestamp再进行比较
     *
     * 返回数组,格式如下:
     * Array
     *  (
     *      [day] => 25
     *      [hour] => 2
     *      [min] => 21
     *      [sec] => 39
     *  )
     *
     * @param DateTime $from 较早的时间
     * @param DateTime $to 较新的时间
     * @return Array
     */
    public static function datediff($from, $to = null)
    {
        $from = strtotime($from);
        if ($to == null)
            $to = time();
        else
            $to = strtotime($to);
        $diff = $to - $from;
        
        $since['day'] = bcdiv($diff, 86400);
        $over = $diff % 86400;
        $since['hour'] = bcdiv($over, 3600);
        $over = $over % 3600;
        $since['min'] = bcdiv($over, 60);
        $since['sec'] = $over % 60;
        return $since;
    }
}