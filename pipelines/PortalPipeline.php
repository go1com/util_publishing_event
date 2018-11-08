<?php

namespace go1\util\publishing\event\pipelines;

use Doctrine\DBAL\Connection;
use go1\util\portal\PortalHelper;
use go1\util\publishing\event\EventInterface;

/**
 * Embed the portal data to the event payload
 *
 * Class PortalPipeline
 * @package go1\util\publishing\event\pipelines
 */
class PortalPipeline implements EventPipelineInterface
{
    protected $db;
    protected $portalTitle;
    protected $portal;

    public function __construct(Connection $db = null, string $portalTitle = null)
    {
        $this->db = $db;
        $this->portalTitle = $portalTitle;
    }

    public function setPortal(\stdClass $portal): void
    {
        $this->portal = $portal;
    }

    public function embed(EventInterface $event): void
    {
        $payload = $event->getPayload();
        if (!isset($payload['embedded']['portal'])) {
            $portal = $this->portal ?? ($this->db ? PortalHelper::load($this->db, $this->portalTitle) : null);
            if ($portal) {
                $event->addPayloadEmbed('portal', $portal);
            }
        }
    }
}
