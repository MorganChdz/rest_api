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
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    protected $id;

    /**
    * @ORM\Column(type="string", name="domain_id", unique= true)
    * @ORM\OneToMany(targetEntity="Domain", mappedBy="id")
     */
    protected $domain_id;

    /**
    * @ORM\Column(type="string", name="code")
     * @ORM\OneToMany(targetEntity="Lang", mappedBy="code")
     */
    protected $code;

    /**
    * @ORM\ManyToMany(targetEntity="Lang")
    * @ORM\JoinTable(name="translation_to_lang",
    *      joinColumns={@ORM\JoinColumn(name="translation_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="lang_id", referencedColumnName="code")}
    * )
    */
   private $translations;

    public function getId()
    {
        return $this->id;
    }

    public function getDomainId()
    {
        return $this->domain_id;
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

    public function setDomainId($domain_id)
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
