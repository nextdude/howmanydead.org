<?php

/**
 * HowManyDead.org
 *
 * LICENSE
 *
 * This source file is subject to the Eclipse Public License that is bundled
 * with this package in the file public/LICENSE.html. It is also available on
 * the web at http://howmanydead.org/LICENSE.html.
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * online, please send an email to license@howmanydead.org so we can send you
 * a copy immediately.
 *
 * @category   Hmd
 * @package    Hmd_Db_Model_Table
 * @subpackage Hmd_Db_Model_Table_Row
 * @copyright  Copyright (c) 2010 Robert F. Lyons (rfl@howmanydead.org)
 * @license    http://howmanydead.org/LICENSE.html Eclipse Public License
 * @version    $Id$
 */
/**
 * @see Zend_Db_Table_Row_Abstract
 */
require_once 'Zend/Db/Table/Row/Abstract.php';

/**
 * Hmd_Db_Model_Table_Row_Abstract - The base hmd model table row class.
 *
 * @category   Hmd
 * @package    Hmd_Db_Model_Table
 * @subpackage Hmd_Db_Model_Table_Row
 */
class Hmd_Db_Model_Table_Row_Abstract extends Zend_Db_Table_Row_Abstract {

  /**
   * An internal variable to cache loaded foreign objects.
   * @var array
   */
  protected $_foreignObjects = array();

  /**
   * Returns the definition of a foreign key from the {@see Hmd_Db_Model_Table_Abstract}
   * object or, if $foreignKey is not provided, the ref map for all foreign keys.
   * @param string $foreignKey OPTIONAL. The foreign key attribute.
   * @return array
   */
  public function getReferenceMap($foreignKey=null) {
    $map = $this->getTable()->info(Zend_Db_Table_Abstract::REFERENCE_MAP);
    return is_null($foreignKey) ? $map :
      (isset($map[$foreignKey]) ? $map[$foreignKey] : null);
  }

  /**
   * Returns true if the requested foreign key attribute is defined for this row class,
   * false otherwise. If no $name is specified, returns true if any foreign keys are
   * defined, false otherwise.
   * @param string $name
   * @return bool
   */
  public function hasForeignKey($name=null) {
    return!is_null($this->getReferenceMap($name));
  }

  /**
   * Given a foreign key id attribute, returns the foreign key object attribute name
   * associated with it. The foreign key attributes and their associated id columns
   * are defined in the $_referenceMap attribute of the associated
   * {@see Hmd_Db_Model_Table_Abstract} object
   * @param string $name The foreign key id attribute.
   * @return string
   */
  public function getForeignKeyFor($name) {
    $map = $this->getReferenceMap();
    foreach ($map as $foreignKey => $spec) {
      if (in_array($name, (array)$map[Zend_Db_Table_Abstract::COLUMNS])) {
        return $foreignKey;
      }
    }
    return null;
  }

  /**
   * Overrides the magic get method to return foreign key attributes. This handles lazy
   * loading of the foreign key attributes. If the requested attribute is not a
   * foreign key attribute, just invokes the parent get method.
   * @param string $name The attribute to get.
   * @return mixed
   */
  public function __get($name) {
    if ($this->hasForeignKey($name)) {
      if (!isset($this->_foreignObjects[$name])) {
        $this->_loadForeignObject($name);
      }
      return $this->_foreignObjects[$name];
    } else {
      return parent::__get($name);
    }
  }

  /**
   * Loads a foreign key object from the database and sets the foreign key object
   * attribute and its associated foreign key id attributes.
   * @param string $name The foreign key to load.
   */
  protected function _loadForeignObject($name) {
    $map = $this->getReferenceMap($name);
    if (!is_null($map)) {
      $object = $this->findParentRow($map[Zend_Db_Table_Abstract::REF_TABLE_CLASS], $name);
      $this->_setForeignObject($name, $object);
    }
  }

  /**
   * Handles setting a foreign key object attribute. These attributes are defined as the
   * keys of the $_referenceMap attribute defined in the {@see Hmd_Db_Model_Table_Abstract}
   * object associated with this row class. After setting the object itself, we take care of
   * setting the associated foreign key id attributes as well.
   * @param string $name The foreign key attribute to set.
   * @param Hmd_Db_Model_Table_Row_Abstract $object The foreign key object.
   */
  protected function _setForeignObject($name, $object) {
    $this->_foreignObjects[$name] = $object;
    $map = $this->getReferenceMap($name);
    $cols = (array) $map[Zend_Db_Table_Abstract::COLUMNS];
    $refCols = (array) $map[Zend_Db_Table_Abstract::REF_COLUMNS];
    for ($i = 0; $i < count($cols); $i++) {
      $col = $cols[$i];
      $refCol = $refCols[$i];
      $this->$col = $object ? $object->$refCol : null;
    }
  }

  /**
   * Overrides the magic set method to handle foreign keys. If the requested attribute
   * is a foreign key object, then we set the foreign key and its associated id attributes.
   * If the requested attribute is a foreign key id column, we unset the associated foreign
   * key object attribute if the id is different, to allow lazy loading the new object
   * later when needed. Otherwise, we just call the parent set method.
   * @param string $name The attribute to set.
   * @param mixed $value The value of the attribute.
   */
  public function __set($name, $value) {
    if ($this->hasForeignKey($name)) {
      $this->_setForeignObject($name, $value);
    } else {
      if ($this->_data[$name] != $value) {
        $foreignKey = $this->getForeignKey($name);
        if (!is_null($foreignKey)) {
          $this->_setForeignObject($foreignKey, null);
        }
        parent::__set($name, $value);
      }
    }
  }

  /**
   * Overrides the magic isset method to support testing if a foreign key object
   * has been loaded for the requested attribute name. If the requested attribute
   * is not a foreign key object attribute, it simply invokes the parent class method.
   * @param string $name The attribute name to test.
   * @return bool
   */
  public function __isset($name) {
    if ($this->hasForeignKey($name)) {
      return isset($this->_foreignObjects[$name]);
    } else {
      return parent::__isset($columnName);
    }
  }

  /**
   * Overrides the magic unset method to support unsetting a foreign key object
   * attribute. If the requested attribute is not a foreign key object attribute,
   * it simply invokes the parent class method.
   * @param string $name The attribute name to unset.
   */
  public function __unset($name) {
    if ($this->hasForeignKey($name)) {
      unset($this->_foreignObjects[$name]);
    } else {
      parent::__unset($columnName);
    }
  }

}
