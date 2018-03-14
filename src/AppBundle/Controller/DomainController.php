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
use AppBundle\Entity\Translation;
use AppBundle\Entity\TranslationToLang;
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

    $formatted_lang = [];
    if(count($domain->getLangs()))
    {foreach ($domain->getLangs() as $lang) {$formatted_lang[] = $lang->getCode(); }}

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
    *  @ParamConverter("domain", class="AppBundle:Domain", options={"repository_method" = "findOneBySlug"})
    */
   public function getDomainTranslationsAction($domain)
   {
     if(count($domain->getTranslations())){
        $res = array_map(function ($translation) {
        $trans = array_map(function ($transTolang) {
        return[
            'lang' => $transTolang->getLang()->getCode(),
           'trans' => $transTolang->getTrans()
        ];
       }, $translation->getTranslationToLang()->toArray());
        $format = array();
        foreach ($trans as $key => $value) {
          foreach ($value as $key_a => $value_a) {
            $format[$value['lang']] = $value_a;
          }
        }
       return [
           'trans' => $format,
           'id' => $translation->getId(),
           'code' => $translation->getCode()
         ];
      }, $domain->getTranslations()->toArray());}
      else {
        $res= array(
          'trans' => ['EN'=>'', 'FR'=>'']
        );
      }

        $data = array(
           "code" => 200,
           "message" => "success",
           "datas" => $res
       );

       $view = $this->view($data, 200);
       return $this->handleView($view);
  }
    /**
    *  @ParamConverter("domain", class="AppBundle:Domain", options={"repository_method" = "findOneBySlug"})
    */
   public function postDomainTranslationsAction($domain, Request $request)
   {
   if (!$request->headers->has('Authorization') && function_exists('apache_request_headers')) {
        $all = apache_request_headers();
        if (isset($all['Authorization'])) {
            $request->headers->set('Authorization', $all['Authorization']);
        }
    }
    $token = $request->headers->get('Authorization');
    if($this->controlAuthorizationToken($token)){
      $trans_to_lang = new TranslationToLang();
      $trans_to_lang->setTrans($request->get('trans'));
    
      $trans = new Translation($trans_to_lang);
      $trans->setCode($request->get('code'));
      $trans->setDomain($domain);

      $entityManager = $this->get('doctrine.orm.entity_manager');
      $entityManager->persist($trans);
      $entityManager->flush();

      $response = [
      "trans"=> $request->get('trans'),
      "id"=> $trans->getId(),
      "code"=> $request->get('code')
      ];

      $data = array(
      "code" => 201,
      "message" => "Created",
      "datas" => $response
      );


      $view = $this->view($data, 201);
      return $this->handleView($view);
      }
      else {
        $data = array(
        "code" => 401,
        "message" => "Authorization token failed",
        "token" => $token
        );

        $view = $this->view($data, 401);
        return $this->handleView($view);
      }
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

   public function controlAuthorizationToken($token){
      $users = $this->get('doctrine.orm.entity_manager')
           ->getRepository('AppBundle:User')
           ->findAll();
      foreach($users as $user){
        if ('Bearer ' . $user->getPassword() == $token){
          return true;
        }
        else
         return false;
      }
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
