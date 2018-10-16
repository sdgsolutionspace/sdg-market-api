<?php

namespace App\Controller;

use App\Entity\PurchaseOffer;
use App\Form\Type\PurchaseOfferType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations as FOSRest;

/**
 * @RouteResource("purchase-offer")
 * @FOSRest\NamePrefix(value="api_v1_purchase_offer_")
 */
class PurchaseOfferController extends FOSRestController
{
    /**
     * Get all projects.
     *
     * @return object
     */
    public function cgetAction()
    {
        $offers = $this->getDoctrine()->getManager()->getRepository(PurchaseOffer::class)->findAll();

        return $offers;
    }

    /**
     * Get single user by id.
     *
     * @param PurchaseOffer $purchaseOffer
     *
     * @return null|object
     */
    public function getAction(PurchaseOffer $purchaseOffer)
    {
        return $purchaseOffer;
    }

    /**
     * Create a new PurchaseOffer entry.
     *
     *
     * @param Request $request
     * @return PurchaseOffer|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $purchaseOffer = new PurchaseOffer();
        $form = $this->createForm(PurchaseOfferType::class, $purchaseOffer);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($purchaseOffer);
        $em->flush();

        return $purchaseOffer;
    }

    /**
     * Create a new PurchaseOffer entry.
     *
     *
     * @param PurchaseOffer $purchaseOffer
     * @param Request $request
     * @return PurchaseOffer|\Symfony\Component\HttpFoundation\Response
     */
    public function putAction(PurchaseOffer $purchaseOffer, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(PurchaseOfferType::class, $purchaseOffer);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($purchaseOffer);
        $em->flush();

        return $purchaseOffer;
    }

    public function deleteAction(PurchaseOffer $purchaseOffer)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($purchaseOffer);
        $em->flush();

        return ['success' => true];
    }
}
