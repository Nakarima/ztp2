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
     * Category service.
     *
     * @var \App\Service\CategoryService
     */
    private $categoryService;

    /**
     * Tag service.
     *
     * @var \App\Service\TagService
     */
    private $tagService;

    /**
     * TaskService constructor.
     *
     * @param \App\Repository\BugRepository           $bugRepository   Bug repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator       Paginator
     * @param \App\Service\CategoryService            $categoryService Category service
     * @param \App\Service\TagService                 $tagService      Tag service
     */
    public function __construct(BugRepository $bugRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService)
    {
        $this->bugRepository = $bugRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Returns all bugs.
     *
     * @param int   $page
     * @param array $filters
     *
     * @return PaginationInterface
     */
    public function getList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->bugRepository->queryAll($filters),
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

    /**
     * Prepare filters for the tasks list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['category_id']) && is_numeric($filters['category_id'])) {
            $category = $this->categoryService->getById(
                $filters['category_id']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (isset($filters['tag_id']) && is_numeric($filters['tag_id'])) {
            $tag = $this->tagService->getById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }
}
