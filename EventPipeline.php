<?php

namespace go1\util\publishing\event;

class EventPipeline implements EventPipelineInterface
{
    protected $type;
    protected $embeds;

    public function __construct(string $type, array $embeds = [])
    {
        $this->type = $type;
        $this->embeds = $embeds;
    }

    public function setEmbeds(array $embeds): void
    {
        $this->embeds = $embeds;
    }

    public function embed(EventInterface $event): void
    {
        if ($this->type && !empty($this->embeds)) {
            $event->addPayloadEmbed($this->type, $this->embeds);
        }
    }
}
