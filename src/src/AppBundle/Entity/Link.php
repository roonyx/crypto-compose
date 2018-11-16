<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Link
 *
 * @ORM\Table(name="link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LinkRepository")
 */
class Link
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
     * Many Links have Many Logs.
     * @ORM\ManyToMany(targetEntity="Log", inversedBy="links")
     * @ORM\JoinTable(name="links_logs")
     */
    private $logs;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $shortUrlId;

    /**
     * @var int
     *
     * @ORM\Column(name="imp_count", type="integer")
     */
    private $impCount;

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


    public function __construct() {
        $this->logs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return mixed
     */
    public function getLogs()
    {
        return $this->logs;
    }


    /**
     * @param Log $log
     *
     * @return Link
     */
    public function addLog($log)
    {
        $this->logs[] = $log;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortUrlId()
    {
        return $this->shortUrlId;
    }

    /**
     * @param string $shortUrlId
     *
     * @return Link
     */
    public function setShortUrlId($shortUrlId)
    {
        $this->shortUrlId = $shortUrlId;

        return $this;
    }

    /**
     * Get impCount.
     *
     * @return int
     */
    public function getImpCount()
    {
        return $this->impCount;
    }

    /**
     * @param int $impCount
     * @return Link
     */
    public function setImpCount($impCount)
    {
        $this->impCount = $impCount;

        return $this;
    }

    /**
     * @return Link
     */
    public function incrementImpCount()
    {
        $this->impCount++;

        return $this;
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
     * @return Link
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}
