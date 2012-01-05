<?php
/**
 * @file
 *
 * Unit tests for Containers.
 */
namespace HPCloud\Tests\Storage\ObjectStorage;

require_once 'src/HPCloud/Bootstrap.php';
require_once 'test/TestCase.php';

use \HPCloud\Storage\ObjectStorage\Container;

class ContainerTest extends \HPCloud\Tests\TestCase {

  const FILENAME = 'unit-test-dummy.txt';
  const FILESTR = 'This is a test.';

  // The factory functions (newFrom*) are tested in the
  // ObjectStorage tests, as they are required there.
  // Rather than build a Mock to achieve the same test here,
  // we just don't test them again.

  public function testConstructor() {
    $container = new Container('foo');
    $this->assertEquals('foo', $container->name());
    $this->assertEquals(0, $container->bytes());
    $this->assertEquals(0, $container->count());
  }

  public function testCountable() {
    // Verify that the interface Countable is properly
    // implemented.

    $mockJSON = array('count' => 5, 'bytes' => 128, 'name' => 'foo');

    $container = Container::newFromJSON($mockJSON, 'fake', 'fake');

    $this->assertEquals(5, count($container));

  }

  /**
   * Get a container from the server.
   */
  protected function containerFixture() {
    $store = $this->swiftAuth();
    $cname = self::$settings['hpcloud.swift.container'];

    try {
      $store->createContainer($cname);
      $container = $store->container($cname);

    }
    // This is why PHP needs 'finally'.
    catch (\Exception $e) {
      // Delete the container.
      $store->deleteContainer($cname);
      throw $e;
    }

    return $container;
  }

  /**
   * Destroy a container fixture.
   *
   * This should be called in any method that uses containerFixture().
   */
  protected function destroyContainerFixture() {
    $store = $this->swiftAuth();
    $cname = self::$settings['hpcloud.swift.container'];

    $store->deleteContainer($cname);
  }

  public function testSave() {
    $container = $this->containerFixture();

    $name = __FUNCTION__;
    $content = "This is a test.";
    $type = 'text/plain';

    $obj = new Object($name, $content, $type);

    $container->save($obj);


    $this->destroyContainerFixture();
  }

  public function testContents() {

  }

  public function testRemove() {

  }

  public function testUpdate() {

  }

  public function testIterable() {

  }

}
