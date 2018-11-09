<?php

namespace go1\util\publishing\event\pipeline;

use Doctrine\DBAL\Connection;
use go1\util\group\GroupHelper;
use go1\util\portal\PortalHelper;
use go1\util\publishing\event\EventInterface;
use go1\util\publishing\event\EventPipeline;

/**
 * Embed the portal data to the event payload
 *
 * Class PortalPipeline
 */
class GroupPipeline extends EventPipeline
{
    protected $db;
    protected $portalTitle;
    protected $portal;

    public function __construct(Connection $db = null, array $ids = [])
    {
        $embeds = [];
        if ($db && !empty($userIds)) {
            $embeds[] = GroupHelper::loadMultiple($db, $ids);
        }

        parent::__construct('group', $embeds);
    }
}
