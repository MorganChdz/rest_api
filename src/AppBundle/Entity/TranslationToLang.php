<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Translation;
use AppBundle\Entity\Lang;
/**
 * @ORM\Entity()
 * @ORM\Table(name="translation_to_lang")
 */
class TranslationToLang
{
    /**
      * @ORM\Id
       * @ORM\JoinColumn(nullable=false)
      * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Translation")
    */
    protected $translation;

     /**
    * @ORM\Id
     * @ORM\JoinColumn(nullable=false, referencedColumnName="code")
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lang")
     */
    protected $lang;

     /**
    * @ORM\Column(type="string")
     */
    protected $trans;

    // public function __construct()
    // {
    //     $this->translation = new ArrayCollection(array());
    //     $this->lang = new ArrayCollection(array());
    // }

    public function getTranslationId()
    {
        return $this->translation_id;
    }

    public function getLang()
    {
        return $this->lang;
    }

      public function getTrans()
    {
        return $this->trans;
    }

    public function setTranslationId($translation_id)
    {
        $this->translation_id = $translation_id;
        return $this;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function setTrans($trans)
    {
        $this->trans = $trans;
        return $this;
    }
}
