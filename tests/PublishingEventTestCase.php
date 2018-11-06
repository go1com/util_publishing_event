<?php

namespace go1\util\publishing\event\tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use go1\util_db\InstallTrait;
use PHPUnit\Framework\TestCase;

abstract class PublishingEventTestCase extends TestCase
{
    use InstallTrait;

    /** @var  Connection */
    protected $db;

    public function setUp()
    {
        $this->db = DriverManager::getConnection(['url' => 'sqlite://sqlite::memory:']);
        $this->installGo1Schema($this->db);
    }
}
