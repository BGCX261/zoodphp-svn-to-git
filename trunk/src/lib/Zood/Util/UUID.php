<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_Util
 * @copyright  Copyright (c) 2009 ZoodPHP Org. (http://zoodphp.touchvalue.com)
 * @since      Dec 10, 2014
 * @version    SVN: $Id$
 */

class Zood_Util_UUID
{
    /**
     * 获取（伪随机生成的）UUID
     *
     * @return      RFC 4122 UUID
     * @see http://www.ietf.org/rfc/rfc4122.txt
     */
    public static function generate()
    {
        $node = isset($_ENV['SERVER_ADDR']) ? $_ENV['SERVER_ADDR'] : null;
        $pid = null;

        if (false !== strpos($node, ':')) {
            if (substr_count($node, '::')) {
                $node = str_replace('::', str_repeat(':0000', 8 - substr_count($node, ':')) . ':', $node);
            }
            $node = explode(':', $node);
            $ipv6 = '';

            foreach ($node as $id) {
                $ipv6 .= str_pad(base_convert($id, 16, 2), 16, 0, STR_PAD_LEFT);
            }

            $node = base_convert($ipv6, 2, 10);

            if (strlen($node) < 38) {
                $node = null;
            } else {
                $node = crc32($node);
            }
        } elseif (empty($node)) {
            $host = isset($_ENV['HOSTNAME']) ? $_ENV['SERVER_ADDR'] : null;

            if (empty($host)) {
                $host = isset($_ENV['HOST']) ? $_ENV['HOST'] : null;
            }

            if (! empty($host)) {
                $ip = gethostbyname($host);

                if ($ip === $host) {
                    $node = crc32($host);
                } else {
                    $node = ip2long($ip);
                }
            }
        } elseif ($node !== '127.0.0.1') {
            $node = ip2long($node);
        } else {
            $node = null;
        }

        if (empty($node)) {
            $salt = '1234567890abcdefg';
            $node = crc32($salt);
        }

        if (function_exists('zend_thread_id')) {
            $pid = zend_thread_id();
        } else {
            $pid = getmypid();
        }

        if (! $pid || $pid > 65535) {
            $pid = mt_rand(0, 0xfff) | 0x4000;
        }

        list($timeMid, $timeLow) = explode(' ', microtime());
        $uuid = sprintf("%08x-%04x-%04x-%02x%02x-%04x%08x", (int) $timeLow, (int) substr($timeMid, 2) & 0xffff, mt_rand(0, 0xfff) | 0x4000, mt_rand(0, 0x3f) | 0x80, mt_rand(0, 0xff), $pid, $node);

        return $uuid;
    }
}
