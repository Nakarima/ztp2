<?php
namespace App\Tests;

use App\Service\BugService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BugServiceTest extends KernelTestCase
{
    public function testGetAllEmpty()
    {
        self::bootKernel();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $bugs = $bugService->getAll(1);

        $this->assertEquals(0, count($bugs));
    }

    /*
    public function testCreate()
    {
        self::bootKernel();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $bugs = $bugService->getAll();

        $this->assertEquals(0, count($bugs));
    }

    public function testUpdate()
    {
        self::bootKernel();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $bugs = $bugService->getAll();

        $this->assertEquals(0, count($bugs));
    }

    public function testGetById()
    {
        self::bootKernel();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $bugs = $bugService->getAll();

        $this->assertEquals(0, count($bugs));
    }

    public function testGetAll()
    {
        self::bootKernel();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $bugs = $bugService->getAll(1);

        $this->assertEquals(0, count($bugs));
    }

    public function testGetByIdNotFound()
    {
        self::bootKernel();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $bugs = $bugService->getAll();

        $this->assertEquals(0, count($bugs));
    }
    */
}