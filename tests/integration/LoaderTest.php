<?php

namespace Securetrading\Loader\Tests\Integration;

use \Securetrading\Loader\Loader as Loader;

class LoaderTest extends \Securetrading\Unittest\IntegrationtestAbstract {
  public function tearDown() {
    Loader::unregisterAutoloaderPaths(Loader::getAutoloaderPaths());
    Loader::unregisterAutoloader();
  }

  /**
   *
   */
  public function testAutoloader_WithUnderscores() {
    $this->assertEquals(false, class_exists('Our_Underscored_ClassName'));
    $this->copySourceDirToTestDir(__DIR__ . '/helpers', 'sub');
    Loader::registerAutoloaderPath($this->_testDir . 'sub/');
    Loader::registerAutoloader();
    $this->assertEquals(true, class_exists('Our_Underscored_ClassName'));
  }

  /**
   *
   */
  public function testAutoloader_WithNamespaceSeparator() {
    $this->assertEquals(false, class_exists('\Our\Name_Spaced\Class_Name'));
    $this->copySourceDirToTestDir(__DIR__ . '/helpers', 'sub');
    Loader::registerAutoloaderPath($this->_testDir . 'sub/');
    Loader::registerAutoloader();
    $this->assertEquals(true, class_exists('\Our\Name_Spaced\Class_Name'));
  }
}