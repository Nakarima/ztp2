<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use App\Service\UserService;
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
        $user = $this->createUser($container);

        $category = $this->createCategory();
        $categoryService->createCategory($category, $user);
        $id = $category->getId();

        $createdCategory = $categoryService->getById($id);

        $this->assertEquals($category, $createdCategory);
    }

    public function testUpdate()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $categoryService = $container->get(CategoryService::class);
        $user = $this->createUser($container);

        $category = $this->createCategory();
        $newTitle = $this->faker->word();

        $categoryService->createCategory($category, $user);
        $id = $category->getId();
        $createdCategory = $categoryService->getById($id);

        $createdCategory->setTitle($newTitle);
        $categoryService->updateCategory($createdCategory);
        $updatedCategory = $categoryService->getById($id);

        $this->assertEquals($newTitle, $updatedCategory->getTitle());
    }

    public function testGetMultiple()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $categoryService = $container->get(CategoryService::class);
        $user = $this->createUser($container);

        // create second page
        array_map(function () use ($user, $categoryService) {
            $category = $this->createCategory();
            $categoryService->createCategory($category, $user);

            return $category;
        }, range(1, CategoryService::PAGINATOR_ITEMS_PER_PAGE));

        sleep(1);

        $created = array_map(function ($i) use ($user, $categoryService) {
            $category = $this->createCategory();
            $categoryService->createCategory($category, $user);

            sleep(1);

            return $category->getId();
        }, range(1, CategoryService::PAGINATOR_ITEMS_PER_PAGE));

        $firstPage = array_map(function ($category) {
            return $category->getId();
        }, $categoryService->getAll(1)->getItems());

        $this->assertEquals(array_reverse($created), $firstPage);
    }

    public function testDelete()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $categoryService = $container->get(CategoryService::class);
        $user = $this->createUser($container);

        $category = $this->createCategory();
        $categoryService->createCategory($category, $user);
        $id = $category->getId();
        $createdCategory = $categoryService->getById($id);

        $categoryService->deleteCategory($createdCategory);
        $updatedCategory = $categoryService->getById($id);

        $this->assertEquals(null, $updatedCategory);
    }

    private function createUser($container): User
    {
        $userService = $container->get(UserService::class);
        $user = new User();
        $user->setEmail($this->faker->email());
        $user->setRoles([User::ROLE_USER, User::ROLE_ADMIN]);
        $user->setPassword($this->faker->password());
        $userService->createUser($user);

        return $user;
    }

    private function createCategory(): Category
    {
        $category = new Category();
        $title = $this->faker->word();

        $category->setTitle($title);

        return $category;
    }
}
