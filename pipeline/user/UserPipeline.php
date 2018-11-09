<?php

namespace go1\util\publishing\event\pipeline\user;

use Doctrine\DBAL\Connection;
use go1\util\portal\PortalHelper;
use go1\util\publishing\event\EventInterface;
use go1\util\publishing\event\EventPipeline;
use go1\util\user\UserHelper;

/**
 * Embed the user data to the event payload
 *
 * Class UserPipeline
 */
class UserPipeline extends EventPipeline
{
    public function __construct(string $type = 'author', Connection $db = null, array $ids = [])
    {
        $embeds = [];
        if ($db && !empty($ids)) {
            $embeds = UserHelper::loadMultiple($db, $ids);
        }

        parent::__construct($type, $embeds);
    }
}
