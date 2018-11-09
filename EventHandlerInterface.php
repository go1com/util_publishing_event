<?php

namespace go1\util\publishing\event;

/**
 * Interface EventHandlerInterface
 */
interface EventHandlerInterface
{

    /**
     * Formatted the given event before adding to the queue
     *
     * @param EventInterface $event
     * @param array $pipelines
     * @return EventInterface
     */
    public function process(EventInterface $event, array $pipelines): EventInterface;
}
