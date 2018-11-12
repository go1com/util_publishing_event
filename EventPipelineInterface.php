<?php

namespace go1\util\publishing\event;

interface EventPipelineInterface
{
    /**
     * Embed the additional data to the given event
     *
     * @param EventInterface $event
     */
    public function embed(EventInterface $event): void;
}
