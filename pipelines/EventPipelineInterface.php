<?php

namespace go1\util\publishing\event\pipelines;

use go1\util\publishing\event\EventInterface;

interface EventPipelineInterface
{
    public function embed(EventInterface $event): void;
}
