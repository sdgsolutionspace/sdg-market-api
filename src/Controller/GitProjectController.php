<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\GitProject;
use Swagger\Annotations as SWG;
use App\Form\Type\GitProjectType;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @RouteResource("git-project")
 * @FOSRest\NamePrefix(value="api_v1_git_project_")
 */
class GitProjectController extends FOSRestController
{
    /**
     * Get all projects.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the list of git project",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=GitProject::class))
     *     )
     * )
     *
     * @return object
     */
    public function cgetAction()
    {
        //$offers = $this->getDoctrine()->getManager()->getRepository(GitProject::class)->findAll();
        $em = $this->getDoctrine()->getManager();

        $user = null;

        if ($this->getUser()) {
            $user = $em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);
        }

        $offers = $this->getDoctrine()->getManager()->getRepository(GitProject::class)->findAllWithPersonalValue($user);

        return $offers;
    }

    /**
     * Get single project by id.
     *
     * @param GitProject $gitProject
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return project corresponding to the ID",
     *     @Model(type=GitProject::class)
     * )
     *
     * @return null|object
     */
    public function getAction(GitProject $gitProject)
    {
        $user = null;
        $em = $this->getDoctrine()->getManager();

        if ($this->getUser()) {
            $user = $em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);
        }

        $gitProject = $em->getRepository(GitProject::class)->findWithOwnership($gitProject, $user);

        return $gitProject;
    }

    /**
     * Create a new a gitProject entry.
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     *
     * @SWG\Parameter(
     *    name="Query body",
     *    in="body",
     *    description="Definition of the project to create",
     *    @Model(type=GitProjectType::class)
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the created git project",
     *     @Model(type=GitProject::class)
     * )
     *
     * @return GitProject|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $gitProject = new gitProject();
        $form = $this->createForm(GitProjectType::class, $gitProject);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $user = $em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);
        $gitProject->setCreatedBy($user);

        $em->persist($gitProject);
        $em->flush();

        return $gitProject;
    }

    /**
     * Update a gitProject entry.
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param GitProject $gitProject
     * @param Request    $request
     *
     * @SWG\Parameter(
     *    name="Query body",
     *    in="body",
     *    description="Definition of the project to update",
     *    @Model(type=GitProjectType::class)
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the updated git project",
     *     @Model(type=GitProject::class)
     * )
     *
     * @return GitProject|\Symfony\Component\HttpFoundation\Response
     */
    public function putAction(GitProject $gitProject, Request $request, AuthorizationCheckerInterface $auth)
    {
        $em = $this->getDoctrine()->getManager();

        $isAdmin = $auth->isGranted("ROLE_ADMIN");
        $user = $em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);

        if (!$isAdmin && !$gitProject->getCreatedBy()) {
            throw new AccessDeniedHttpException("Only admin can update project from other users");
        }

        if (!$isAdmin && ($gitProject->getCreatedBy()->getId() != $user->getId())) {
            throw new AccessDeniedHttpException("Only admin can update project from other users");
        }

        $form = $this->createForm(GitProjectType::class, $gitProject);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($gitProject);
        $em->flush();

        return $gitProject;
    }

    /**
     * Remove a project.
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return a success array",
     * )
     *
     * @param GitProject $gitProject
     */
    public function deleteAction(GitProject $gitProject)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($gitProject);
        $em->flush();

        return ['success' => true];
    }
}
