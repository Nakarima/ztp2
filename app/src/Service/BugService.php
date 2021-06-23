<?php
/**
 * Bug service.
 */

namespace App\Service;

use App\Entity\Bug;
use App\Entity\User;
use App\Repository\BugRepository;
use DateTime;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BugService.
 */
class BugService
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * @var BugRepository
     */
    private $bugRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * BugController constructor.
     *
     * @param BugRepository      $bugRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(BugRepository $bugRepository, PaginatorInterface $paginator)
    {
        $this->bugRepository = $bugRepository;
        $this->paginator = $paginator;
    }

    /**
     * Returns all bugs.
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function getAll(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->bugRepository->queryAll(),
            $page,
            BugService::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Returns one bug.
     * @param int $id
     *
     * @return Bug|null
     */
    public function getById(int $id): ?Bug
    {
        return $this->bugRepository->find($id);
    }

    /**
     * Creates bug.
     *
     * @param Bug  $bug
     * @param User $user
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createBug(Bug $bug, User $user)
    {
        $bug->setAuthor($user);
        $this->bugRepository->save($bug);
    }

    /**
     * Updates bug.
     *
     * @param Bug $bug
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateBug(Bug $bug)
    {
        $this->bugRepository->save($bug);
    }

    /**
     * Deletes bug.
     *
     * @param Bug $bug
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteBug(Bug $bug)
    {
        $this->bugRepository->delete($bug);
    }
}
