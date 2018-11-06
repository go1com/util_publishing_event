<?php

namespace go1\util\publishing\event\tests;

use go1\util\publishing\event\Event;
use go1\util\publishing\event\MQEvent;

class MQEventTest extends PublishingEventTestCase
{
    public function test()
    {
        $event = new Event([], 'routingKey');
        try {
            (new MQEvent)->process($event);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function testUpdateSuccess()
    {
        $payload = ['id' => 1, 'user' => 1];
        $payload['original'] = ['id' => 1, 'user' => 2];

        $event = new Event($payload, 'message.update');
        try {
            (new MQEvent)->process($event);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function testUpdateFail()
    {
        $payload = ['id' => 1, 'user' => 1];

        $event = new Event($payload, 'message.update');
        try {
            (new MQEvent)->process($event);
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }
}
