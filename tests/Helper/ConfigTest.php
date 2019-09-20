<?php
namespace Kepeder\Tests\Helper;

use \Kepeder\Agent;
use Kepeder\Tests\TestCase;

/**
 * Test Case for @see \Kepeder\Helper\Config
 */
final class ConfigTest extends TestCase {

  /**
   * @covers \Kepeder\Helper\Config::__construct
   * @covers \Kepeder\Agent::getConfig
   * @covers \Kepeder\Helper\Config::getDefaultConfig
   * @covers \Kepeder\Helper\Config::asArray
   */
  public function testControlDefaultConfig() {
    $appName = sprintf( 'app_name_%d', rand( 10, 99 ) );
    $agent = new Agent( [ 'appName' => $appName, 'active' => false, ] );

    // Control Default Config
    $config = $agent->getConfig()->asArray();

    $this->assertArrayHasKey( 'appName', $config );
    $this->assertArrayHasKey( 'secretToken', $config );
    $this->assertArrayHasKey( 'serverUrl', $config );
    $this->assertArrayHasKey( 'hostname', $config );
    $this->assertArrayHasKey( 'active', $config );
    $this->assertArrayHasKey( 'timeout', $config );
    $this->assertArrayHasKey( 'appVersion', $config );
    $this->assertArrayHasKey( 'env', $config );
    $this->assertArrayHasKey( 'cookies', $config );
    $this->assertArrayHasKey( 'httpClient', $config );
    $this->assertArrayHasKey( 'environment', $config );
    $this->assertArrayHasKey( 'backtraceLimit', $config );

    $this->assertEquals( $config['appName'], $appName );
    $this->assertNull( $config['secretToken'] );
    $this->assertEquals( $config['serverUrl'], 'http://127.0.0.1:8200' );
    $this->assertEquals( $config['hostname'], gethostname() );
    $this->assertFalse( $config['active'] );
    $this->assertEquals( $config['timeout'], 10 );
    $this->assertEquals( $config['env'], ['SERVER_SOFTWARE'] );
    $this->assertEquals( $config['cookies'], [] );
    $this->assertEquals( $config['httpClient'], [] );
    $this->assertEquals( $config['environment'], 'development' );
    $this->assertEquals( $config['backtraceLimit'], 0 );
  }

  /**
   * @depends testControlDefaultConfig
   *
   * @covers \Kepeder\Helper\Config::__construct
   * @covers \Kepeder\Agent::getConfig
   * @covers \Kepeder\Helper\Config::getDefaultConfig
   * @covers \Kepeder\Helper\Config::asArray
   */
  public function testControlInjectedConfig() {
    $init = [
      'appName'       => sprintf( 'app_name_%d', rand( 10, 99 ) ),
      'secretToken'   => hash( 'tiger128,3', time() ),
      'serverUrl'     => sprintf( 'https://node%d.domain.tld:%d', rand( 10, 99 ), rand( 1000, 9999 ) ),
      'appVersion'    => sprintf( '%d.%d.42', rand( 0, 3 ), rand( 0, 10 ) ),
      'frameworkName' => uniqid(),
      'timeout'       => rand( 10, 20 ),
      'hostname'      => sprintf( 'host_%d', rand( 0, 9 ) ),
      'active'        => false,
    ];

    $agent = new Agent( $init );

    // Control Default Config
    $config = $agent->getConfig()->asArray();
    foreach( $init as $key => $value ) {
        $this->assertEquals( $config[$key], $init[$key], 'key: ' . $key );
    }
  }

  /**
   * @depends testControlInjectedConfig
   *
   * @covers \Kepeder\Helper\Config::__construct
   * @covers \Kepeder\Agent::getConfig
   * @covers \Kepeder\Helper\Config::getDefaultConfig
   * @covers \Kepeder\Helper\Config::get
   */
  public function testGetConfig() {
    $init = [
      'appName' => sprintf( 'app_name_%d', rand( 10, 99 ) ),
      'active'  => false,
    ];

    $agent = new Agent( $init );
    $this->assertEquals( $agent->getConfig()->get( 'appName' ), $init['appName'] );
  }

}
