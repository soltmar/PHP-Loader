<?php

namespace Securetrading\Loader;

class Loader {
  protected static $_init = false;
  
  protected static $_autoloaderPaths = array();

  public static function getRootPath() {
    return realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR) . '/';
  }

  public static function getVarPath() {
    return static::getRootPath() . 'var' . DIRECTORY_SEPARATOR;
  }
  
  public static function getLogPath() {
    return static::getVarPath() . 'log' . DIRECTORY_SEPARATOR;
  }
  
  public static function getLogArchivePath() {
    return static::getLogPath() . 'archive' . DIRECTORY_SEPARATOR;
  }
  
  public static function registerAutoloaderPath($path) {
    static::$_autoloaderPaths[$path] = true;
  }

  public static function registerAutoloaderPaths(array $paths) {
    foreach($paths as $path) {
      static::registerAutoloaderPath($path);
    }
  }

  public static function unregisterAutoloaderPath($path) {
    unset(static::$_autoloaderPaths[$path]);
  }

  public static function unregisterAutoloaderPaths(array $paths) {
    foreach($paths as $path) {
      static::unregisterAutoloaderPath($path);
    }
  }
  
  public static function getAutoloaderPaths() {
    return array_keys(static::$_autoloaderPaths);
  }

  public static function setAutoloaderPaths(array $paths) {
    static::$_autoloaderPaths = array();
    static::registerAutoloaderPaths($paths);
  }

  public static function registerAutoloader() {
    if (!static::$_init) {
      spl_autoload_register(array('static', '_autoload'), true, true);
      static::$_init = true;
    }
  }

  public static function unregisterAutoloader() {
    spl_autoload_unregister(array('static', '_autoload'));
    static::$_init = false;
  }

  public static function createPsr0FilePath($className) {
    $nameSpace = '';
    if (false !== ($lastNsSeparator = strrpos($className, '\\'))) {
      $nameSpace = substr($className, 0, $lastNsSeparator+1);
      $className = substr($className, $lastNsSeparator+1);
    }
    $nameSpace = str_replace('\\', DIRECTORY_SEPARATOR, $nameSpace);
    $className = str_replace('_', DIRECTORY_SEPARATOR, $className);
    return $nameSpace . $className . '.php';
  }

  protected static function _autoload($className) { // See PSR-0
    foreach(static::getAutoloaderPaths() as $path) {
      $filePath = static::createPsr0FilePath($className);
      $filePath = $path . $filePath;
      if (file_exists($filePath)) {
	require_once($filePath);
	return true;
      }
    }
  }
}
