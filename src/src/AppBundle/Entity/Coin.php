<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\OneToMany(targetEntity="LogCoinRate", mappedBy="coin", fetch="EXTRA_LAZY", orphanRemoval=true, cascade={"persist"})
     */
    private $loggedRates;

    /**
     * Coin name
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * Coin short name
     * @ORM\Column(type="string", length=10)
     */
    private $shortName;


    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", length=100)
     * @Assert\DateTime()
     */
    private $createdAt;


    /**
     * @var string
     *
     * @ORM\Column(name="updated_at", type="datetime", length=100)
     * @Assert\DateTime()
     */
    private $updatedAt;


    public function __construct()
    {
        $this->loggedRates = new ArrayCollection();
        $this->updatedAt = new \DateTime();
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
     * @return Coin
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Coin
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Coin
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param mixed $shortName
     * @return Coin
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

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
     * @return ArrayCollection|Log[]
     */
    public function getLoggedRates()
    {
        return $this->loggedRates;
    }

    public function addLoggedRate(LogCoinRate $logCoinRate)
    {
        if ($this->loggedRates->contains($logCoinRate)) {
            return;
        }
        $this->loggedRates[] = $logCoinRate;
        $logCoinRate->setCoin($this);
    }

    public function removeLoggedRate(LogCoinRate $logCoinRate)
    {
        if (!$this->loggedRates->contains($logCoinRate)) {
            return;
        }
        $this->loggedRates->removeElement($logCoinRate);
        $logCoinRate->setCoin(null);
    }

}
