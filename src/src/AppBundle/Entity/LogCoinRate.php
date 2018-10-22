<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogCoinRate
 *
 * @ORM\Table(name="log_coin_rate")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogCoinRateRepository")
 */
class LogCoinRate
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Coin", inversedBy="loggedRates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $coin;

    /**
     * @ORM\ManyToOne(targetEntity="Log", inversedBy="coinsOfLog")
     * @ORM\JoinColumn(nullable=false)
     */
    private $log;


    /**
     * Currency rate per USD
     * @ORM\Column(type="string")
     */
    private $rate;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getCoin()
    {
        return $this->coin;
    }

    /**
     * Set coin.
     * @param $coin
     * @return LogCoinRate
     */
    public function setCoin($coin)
    {
        $this->coin = $coin;

        return $this;
    }

    /**
     * Get log.
     *
     * @return Log
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Set log.
     * @param $log
     * @return LogCoinRate
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get id.
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set rate.
     * @param $rate
     * @return LogCoinRate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }
}
