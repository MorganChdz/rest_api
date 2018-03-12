<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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

    public function getTranslationId()
    {
        return $this->translation_id;
    }

    public function getLangId()
    {
        return $this->lang_id;
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

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_d;
        return $this;
    }

    public function setTrans($trans)
    {
        $this->trans = $translation_id;
        return $this;
    }
}
