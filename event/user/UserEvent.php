<?php

namespace go1\util\publishing\event\event\user;

use Doctrine\DBAL\Connection;
use go1\util\publishing\event\Event;
use go1\util\publishing\event\pipelines\JWTPipeline;
use go1\util\publishing\event\pipelines\PortalPipeline;
use Symfony\Component\HttpFoundation\Request;

/**
 * Process the user event before adding to the queue
 *
 * Embed the portal data
 * Embed the the user jwt if given the request
 *
 * Class UserEvent
 * @package go1\util\publishing\event\event\user
 */
class UserEvent extends Event
{
    protected $db;
    protected $req;
    protected $portal;

    public function setDb(Connection $db): self
    {
        $this->db = $db;

        return $this;
    }

    public function setReq(Request $req): self
    {
        $this->req = $req;

        return $this;
    }

    public function setPortal($portal): self
    {
        $this->portal = $portal;

        return $this;
    }

    public function pipelines(): void
    {
        $payload = $this->getPayload();

        $portalPipe = new PortalPipeline;
        if ($this->portal) {
            $portalPipe->setPortal($this->portal);
        } else {
            $portalPipe->setDb($this->db)
                ->setPortalTitle($payload['instance']);
        }

        $pipelines = [$portalPipe];
        $this->req && ($pipelines[] = new JWTPipeline($this->req, $payload['instance']));

        $this->setPipelines($pipelines);
    }
}
