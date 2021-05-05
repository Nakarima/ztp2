<?php
namespace App\Tests;

use App\Service\BugService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BugServiceSpec extends KernelTestCase
{
    public function testGetAll()
    {
        self::bootKernel();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $bugs = $bugService->getAll();

        $this->assertEmpty($bugs);
    }
}