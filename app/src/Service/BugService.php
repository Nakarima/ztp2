<?php
/**
 * Bug service.
 */

namespace App\Service;

use App\Repository\BugRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BugService.
 */
class BugService
{
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
            BugRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
