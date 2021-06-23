<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class CategoryService
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
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * CategoryController constructor.
     *
     * @param CategoryRepository $categoryRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(CategoryRepository $categoryRepository, PaginatorInterface $paginator)
    {
        $this->categoryRepository = $categoryRepository;
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
            $this->categoryRepository->queryAll(),
            $page,
            CategoryService::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Returns one category.
     * @param int $id
     *
     * @return Category|null
     */
    public function getById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    /**
     * Creates category.
     *
     * @param Category $category
     * @param User     $user
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createCategory(Category $category, User $user)
    {
        $category->setAuthor($user);
        $this->categoryRepository->save($category);
    }

    /**
     * Updates category.
     *
     * @param Category $category
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateCategory(Category $category)
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Deletes category.
     *
     * @param Category $category
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteCategory(Category $category)
    {
        $this->categoryRepository->delete($category);
    }
}
