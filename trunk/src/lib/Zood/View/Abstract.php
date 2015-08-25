<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood_View
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Feb 23, 2009
 * @version    SVN: $Id$
 */


/**
 * Abstract of View handler
 *
 * @category   Zood
 * @package    Zood_View
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
abstract class Zood_View_Abstract
{
	/**
	 * Path for script directory.
	 *
	 * @var array
	 */
	protected $_path = array(
        'script' => null
    );
	
	/**
	 * Data for display or return
	 * 
	 * @var array
	 */
	protected $_data = array();

	/**
	 * Resets view script path.
	 *
	 * @param string The directory to set as the path.
	 * @return Zood_View_Abstract
	 */
	public function setScriptPath($path)
	{
		$this->_path['script'] = $path;
		return $this;
	}
	
	public function getScriptPath()
	{
		return $this->_path['script'];
	}

	/**
	 * Return full path to a view script specified by $name
	 *
	 * @param  string $name
	 * @return false|string False if script not found
	 * @throws Zend_Exception if no script directory set
	 */
	public function getScript($name)
	{
		$path = $this->_path['script'] . '/' . $name;

		if (is_readable($path)) {
			return $path;
		} else {
			require_once 'Zood/Exception.php';
			$message = "script '$name' not found in path (" . $this->_path['script'] . ")";
			throw new Zood_Exception($message);
		}
	}

	/**
	 * Put data
	 *
	 * @param Array|String|Mix $data
	 * @return Void
	 */
	public function setData($dataOrKey,$value = null)
	{
		$data = $dataOrKey;
		if (is_array($data)) {
			$this->_data += (array) $data;
		} else if (is_string($data))
		{
			$this->_data[$data] = $value;
		}

		return $this;
	}

	/**
	 * Get data
	 *
	 * @return Array
	 */
	public function getData($key = null, $default = null)
	{
		if (null !== $key) {
			$key = (string) $key;
			if (isset($this->_data[$key])) {
				return $this->_data[$key];
			} else {
				return $default;
			}
		} else {
			return $this->_data;
		}
	}
}
?>