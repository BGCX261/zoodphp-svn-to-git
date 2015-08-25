<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Sep 12, 2010
 * @version    SVN: $Id$
 */

/**
 * Zood_Entity_Null
 */
require_once 'Zood/Entity/Null.php';

/**
 * Simple object class
 *
 * @category   Zood
 * @package    Zood_Entity
 * @copyright  Copyright (c) 2005-2008 ZoodPHP Org. (http://zoodphp.ahdong.com)
 */
abstract class Zood_Entity_Abstract
{
    /**
     * Case of property key when import(fromArray) or export(toArray)
     */
    const KEY_AUTO = 0; // Auto
    const KEY_LOWER = 1; // To lower case
    const KEY_UPPER = 2; // To upper case
    const KEY_NATURAL = 3; // Do not change case


    /**
     * Cache of reflected properties
     */
    private $_ReflectedPropertiesCache = null;

    /**
     * Flag if the entity is empty
     *
     * @var boolean
     */
    private $_empty = true;

    /**
     * Constructor
     * 
     * Set all public and protected member variables to Zood_Entity_Null
     *
     * @return void
     */
    public function __construct()
    {
        static $Zood_Entity_Null;
        if (is_null($Zood_Entity_Null)) {
            $Zood_Entity_Null = Zood_Entity_Null::getInstance();
        }

        if (count($p = $this->getReflectedProperties())) {
            foreach ($p as $propertyName) {
                if (is_null($this->$propertyName)) {
                    $this->$propertyName = $Zood_Entity_Null;
                }
            }
        }
    }

    /**
     * Get public and protected properties of current class
     *
     * @return array
     */
    protected function getReflectedProperties()
    {
        if (is_null($this->_ReflectedPropertiesCache)) {
            $this->_ReflectedPropertiesCache = array();

            $refectionClass = new ReflectionClass($this);
            $propertiesArray = $refectionClass->getProperties();
            if (is_array($propertiesArray) and count($propertiesArray) > 0) {
                while (list(, $property) = each($propertiesArray)) {
                    $refectionProperty = new ReflectionProperty($property->class, $property->name);
                    if ($refectionProperty->isPublic() || $refectionProperty->isProtected()) {
                        $this->_ReflectedPropertiesCache[] = $property->name;
                    }
                }
            }
        }

        return $this->_ReflectedPropertiesCache;
    }

    /**
     * Export entity to an array
     *
     * @param integer $keyCase Case of array key
     * @return array
     */
    public function toArray($keyCase = self::KEY_NATURAL)
    {
        if (count($p = $this->getReflectedProperties())) {
            $properties = array();
            foreach ($p as $propertyName) {
                if ($this->$propertyName instanceof Zood_Entity_Null) {
                    continue;
                }
                $properties[$propertyName] = $this->$propertyName;
            }
        } else {
            return array();
        }

        $filters = array();

        switch ($keyCase) {
            case self::KEY_LOWER :
                $properties = array_change_key_case($properties, CASE_LOWER);
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_LOWER);
                    return array_diff_key($properties, $filters);
                } else {
                    return $properties;
                }
                break;

            case self::KEY_UPPER :
                $properties = array_change_key_case($properties, CASE_UPPER);
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_UPPER);
                    return array_diff_key($properties, $filters);
                } else {
                    return $properties;
                }
                break;

            case self::KEY_NATURAL :
                if (count($filters) > 0) {
                    return array_diff_key($properties, $filters);
                } else {
                    return $properties;
                }
                break;

            default : // KEY_AUTO
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_LOWER);
                    while (list($key, ) = each($properties)) {
                        if (array_key_exists(strtolower($key), $filters)) {
                            unset($properties[$key]);
                        }
                    }
                }
                return $properties;
                break;
        }
    }

    /**
     * Import values of another entity
     *
     * @param Zood_Entity_Abstract|array $propertySet
     * @param integer $keyCase
     * @return Zood_Entity_Abstract
     */
    public function fromObject($propertySet, $keyCase = self::KEY_NATURAL)
    {
        if (is_object($propertySet) && $propertySet instanceof Zood_Entity_Abstract) {
            $data = $propertySet->toArray();
            $this->fromArray($data, $keyCase);
        } elseif (is_array($propertySet)) {
            $this->fromArray($propertySet, $keyCase);
        }

        return $this;
    }

    /**
     * Import values of an array to entity
     *
     * @return Zood_Entity_Abstract
     */
    public function fromArray($propertySet, $keyCase = self::KEY_NATURAL)
    {
        $filters = array();

        switch ($keyCase) {

            case self::KEY_LOWER :
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_LOWER);
                    $propertySet = array_diff_key($propertySet, $filters); // filtered array
                }
                $this->setProperty($propertySet, self::KEY_LOWER);
                break;

            case self::KEY_UPPER :
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_UPPER);
                    $propertySet = array_diff_key($propertySet, $filters); // filtered array
                }
                $this->setProperty($propertySet, self::KEY_UPPER);
                break;

            case self::KEY_NATURAL :
                if (count($filters) > 0) {
                    $propertySet = array_diff_key($propertySet, $filters); // filtered array
                }
                $this->setProperty($propertySet, self::KEY_NATURAL);
                break;

            default : // KEY_AUTO, to lower case
                $propertySet = array_change_key_case($propertySet, CASE_LOWER);
                if (count($filters) > 0) {
                    $filters = array_change_key_case($filters, CASE_LOWER);
                    $propertySet = array_diff_key($propertySet, $filters); // filtered array
                }
                $this->setProperty($propertySet, self::KEY_LOWER);
                break;
        }

        return $this;
    }

    /**
     * @return void
     */
    protected function setProperty($propertySet, $case = self::KEY_NATURAL)
    {
        if (count($properties = $this->getReflectedProperties()) == 0) {
            return;
        }

        while (list(, $propertyName) = each($properties)) {
            if ($case == self::KEY_LOWER) {
                $keyNew = strtolower($propertyName);
            } elseif ($case == self::KEY_UPPER) {
                $keyNew = strtoupper($propertyName);
            } else {
                $keyNew = $propertyName;
            }
            if (isset($propertySet[$keyNew])) {
                $this->$propertyName = $propertySet[$keyNew];
                $this->_empty = false;
            }
        }
    }

    /**
     * Check if the member variables have been set
     *
     * @return boolean Return true only if none variable has been set AND all of them are instancs of Zood_Entity_Null
     */
    public function isEmpty()
    {
        if (! $this->_empty) {
            return false;
        }

        if (count($p = $this->getReflectedProperties())) {
            $properties = array();
            foreach ($p as $propertyName) {
                if (! $this->$propertyName instanceof Zood_Entity_Null) {
                    return false;
                }
            }
        }

        return true;
    }
    
    /**
     * Set a member variable to Zood_Entity_Null
     * 
     * @param string $propertyName
     * @return $this
     */
    public function setToNull($propertyName)
    {
        $this->$propertyName = Zood_Entity_Null::getInstance();
        return $this;
    }
    
    /**
     * Check if a member variable is Zood_Entity_Null
     * 
     * @param string $propertyName
     * @return boolean
     */
    public function isNull($propertyName)
    {
        if ($this->$propertyName instanceof Zood_Entity_Null) {
            return true;
        } else {
            return false;
        }
    }
}
