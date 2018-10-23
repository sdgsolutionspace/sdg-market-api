<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\User;
use App\Entity\SellOffer;
use App\Entity\Transaction;
use App\Form\Type\SellOfferType;
use FOS\RestBundle\Request\ParamFetcher;
use App\Form\Type\TransactionBuyTokenType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\Annotations\QueryParam;

/**
 * @FOSRest\RouteResource("sell-offer")
 * @FOSRest\NamePrefix(value="api_v1_sell_offer_")
 */
class SellOfferController extends FOSRestController implements ClassResourceInterface
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
        $offers = $this->getDoctrine()->getManager()->getRepository(SellOffer::class)->findFiltered(
            $paramFetcher->get('project'),
            $paramFetcher->get('include_expired')
        );

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

    public function putBuyAction(Request $request, SellOffer $sellOffer)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);

        $transactionBuy = new Transaction();
        $transactionBuy->setToUser($user);

        $form = $this->createForm(TransactionBuyTokenType::class, $transactionBuy);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($transactionBuy);
        $em->flush();

        return $transactionBuy;
    }

    /**
     * Create a new SellOffer entry.
     *
     *
     * @param Request $request
     *
     * @return SellOffer|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);

        $sellOffer = new SellOffer($user);
        $form = $this->createForm(SellOfferType::class, $sellOffer);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (!$sellOffer->getOfferStartsUtcDate()) {
            $sellOffer->setOfferStartsUtcDate(new DateTime());
            $sellOffer->setOfferExpiresAtUtcDate(clone $sellOffer->getOfferStartsUtcDate());
            $sellOffer->getOfferExpiresAtUtcDate()->add(new DateInterval('P7D'));
        }

        $sellOffer->setSeller($user);

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
     * @param Request   $request
     *
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
