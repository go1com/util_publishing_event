<?php

namespace go1\util\publishing\event;

interface MQEventInterface
{
    public function process(EventInterface $event);
}
