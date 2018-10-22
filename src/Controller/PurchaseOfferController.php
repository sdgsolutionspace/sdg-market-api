<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\User;
use App\Entity\PurchaseOffer;
use App\Form\Type\PurchaseOfferType;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\QueryParam;

/**
 * @FOSRest\RouteResource("purchase-offer")
 * @FOSRest\NamePrefix(value="api_v1_purchase_offer_")
 */
class PurchaseOfferController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get all projects.
     *
     * @QueryParam(name="project", requirements="\d+", allowBlank=true, description="Project for which getting the offers")
     * @QueryParam(name="include_expired", requirements="^(0|1)$", default="0", strict=true, allowBlank=true, description="Project for which getting the offers")
     *
     * @return object
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $offers = $this->getDoctrine()->getManager()->getRepository(PurchaseOffer::class)->findFiltered(
            $paramFetcher->get('project'),
            $paramFetcher->get('include_expired')
        );

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
     *
     * @return PurchaseOffer|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);

        $purchaseOffer = new PurchaseOffer($user);
        $form = $this->createForm(PurchaseOfferType::class, $purchaseOffer);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (!$purchaseOffer->getOfferStartsUtcDate()) {
            $purchaseOffer->setOfferStartsUtcDate(new DateTime());
            $purchaseOffer->setOfferExpiresAtUtcDate(clone $purchaseOffer->getOfferStartsUtcDate());
            $purchaseOffer->getOfferExpiresAtUtcDate()->add(new DateInterval('P7D'));
        }

        $purchaseOffer->setPurchaser($user);

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
     * @param Request       $request
     *
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
