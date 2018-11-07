<?php

namespace go1\util\publishing\event;

interface MQEventHandlerInterface
{
    /**
     * Process the given event
     * The handler can return the formatted event before adding to the queue
     *
     * @param EventInterface $event
     * @return EventInterface
     */
    public function process(EventInterface $event): EventInterface;
}
