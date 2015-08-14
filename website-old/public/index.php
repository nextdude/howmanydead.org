<?php

/**
 * Application path
 */
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

$appEnvUnset = defined('APPLICATION_ENV') ? false : true;
if ($appEnvUnset) {
  $appEnv = getenv('APPLICATION_ENV');
  if ($appEnv) {
    define('APPLICATION_ENV',$appEnv);
    $appEnvUnset = false;
  }
  else {
    /**
     * set this so we can init app and then throw exception
     */
    define('APPLICATION_ENV','development');
  }
}

// update include path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/models'),
    realpath(APPLICATION_PATH . '/classes'),
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/**
 * @see Zend_Application
 */
require_once 'Zend/Application.php';

/**
 * @see Zend_Config_Xml
 */
require_once 'Zend/Config/Xml.php';

/**
 * @see Zend_Registry
 */
require_once 'Zend/Registry.php';

/**
 * @see Hmd_Exception_AppEnv
 */
require_once 'Hmd/Exception/AppEnv.php';

// Create application, bootstrap, and run
$config = new Zend_Config_Xml(APPLICATION_PATH . '/config.xml', APPLICATION_ENV);
Zend_Registry::set('config', $config);
$application = new Zend_Application(APPLICATION_ENV, $config);
$application->bootstrap();
if ($appEnvUnset) {
  throw new Hmd_Exception_AppEnv("APPLICATION_ENV is unset in this web server configuration. Must be set to some value (like 'production' or 'development'.)");
}

if (!defined('HMD_CLI')) {
  $application->run();
}
