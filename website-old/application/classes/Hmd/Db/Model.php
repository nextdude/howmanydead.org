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
 * @package    Hmd_Db
 * @subpackage Hmd_Db_Model
 * @copyright  Copyright (c) 2010 Robert F. Lyons (rfl@howmanydead.org)
 * @license    http://howmanydead.org/LICENSE.html Eclipse Public License 
 * @version    $Id$
 */

/**
 * Hmd_Db_Model - A model locator class to make access to database model
 * classes easier.
 *
 * @category   Hmd
 * @package    Hmd_Db
 * @subpackage Hmd_Db_Model
 */
class Hmd_Db_Model {

  /**
   * The singleton instance.
   * @var Hmd_Db_Model
   */
  private static $_instance;

  /**
   * Our table class loader.
   * @var Zend_Loader_PluginLoader
   */
  protected $_tableLoader;

  /**
   * Array of table objects.
   * @var array
   */
  protected $_tables;

  /**
   * A select object.
   * @var Zend_Db_Select
   */
  protected $_select;

  /**
   * The result of executing the last select query.
   * @var Zend_Db_Table_Rowset|array
   */
  protected $_lastResult;

  /**
   * Make constructor private to enforce singleton pattern.
   */
  private function  __construct() {}

  /**
   * Disallow cloning to enforce singleton pattern.
   */
  final private function __clone() {}

  /**
   * Returns the singleton instance.
   * @return Hmd_Db_Model
   */
  public static function getInstance() {
    if (!isset(self::$_instance)) {
      self::$_instance = new Hmd_Db_Model();
    }
    return self::$_instance;
  }

  /**
   * Unsets the instance, forcing creation of a new instance on the next
   * getInstance() call. This is here to support testing.
   */
  public static function reset() {
    unset(self::$_instance);
  }

  /**
   * Returns the table loader for this instance.
   * @return Zend_Loader_PluginLoader
   */
  protected function getTableLoader() {
    if (!isset($this->_tableLoader)) {
      $this->_tableLoader = new Zend_Loader_PluginLoader();
      $this->_tableLoader->addPrefixPath('Hmd_Db_Model_Table', 'Hmd/Db/Model/Table');
    }
    return $this->_tableLoader;
  }

  /**
   * Returns a table object for the requested model.
   * @param string $model The base name of the model to get a table object for.
   * @return Zend_Db_Table_Abstract
   */
  public function getTableFor($model) {
    if (!isset($this->tables[$model])) {
      $modelClass = $this->getTableLoader()->load($model, true);
      $this->tables[$model] = new $modelClass();
    }
    return $this->tables[$model];
  }

  /**
   * Creates an row object for the requested model with the requested data. Note
   * that the data array may contain keys that are not columns in the model's
   * table since these will be filtered out before being sent into the table's
   * createRow() method.
   * @param string $model The model to create an instance of.
   * @param array $data An array of initializers for the model instance.
   * @return Zend_Db_Table_Row_Abstract The created row object.
   */
  public function createInstance($model, $data = array()) {
    $table = $this->getTableFor($model);
    $cols  = $table->info(Zend_Db_Table_Abstract::COLS);
    $xdata = array_intersect_key($data, array_fill_keys($cols, 1));
    return $table->createRow($xdata);
  }

  /**
   * Finds a model row given its primary key. Follows the same convention as
   * Zend_Db_Table::find() except it only ever returns a single instance or none
   * at all.
   * @param string The model class.
   * @param mixed The primary key of the row.
   * @return Hmd_Db_Model_Table_Row_Abstract
   */
  public function findInstance() {
    $args = func_get_args();
    if (count($args) < 2) {
      throw new Exception("find() expects at least two arguments");
    }
    $model = array_shift($args);
    $table = $this->getTableFor($model);
    $rows = call_user_func_array(array($table, 'find'), $args);
    return $rows->current();
  }

  /**
   * Generate a new select object for performing a query.
   * @param string $model The model to select from.
   * @param bool $allowJoins True if you want to allow joins to other models.
   * @return Hmd_Db_Model singleton
   */
  public function select($model = null, $allowJoins = false) {
    if (is_null($model)) {
      $db = Zend_Db_Table::getDefaultAdapter();
      $select = $db->select();
    }
    else {
      $table = $this->getTableFor($model);
      $select = $table->select($allowJoins)->setIntegrityCheck(!$allowJoins);
    }
    $this->_select = $select;
    return $this;
  }

  public function clearSelect() {
    $this->_select = null;
  }

  public function getSelect() {
    return $this->_select;
  }

  public function getLastResult() {
    return $this->_lastResult;
  }

  protected function _proxySelect($method, $args) {
    if (!($this->_select instanceof Zend_Db_Select)) {
      $this->select();
    }
    call_user_func_array(array($this->_select, $method), $args);
    return $this;
  }

  public function from()        {return $this->_proxySelect('from',        func_get_args());}
  public function bind()        {return $this->_proxySelect('bind',        func_get_args());}
  public function distinct()    {return $this->_proxySelect('distinct',    func_get_args());}
  public function columns()     {return $this->_proxySelect('columns',     func_get_args());}
  public function union()       {return $this->_proxySelect('union',       func_get_args());}
  public function join()        {return $this->_proxySelect('join',        func_get_args());}
  public function joinInner()   {return $this->_proxySelect('joinInner',   func_get_args());}
  public function joinLeft()    {return $this->_proxySelect('joinLeft',    func_get_args());}
  public function joinRight()   {return $this->_proxySelect('joinRight',   func_get_args());}
  public function joinFull()    {return $this->_proxySelect('joinFull',    func_get_args());}
  public function joinCross()   {return $this->_proxySelect('joinCross',   func_get_args());}
  public function joinNatural() {return $this->_proxySelect('joinNatural', func_get_args());}
  public function where()       {return $this->_proxySelect('where',       func_get_args());}
  public function orWhere()     {return $this->_proxySelect('orWhere',     func_get_args());}
  public function group()       {return $this->_proxySelect('group',       func_get_args());}
  public function having()      {return $this->_proxySelect('having',      func_get_args());}
  public function orHaving()    {return $this->_proxySelect('orHaving',    func_get_args());}
  public function order()       {return $this->_proxySelect('order',       func_get_args());}
  public function limit()       {return $this->_proxySelect('limit',       func_get_args());}
  public function limitPage()   {return $this->_proxySelect('limitPage',   func_get_args());}
  public function forUpdate()   {return $this->_proxySelect('forUpdate',   func_get_args());}

  public function fetchRow() {
    $select = $this->_select;
    if (is_null($select)) {
      $this->_lastResult = null;
    }
    else if ($select instanceof Zend_Db_Table_Select) {
      $table = $select->getTable();
      $this->_lastResult = $table->fetchRow($select);
    }
    else {
      $db = Zend_Db_Table::getDefaultAdapter();
      $stmt = $db->query($select);
      $this->_lastResult = $stmt->fetchObject();
    }
    return $this->_lastResult;
  }

  public function fetchAll() {
    $select = $this->_select;
    if (is_null($select)) {
      $this->_lastResult = null;
    }
    else if ($select instanceof Zend_Db_Table_Select) {
      $table = $select->getTable();
      $this->_lastResult = $table->fetchAll($select);
    }
    else {
      $db = Zend_Db_Table::getDefaultAdapter();
      $stmt = $db->query($select);
      $this->_lastResult = $stmt->fetchAll();
    }
    return $this->_lastResult;
  }






}
