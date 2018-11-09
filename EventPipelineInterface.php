<?php

namespace go1\util\publishing\event;

interface EventPipelineInterface
{
    /**
     * Embed the additional data to the event
     *
     * $event->addEmbedded('portal', $portal)
     * $payload['embedded']['portal'] = $portal
     *
     * @param EventInterface $event
     */
    public function embed(EventInterface $event): void;
}
