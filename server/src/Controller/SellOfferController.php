<?php

namespace App\Controller;

use App\Entity\SellOffer;
use App\Form\Type\SellOfferType;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;

/**
 * @FOSRest\RouteResource("sell-offer")
 * @FOSRest\NamePrefix(value="api_v1_sell_offer_")
 */
class SellOfferController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get all projects.
     *
     * @return object
     */
    public function cgetAction()
    {
        $offers = $this->getDoctrine()->getManager()->getRepository(SellOffer::class)->findAll();

        return $offers;
    }

    /**
     * Get single user by id.
     *
     * @param SellOffer $sellOffer
     *
     * @return null|object
     */
    public function getAction(SellOffer $sellOffer)
    {
        return $sellOffer;
    }

    /**
     * Create a new SellOffer entry.
     *
     *
     * @param Request $request
     * @return SellOffer|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sellOffer = new SellOffer();
        $form = $this->createForm(SellOfferType::class, $sellOffer);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($sellOffer);
        $em->flush();

        return $sellOffer;
    }

    /**
     * Create a new SellOffer entry.
     *
     *
     * @param SellOffer $sellOffer
     * @param Request $request
     * @return SellOffer|\Symfony\Component\HttpFoundation\Response
     */
    public function putAction(SellOffer $sellOffer, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SellOfferType::class, $sellOffer);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($sellOffer);
        $em->flush();

        return $sellOffer;
    }

    public function deleteAction(SellOffer $sellOffer)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($sellOffer);
        $em->flush();

        return ['success' => true];
    }
}
