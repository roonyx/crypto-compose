<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Log
 *
 * @ORM\Table(name="log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogRepository")
 */
class Log
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
     * @ORM\OneToMany(targetEntity="LogCoinRate", mappedBy="log")
     */
    private $coinsOfLog;


    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", length=100)
     */
    private $createdAt;

    public function __construct()
    {
        $this->coinsOfLog = new ArrayCollection();
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
     * @return ArrayCollection|Coin[]
     */
    public function getCoinsOfLog()
    {
        return $this->coinsOfLog;
    }


    public function addCoinsOfLog(Coin $coin)
    {
        if ($this->coinsOfLog->contains($coin)) {
            return;
        }
        $this->coinsOfLog[] = $coin;
        // not needed for persistence, just keeping both sides in sync
        $coin->addLoggedRate($this);
    }

    public function removeCoinsOfLog(Coin $coin)
    {
        if (!$this->coinsOfLog->contains($coin)) {
            return;
        }
        $this->coinsOfLog->removeElement($coin);
        // not needed for persistence, just keeping both sides in sync
        $coin->removeLoggedRate($this);
    }
}
