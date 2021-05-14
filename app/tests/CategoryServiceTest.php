<?php

namespace App\Tests;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CategoryServiceTest
 * @package App\Tests
 */
class CategoryServiceTest extends KernelTestCase
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
        $categoryService = $container->get(CategoryService::class);

        $categories = $categoryService->getAll(1);

        $this->assertEquals(0, count($categories));
    }

    public function testGetByIdNotFound()
    {
        self::bootKernel();
        $container = self::$container;
        $categoryService = $container->get(CategoryService::class);

        $category = $categoryService->getById(0);

        $this->assertEquals(null, $category);
    }

    public function testCreate()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $categoryService = $container->get(CategoryService::class);

        $category = new Category();
        $title = $this->faker->word();
        $category->setTitle($title);
        $categoryService->createCategory($category);
        $id = $category->getId();

        $createdCategory = $categoryService->getById($id);

        $this->assertEquals($title, $createdCategory->getTitle());
    }

    public function testUpdate()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $categoryService = $container->get(CategoryService::class);

        $category = new Category();
        $title = $this->faker->word();
        $newTitle = $this->faker->word();

        $category->setTitle($title);
        $categoryService->createCategory($category);
        $id = $category->getId();
        $createdCategory = $categoryService->getById($id);

        $createdCategory->setTitle($newTitle);
        $categoryService->updateCategory($createdCategory);
        $updatedCategory = $categoryService->getById($id);

        $this->assertEquals($newTitle, $updatedCategory->getTitle());
        $this->assertEquals($category->getTitle(), $updatedCategory->getTitle());
    }

    public function testDelete()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $categoryService = $container->get(CategoryService::class);

        $category = new Category();
        $title = $this->faker->word();

        $category->setTitle($title);
        $categoryService->createCategory($category);
        $id = $category->getId();
        $createdCategory = $categoryService->getById($id);

        $categoryService->deleteCategory($createdCategory);
        $updatedCategory = $categoryService->getById($id);

        $this->assertEquals(null, $updatedCategory);
    }

    public function testGetMultiple()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $categoryService = $container->get(CategoryService::class);

        // create second page
        array_map(function () use ($categoryService) {
            $category = new Category();
            $title = $this->faker->word();
            $category->setTitle($title);
            $categoryService->createCategory($category);

            return $category;
        }, range(1, CategoryRepository::PAGINATOR_ITEMS_PER_PAGE));

        sleep(1);

        $created = array_map(function () use ($categoryService) {
            $category = new Category();
            $title = $this->faker->word();
            $category->setTitle($title);
            $categoryService->createCategory($category);
            sleep(1); // hack motzno, w scali można zrobić tick time na threadzie

            return $category->getId();
        }, range(1, CategoryRepository::PAGINATOR_ITEMS_PER_PAGE));

        $firstPage = array_map(function ($category) {
            return $category->getId();
        }, $categoryService->getAll(1)->getItems());

        $this->assertEquals(array_reverse($created), $firstPage);
    }

    /*

        public function testGetAll()
        {
            self::bootKernel();
            $container = self::$container;
            $categoryService = $container->get(CategoryService::class);

            $categorys = $categoryService->getAll(1);

            $this->assertEquals(0, count($categorys));
        }
    */
}
