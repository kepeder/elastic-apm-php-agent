<?php
namespace Kepeder\Tests\Helper;

use Kepeder\Exception\Timer\AlreadyRunningException;
use \Kepeder\Helper\Timer;
use Kepeder\Tests\TestCase;

/**
 * Test Case for @see \Kepeder\Helper\Timer
 */
final class TimerTest extends TestCase {

  /**
   * @covers \Kepeder\Helper\Timer::start
   * @covers \Kepeder\Helper\Timer::stop
   * @covers \Kepeder\Helper\Timer::getDuration
   * @covers \Kepeder\Helper\Timer::toMicro
   */
  public function testCanBeStartedAndStoppedWithDuration() {
    $timer = new Timer();
    $duration = rand( 25, 100 );

    $timer->start();
    usleep( $duration );
    $timer->stop();

    $this->assertGreaterThanOrEqual( $duration, $timer->getDuration() );
  }

    /**
     * @covers \Kepeder\Helper\Timer::start
     * @covers \Kepeder\Helper\Timer::stop
     * @covers \Kepeder\Helper\Timer::getDuration
     * @covers \Kepeder\Helper\Timer::toMicro
     */
    public function testCanCalculateDurationInMilliseconds() {
        $timer = new Timer();
        $duration = rand( 25, 100 ); // duration in milliseconds

        $timer->start();
        usleep( $duration * 1000 ); // sleep microseconds
        $timer->stop();

        $this->assertDurationIsWithinThreshold($duration, $timer->getDurationInMilliseconds());
    }

  /**
   * @depends testCanBeStartedAndStoppedWithDuration
   *
   * @covers \Kepeder\Helper\Timer::start
   * @covers \Kepeder\Helper\Timer::stop
   * @covers \Kepeder\Helper\Timer::getDuration
   * @covers \Kepeder\Helper\Timer::getElapsed
   * @covers \Kepeder\Helper\Timer::toMicro
   */
  public function testGetElapsedDurationWithoutError() {
    $timer = new Timer();

    $timer->start();
    usleep( 10 );
    $elapsed = $timer->getElapsed();
    $timer->stop();

    $this->assertGreaterThanOrEqual( $elapsed, $timer->getDuration() );
    $this->assertEquals( $timer->getElapsed(), $timer->getDuration() );
  }

  /**
   * @depends testCanBeStartedAndStoppedWithDuration
   *
   * @covers \Kepeder\Helper\Timer::start
   * @covers \Kepeder\Helper\Timer::getDuration
   */
  public function testCanBeStartedWithForcingDurationException() {
    $timer = new Timer();
    $timer->start();

    $this->expectException( \Kepeder\Exception\Timer\NotStoppedException::class );

    $timer->getDuration();
  }

  /**
   * @depends testCanBeStartedWithForcingDurationException
   *
   * @covers \Kepeder\Helper\Timer::stop
   */
  public function testCannotBeStoppedWithoutStart() {
    $timer = new Timer();

    $this->expectException( \Kepeder\Exception\Timer\NotStartedException::class );

    $timer->stop();
  }

    /**
     * @covers \Kepeder\Helper\Timer::start
     * @covers \Kepeder\Helper\Timer::getDurationInMilliseconds
     */
    public function testCanBeStartedWithExplicitStartTime() {
        $timer = new Timer(microtime(true) - .5); // Start timer 500 milliseconds ago

        usleep(500 * 1000); // Sleep for 500 milliseconds

        $timer->stop();

        $duration = $timer->getDurationInMilliseconds();

        // Duration should be more than 1000 milliseconds
        //  sum of initial offset and sleep
        $this->assertGreaterThanOrEqual(1000, $duration);
    }

    /**
     * @covers \Kepeder\Helper\Timer::start
     */
    public function testCannotBeStartedIfAlreadyRunning() {
        $timer = new Timer(microtime(true));

        $this->expectException(AlreadyRunningException::class);
        $timer->start();
    }
}
