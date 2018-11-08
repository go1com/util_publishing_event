<?php

namespace go1\util\publishing\event;

interface EventInterface
{
    /**
     * Get the event subject
     *
     * @return string
     */
    public function getSubject(): string;

    /**
     * Get the event context
     *
     * @return string
     */
    public function getContext(): array;

    /**
     * Get the event payload
     *
     * @return string
     */
    public function getPayload(): array;

    /**
     * Add a value to the context by the given key
     *
     * @param string $key
     * @param $value
     */
    public function addContext(string $key, $value): void;

    /**
     * Add a value to the payload embedded by the given key
     *
     * @param string $key
     * @param $value
     */
    public function addEmbedded(string $key, $value): void;

    /**
     * Embed the event payload by the given pipelines
     *
     * @param array $pipelines
     */
    public function embedded(array $pipelines): void;
}
