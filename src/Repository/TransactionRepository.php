<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\SellOffer;
use App\Entity\GitProject;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function getProjectOwnValue(GitProject $project, User $user)
    {
        $positive = $this
            ->createQueryBuilder('t')
            ->select('sum(t.nbTokens)')
            ->leftJoin('t.toUser', 'toUser')
            ->where('t.project=:projectId')->setParameter('projectId', $project->getId())
            ->andWhere('toUser.id=:userId')->setParameter('userId', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();

        $negative = $this
            ->createQueryBuilder('t')
            ->select('sum(t.nbTokens)')
            ->leftJoin('t.fromUser', 'fromUser')
            ->where('t.project=:projectId')->setParameter('projectId', $project->getId())
            ->andWhere('fromUser.id=:userId')->setParameter('userId', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();

        return $positive - $negative;
    }

    public function findTransactionToUpdate()
    {
        return $this
            ->createQueryBuilder('t')
            ->select('t')
            ->where('t.id IN(SELECT pp.id FROM App:ProjectParticipation pp)')
            ->andWhere('t.toUser IS NULL')
            ->getQuery()
            ->getResult();
    }

    /**
     * Return the list of transaction depending the filtering options sent
     *
     * @param string|null $project Project id to be filtered
     * @param integer|null $fromUserId User Id of the transaction emitter to be filtered
     * @param integer|null $toUserId User Id of the transaction receiver to be filtered
     * @return array
     */
    public function findByParameters(?string $project, ?int $fromUserId, ?int $toUserId): array
    {
        $qb =  $this
            ->createQueryBuilder('t')
            ->select('t, fu, tu')
            ->leftJoin('t.project', "p")
            ->leftJoin('t.fromUser', "fu")
            ->leftJoin('t.toUser', "tu");

        if ($project) {
            $qb->andWhere("p.id = :project")->setParameter("project", $project);
        }

        if ($fromUserId) {
            $qb->andWhere("fu.id = :fromUserId")->setParameter("fromUserId", $fromUserId);
        }

        if ($toUserId) {
            $qb->andWhere("tu.id = :toUserId")->setParameter("toUserId", $toUserId);
        }

        return $qb->getQuery()->getResult();
    }

    public function getTransactionsConcerningOffer(SellOffer $sellOffer)
    {
        return $this
            ->createQueryBuilder('t')
            ->select('t')
            ->leftJoin('t.sellOffer', 'sellOffer')
            ->where('sellOffer.id=:sellOfferId')->setParameter('sellOfferId', $sellOffer->getId())
            ->getQuery()
            ->getResult();
    }

    public function getRemainingTokensForOffer(SellOffer $sellOffer)
    {
        $transactions = $this->getTransactionsConcerningOffer($sellOffer);
        $remainingTokens = $sellOffer->getNumberOfTokens();
        foreach ($transactions as $currentTransaction) {
            $remainingTokens -= abs($currentTransaction->getNbTokens());
        }

        return $remainingTokens;
    }
}
