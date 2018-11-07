<?php

namespace go1\util\publishing\event;

use go1\util\queue\Queue;
use Exception;

class MQEventHandler implements MQEventHandlerInterface
{
    public function process(EventInterface $event): EventInterface
    {
        if ($event->getRoutingKey() == Queue::QUIZ_USER_ANSWER_UPDATE) {
            return null;
        }

        $explode = explode('.', $event->getRoutingKey());
        $isLazy = isset($explode[0]) && ('do' == $explode[0]);

        if (strpos($event->getRoutingKey(), '.update') && !$isLazy) {
            if (substr($event->getRoutingKey(), 0, 5) === 'post_') {
                return null;
            }

            $validKeys = array_filter($event->getPayload(), function ($value, $key) {
                return (in_array($key, ['id', 'original']) && $value);
            }, ARRAY_FILTER_USE_BOTH);
            if (count($validKeys) !== 2) {
                throw new Exception("Missing entity ID or original data.");
            }
        }

        if ($service = getenv('SERVICE_80_NAME')) {
            $event->addContext(Event::CONTEXT_APP, $service);
        }
        $event->addContext(Event::CONTEXT_TIMESTAMP, time());

        $event->embedded();

        return $event;
    }
}
