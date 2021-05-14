<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserServiceTest
 * @package App\Tests
 */
class UserServiceTest extends KernelTestCase
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
        $userService = $container->get(UserService::class);

        $users = $userService->getAll(1);

        $this->assertEquals(0, count($users));
    }

    public function testGetByIdNotFound()
    {
        self::bootKernel();
        $container = self::$container;
        $userService = $container->get(UserService::class);

        $user = $userService->getById(0);

        $this->assertEquals(null, $user);
    }

    public function testCreate()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $userService = $container->get(UserService::class);

        $user = $this->createUser();
        $userService->createUser($user);
        $id = $user->getId();

        $createdUser = $userService->getById($id);

        $this->assertEquals($user, $createdUser);
    }

    public function testUpdate()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $userService = $container->get(UserService::class);

        $user = $this->createUser();
        $newEmail = $this->faker->email();

        $userService->createUser($user);
        $id = $user->getId();
        $createdUser = $userService->getById($id);

        $createdUser->setEmail($newEmail);
        $userService->updateUser($createdUser);
        $updatedUser = $userService->getById($id);

        $this->assertEquals($newEmail, $updatedUser->getEmail());
    }

    public function testDelete()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $userService = $container->get(UserService::class);

        $user = $this->createUser();
        $userService->createUser($user);
        $id = $user->getId();
        $createdUser = $userService->getById($id);

        $userService->deleteUser($createdUser);
        $updatedUser = $userService->getById($id);

        $this->assertEquals(null, $updatedUser);
    }

    public function testGetMultiple()
    {
        self::bootKernel();
        $this->faker = Factory::create();
        $container = self::$container;
        $userService = $container->get(UserService::class);

        // create second page
        array_map(function () use ($userService) {
            $user = $this->createUser();
            $userService->createUser($user);

            return $user;
        }, range(1, UserRepository::PAGINATOR_ITEMS_PER_PAGE * 2));

        $firstPage = array_map(function ($user) {
            return $user->getEmail();
        }, $userService->getAll(1)->getItems());

        $this->assertEquals(UserRepository::PAGINATOR_ITEMS_PER_PAGE, count($firstPage));
    }

    private function createUser(): User
    {
        $user = new User();
        $user->setEmail($this->faker->email());
        $user->setRoles([User::ROLE_USER, User::ROLE_ADMIN]);
        $user->setPassword($this->faker->password());

        return $user;
    }
}
