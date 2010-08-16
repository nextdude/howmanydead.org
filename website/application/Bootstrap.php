<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

  /**
   * Initialize the view resource.
   */
  protected function _initView() {
    $view = new Zend_View();
    $view->doctype('HTML5');
    
    // add our view helper path
    $view->addHelperPath(realpath(APPLICATION_PATH . '/classes/Hmd/View/Helper'), 'Hmd_View_Helper');
    
    // set up view title and separators
    $tx = $this->getResource("translate");
    $title = $tx->translate("app-title");
    $sep   = $tx->translate("title-sep");
    $view->headTitle($title)->setSeparator($sep);
    
    // setup content type meta tag
    $view->headMeta()->appendHttpEquiv('Content-Type','text/html; charset=UTF-8');
                         
    // configure view renderer                     
    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
    $viewRenderer->setView($view);

    // add to controller action helper path
    Zend_Controller_Action_HelperBroker::addPrefix('Hmd_Controller_Action_Helper');
    
    return $view;
  }

  /**
   * Initialize the locale resource.
   */
  protected function _initLocale() {
    $session = $this->getResource("session");
    Zend_Locale::setDefault('en_US');
    if (!isset($session->locale)) {
      $session->locale = 'en_US';
      $session->localeChosen = false;
    }
    $locale = new Zend_Locale($session->locale);
    Zend_Registry::set('Zend_Locale',$locale);
    return $locale;
  }

  /**
   * Initialize the session resource.
   */
  protected function _initSession() {
    $options = $this->getOptions();
    $sessionOptions = array_change_key_case($options['resources']['session'], CASE_LOWER);
    $sessionResource = new Zend_Application_Resource_Session($sessionOptions);
    if (isset($sessionOptions['savehandler'])) {
      $sessionResource->setSaveHandler($sessionOptions['savehandler']);
    }
    $sessionResource->init();
    $session = new Zend_Session_Namespace("app");
    Zend_Registry::set('session',$session);
    return $session;
  }

  /**
   * Initialize the translation resource.
   */
  protected function _initTranslate() {
    /*
    $frontOptions = array(
      'automatic_serialization' => true
    );
    $backOptions  =  array(
      'cache_dir' => realpath(APPLICATION_PATH . '/../data/locale'),
    );
    $cache = Zend_Cache::factory('Core','File',$frontOptions, $backOptions);
    Zend_Translate::setCache($cache);
    */
    $session = $this->getResource('session');
    $translate = new Zend_Translate('tmx',realpath(APPLICATION_PATH . '/language'),$session->locale);
    Zend_Registry::set('Zend_Translate',$translate);
    return $translate;
  }


  /**
   * Initialize db profiling to firebug. Disabled on production.
   */
  protected function _initProfiling() {
    $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
    $profiler->setEnabled(APPLICATION_ENV != 'production');
    $db = $this->getResource('db');
    $db->setProfiler($profiler);
    return $profiler;
  }
    
  /**
   * Initialize logging to firebug. Disabled on production.
   */
  protected function _initLogging() {
    $logger = new Zend_Log();
    if (defined('HMD_CLI')) {
      $writer = new Zend_Log_Writer_Stream('php://stderr');
    }
    else {
      $writer = new Zend_Log_Writer_Firebug();
      $writer->setEnabled(APPLICATION_ENV != 'production');
    }
    $logger->addWriter($writer);
    Zend_Registry::set('logger',$logger);
    return $logger;
  }

  /**
   * Overrride this protected method of our parent class to force the order of initializing resources
   *
   */
  protected function _bootstrap($resource = null) {
    if ($resource == null) {
      $resources = array('db','profiling','logging','session','locale','translate','view');
      foreach ($resources as $resource) {
        $this->_executeResource($resource);
      }
      // now configure anything else in the config not already initialized
      foreach ($this->getPluginResourceNames() as $resource) {
        if (!in_array($resource,$resources)) {
          $this->_executeResource($resource);
        }
      }
    }
    else {
      return parent::_bootstrap($resource);
    }
  }

}

