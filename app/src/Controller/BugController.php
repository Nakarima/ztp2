<?php
/**
 * Bug controller.
 */

namespace App\Controller;

use App\Entity\Bug;
use App\Service\BugService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BugController.
 *
 * @Route("/bug")
 */
class BugController extends AbstractController
{
    /**
     * @var BugService
     */
    private $bugService;

    /**
     * BugController constructor.
     * @param BugService $bugService
     */
    public function __construct(BugService $bugService)
    {
        $this->bugService = $bugService;
    }

    /**
     * Index action.
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="bug_index",
     * )
     */
    public function index(): Response
    {
        return $this->render(
            'bug/index.html.twig',
            ['bugs' => $this->bugService->getAll()]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Bug $bug Bug entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="bug_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Bug $bug): Response
    {
        return $this->render(
            'bug/show.html.twig',
            ['bug' => $bug]
        );
    }
}
