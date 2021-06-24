<?php
/**
 * Bug controller.
 */

namespace App\Controller;

use App\Entity\Bug;
use App\Form\BugType;
use App\Service\BugService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BugController.
 *
 * @Route("/")
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
     * @param Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="bug_index",
     * )
     */
    public function index(Request $request): Response
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        $page = $request->query->getInt('page', 1);
        $pagination = $this->bugService->getList($page, $filters);

        return $this->render(
            'bug/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param int $bugId Bug id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/bug/{bugId}",
     *     methods={"GET"},
     *     name="bug_show",
     *     requirements={"bugId": "[1-9]\d*"},
     * )
     */
    public function show(int $bugId): Response
    {
        $bug = $this->bugService->getById($bugId);

        return $this->render(
            'bug/show.html.twig',
            ['bug' => $bug]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/bug/create",
     *     methods={"GET", "POST"},
     *     name="bug_create",
     * )
     */
    public function create(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $bug = new Bug();
        $form = $this->createForm(BugType::class, $bug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bugService->createBug($bug, $this->getUser());
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('bug_index');
        }

        return $this->render(
            'bug/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param int                                       $bugId   Bug id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/bug/{bugId}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"bugId": "[1-9]\d*"},
     *     name="bug_edit",
     * )
     */
    public function edit(Request $request, int $bugId): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $bug = $this->bugService->getById($bugId);
        $form = $this->createForm(BugType::class, $bug, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bugService->updateBug($bug);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('bug_index');
        }

        return $this->render(
            'bug/edit.html.twig',
            [
                'form' => $form->createView(),
                'bug' => $bug,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param int                                       $bugId   Bug id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/bug/{bugId}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"bugId": "[1-9]\d*"},
     *     name="bug_delete",
     * )
     */
    public function delete(Request $request, int $bugId): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $bug = $this->bugService->getById($bugId);
        $form = $this->createForm(FormType::class, $bug, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bugService->deleteBug($bug);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('bug_index');
        }

        return $this->render(
            'bug/delete.html.twig',
            [
                'form' => $form->createView(),
                'bug' => $bug,
            ]
        );
    }
}
