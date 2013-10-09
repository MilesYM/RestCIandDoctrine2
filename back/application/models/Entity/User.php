<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
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
     * @ORM\Column(name="username", type="string", length=32, precision=0, scale=0, nullable=false, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, precision=0, scale=0, nullable=false, unique=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, precision=0, scale=0, nullable=false, unique=true)
     */
    private $email;

    /**
     * @var \Entity\Security_Key
     *
     * @ORM\OneToOne(targetEntity="Entity\Security_Key", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="private_key_id", referencedColumnName="id", unique=true, onDelete="SET NULL")
     * })
     */
    private $private_key;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->friends = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * Encrypt a Password
     *
     * @param   string  $password
     * @return  string
     */
    public function hashPassword($password)
    {
        if ( ! $this->email)
        {
            throw new \Exception('The email must be set before the password can be hashed.');
        }

        return hash('sha256', $password . $this->email);
    }
    /**
     * Encrypt and set a Password
     *
     * @param   string  $password
     * @return  string
     */
    public function sethashedPassword($password)
    {
        if ( ! $this->email)
        {
            throw new \Exception('The email must be set before the password can be hashed.');
        }

        $this->password = hash('sha256', $password . $this->email);
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $this->hashPassword( $password );
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set notation
     *
     * @param integer $notation
     * @return User
     */
    public function setNotation($notation)
    {
        $this->notation = $notation;
    
        return $this;
    }

    /**
     * Get notation
     *
     * @return integer 
     */
    public function getNotation()
    {
        return $this->notation;
    }

    /**
     * Add friends
     *
     * @param \Entity\Friend $friends
     * @return User
     */
    public function addFriend(\Entity\Friend $friends)
    {
        $this->friends[] = $friends;
    
        return $this;
    }

    /**
     * Remove friends
     *
     * @param \Entity\Friend $friends
     */
    public function removeFriend(\Entity\Friend $friends)
    {
        $this->friends->removeElement($friends);
    }

    /**
     * Get friends
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * Set private_key
     *
     * @param \Entity\Security_Key $privateKey
     * @return User
     */
    public function setPrivateKey(\Entity\Security_Key $privateKey = null)
    {
        $this->private_key = $privateKey;
    
        return $this;
    }

    /**
     * Get private_key
     *
     * @return \Entity\Security_Key 
     */
    public function getPrivateKey()
    {
        return $this->private_key;
    }

    /**
     * Set age
     *
     * @param integer $age
     * @return User
     */
    public function setAge($age)
    {
        $this->age = $age;
    
        return $this;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set genre
     *
     * @param string $genre
     * @return User
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    
        return $this;
    }

    /**
     * Get genre
     *
     * @return string 
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set lookFor
     *
     * @param string $lookFor
     * @return User
     */
    public function setLookFor($lookFor)
    {
        $this->lookFor = $lookFor;
    
        return $this;
    }

    /**
     * Get lookFor
     *
     * @return string 
     */
    public function getLookFor()
    {
        return $this->lookFor;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return User
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return User
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}