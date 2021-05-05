<?php
/**
 * Bug service.
 */

namespace App\Service;

use App\Entity\Bug;
use App\Repository\BugRepository;

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
     * BugController constructor.
     * @param BugRepository $bugRepository
     */
    public function __construct(BugRepository $bugRepository)
    {
        $this->bugRepository = $bugRepository;
    }

    /**
     * Returns all bugs.
     *
     * @return Bug[]
     */
    public function getAll(): array
    {
        return $this->bugRepository->findAll();
    }
}
