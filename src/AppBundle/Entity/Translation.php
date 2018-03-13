<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Domain;
use AppBundle\Entity\TranslationToLang;

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

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\TranslationToLang", mappedBy="translation")
    */
   private $translationToLang;

    // public function __construct()
    // {
    //     $this->domain = new ArrayCollection(array());
    //     $this->translationToLang = new ArrayCollection(array());
    // }

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
        public function getTranslationToLang()
    {
        return $this->translationToLang;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setDomain($domain)
    {
        $this->domain= $domain;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
   public function setTranslationToLang($translationToLang)
    {
        $this->translationToLang = $translationToLang;
        return $this;
    }

}
