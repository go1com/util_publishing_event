<?php

namespace go1\util\publishing\event;

use go1\util\queue\Queue;
use Exception;

class EventHandler implements EventHandlerInterface
{
    public function process(EventInterface $event, array $pipelines = []): EventInterface
    {
        if ($event->getSubject() == Queue::QUIZ_USER_ANSWER_UPDATE) {
            return null;
        }

        $explode = explode('.', $event->getSubject());
        $isLazy = isset($explode[0]) && ('do' == $explode[0]);

        if (strpos($event->getSubject(), '.update') && !$isLazy) {
            if (substr($event->getSubject(), 0, 5) === 'post_') {
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

        !empty($pipelines) && $event->embedded($pipelines);

        return $event;
    }
}
