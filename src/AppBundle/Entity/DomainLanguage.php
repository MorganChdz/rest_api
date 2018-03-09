<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="domain_lang")
 */
class DomainLanguage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $domain_id;

    /**
    * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $lang_id;

    public function getDomainId()
    {
        return $this->domain_id;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setDomainId($domain_id)
    {
        $this->domain_id = $domain_id;
        return $this;
    }

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_id;
        return $this;
    }
}