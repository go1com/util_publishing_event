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

    public function setDb(Connection $db): self
    {
        $this->db = $db;

        return $this;
    }

    public function setPortalTitle(string $portalTitle): self
    {
        $this->portalTitle = $portalTitle;

        return $this;
    }

    public function setPortal($portal): self
    {
        $this->portal = $portal;

        return $this;
    }

    public function embed(EventInterface $event): void
    {
        $payload = $event->getPayload();
        if (!isset($payload['embedded']['portal'])) {
            $portal = $this->portal ?? PortalHelper::load($this->db, $this->portalTitle);
            if ($portal) {
                $event->addEmbedded('portal', $portal);
            }
        }
    }
}
