<?php

namespace go1\util\publishing\event\pipelines;

use Doctrine\DBAL\Connection;
use go1\util\portal\PortalHelper;
use go1\util\publishing\event\EventInterface;

class PortalPipeline implements EventPipelineInterface
{
    private $db;
    private $portalTitle;

    public function __construct(Connection $db, string $portalTitle)
    {
        $this->db = $db;
        $this->portalTitle = $portalTitle;
    }

    public function embed(EventInterface $event): void
    {
        $payload = $event->getPayload();
        if (!isset($payload['embedded']['portal'])) {
            $portal = PortalHelper::load($this->db, $this->portalTitle);
            if ($portal) {
                $event->addEmbedded('portal', $portal);
            }
        }
    }
}
