<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_View
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Dec 4, 2011
 * @version    SVN: $Id$
 */


/** Zood_Exception */
require_once 'Zood/Exception.php';

/** Zood_Controller_Dispatcher_Abstract */
require_once 'Zood/View/Abstract.php';


/**
 * XML View handler
 *
 * @category   Zood
 * @package    Zood_View
 * @copyright  Copyright (c) 2009-2010 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
class Zood_View_Xml extends Zood_View_Abstract
{
    public static $version = '1.0';
    public static $encoding = 'UTF-8';
    public static $root = 'data';
    public static $listKey = 'i';

    /**
     * Process result data controller action returned
     *
     * @param array $rs
     * @param Zood_Controller_Action $action
     */
    public function process(array $rs, Zood_Controller_Action $action)
    {
        $xml = new Array2Xml(self::$root, self::$listKey, self::$encoding, self::$version);
        echo $xml->toXml($action->getData());
    }
}

class Array2Xml {
    private $version = '1.0';
    private $encoding = 'UTF-8';
    private $root = 'data';
    private $listKey = 'i';
    private $xml = null;

    function __construct($root = 'data', $listKey = 'i', $encoding = 'UTF-8', $version = '1.0') {
        $this->version = $version;
        $this->encoding = $encoding;
        $this->root = $root;
        $this->xml = new XmlWriter();
    }
    function toXml($data, $eIsArray=FALSE) {
        if(!$eIsArray) {
            $this->xml->openMemory();
            $this->xml->startDocument($this->version, $this->encoding);
            $this->xml->startElement($this->root);
        }
        foreach($data as $key => $value){
            if (is_int($key)) {
                $key = $this->listKey;
            }
            if(is_array($value)){
                $this->xml->startElement($key);
                $this->toXml($value, TRUE);
                $this->xml->endElement();
                continue;
            }
            $this->xml->writeElement($key, $value);
        }
        if(!$eIsArray) {
            $this->xml->endElement();
            return $this->xml->outputMemory(true);
        }
    }
}
?>