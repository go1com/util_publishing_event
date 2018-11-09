<?php

namespace go1\util\publishing\event\handler\user;

use Doctrine\DBAL\Connection;
use go1\util\publishing\event\EventHandler;
use go1\util\publishing\event\EventInterface;
use go1\util\publishing\event\EventPipeline;
use go1\util\publishing\event\pipeline\portal\PortalPipeline;
use go1\util\user\UserHelper;
use Symfony\Component\HttpFoundation\Request;

/**
 * Process the user event before adding to the queue
 *
 * Embed portal data
 * Embed user_id
 *
 * Class UserEventHandler
 */
class UserEventHandler extends EventHandler
{
    protected $db;
    protected $accountsName;
    protected $portal;
    protected $userId;

    public function __construct(Connection $db = null, string $accountsName = '')
    {
        $this->db = $db;
        $this->accountsName = $accountsName;
    }

    public function setPortal(\stdClass $portal): self
    {
        $this->portal = $portal;

        return $this;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function process(EventInterface $event, array $pipelines = []): EventInterface
    {
        $payload = $event->getPayload();

        if ($this->portal) {
            $portalPipe = new PortalPipeline();
            $portalPipe->setEmbeds([$this->portal]);
        } else if ($this->db) {
            $portalPipe = new PortalPipeline($this->db, [$payload['instance']]);
        }
        ($portalPipe instanceof PortalPipeline) && ($pipelines[] = $portalPipe);

        $userId = $this->userId;
        if (!$userId && $this->db && $this->accountsName) {
            $userId = UserHelper::userId($this->db, $payload['id'], $this->accountsName);
        }
        $userId && ($pipelines[] = new EventPipeline('user_id', [$userId]));

        $event = parent::process($event, $pipelines);

        return $event;
    }
}
