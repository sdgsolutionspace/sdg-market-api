<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SellOffer
 *
 * @ORM\Table(name="sell_offer", indexes={@ORM\Index(name="fk_trading_user_idx", columns={"seller_id"}), @ORM\Index(name="fk_sell_offer_project1_idx", columns={"project_id"})})
 * @ORM\Entity
 */
class SellOffer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="seller_id", type="integer", nullable=false)
     */
    private $sellerId;

    /**
     * @var string
     *
     * @ORM\Column(name="number_of_tokens", type="decimal", precision=6, scale=2, nullable=false)
     */
    private $numberOfTokens;

    /**
     * @var string
     *
     * @ORM\Column(name="sell_price_per_token", type="decimal", precision=6, scale=2, nullable=false)
     */
    private $sellPricePerToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="offer_stats_utc_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $offerStatsUtcDate = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="offer_expires_at_utc_date", type="datetime", nullable=false, options={"default"="0000-00-00 00:00:00"})
     */
    private $offerExpiresAtUtcDate = '0000-00-00 00:00:00';

    /**
     * @var \GitProject
     *
     * @ORM\ManyToOne(targetEntity="GitProject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;


}
