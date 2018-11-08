<?php

namespace go1\util\publishing\event;

use go1\util\publishing\event\pipelines\EventPipelineInterface;

class Event implements EventInterface
{
    const CONTEXT_APP           = 'app';
    const CONTEXT_ACTOR_ID      = 'actor_id';
    const CONTEXT_REQUEST_ID    = 'request_id';
    const CONTEXT_TIMESTAMP     = 'timestamp';

    protected $subject;
    protected $payload;
    protected $context = [];

    public function __construct($payload, string $routingKey, array $context = [])
    {
        $this->subject = $routingKey;
        $this->payload = is_scalar($payload) ? json_decode($payload, true) : (is_object($payload) ? ((array) $payload) : $payload);
        $this->context = $context;

        if (!isset($this->payload['embedded'])) {
            $this->payload['embedded'] = [];
        }
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function addContext(string $key, $value): void
    {
        if (!isset($this->context[$key])) {
            $this->context[$key] = $value;
        }
    }

    public function addPayloadEmbed(string $key, $value): void
    {
        $this->payload['embedded'][$key] = $value;
    }

    public function embed(array $pipelines = []): void
    {
        /**
         * @var EventPipelineInterface $pipeline
         */
        foreach ($pipelines as $pipeline) {
            $pipeline->embed($this);
        }
    }
}
