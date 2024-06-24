<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CopyCommandTest extends KernelTestCase
{
    public function testOnly(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('Copy');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'feedId' => '2',
            '--only' => 'instagram',
        ]);
        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString("Entries from feed ID '2'", $output);
    }

    public function testIcludePosts(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('Copy');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'feedId' => '2',
            '--include-posts' => '3',
        ]);
        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString("posts", $output);
    }

}