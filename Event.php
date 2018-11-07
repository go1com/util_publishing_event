<?php

namespace go1\util\publishing\event;

use go1\util\publishing\event\pipelines\EventPipelineInterface;

class Event implements EventInterface
{
    const CONTEXT_APP           = 'app';
    const CONTEXT_ACTOR_ID      = 'actor_id';
    const CONTEXT_REQUEST_ID    = 'request_id';
    const CONTEXT_TIMESTAMP     = 'timestamp';

    protected $routingKey;
    protected $payload;
    protected $context = [];
    protected $pipelines = [];

    public function __construct($payload, string $routingKey, array $context = [])
    {
        $this->routingKey = $routingKey;
        $this->payload = is_scalar($payload) ? json_decode($payload, true) : (is_object($payload) ? ((array) $payload) : $payload);
        $this->context = $context;

        if (!isset($this->payload['embedded'])) {
            $this->payload['embedded'] = [];
        }
    }

    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setPipelines(array $pipelines): void
    {
        $this->pipelines = $pipelines;
    }

    public function addContext(string $key, string $value)
    {
        if (!isset($this->context[$key])) {
            $this->context[$key] = $value;
        }
    }

    public function addEmbedded(string $key, $value)
    {
        $this->payload['embedded'][$key] = $value;
    }

    public function embedded()
    {
        /**
         * @var EventPipelineInterface $pipeline
         */
        foreach ($this->pipelines as $pipeline) {
            $pipeline->embed($this);
        }
    }
}
