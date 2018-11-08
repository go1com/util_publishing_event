<?php

namespace go1\util\publishing\event\tests\user;

use go1\util\publishing\event\event\user\UserEvent;
use go1\util\publishing\event\MQEventHandler;
use go1\util\publishing\event\tests\PublishingEventTestCase;
use go1\util\schema\mock\PortalMockTrait;
use go1\util\schema\mock\UserMockTrait;
use go1\util\user\UserHelper;

class UserEventTest extends PublishingEventTestCase
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
        $event = new UserEvent($user, 'user.create');
        $event->setDb($this->db);
        $event->pipelines();

        try {
            $newEvent = (new MQEventHandler)->process($event);
        } catch (\Exception $e) {
            $this->assertTrue(false);
        }

        $payload = $newEvent->getPayload();
        $this->assertEquals($userId, $payload['id']);
        $this->assertEquals($this->portalTitle, $payload['embedded']['portal']->title);
    }
}
