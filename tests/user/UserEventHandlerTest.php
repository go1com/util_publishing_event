<?php

namespace go1\util\publishing\event\tests\user;

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

    private $mail       = 'abc@mail.com';
    private $profileId  = 123;
    private $portalTitle   = 'qa.mygo1.com';

    public function testFormat()
    {
        $this->createPortal($this->db, ['title' => $this->portalTitle]);
        $data = [
            'mail'       => $this->mail,
            'profile_id' => $this->profileId,
            'name'       => 'Bob Bay',
            'login'      => time(),
            'access'     => time(),
            'first_name' => 'Bob',
            'last_name'  => 'Bay',
            'status'     => 1,
            'instance'   => $this->portalTitle
        ];

        $userId = $this->createUser($this->db, $data);
        $user = UserHelper::load($this->db, $userId);

        $event = new Event($user, 'user.create');
        $handler = new UserEventHandler($this->db);
        $newEvent = $handler->process($event);
        $payload = $newEvent->getPayload();

        $this->assertEquals($userId, $payload['id']);
        $this->assertEquals($this->portalTitle, $payload['embedded']['portal']->title);
    }
}
