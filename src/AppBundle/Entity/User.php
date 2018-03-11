<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User
{

 
    /**
      * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    protected $id;

    /**
     * @ORM\Column(type="string", name="username")

     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     */

    protected $email;

    /**
     * @ORM\Column(type="string")
     */

    protected $password;

    /**
     * @ORM\ManyToOne(targetEntity="Domain", inversedBy="users")
     * @var Domain
     */
    protected $domains;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

        public function getDomains()
    {
        return $this->domains;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

   public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

       public function setDomains($domains)
    {
        $this->domains = $domains;
        return $this;
    }
}