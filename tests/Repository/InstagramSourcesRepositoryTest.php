<?php

namespace App\Tests\Repository;

use App\Entity\InstagramSources;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InstagramSourcesRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testfindIt(): void
    {
        $instagram = $this->entityManager
            ->getRepository(InstagramSources::class)
            ->findIt('2');

        $this->assertIsString($instagram[0]['name']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}