<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="translation")
 */
class Translation
{
    /**
    * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    protected $id;

    /**
     * @ORM\JoinColumn(nullable=false)
    * @ORM\ManyToOne(targetEntity="Domain", inversedBy="translations")
     */
    protected $domain;

    /**
    * @ORM\Column(type="string", name="code")
     */
    protected $code;


    public function getId()
    {
        return $this->id;
    }

    public function getDomain()
    {
        return $this->domain;
    }
    public function getCode()
    {
        return $this->code;
    }
        public function getTranslations()
    {
        return $this->translations;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setDomain($domain)
    {
        $this->domain_id = $domain_id;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
   public function setTranslations($translations)
    {
        $this->translations = $translations;
        return $this;
    }

}
