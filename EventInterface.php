<?php

namespace go1\util\publishing\event;

interface EventInterface
{
    public function embedded();

    public function getRoutingKey(): string;

    public function getContext(): array;

    public function getPayload(): array;

    public function addContext(string $key, string $value);

    public function addEmbedded(string $key, $value);

    public function setPipelines(array $pipelines): void;
}
