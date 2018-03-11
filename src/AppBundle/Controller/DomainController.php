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
    // /**
    //  * @Rest\View()
    //  * @Rest\Get("/domains{extension}")
    //  */
    // public function getDomainsAction(Request $request, $extension = '')
    // {
    //     // if($extension=='.json'){
    //         $domain = $this->get('doctrine.orm.entity_manager')
    //                 ->getRepository('AppBundle:Domain')
    //                 ->findAll();
    //         /* @var $domain Domain[] */

    //     //     $formatted = [];
    //     //     foreach ($domain as $_domain) {
    //     //         $formatted[] = [
    //     //            'id' => $_domain->getId(),
    //     //            'slug' => $_domain->getSlug(),
    //     //             'name' => $_domain->getName(),
    //     //            'description' => $_domain->getDescription(),
    //     //         ];
    //     //     }
    //     // }
    //     // else {
    //     //     return new JsonResponse(array('code' => 400, 'message' => 'Bad Request', 'datas' => array('.json')), 400);
    //     // }

    //     // Récupération du view handler
    //      $data = array(
    //        "code" => 200,
    //        "message" => "success",
    //        "datas" => $domain
    //    );
    //    $view = $this->view($data, 200);
    //    return $this->handleView($view);   
    // }
    //     /**
    //  * @Rest\View()
    //  * @Rest\Get("/api/domains/{slug}.{extension}")
    //  */

    //     public function getDomainAction(Request $request, $extension = '', $slug ='')
    // {
       
    //         $domain = $this->get('doctrine.orm.entity_manager')
    //                 ->getRepository('AppBundle:Domain')
    //                 ->findOneBy(['slug'=>$slug]);
    //         /* @var $domain Domain[] */

    //        $formatted = [];
    //         foreach ($domain as $_domain) {
    //             $formatted[] = [
    //                'id' => $_domain->getId(),
    //                'slug' => $_domain->getSlug(),
    //                 'name' => $_domain->getName(),
    //                'description' => $_domain->getDescription(),
    //                'creators' => $_domain->getUser(),
    //                'created_at'=> $_domain->getCreatedAt()
    //             ];
    //         }
        
     

     

    //     // Gestion de la réponse
    //     //return $domain;

    //     return ['code' => 200, 'message' => 'success', 'datas' => $formatted];
    // }

       public function getDomainsAction()
  {
       $domains = $this->get('doctrine.orm.entity_manager')
                   ->getRepository('AppBundle:Domain')
                   ->findAll();
     $data = array(
          "code" => 200,
          "message" => "success",
          "datas" => $domains
      );
      $view = $this->view($data, 200);
      return $this->handleView($view);  
}

     /**
    * @ParamConverter("domain", class="AppBundle:Domain", options={"repository_method" = "findOneBySlug"})
    */
   public function getDomainAction($domain)
   {
           $formatted = [];
            foreach ($domain as $_domain) {
                $formatted[] = [
                   'id' => $_domain->getId(),
                   'slug' => $_domain->getSlug(),
                    'name' => $_domain->getName(),
                   'description' => $_domain->getDescription(),
                   'creators' => $_domain->getUser(),
                   'created_at'=> $_domain->getCreatedAt(),
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
}