<?php

namespace App\Tests;

use App\Entity\Bug;
use App\Repository\BugRepository;
use App\Service\BugService;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class BugServiceTest
 * @package App\Tests
 */
class BugServiceTest extends KernelTestCase
{
    /**
     * Faker.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    public function testGetAllEmpty()
    {
        self::bootKernel();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $categories = $bugService->getAll(1);

        $this->assertEquals(0, count($categories));
    }

    public function testGetByIdNotFound()
    {
        self::bootKernel();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $bug = $bugService->getById(0);

        $this->assertEquals(null, $bug);
    }

    public function testCreate()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $bug = new Bug();
        $title = $this->faker->word();
        $bug->setTitle($title);
        $bugService->createBug($bug);
        $id = $bug->getId();

        $createdBug = $bugService->getById($id);

        $this->assertEquals($title, $createdBug->getTitle());
    }

    public function testUpdate()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $bug = new Bug();
        $title = $this->faker->word();
        $newTitle = $this->faker->word();

        $bug->setTitle($title);
        $bugService->createBug($bug);
        $id = $bug->getId();
        $createdBug = $bugService->getById($id);

        $createdBug->setTitle($newTitle);
        $bugService->updateBug($createdBug);
        $updatedBug = $bugService->getById($id);

        $this->assertEquals($newTitle, $updatedBug->getTitle());
        $this->assertEquals($bug->getTitle(), $updatedBug->getTitle());
    }

    public function testDelete()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $bug = new Bug();
        $title = $this->faker->word();

        $bug->setTitle($title);
        $bugService->createBug($bug);
        $id = $bug->getId();
        $createdBug = $bugService->getById($id);

        $bugService->deleteBug($createdBug);
        $updatedBug = $bugService->getById($id);

        $this->assertEquals(null, $updatedBug);
    }

    public function testGetMultiple()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        // create second page
        array_map(function () use ($bugService) {
            $bug = new Bug();
            $title = $this->faker->word();
            $bug->setTitle($title);
            $bugService->createBug($bug);

            return $bug;
        }, range(1, BugRepository::PAGINATOR_ITEMS_PER_PAGE));

        sleep(1);

        $created = array_map(function () use ($bugService) {
            $bug = new Bug();
            $title = $this->faker->word();
            $bug->setTitle($title);
            $bugService->createBug($bug);
            sleep(1); // hack motzno, w scali można zrobić tick time na threadzie

            return $bug->getId();
        }, range(1, BugRepository::PAGINATOR_ITEMS_PER_PAGE));

        $firstPage = array_map(function ($bug) {
            return $bug->getId();
        }, $bugService->getAll(1)->getItems());

        $this->assertEquals(array_reverse($created), $firstPage);
    }

    /*

        public function testGetAll()
        {
            self::bootKernel();
            $container = self::$container;
            $bugService = $container->get(BugService::class);

            $bugs = $bugService->getAll(1);

            $this->assertEquals(0, count($bugs));
        }
    */
}
