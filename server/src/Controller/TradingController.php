<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Trading;
use App\Entity\SellOffer;
use App\Entity\UserToken;
use App\Entity\Transaction;
use App\Entity\PurchaseOffer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use App\Form\Type\TradingType;

/**
 * Class TradingController.
 *
 * @FOSRest\NamePrefix(value="api_v1_trades_")
 * @FOSRest\Prefix("/trading")
 */
class TradingController extends FOSRestController
{
    public function cgetAction()
    {
        $trades = $this->getDoctrine()->getManager()->getRepository(Trading::class)->findAll();

        return $trades;
    }

    /**
     * Get single user by id.
     *
     * @param Trading $trading
     *
     * @return null|object
     */
    public function getAction(Trading $trading)
    {
        return $trading;
    }

    /**
     * Create a new trading entry.
     *
     *
     * @param Request $request
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $trading = new Trading();
        $form = $this->createForm(TradingType::class, $trading);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($trading);
        $em->flush();

        return $trading;
    }

    public function deleteAction(Trading $trading)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($trading);
        $em->flush();

        return ['success' => 'OK'];
    }

//    /**
//     * @Route("/user/{id}/tokens", name="get_user_token", methods={"GET"})
//     * @return array
//     */
//    public function getUserTokenAction($id)
//    {
//        $repository = $this->getDoctrine()->getRepository(UserToken::class);
//        $userTokens = $repository->findByUserId($id);
//
//        if(!$userTokens) {
//            return new JsonResponse([
//                'message' => 'User Token not found'
//            ], JsonResponse::HTTP_NOT_FOUND);
//        }
//
//        return new JsonResponse(["data"=>$userTokens], JsonResponse::HTTP_OK);
//
//    }
//
//    /**
//     * @Route("/user/{id}/tokens/{git_project_id}", name="get_user_project_token", methods={"GET"})
//     * @return array
//     */
//    public function getUserProjectTokenAction($id,$git_project_id)
//    {
//        $repository = $this->getDoctrine()->getRepository(UserToken::class);
//        $userTokens = $repository->findBy(
//                        array('user_id' => $id),
//                        array('git_project_id' => $git_project_id)
//                    );
//
//        if(!$userTokens) {
//            return new JsonResponse([
//                'message' => 'User Project Token not found'
//            ], JsonResponse::HTTP_NOT_FOUND);
//        }
//
//        return new JsonResponse(["data"=>$userTokens], JsonResponse::HTTP_OK);
//
//    }
//
//    /**
//     * @Route("/offers/sell", name="get_offers_sell", methods={"GET"})
//     * @return array
//     */
//    public function getOffersSellAction()
//    {
//        $repository = $this->getDoctrine()->getRepository(SellOffer::class);
//        $offersSell = $repository->findAll();
//
//        if(!$offersSell) {
//            return new JsonResponse([
//                'message' => 'Offers Sell not found'
//            ], JsonResponse::HTTP_NOT_FOUND);
//        }
//
//        return new JsonResponse(["data"=>$offersSell], JsonResponse::HTTP_OK);
//
//    }
//
//    /**
//     * @Route("/offers/purchase", name="get_purhase_offers", methods={"GET"})
//     * @return array
//     */
//    public function getPurchaseOfferAction()
//    {
//        $repository = $this->getDoctrine()->getRepository(PurchaseOffer::class);
//        $purchaseOffer = $repository->findAll();
//
//        if(!$purchaseOffer) {
//            return new JsonResponse([
//                'message' => 'Purchase Offer not found'
//            ], JsonResponse::HTTP_NOT_FOUND);
//        }
//
//        return new JsonResponse(["data"=>$purchaseOffer], JsonResponse::HTTP_OK);
//
//    }
//
//    /**
//     * @Route("/offers/sell", name="create_sell_offer", methods={"POST"})
//     * @return array
//     */
//    public function createSellOffer(Request $request)
//    {
//        $offersSell = new SellOffer();
//        $offersSell->setSellerId($request->request->get('seller_id'));
//        $offersSell->setNumberOfTokens($request->request->get('number_of_tokens'));
//        $offersSell->setSellPricePerToken($request->request->get('sell_price_per_token'));
//        $offersSell->setOfferStartsUtcDate($request->request->get('offer_start_utc_date'));
//        $offersSell->setOfferExpiresAtUtcDate($request->request->get('offer_expire_utc_date'));
//        $offersSell->setProject($request->request->get('project'));
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($offersSell);
//        $em->flush();
//
//        return new JsonResponse(["message"=>"created Successfullly"], JsonResponse::HTTP_CREATED);
//
//    }
//
//
//    /**
//     * @Route("/offers/purchase", name="create_purchase_offer", methods={"POST"})
//     * @return array
//     */
//    public function createPurchaseOffer(Request $request)
//    {
//        $purchaseOffer = new PurchaseOffer();
//        $purchaseOffer->setPurchaserId($request->request->get('purchaser_id'));
//        $purchaseOffer->setNumberOfTokens($request->request->get('number_of_tokens'));
//        $purchaseOffer->setPurchasePricePerToken($request->request->get('purchase_price_per_token'));
//        $purchaseOffer->setOfferStartsUtcDate($request->request->get('offer_start_utc_date'));
//        $purchaseOffer->setOfferExpiresAtUtcDate($request->request->get('offer_expire_utc_date'));
//        $purchaseOffer->setProject($request->request->get('project'));
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($purchaseOffer);
//        $em->flush();
//
//        return new JsonResponse(["message"=>"created Successfullly"], JsonResponse::HTTP_CREATED);
//
//    }
//
//
//    /**
//     * @Route("/offers/buy", name="buy_offer", methods={"POST"})
//     * @return array
//     */
//    public function createBuyOffer(Request $request)
//    {
//
//    }
//
//
//    /**
//     * @Route("/users/{id}/transactions", name="user_transactions", methods={"GET"})
//     * @return array
//     */
//    public function getUserTransactionsAction($id)
//    {
//        $repository = $this->getDoctrine()->getRepository(Transaction::class);
//        $userTransactions = $repository->findByUserId($id);
//
//        if(!$userTransactions) {
//            return new JsonResponse([
//                'message' => 'User Transaction not found'
//            ], JsonResponse::HTTP_NOT_FOUND);
//        }
//
//        return new JsonResponse(["data"=>$userTransactions], JsonResponse::HTTP_OK);
//
//    }
//
//    /**
//     * @Route("/projects/{id}/transactions", name="project_transactions", methods={"GET"})
//     * @return array
//     */
//    public function getProjectTransactionsAction($id)
//    {
//        $repository = $this->getDoctrine()->getRepository(Transaction::class);
//        $projectTransactions = $repository->findByProjectParticipationId($id);
//
//        if(!$projectTransactions) {
//            return new JsonResponse([
//                'message' => 'Project Transaction not found'
//            ], JsonResponse::HTTP_NOT_FOUND);
//        }
//
//        return new JsonResponse(["data"=>$projectTransactions], JsonResponse::HTTP_OK);
//    }
//
//
//    /**
//     * @Route("/trades", name="trades", methods={"GET"})
//     * @return array
//     */
//    public function getTrades()
//    {
//        $repository = $this->getDoctrine()->getRepository(Trading::class);
//        $trades = $repository->findAll();
//
//        if(!$trades) {
//            return new JsonResponse([
//                'message' => 'Trades not found'
//            ], JsonResponse::HTTP_NOT_FOUND);
//        }
//
//        return new JsonResponse(["data"=>$trades], JsonResponse::HTTP_OK);
//    }
//
//
//
}
