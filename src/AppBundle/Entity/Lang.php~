<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="lang")
 */
class Lang
{
    /**
      * @ORM\Id
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    protected $code;

   // public function __construct()
   //  {
   //      $this->code = new ArrayCollection();
   //  }


    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
}
