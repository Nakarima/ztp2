<?php

namespace App\Tests;

use App\Entity\Bug;
use App\Entity\Category;
use App\Entity\Status;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\StatusRepository;
use App\Service\BugService;
use App\Service\CategoryService;
use App\Service\TagService;
use App\Service\UserService;
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
        $user = $this->createUser($container);
        $category = $this->createCategory($container, $user);
        $statuses = $this->createStatuses($container);

        $bug = $this->createBug($category, $statuses[0]);
        $bugService->createBug($bug, $user);
        $id = $bug->getId();

        $createdBug = $bugService->getById($id);

        $this->assertEquals($bug, $createdBug);
    }

    public function testUpdateTitle()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $bugService = $container->get(BugService::class);
        $user = $this->createUser($container);
        $category = $this->createCategory($container, $user);
        $statuses = $this->createStatuses($container);

        $bug = $this->createBug($category, $statuses[0]);
        $bugService->createBug($bug, $user);
        $id = $bug->getId();
        $createdBug = $bugService->getById($id);

        $newTitle = $this->faker->word();
        $createdBug->setTitle($newTitle);
        $bugService->updateBug($createdBug);
        $updatedBug = $bugService->getById($id);

        $this->assertEquals($newTitle, $updatedBug->getTitle());
    }

    public function testUpdateDesc()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $bugService = $container->get(BugService::class);
        $user = $this->createUser($container);
        $category = $this->createCategory($container, $user);
        $statuses = $this->createStatuses($container);

        $bug = $this->createBug($category, $statuses[0]);
        $bugService->createBug($bug, $user);
        $id = $bug->getId();
        $createdBug = $bugService->getById($id);

        $newDesc = $this->faker->sentence(12);
        $createdBug->setDescription($newDesc);
        $bugService->updateBug($createdBug);
        $updatedBug = $bugService->getById($id);

        $this->assertEquals($newDesc, $updatedBug->getDescription());
    }

    public function testAddTag()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $bugService = $container->get(BugService::class);
        $user = $this->createUser($container);
        $category = $this->createCategory($container, $user);
        $statuses = $this->createStatuses($container);
        $tag = $this->createTag($container);

        $bug = $this->createBug($category, $statuses[0]);
        $bugService->createBug($bug, $user);
        $id = $bug->getId();
        $createdBug = $bugService->getById($id);

        $createdBug->addTag($tag);
        $bugService->updateBug($createdBug);
        $updatedBug = $bugService->getById($id);

        $this->assertEquals($tag->getId(), $updatedBug->getTags()[0]->getId());
    }

    public function testAddMultipleTags()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $bugService = $container->get(BugService::class);
        $user = $this->createUser($container);
        $category = $this->createCategory($container, $user);
        $statuses = $this->createStatuses($container);
        $tag = $this->createTag($container);
        $tag2 = $this->createTag($container);

        $bug = $this->createBug($category, $statuses[0]);
        $bugService->createBug($bug, $user);
        $id = $bug->getId();
        $createdBug = $bugService->getById($id);

        $createdBug->addTag($tag);
        $createdBug->addTag($tag2);
        $bugService->updateBug($createdBug);
        $updatedBug = $bugService->getById($id);

        $expected = array_map(function ($t) {
            return $t->getId();
        }, [$tag, $tag2]);

        $actual = array_map(function ($t) use ($updatedBug)  {
            return $t->getId();
        }, [$updatedBug->getTags()[0], $updatedBug->getTags()[1]]);

        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function testUpdateCategory()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $bugService = $container->get(BugService::class);
        $user = $this->createUser($container);
        $category = $this->createCategory($container, $user);
        $statuses = $this->createStatuses($container);

        $bug = $this->createBug($category, $statuses[0]);
        $bugService->createBug($bug, $user);
        $id = $bug->getId();
        $createdBug = $bugService->getById($id);

        $newCategory = $this->createCategory($container, $user);
        $createdBug->setCategory($newCategory);
        $createdBug->setStatus($statuses[1]);
        $bugService->updateBug($createdBug);
        $updatedBug = $bugService->getById($id);

        $this->assertEquals($newCategory, $updatedBug->getCategory());
    }

    public function testDelete()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $bugService = $container->get(BugService::class);

        $user = $this->createUser($container);
        $category = $this->createCategory($container, $user);
        $statuses = $this->createStatuses($container);

        $bug = $this->createBug($category, $statuses[0]);
        $bugService->createBug($bug, $user);
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
        $user = $this->createUser($container);
        $category = $this->createCategory($container, $user);
        $statuses = $this->createStatuses($container);

        // create second page
        array_map(function () use ($user, $category, $statuses, $bugService) {
            $bug = $this->createBug($category, $statuses[0]);
            $bugService->createBug($bug, $user);

            return $bug;
        }, range(1, BugService::PAGINATOR_ITEMS_PER_PAGE));

        sleep(1);

        $created = array_map(function () use ($user, $category, $statuses, $bugService) {
            $bug = $this->createBug($category, $statuses[0]);
            $bugService->createBug($bug, $user);
            sleep(1); // hack motzno, w scali można zrobić tick time na threadzie

            return $bug->getId();
        }, range(1, BugService::PAGINATOR_ITEMS_PER_PAGE));

        $firstPage = array_map(function ($bug) {
            return $bug->getId();
        }, $bugService->getAll(1)->getItems());

        $this->assertEquals(array_reverse($created), $firstPage);
    }

    private function createCategory($container, $user): Category
    {
        $categoryService = $container->get(CategoryService::class);

        $category = new Category();
        $title = $this->faker->word();
        $category->setTitle($title);
        $categoryService->createCategory($category, $user);

        return $category;
    }

    private function createTag($container): Tag
    {
        $tagService = $container->get(TagService::class);

        $tag = new Tag();
        $title = $this->faker->word();
        $tag->setTitle($title);
        $tagService->createTag($tag);

        return $tag;
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

    private function createStatuses($container): array
    {
        $statusService = $container->get(StatusRepository::class);
        $status1 = new Status();
        $status1->setTitle("solved");
        $statusService->save($status1);
        $status2 = new Status();
        $status2->setTitle("unsolved");
        $statusService->save($status2);

        return [$status1, $status2];
    }

    private function createBug($category, $status): Bug
    {
        $bug = new Bug();
        $title = $this->faker->word();

        $bug->setTitle($title);

        $desc = $this->faker->sentence(10);
        $bug->setDescription($desc);
        $bug->setCategory($category);
        $bug->setStatus($status);

        return $bug;
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
