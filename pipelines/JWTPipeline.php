<?php

namespace go1\util\publishing\event\pipelines;

use go1\util\AccessChecker;
use go1\util\publishing\event\EventInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Embed the user jwt to the event payload
 *
 * Class JWTPipeline
 * @package go1\util\publishing\event\pipelines
 */
class JWTPipeline implements EventPipelineInterface
{
    private $req;
    private $portalTitle;

    public function __construct(Request $req, string $portalTitle = null)
    {
        $this->req = $req;
        $this->portalTitle = $portalTitle;
    }

    public function embed(EventInterface $event): void
    {
        $payload = $event->getPayload();
        if (!isset($payload['embedded']['jwt']['user'])) {
            $user = (new AccessChecker)->validUser($this->req, $this->portalTitle);
            if ($user) {
                $event->addEmbedded('jwt', ['user' => $user]);
            }
        }
    }
}
