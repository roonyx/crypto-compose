<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Coin
 *
 * @ORM\Table(name="coin")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CoinRepository")
 */
class Coin
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
     * @ORM\OneToMany(targetEntity="LogCoinRate", mappedBy="coin", fetch="EXTRA_LAZY")
     */
    private $loggedRates;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", length=100)
     */
    private $createdAt;


    /**
     * @var string
     *
     * @ORM\Column(name="updated_at", type="datetime", length=100)
     */
    private $updatedAt;


    public function __construct()
    {
        $this->loggedRates = new ArrayCollection();
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
    }


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
     * @return ArrayCollection|Log[]
     */
    public function getLoggedRates()
    {
        return $this->loggedRates;
    }

    public function addLoggedRate(Log $log)
    {
        if ($this->loggedRates->contains($log)) {
            return;
        }
        $this->loggedRates[] = $log;
        $log->addCoinsOfLog($this);
    }

    public function removeLoggedRate(Log $log)
    {
        if (!$this->loggedRates->contains($log)) {
            return;
        }
        $this->loggedRates->removeElement($log);
        $log->removeCoinsOfLog($this);
    }

}
