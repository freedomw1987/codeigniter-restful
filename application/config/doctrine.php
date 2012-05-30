<?php
  require_once(APPPATH.'/libraries/doctrine/Doctrine.php');

  if (is_array($db)) {
    foreach ($db as $dn => $setting) {
      $dsnes[$dn] = $setting['dbdriver'] . 
                              '://' . $setting['username'] . 
                              ':' . $setting['password']. 
                              '@' . $setting['hostname'] .
                              ':' . $setting['port'] .
                              '/' . $setting['database'];
    }

  }
  spl_autoload_register(array('Doctrine', 'autoload'));
  foreach ($dsnes as $dbname => $dsn) {
    Doctrine_Manager::connection($dsn, $dbname)->setCharset('utf8'); 
  }
  Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, TRUE);
  Doctrine::loadModels(realpath(dirname(__FILE__) . '/..') . DIRECTORY_SEPARATOR . 'models');
