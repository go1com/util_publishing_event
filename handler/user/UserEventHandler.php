<?php

namespace go1\util\publishing\event\handler\user;

use Doctrine\DBAL\Connection;
use go1\util\publishing\event\EventHandler;
use go1\util\publishing\event\EventInterface;
use go1\util\publishing\event\pipeline\JWTPipeline;
use go1\util\publishing\event\pipeline\PortalPipeline;
use Symfony\Component\HttpFoundation\Request;

/**
 * Process the user event before adding to the queue
 *
 * Embed the portal data
 * Embed the the user jwt if given the request
 *
 * Class UserEventHandler
 * @package go1\util\publishing\event\event\user
 */
class UserEventHandler extends EventHandler
{
    protected $db;
    protected $req;
    protected $portal;

    public function __construct(Connection $db = null, Request $req = null)
    {
        $this->db = $db;
        $this->req = $req;
    }

    public function setPortal(\stdClass $portal): self
    {
        $this->portal = $portal;

        return $this;
    }

    public function process(EventInterface $event, array $pipelines = []): EventInterface
    {
        $payload = $event->getPayload();

        $portalPipe = new PortalPipeline($this->db, $payload['instance']);
        if ($this->portal) {
            $portalPipe->setPortal($this->portal);
        }

        $pipelines = [$portalPipe];
        $this->req && ($pipelines[] = new JWTPipeline($this->req, $payload['instance']));

        $event = parent::process($event, $pipelines);

        return $event;
    }
}
