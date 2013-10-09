<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Security_Key
 *
 * @ORM\Table(name="sec_keys")
 * @ORM\Entity
 */
class Security_Key
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=40, precision=0, scale=0, nullable=false, unique=false)
     */
    private $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $level;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ignore_limits", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $ignoreLimits;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_private_key", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $isPrivateKey;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_addresses", type="text", precision=0, scale=0, nullable=true, unique=false)
     */
    private $ipAddresses;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateCreated;

    public function __construct()
    {
        $this->dateCreated = new \DateTime('now');
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Security_Key
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Security_Key
     */
    public function setLevel($level)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set ignoreLimits
     *
     * @param boolean $ignoreLimits
     * @return Security_Key
     */
    public function setIgnoreLimits($ignoreLimits)
    {
        $this->ignoreLimits = $ignoreLimits;
    
        return $this;
    }

    /**
     * Get ignoreLimits
     *
     * @return boolean 
     */
    public function getIgnoreLimits()
    {
        return $this->ignoreLimits;
    }

    /**
     * Set isPrivateKey
     *
     * @param boolean $isPrivateKey
     * @return Security_Key
     */
    public function setIsPrivateKey($isPrivateKey)
    {
        $this->isPrivateKey = $isPrivateKey;
    
        return $this;
    }

    /**
     * Get isPrivateKey
     *
     * @return boolean 
     */
    public function getIsPrivateKey()
    {
        return $this->isPrivateKey;
    }

    /**
     * Set ipAddresses
     *
     * @param string $ipAddresses
     * @return Security_Key
     */
    public function setIpAddresses($ipAddresses)
    {
        $this->ipAddresses = $ipAddresses;
    
        return $this;
    }

    /**
     * Get ipAddresses
     *
     * @return string 
     */
    public function getIpAddresses()
    {
        return $this->ipAddresses;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Security_Key
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    
        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }
}