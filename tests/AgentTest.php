<?php
namespace Kepeder\Tests;

use Kepeder\Agent;
use Kepeder\Stores\TransactionsStore;

/**
 * Test Case for @see \Kepeder\Agent
 */
final class AgentTest extends TestCase {

    /**
     * @covers \Kepeder\Agent::__construct
     * @covers \Kepeder\Agent::startTransaction
     * @covers \Kepeder\Agent::stopTransaction
     * @covers \Kepeder\Agent::getTransaction
     */
    public function testStartAndStopATransaction()
    {
        $agent = new Agent( [ 'appName' => 'phpunit_1', 'active' => false, ] );

        // Create a Transaction, wait and Stop it
        $name = 'trx';
        $agent->startTransaction( $name );
        usleep( 10 * 1000 ); // sleep milliseconds
        $agent->stopTransaction( $name );

        // Transaction Summary must be populated
        $summary = $agent->getTransaction( $name )->getSummary();

        $this->assertArrayHasKey( 'duration', $summary );
        $this->assertArrayHasKey( 'backtrace', $summary );

        // Expect duration in milliseconds
        $this->assertDurationIsWithinThreshold(10, $summary['duration']);
        $this->assertNotEmpty( $summary['backtrace'] );
    }

    /**
     * @covers \Kepeder\Agent::__construct
     * @covers \Kepeder\Agent::startTransaction
     * @covers \Kepeder\Agent::stopTransaction
     * @covers \Kepeder\Agent::getTransaction
     */
    public function testStartAndStopATransactionWithExplicitStart()
    {
        $agent = new Agent( [ 'appName' => 'phpunit_1', 'active' => false, ] );

        // Create a Transaction, wait and Stop it
        $name = 'trx';
        $agent->startTransaction( $name, [], microtime(true) - 1);
        usleep( 500 * 1000 ); // sleep milliseconds
        $agent->stopTransaction( $name );

        // Transaction Summary must be populated
        $summary = $agent->getTransaction( $name )->getSummary();

        $this->assertArrayHasKey( 'duration', $summary );
        $this->assertArrayHasKey( 'backtrace', $summary );

        // Expect duration in milliseconds
        $this->assertDurationIsWithinThreshold(1500, $summary['duration'], 150);
        $this->assertNotEmpty( $summary['backtrace'] );
    }

    /**
     * @depends testStartAndStopATransaction
     *
     * @covers \Kepeder\Agent::__construct
     * @covers \Kepeder\Agent::getTransaction
     */
    public function testForceErrorOnUnknownTransaction()
    {
        $agent = new Agent( [ 'appName' => 'phpunit_x', 'active' => false, ] );

        $this->expectException( \Kepeder\Exception\Transaction\UnknownTransactionException::class );

        // Let it go boom!
        $agent->getTransaction( 'unknown' );
    }

    /**
     * @depends testForceErrorOnUnknownTransaction
     *
     * @covers \Kepeder\Agent::__construct
     * @covers \Kepeder\Agent::stopTransaction
     */
    public function testForceErrorOnUnstartedTransaction()
    {
        $agent = new Agent( [ 'appName' => 'phpunit_2', 'active' => false, ] );

        $this->expectException( \Kepeder\Exception\Transaction\UnknownTransactionException::class );

        // Stop an unstarted Transaction and let it go boom!
        $agent->stopTransaction( 'unknown' );
    }

}
