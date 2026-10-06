<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Payment;
use PHPUnit\Framework\TestCase;

class OrderWorkflowTest extends TestCase
{
    public function test_order_status_transition_rules_are_enforced(): void
    {
        $order = new Order(['order_status' => 'pending']);

        $this->assertTrue($order->canTransitionTo('confirmed'));
        $this->assertTrue($order->canTransitionTo('cancelled'));
        $this->assertFalse($order->canTransitionTo('shipping'));

        $order->order_status = 'completed';
        $this->assertFalse($order->canTransitionTo('pending'));
    }

    public function test_payment_status_transition_rules_are_enforced(): void
    {
        $payment = new Payment(['status' => 'pending']);

        $this->assertTrue($payment->canTransitionTo('paid'));
        $this->assertTrue($payment->canTransitionTo('failed'));
        $this->assertFalse($payment->canTransitionTo('refunded'));

        $payment->status = 'paid';
        $this->assertFalse($payment->canTransitionTo('pending'));
    }
}
