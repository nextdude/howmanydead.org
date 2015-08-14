<?php
/**
 * Hmd Db Adapter mysql class to support fake nested transactions.
 */

require_once 'Zend/Db/Adapter/Pdo/Mysql.php';

class Hmd_Db_Adapter_Pdo_Mysql extends Zend_Db_Adapter_Pdo_Mysql {
    /**
     * Current Transaction Level
     *
     * @var int
     */
    protected $_txLevel = 0;
 
    /**
     * Begin new DB transaction for connection
     *
     * @return App_Zend_Db_Adapter_Mysqli
     */
    public function beginTransaction() {
      if ( $this->_txLevel === 0 ) {
        //error_log("begin tx for real");
        parent::beginTransaction();
      }
      else {
        //error_log("begin tx " . $this->_txLevel+1);
      }
      $this->_txLevel++;

      return $this;
    }
 
    /**
     * Commit DB transaction
     *
     * @return App_Zend_Db_Adapter_Mysqli
     */
    public function commit() {
      if ( $this->_txLevel === 1 ) {
        //error_log("commit tx for real");
        parent::commit();
      }
      else {
        //error_log("commit tx " . $this->_txLevel-1);
      }
      $this->_txLevel--;

      return $this;
    }
 
    /**
     * Rollback DB transaction
     *
     * @return App_Zend_Db_Adapter_Mysqli
     */
    public function rollback() {
      if ( $this->_txLevel === 1 ) {
//        error_log("rollback tx for real");
        parent::rollback();
      }
      else {
//        error_log("rollback tx " . $this->_txLevel-1);
      }
      $this->_txLevel--;

      return $this;
    }
 
    /**
     * Get adapter transaction level state. Return 0 if all transactions are complete
     *
     * @return int
     */
    public function getTransactionLevel() {
      return $this->_txLevel;
    }
}
