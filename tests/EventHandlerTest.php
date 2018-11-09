<?php

namespace go1\util\publishing\event\tests;

use go1\util\publishing\event\Event;
use go1\util\publishing\event\EventHandler;
use go1\util\publishing\event\EventPipeline;

class EventHandlerTest extends PublishingEventTestCase
{
    public function test()
    {
        $event = new Event([], 'routingKey');
        $handler = new EventHandler();
        $newEvent = $handler->process($event);

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

    public function testPipes()
    {
        $pipe = new EventPipeline('type', ['data' => 1]);
        $event = new Event([], 'routingKey');
        $newEvent = (new EventHandler)->process($event, [$pipe]);

        $this->assertArrayHasKey('timestamp', $newEvent->getContext());
        $payload = $newEvent->getPayload();
        $this->assertArrayHasKey('embedded', $payload);

        $this->assertEquals(['type' => ['data' => 1]], $payload['embedded']);
    }

    public function testContextService()
    {
        putenv('SERVICE_80_NAME=service');
        $event = new Event([], 'routingKey');
        $newEvent = (new EventHandler)->process($event);

        $context = $newEvent->getContext();
        $this->assertArrayHasKey('timestamp', $context);

        $this->assertEquals('service', $context[Event::CONTEXT_APP]);
    }
}
