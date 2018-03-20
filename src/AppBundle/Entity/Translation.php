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
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\TranslationToLang", mappedBy="translation", cascade={"persist"})
    */
   private $translationToLang;

    public function __construct()
    {
        $this->domain = new ArrayCollection();
        $this->translationToLang = new ArrayCollection();
    }

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


    /**
     * Add translationToLang
     *
     * @param \AppBundle\Entity\TranslationToLang $translationToLang
     *
     * @return Translation
     */
    public function addTranslationToLang(\AppBundle\Entity\TranslationToLang $translationToLang)
    {
        $this->translationToLang[] = $translationToLang;

        return $this;
    }

    /**
     * Remove translationToLang
     *
     * @param \AppBundle\Entity\TranslationToLang $translationToLang
     */
    public function removeTranslationToLang(\AppBundle\Entity\TranslationToLang $translationToLang)
    {
        $this->translationToLang->removeElement($translationToLang);
    }

    public function getVirtualLangs()
   {
       $res = [];
       foreach ($this->getTranslationToLang() as $ttl) {
           $res[$ttl->getLang()->getCode()] = $ttl->getTrans();
       }
       foreach ($this->getDomain()->getLangs() as $lang) {
           if (!isset($res[$lang->getCode()]))
               $res[$lang->getCode()] = $this->code;
       }
       return $res;
   }
}
