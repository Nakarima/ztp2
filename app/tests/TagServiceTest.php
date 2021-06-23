<?php

namespace App\Tests;

use App\Entity\Tag;
use App\Entity\User;
use App\Repository\TagRepository;
use App\Service\TagService;
use App\Service\UserService;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class TagServiceTest
 * @package App\Tests
 */
class TagServiceTest extends KernelTestCase
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
        $tagService = $container->get(TagService::class);

        $categories = $tagService->getAll(1);

        $this->assertEquals(0, count($categories));
    }

    public function testGetByIdNotFound()
    {
        self::bootKernel();
        $container = self::$container;
        $tagService = $container->get(TagService::class);

        $tag = $tagService->getById(0);

        $this->assertEquals(null, $tag);
    }

    public function testCreate()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $tagService = $container->get(TagService::class);

        $tag = $this->createTag();
        $tagService->createTag($tag);
        $id = $tag->getId();

        $createdTag = $tagService->getById($id);

        $this->assertEquals($tag, $createdTag);
    }

    public function testGetByTitle()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $tagService = $container->get(TagService::class);

        $tag = $this->createTag();
        $tagService->createTag($tag);

        $createdTag = $tagService->getByTitle($tag->getTitle());

        $this->assertEquals($tag, $createdTag);
    }

    public function testUpdate()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $tagService = $container->get(TagService::class);

        $tag = $this->createTag();
        $newTitle = $this->faker->word();

        $tagService->createTag($tag);
        $id = $tag->getId();
        $createdTag = $tagService->getById($id);

        $createdTag->setTitle($newTitle);
        $tagService->updateTag($createdTag);
        $updatedTag = $tagService->getById($id);

        $this->assertEquals($newTitle, $updatedTag->getTitle());
    }

    public function testGetMultiple()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $tagService = $container->get(TagService::class);

        // create second page
        array_map(function () use ($tagService) {
            $tag = $this->createTag();
            $tagService->createTag($tag);

            return $tag;
        }, range(1, TagService::PAGINATOR_ITEMS_PER_PAGE));

        sleep(1);

        $created = array_map(function ($i) use ($tagService) {
            $tag = $this->createTag();
            $tagService->createTag($tag);

            sleep(1);

            return $tag->getId();
        }, range(1, TagService::PAGINATOR_ITEMS_PER_PAGE));

        $firstPage = array_map(function ($tag) {
            return $tag->getId();
        }, $tagService->getAll(1)->getItems());

        $this->assertEquals(array_reverse($created), $firstPage);
    }

    public function testDelete()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $tagService = $container->get(TagService::class);

        $tag = $this->createTag();
        $tagService->createTag($tag);
        $id = $tag->getId();
        $createdTag = $tagService->getById($id);

        $tagService->deleteTag($createdTag);
        $updatedTag = $tagService->getById($id);

        $this->assertEquals(null, $updatedTag);
    }

    private function createTag(): Tag
    {
        $tag = new Tag();
        $title = $this->faker->word();

        $tag->setTitle($title);

        return $tag;
    }
}
