<?php

namespace go1\util\publishing\event\user;

use Doctrine\DBAL\Connection;
use go1\util\publishing\event\Event;
use go1\util\publishing\event\pipelines\JWTPipeline;
use go1\util\publishing\event\pipelines\PortalPipeline;
use Symfony\Component\HttpFoundation\Request;

class UserEvent extends Event
{
    public function pipelines(Connection $db, Request $req = null): void
    {
        $payload = $this->getPayload();
        $this->pipelines = [
            new PortalPipeline($db, $payload['instance'])
        ];
        $req && ($this->pipelines[] = new JWTPipeline($req, $payload['instance']));
    }
}
