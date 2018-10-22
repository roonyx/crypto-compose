<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use LinkerBundle\Validator\Constraints as LinkerAssert;
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
    public function setLastName($shortUrlId)
    {
        $this->shortUrlId = $shortUrlId;

        return $this;
    }
}
