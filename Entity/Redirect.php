<?php

namespace Funstaff\Bundle\RedirectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */

/**
 * @ORM\Entity(repositoryClass="Funstaff\Bundle\RedirectBundle\Entity\Repository\RedirectRepository")
 * @ORM\Table(name="redirect")
 * @UniqueEntity(fields={"source"})
 */
class Redirect
{
    const STATUS_MOVED_PERMANENTLY  = 301;
    const STATUS_FOUND              = 302;
    const STATUS_TEMPORARY_REDIRECT = 307;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @ORM\Column(name="source", type="string", length=255, unique=true)
     * @Assert\NotNull()
     * @Assert\length(max=255)
     */
    protected $source;

    /**
     * @ORM\Column(name="destination", type="string", length=255)
     * @Assert\NotNull()
     * @Assert\length(max=255)
     */
    protected $destination;

    /**
     * @ORM\Column(name="status_code", type="integer")
     */
    protected $statusCode;

    /**
     * @ORM\Column(name="last_accessed", type="datetime", nullable=true)
     */
    protected $lastAccessed;

    /**
     * @ORM\Column(name="stat_count", type="integer")
     */
    protected $statCount;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->status = self::STATUS_FOUND;
        $this->statCount = 0;
    }

    /**
     * Get Id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Created At
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get Updated At
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set Enabled
     *
     * @param boolean $enabled
     *
     * @return Redirect
     */
    public function setEnabled($enabled = true)
    {
        $this->enabled = (boolean) $enabled;

        return $this;
    }

    /**
     * Get Is Enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Get Is Enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set Source
     *
     * @param string $source
     *
     * @return Redirect
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get Source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set Destination
     *
     * @param string $destination
     *
     * @return Redirect
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get Destination
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set Status Code
     *
     * @param integer $statusCode
     *
     * @throws InvalidArgumentException
     * @return Redirect
     */
    public function setStatusCode($statusCode)
    {
        if (!in_array($statusCode, array_keys(Response::$statusTexts))) {
            throw new \InvalidArgumentException("Invalid status code");
        }

        $this->statusCode = (int) $statusCode;

        return $this;
    }

    /**
     * Get Status Code
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set Last Accessed
     *
     * @param DateTime $lastAccessed
     *
     * @return Redirect
     */
    public function setLastAccessed(\DateTime $lastAccessed)
    {
        $this->lastAccessed = $lastAccessed;

        return $this;
    }

    /**
     * Get Last Accessed
     *
     * @return DateTime
     */
    public function getLastAccessed()
    {
        return $this->lastAccessed;
    }

    /**
     * Set Stat Count
     *
     * @param integer $statCount
     *
     * @return Redirect
     */
    public function setStatCount($statCount)
    {
        $this->statCount = (int) $statCount;

        return $this;
    }

    /**
     * get Stat Count
     *
     * @return integer
     */
    public function getStatCount()
    {
        return $this->statCount;
    }

    /**
     * increase Stat Count
     *
     * @return Redirect
     */
    public function increaseStatCount()
    {
        $this->statCount++;

        return $this;
    }
}