<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="domain")
 */
class Domain
{


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */

    protected $id;

    /**
    * @ORM\Column(type="integer", name="user_id")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", fetch="EAGER")
     * @var User[]
     */
    protected $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
         $this->domain_lang = new ArrayCollection();
    }
        /**
     * @ORM\ManyToMany(targetEntity="DomainLanguage", inversedBy="domain_id")
     * @var DomainLanguage[]
     */
    protected $domain_lang;


   


    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    public function getUser()
    {
        return $this->user;
    }

        public function getDomainLang()
    {
        return $this->domain_lang;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

        public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setDomainLang($domain_lang)
    {
        $this->domain_lang = $domain_lang;
        return $this;
    }
}