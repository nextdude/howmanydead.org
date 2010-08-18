<?php
//---[ THIS FILE WAS AUTO-GENERATED AT 2010-08-18T13:40:10Z ]
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
 * @see Hmd_Db_Model_Table_Abstract
 */
require_once 'Hmd/Db/Model/Table/Abstract.php';

/**
 * Hmd_Db_Model_Table_User - An hmd db model table class.
 *
 * @category   Hmd
 * @package    Hmd_Db_Model
 * @subpackage Hmd_Db_Model_Table
 */
class Hmd_Db_Model_Table_User extends Hmd_Db_Model_Table_Abstract {

  protected $_name         = 'user';
  protected $_primary      = array('id');
  protected $_defaultOrder = array('email');

}
