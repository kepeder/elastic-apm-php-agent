<?php
namespace Kepeder\Tests\Events;

use \Kepeder\Events\Transaction;
use Kepeder\Tests\TestCase;

/**
 * Test Case for @see \Kepeder\Events\Transaction
 */
final class TransactionTest extends TestCase {

    /**
     * @covers \Kepeder\Events\EventBean::getId
     * @covers \Kepeder\Events\EventBean::getTraceId
     * @covers \Kepeder\Events\Transaction::getTransactionName
     * @covers \Kepeder\Events\Transaction::setTransactionName
     */
    public function testParentConstructor() {
        $name = 'testerus-grandes';
        $transaction = new Transaction($name, []);

        $this->assertEquals($name, $transaction->getTransactionName());
        $this->assertNotNull($transaction->getId());
        $this->assertNotNull($transaction->getTraceId());
        $this->assertNotNull($transaction->getTimestamp());

        $now = round(microtime(true) * 1000000);
        $this->assertGreaterThanOrEqual($transaction->getTimestamp(), $now);
    }

    /**
     * @depends testParentConstructor
     *
     * @covers \Kepeder\Events\EventBean::setParent
     * @covers \Kepeder\Events\EventBean::getTraceId
     * @covers \Kepeder\Events\EventBean::ensureGetTraceId
     */
    public function testParentReference() {
        $parent = new Transaction('parent', []);
        $child  = new Transaction('child', []);
        $child->setParent($parent);

        $arr = json_decode(json_encode($child), true);

        $this->assertEquals($arr['transaction']['id'], $child->getId());
        $this->assertEquals($arr['transaction']['parent_id'], $parent->getId());
        $this->assertEquals($arr['transaction']['trace_id'], $parent->getTraceId());
        $this->assertEquals($child->getTraceId(), $parent->getTraceId());
    }

}
