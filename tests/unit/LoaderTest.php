<?php

namespace Securetrading\Loader\Tests\Unit;

use \Securetrading\Loader\Loader as Loader;

class LoaderTest extends \Securetrading\Unittest\UnittestAbstract {
  public function setUp() {
    $this->_expectedRootPath = realpath(__DIR__ . '/../../../') . '/';
    $this->assertNotEquals(false, $this->_expectedRootPath);
  }

  public function tearDown() {
    Loader::unregisterAutoloaderPaths(Loader::getAutoloaderPaths());
    Loader::unregisterAutoloader();
  }

  /**
   *
   */
  public function testGetRootPath() {
    $this->assertEquals($this->_expectedRootPath, Loader::getRootPath());
  }

  /**
   *
   */
  public function testGetVarPath() {
    $this->assertEquals($this->_expectedRootPath . 'var/', Loader::getVarPath());
  }

  /**
   *
   */
  public function testGetLogPath() {
    $this->assertEquals($this->_expectedRootPath . 'var/log/', Loader::getLogPath());
  }

  /**
   *
   */
  public function testGetLogArchivePath() {
    $this->assertEquals($this->_expectedRootPath . 'var/log/archive/', Loader::getLogArchivePath());
  }

  /**
   *
   */
  public function test_AutoloaderPathFunctions() {
    $this->assertEquals(array(), Loader::getAutoloaderPaths());
    Loader::registerAutoloaderPath('/tmp/loader1');
    $this->assertEquals(array('/tmp/loader1'), Loader::getAutoloaderPaths());
    Loader::registerAutoloaderPaths(array('/tmp/loader2', '/tmp/loader3', '/tmp/loader4'));
    $this->assertEquals(array('/tmp/loader1', '/tmp/loader2', '/tmp/loader3', '/tmp/loader4'), Loader::getAutoloaderPaths());
    Loader::unregisterAutoloaderPath('/tmp/loader3');
    $this->assertEquals(array('/tmp/loader1', '/tmp/loader2', '/tmp/loader4'), Loader::getAutoloaderPaths());
    Loader::unregisterAutoloaderPaths(array('/tmp/loader1', '/tmp/loader2'));
    $this->assertEquals(array('/tmp/loader4'), Loader::getAutoloaderPaths());
    Loader::setAutoloaderPaths(array('/tmp/loader1', '/tmp/loader2'));
    $this->assertEquals(array('/tmp/loader1', '/tmp/loader2'), Loader::getAutoloaderPaths());
  }

  /**
   *
   */
  public function test_Register_And_Unregister_Autoloader() {
    $initialCount = count(spl_autoload_functions());
    Loader::registerAutoloader();
    $this->assertEquals($initialCount + 1, count(spl_autoload_functions()));
    Loader::registerAutoloader();
    $this->assertEquals($initialCount + 1, count(spl_autoload_functions())); // Assert that it cannot be registered twice.
    Loader::unRegisterAutoloader();
    $this->assertEquals($initialCount, count(spl_autoload_functions()));
    Loader::registerAutoloader();
    $this->assertEquals($initialCount + 1, count(spl_autoload_functions())); // Assert that it can be re-registered.
  }

  /**
   * @dataProvider providerCreatePsr0FilePath
   */
  public function testCreatePsr0FilePath($inputClassName, $expectedReturnValue) {
    $actualReturnValue = Loader::createPsr0FilePath($inputClassName);
    $this->assertEquals($expectedReturnValue, $actualReturnValue);
  }

  public function providerCreatePsr0FilePath() {
    $this->_addDataSet('A', 'A.php');
    $this->_addDataSet('\A', '/A.php');
    $this->_addDataSet('A_B', 'A/B.php');
    $this->_addDataSet('\A\B_C', '/A/B/C.php');
    $this->_addDataSet('\A_B\C_D', '/A_B/C/D.php');
    return $this->_getDataSets();
  }
}