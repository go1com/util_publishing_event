<?php

namespace go1\util\publishing\event\pipeline\portal;

use Doctrine\DBAL\Connection;
use go1\util\portal\PortalHelper;
use go1\util\publishing\event\EventPipeline;

/**
 * Embed the portal data to the event payload
 *
 * Class PortalPipeline
 */
class PortalPipeline extends EventPipeline
{
    public function __construct(Connection $db = null, array $portalNames = [])
    {
        $embeds = [];
        if ($db && !empty($portalNames)) {
            foreach ($portalNames as $portalName) {
                if ($portal = PortalHelper::load($db, $portalName)) {
                    $embeds[] = $portal;
                }
            }
        }

        parent::__construct('portal', $embeds);
    }
}
