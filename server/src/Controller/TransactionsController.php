<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\Type\TransactionType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations as FOSRest;

/**
 * @RouteResource("Transaction")
 * @FOSRest\NamePrefix(value="api_v1_transactions_")
 */
class TransactionsController extends FOSRestController
{
    /**
     * Get all projects.
     *
     * @return object
     */
    public function cgetAction()
    {
        $offers = $this->getDoctrine()->getManager()->getRepository(Transaction::class)->findAll();

        return $offers;
    }

    /**
     * Get single user by id.
     *
     * @param Transaction $transaction
     *
     * @return null|object
     */
    public function getAction(Transaction $transaction)
    {
        return $transaction;
    }

    /**
     * Create a new Transaction entry.
     *
     *
     * @param Request $request
     * @return Transaction|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($transaction);
        $em->flush();

        return $transaction;
    }

    /**
     * Create a new Transaction entry.
     *
     *
     * @param Transaction $transaction
     * @param Request $request
     * @return Transaction|\Symfony\Component\HttpFoundation\Response
     */
    public function putAction(Transaction $transaction, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $em->persist($transaction);
        $em->flush();

        return $transaction;
    }

    public function deleteAction(Transaction $transaction)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($transaction);
        $em->flush();

        return ['success' => true];
    }
}
