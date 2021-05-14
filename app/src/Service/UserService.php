<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserService.
 */
class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * UserController constructor.
     *
     * @param UserRepository     $userRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
    }

    /**
     * Returns all categories.
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function getAll(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Returns one user.
     *
     * @param int $id
     *
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * Creates user.
     *
     * @param User $user
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createUser(User $user)
    {
        $this->userRepository->save($user);
    }

    /**
     * Updates user.
     *
     * @param User $user
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateUser(User $user)
    {
        $this->userRepository->save($user);
    }

    /**
     * Deletes user.
     *
     * @param User $user
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteUser(User $user)
    {
        $this->userRepository->delete($user);
    }
}
