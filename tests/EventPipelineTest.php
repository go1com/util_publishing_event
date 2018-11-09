<?php

namespace go1\util\publishing\event\tests;

use go1\util\publishing\event\Event;
use go1\util\publishing\event\EventPipeline;

class EventPipelineTest extends PublishingEventTestCase
{
    public function test()
    {
        $event = new Event([], 'routingKey');
        $pipe = new EventPipeline('type', ['id' => 100]);
        $pipe->embed($event);
        $payload = $event->getPayload();

        $this->assertEquals(['type' => ['id' => 100]], $payload['embedded']);
    }

    public function testSetEmbed()
    {
        $event = new Event([], 'routingKey');
        $pipe = new EventPipeline('type');
        $pipe->setEmbeds(['id' => 100]);
        $pipe->embed($event);
        $payload = $event->getPayload();

        $this->assertEquals(['type' => ['id' => 100]], $payload['embedded']);
    }
}
