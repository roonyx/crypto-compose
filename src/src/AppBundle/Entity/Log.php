<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
     * Many Logs have Many Links.
     * @ORM\ManyToMany(targetEntity="Link", mappedBy="logs")
     */
    private $links;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", length=100)
     * @Assert\DateTime()
     */
    private $createdAt;

    public function __construct()
    {
        $this->coinsOfLog = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Log
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param Link $link
     *
     * @return Log
     */
    public function addLink($link)
    {
        $this->links[] = $link;

        return $this;
    }
}
