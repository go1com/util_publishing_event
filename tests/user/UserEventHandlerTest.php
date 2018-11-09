<?php

namespace go1\util\publishing\event\tests\user;

use go1\util\portal\PortalHelper;
use go1\util\publishing\event\Event;
use go1\util\publishing\event\handler\user\UserEventHandler;
use go1\util\publishing\event\tests\PublishingEventTestCase;
use go1\util\schema\mock\PortalMockTrait;
use go1\util\schema\mock\UserMockTrait;
use go1\util\user\UserHelper;

class UserEventHandlerTest extends PublishingEventTestCase
{
    use UserMockTrait;
    use PortalMockTrait;

    private $mail           = 'abc@mail.com';
    private $profileId      = 123;
    private $portalName     = 'qa.mygo1.com';
    private $accountsName   = 'accounts.dev.go1.com';

    public function testFormat()
    {
        $this->createPortal($this->db, ['title' => $this->portalName]);
        $data = [
            'mail'       => $this->mail,
            'profile_id' => $this->profileId,
            'name'       => 'Bob Bay',
            'login'      => time(),
            'access'     => time(),
            'first_name' => 'Bob',
            'last_name'  => 'Bay',
            'status'     => 1,
            'instance'   => $this->portalName
        ];
        $accountId = $this->createUser($this->db, $data);
        $account = UserHelper::load($this->db, $accountId);

        $userData = $data;
        $userData['instance'] = $this->accountsName;
        $userId = $this->createUser($this->db, $userData);

        $event = new Event($account, 'user.create');

        // Using connection
        $handler = new UserEventHandler($this->db, $this->accountsName);
        $newEvent = $handler->process($event);
        $payload = $newEvent->getPayload();

        $this->assertEquals($accountId, $payload['id']);
        $this->assertEquals($this->portalName, $payload['embedded']['portal'][0]->title);
        $this->assertEquals($userId, $payload['embedded']['user_id'][0]);

        // Using set object

        $portal = PortalHelper::load($this->db, $this->portalName);
        $handler = new UserEventHandler();
        $handler->setPortal($portal);
        $handler->setUserId($userId);
        $newEvent = $handler->process($event);
        $payload = $newEvent->getPayload();

        $this->assertEquals($this->portalName, $payload['embedded']['portal'][0]->title);
        $this->assertEquals($userId, $payload['embedded']['user_id'][0]);
    }
}
