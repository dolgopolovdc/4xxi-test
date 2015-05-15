<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
*
* @ORM\Entity
* @ORM\Table(name="messages")
*/
class Message
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
     * @var User
     *
     * @ORM\ManyToOne(
     *      targetEntity="AppBundle\Entity\User",
     *      inversedBy="messages"
     * )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;


    /**
     * @var string
     *
     * @ORM\Column(
     *      name="message",
     *      type="string",
     *      length=255
     * )
     */
    private $message;
    

    /**
     * @ORM\Column(
     *      name="created",
     *      type="datetime"
     * )
     */
    protected $created;
    
    /**
     * @ORM\Column(
     *      name="updated",
     *      type="datetime"
     * )
     */
    protected $updated;

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
     * @return Message
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Message
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Message
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Message
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
