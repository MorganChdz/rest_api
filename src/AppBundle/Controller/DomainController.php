<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Domain;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;

class DomainController extends FOSRestController
{
       public function getDomainsAction()
  {
       $domains = $this->get('doctrine.orm.entity_manager')
                   ->getRepository('AppBundle:Domain')
                   ->findAll();

        $formatted = [];
        foreach ($domains as $_domain) {
            $formatted[] = [
               'id' => $_domain->getId(),
               'slug' => $_domain->getSlug(),
                'name' => $_domain->getName(),
               'description' => $_domain->getDescription()
            ];
        }
     $data = array(
          "code" => 200,
          "message" => "success",
          "datas" => $formatted
      );
      $view = $this->view($data, 200);
      return $this->handleView($view);  
}

     /**
    * @ParamConverter("domain", class="AppBundle:Domain", options={"repository_method" = "findOneBySlug"})
    */
   public function getDomainAction($domain)
   {

    foreach ($domain->getLangs() as $lang) {$formatted_lang[] = $lang->getCode(); }
       $formatted = [];
        $formatted = [
            'langs' => $formatted_lang,
           'id' => $domain->getId(),
           'slug' => $domain->getSlug(),
            'name' => $domain->getName(),
           'description' => $domain->getDescription(),
           'creator' => ['id' => $domain->getUser()->getId(), 'username' => $domain->getUser()->getUsername() ],
           'created_at'=> $domain->getCreatedAt()
        ];

        $data = array(
           "code" => 200,
           "message" => "success",
           "datas" => $formatted
       );

       $view = $this->view($data, 200);
       return $this->handleView($view);   
}

    /**
    * @Route("/{slug}", name="donation.oldhomepage", requirements={"slug" = ".*.[^json]$"})
    */
   public function indexAction(Request $request, $slug)
   {
       $response = new JsonResponse([
           "code"=> 400,
           "message"=> 'Bad request',
           "datas"=> [
               'authorized format' => ['json']
           ]
       ]);
       $response->setStatusCode(400);
       return $response;
   }

    public function formatted($nb, $response)
   {
        $formatted = [];
        $i = 0;
        for($i =0; $i < $nb; $i++){
            array_push($formatted, $response[$i]);
        }
       return $formatted;
   }
}