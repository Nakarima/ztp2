<?php
/**
 * Tag service.
 */

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TagService.
 */
class TagService
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
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * TagController constructor.
     *
     * @param TagRepository      $tagRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(TagRepository $tagRepository, PaginatorInterface $paginator)
    {
        $this->tagRepository = $tagRepository;
        $this->paginator = $paginator;
    }

    /**
     * Returns all tags.
     *
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function getAll(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->tagRepository->queryAll(),
            $page,
            TagService::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Returns one tag.
     *
     * @param int $id
     *
     * @return Tag|null
     */
    public function getById(int $id): ?Tag
    {
        return $this->tagRepository->find($id);
    }

    /**
     * Returns one tag.
     *
     * @param string $title
     *
     * @return Tag|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneByTitle(strtolower($title));
    }

    /**
     * Creates tag.
     *
     * @param Tag $tag
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createTag(Tag $tag)
    {
        $this->tagRepository->save($tag);
    }

    /**
     * Updates tag.
     *
     * @param Tag $tag
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateTag(Tag $tag)
    {
        $this->tagRepository->save($tag);
    }

    /**
     * Deletes tag.
     *
     * @param Tag $tag
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteTag(Tag $tag)
    {
        $this->tagRepository->delete($tag);
    }
}
