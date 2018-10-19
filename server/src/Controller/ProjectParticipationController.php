<?php

namespace App\Controller;

use App\Entity\ProjectParticipation;
use FOS\RestBundle\Request\ParamFetcher;
use App\Form\Type\ProjectParticipationType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @RouteResource("contributions")
 * @FOSRest\NamePrefix(value="api_v1_contributions_")
 */
class ProjectParticipationController extends FOSRestController
{
    /**
     * Get all projects.
     *
     * @QueryParam(name="project", requirements="\d+", allowBlank=true, description="Project for which getting the participations")
     *
     * @return object
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $participations = $this->getDoctrine()->getManager()->getRepository(ProjectParticipation::class)->findFiltered(
            $paramFetcher->get('project')
        );

        return $participations;
    }

    /**
     * Get single user by id.
     *
     * @param ProjectParticipation $contribution
     *
     * @return null|object
     */
    public function getAction(ProjectParticipation $contribution)
    {
        return $contribution;
    }

    /**
     * Create a new contribution entry.
     *
     *
     * @param Request $request
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return ProjectParticipation|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contribution = new ProjectParticipation();
        $form = $this->createForm(ProjectParticipationType::class, $contribution);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($contribution);
        $em->flush();

        return $contribution;
    }

    /**
     * Create a new contribution entry.
     *
     *
     * @param ProjectParticipation $contribution
     * @param Request              $request
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return ProjectParticipation|\Symfony\Component\HttpFoundation\Response
     */
    public function putAction(ProjectParticipation $contribution, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProjectParticipationType::class, $contribution);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($contribution);
        $em->flush();

        return $contribution;
    }

    /**
     * Remove a contribution.
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param ProjectParticipation $contribution
     */
    public function deleteAction(ProjectParticipation $contribution)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($contribution);
        $em->flush();

        return ['success' => true];
    }
}
