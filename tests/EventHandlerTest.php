<?php

namespace go1\util\publishing\event\tests;

use go1\util\publishing\event\Event;
use go1\util\publishing\event\EventHandler;

class EventHandlerTest extends PublishingEventTestCase
{
    public function test()
    {
        $event = new Event([], 'routingKey');
        $newEvent = (new EventHandler)->process($event);

        $this->assertArrayHasKey('embedded', $newEvent->getPayload());
        $this->assertArrayHasKey('timestamp', $newEvent->getContext());
    }

    public function testUpdateSuccess()
    {
        $payload = ['id' => 1, 'user' => 1];
        $payload['original'] = ['id' => 1, 'user' => 2];

        $event = new Event($payload, 'message.update');
        $newEvent = (new EventHandler)->process($event);

        $this->assertArrayHasKey('embedded', $newEvent->getPayload());
        $this->assertArrayHasKey('timestamp', $newEvent->getContext());
    }

    public function testUpdateFail()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Missing entity ID or original data.");

        $payload = ['id' => 1, 'user' => 1];

        $event = new Event($payload, 'message.update');
        (new EventHandler)->process($event);
    }
}
