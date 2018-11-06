<?php

namespace go1\util\publishing\event\tests\user;

use go1\util\publishing\event\Event;
use go1\util\publishing\event\MQEvent;
use go1\util\publishing\event\tests\PublishingEventTestCase;
use go1\util\publishing\event\user\UserEvent;
use go1\util\schema\mock\PortalMockTrait;
use go1\util\schema\mock\UserMockTrait;
use go1\util\user\UserHelper;

class UserEventTest extends PublishingEventTestCase
{
    use UserMockTrait;
    use PortalMockTrait;

    private $mail       = 'abc@mail.com';
    private $profileId  = 123;
    private $instance   = 'qa.mygo1.com';

    public function testFormat()
    {
        $this->createPortal($this->db, ['title' => $this->instance]);
        $data = [
            'mail'       => $this->mail,
            'profile_id' => $this->profileId,
            'name'       => 'Bob Bay',
            'login'      => time(),
            'access'     => time(),
            'first_name' => 'Bob',
            'last_name'  => 'Bay',
            'status'     => 1,
            'instance'   => $this->instance
        ];

        $userId = $this->createUser($this->db, $data);
        $user = UserHelper::load($this->db, $userId);
        $event = new UserEvent($user, 'user.create');
        $event->pipelines($this->db);

        try {
            $newEvent = (new MQEvent)->process($event);
        } catch (\Exception $e) {
            $this->assertTrue(false);
        }

        $payload = $newEvent->getPayload();
        $this->assertEquals($userId, $payload['id']);
        $this->assertEquals($this->instance, $payload['embedded']['portal']->title);
    }
}
