<?php

namespace App\Test\TestCase\Command;

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class UpdateActionStatusCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    public function setUp()
    {
        parent::setUp();
        $this->useCommandRunner();
    }

    public function testDescriptionOutput()
    {
        $this->exec('notifica_follow_up_meta_nao_atingida --help');
        $this->assertOutputContains('My cool console app');
    }
}
