<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements UserInterface//, \Serializable
{
    /**
     * @var integer $id
     * 
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(
     *      name="id",
     *      type="integer"
     * )
     */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(
     *      name="username",
     *      type="string",
     *      length=25,
     *      unique=true
     * )
     */
    private $username;

    /**
     * @var string
     * 
     * @ORM\Column(
     *      name="resource_username",
     *      type="string",
     *      length=25,
     *      nullable=true
     * )
     */
    private $resourceUsername;

    /**
     * @var string
     * 
     * @ORM\Column(
     *      name="realname",
     *      type="string",
     *      length=25,
     *      nullable=true
     * )
     */
    private $realname;

    /**
     * @var string
     * 
     * @ORM\Column(
     *      name="password",
     *      type="string",
     *      length=64,
     *      nullable=true
     * )
     */
    private $password;

    /**
     * @ORM\Column(
     *      name="email",
     *      type="string",
     *      length=100,
     *      unique=true
     * )
     */
    private $email;

    /**
     * @var string
     * 
     * @ORM\Column(
     *      name="salt",
     *      type="string",
     *      length=100,
     *      unique=false
     *  )
     */
    private $salt;

    /**
     * @var boolean
     * 
     * @ORM\Column(
     *      name="$is_active",
     *      name="is_active",
     *      type="boolean"
     * )
     */
    private $isActive;

    /**
     * @var string
     * 
     * @ORM\Column(
     *      name="resource",
     *      type="string",
     *      length=100
     * )
     */
    private $resource;

    /**
     * @var datetime
     * 
     * @ORM\Column(
     *      name="last_login",
     *      type="datetime"
     * )
     */
    private $lastLogin;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(
     *      targetEntity="AppBundle\Entity\Message",
     *      mappedBy="user"
     * )
     * @ORM\OrderBy({"created"="DESC"})
     */
    private $messages;

    public function __construct()
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->roles = array();
        $this->setIsActive(true);
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
     * Set id
     *
     * @param integer
     * @return User
     */
    public function setId($id)
    {
         $this->id = $id;

         return $this;
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
     * Set resourceUsername
     *
     * @param string $resourceUsername
     * @return User
     */
    public function setResourceUsername($resourceUsername)
    {
        $this->resourceUsername = $resourceUsername;

        return $this;
    }

    /**
     * Get resourceUsername
     *
     * @return string 
     */
    public function getResourceUsername()
    {
        return $this->resourceUsername;
    }

    /**
     * Set realname
     *
     * @param string $realname
     * @return User
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;

        return $this;
    }

    /**
     * Get realname
     *
     * @return string 
     */
    public function getRealname()
    {
        return $this->realname;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

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
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set resource
     *
     * @param string $resource
     * @return User
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource
     *
     * @return string 
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime 
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\User\UserInterface::getRoles()
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }
    
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\User\UserInterface::eraseCredentials()
     */
    public function eraseCredentials()
    {
        //todo
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
