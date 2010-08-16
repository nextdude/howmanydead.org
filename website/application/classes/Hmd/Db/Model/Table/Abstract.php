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
 * @package    Hmd_Db_Model
 * @subpackage Hmd_Db_Model_Table
 * @copyright  Copyright (c) 2010 Robert F. Lyons (rfl@howmanydead.org)
 * @license    http://howmanydead.org/LICENSE.html Eclipse Public License
 * @version    $Id$
 */

/**
 * @see Zend_Db_Table_Abstract
 */
require_once 'Zend/Db/Table/Abstract.php';

/**
 * Hmd_Db_Model_Table_Abstract - The base hmd db model table class.
 *
 * @category   Hmd
 * @package    Hmd_Db
 * @subpackage Hmd_Db_Model
 */
class Hmd_Db_Model_Table_Abstract extends Zend_Db_Table_Abstract {

  const DEFAULT_ORDER = "defaultOrder";

  protected $_rowClass     = 'Hmd_Db_Model_Table_Row_Abstract';
  
  /**
   * The default order spec for this table. If you define an index called
   * "ORDER" on this table, its columns will be used for the default order,
   * otherwise, the primary key column(s) is (are) used.
   * @var array
   */
  protected $_defaultOrder;

  /**
   * Override Zend_Db_Table_Abstract setOptions method to allow setting of 
   * our custom configuration options. At the moment, this just includes
   * "defaultOrder", the default order spec array for this table.
   * 
   * @param array $options 
   */
  public function setOptions(array $options) {
    parent::setOptions($options);
    foreach ($options as $key => $val) {
      switch ($key) {
        case self::DEFAULT_ORDER:
          $this->_defaultOrder = (array) $val;
          break;
        default:
          // ignore
          break;
      }
    }
  }

  /**
   * Override parent info() method to allow returning default order configuration
   * option.
   * @param string $key The specific info part to return. OPTIONAL.
   * @return mixed
   */
  public function info($key = null) {
    if ($key == self::DEFAULT_ORDER) {
      return $this->getDefaultOrder();
    }
    else {
      return parent::info($key);
    }
  }

  /**
   * Returns the default order spec.
   * @return array
   */
  public function getDefaultOrder() {
    if (!isset($this->_defaultOrder)) {
      return (array) $this->_primary;
    }
    else {
      return (array) $this->_defaultOrder;
    }
  }
}
