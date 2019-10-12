<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\User;
use App\Entity\SellOffer;
use App\Entity\Transaction;
use Swagger\Annotations as SWG;
use App\Form\Type\SellOfferType;
use App\Service\AutoMatchTransaction;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\Model;
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
     * Get all selling offers.
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="JWT token for authentication Bearer: Your_Token",
     *     required=true,
     *     type="string"
     * ),
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the list of selling offer",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=SellOffer::class))
     *     )
     * )
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
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="JWT token for authentication Bearer: Your_Token",
     *     required=true,
     *     type="string"
     * ),
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the selling offer corresponding to the id",
     *     @Model(type=SellOffer::class)
     * )
     *
     * @return null|object
     */
    public function getAction(SellOffer $sellOffer)
    {
        return $sellOffer;
    }

    /**
     * Buy part of an offer.
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="JWT token for authentication Bearer: Your_Token",
     *     required=true,
     *     type="string"
     * ),
     *
     * @SWG\Parameter(
     *    name="Query body",
     *    in="body",
     *    description="Reflect the buying proposition",
     *    @Model(type=TransactionBuyTokenType::class)
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the result of the transaction",
     *     @Model(type=Transaction::class)
     * )
     *
     * @param Request   $request
     * @param SellOffer $sellOffer
     */
    public function putBuyAction(Request $request, SellOffer $sellOffer)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);

        $transactionBuy = new Transaction();
        $transactionBuy->setToUser($user);
        $transactionBuy->setFromUser($sellOffer->getSeller());
        $transactionBuy->setSellOffer($sellOffer);

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
     * Create a new offer entry.
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="JWT token for authentication Bearer: Your_Token",
     *     required=true,
     *     type="string"
     * ),
     *
     * @SWG\Parameter(
     *    name="Query body",
     *    in="body",
     *    description="Reflect the offer to make",
     *    @Model(type=SellOfferType::class)
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the sell offer saved",
     *     @Model(type=SellOffer::class)
     * )
     *
     * @param Request $request
     *
     * @return SellOffer|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);

        /** @var AutoMatchTransaction $autoMatcher */
        $autoMatcher = $this->get(AutoMatchTransaction::class);

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

        $em->beginTransaction();
        try {
            $autoMatcher->autoMatchSell($sellOffer);
            $em->commit();
        } catch (Exception $e) {
            // If auto transaction is not working, the error is ignored and the purchase offer is created
            $em->rollback();
        }

        if ($sellOffer->getNumberOfTokens() > 0) {
            $em->persist($sellOffer);
        }
        $em->flush();

        return $sellOffer;
    }

    /**
     * Update SellOffer entry.
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="JWT token for authentication Bearer: Your_Token",
     *     required=true,
     *     type="string"
     * ),
     *
     * @SWG\Parameter(
     *    name="Query body",
     *    in="body",
     *    description="Reflect the new data of the offer",
     *    @Model(type=SellOfferType::class)
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the updated offer",
     *     @Model(type=Transaction::class)
     * )
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

    /**
     * Delete an offer.
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="JWT token for authentication Bearer: Your_Token",
     *     required=true,
     *     type="string"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return a success array",
     * )
     */
    public function deleteAction(SellOffer $sellOffer)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($sellOffer);
        $em->flush();

        return ['success' => true];
    }
}
