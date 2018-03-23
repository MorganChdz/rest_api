<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Domain;
use AppBundle\Entity\Lang;
use AppBundle\Entity\Translation;
use AppBundle\Entity\TranslationToLang;
use AppBundle\Form\Type\TranslationType;
use AppBundle\Form\Type\DomainType;
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
   public function getDomainAction($domain, Request $request)
   {
    if (!$request->headers->has('Authorization') && function_exists('apache_request_headers')) {
        $all = apache_request_headers();
        if (isset($all['Authorization'])) {
            $request->headers->set('Authorization', $all['Authorization']);
        }
    }
    $token = $request->headers->get('Authorization');
    $creator = ['id' => $domain->getUser()->getId(), 'username' => $domain->getUser()->getUsername()];
    if ($this-> getUserApi($token)){
      if ($this-> getUserApi($token)->getId() == $domain->getUser()->getId()){
        $creator = ['id' => $domain->getUser()->getId(), 'username' => $domain->getUser()->getUsername(), 'email' => $domain->getUser()->getEmail()];
      }
    }
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
           'creator' => $creator,
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
    * @QueryParam(name="code", requirements="[a-z0-9_-]+", default="", description="filter")
    */
   public function getDomainTranslationsAction($domain, ParamFetcher $paramFetcher)
   {
      $code = $paramFetcher->get('code');

      if($code != ""){
        if(count($domain->getTranslations())){
           $res = array_map(function ($translation) {
             return [
              'trans' => $translation->getVirtualLangs(),
              'id' => $translation->getId(),
              'code' => $translation->getCode()
            ];}, $domain->getTranslationFilter($code));
         }
          else {
            $res= array(
              'trans' => ['EN'=>'', 'FR'=>'', 'PL'=>'']
            );
          }
        }
      if($code == ""){
        if(count($domain->getTranslations())){
           $res = array_map(function ($translation) {
             return [
              'trans' => $translation->getVirtualLangs(),
              'id' => $translation->getId(),
              'code' => $translation->getCode()
            ];}, $domain->getTranslations()->toArray());
         }
          else {
            $res= array(
              'trans' => ['EN'=>'', 'FR'=>'', 'PL'=>'']
            );
          }
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
if (!$this-> getUserApi($token)) throw new \Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
      if ($this-> getUserApi($token)->getId() != $domain->getUser()->getId()) throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
      $form = $this->createForm(TranslationType::class, $request->request->all());
      $form->submit($request->request->all());
      if (count($form->getErrors()) || !$form->isValid()) throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
      foreach ($request->get('trans') as $key => $lang) {
          if (!$this->getLangApi($key)) throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
      }
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $transl = new Translation();
        $transl->setCode($request->get('code'));
        $transl->setDomain($domain);
        $entityManager->persist($transl);

        foreach ($request->get('trans') as $key => $trans) {
            $trans_to_lang = new TranslationToLang();
            $trans_to_lang->setTranslation($transl);
            $trans_to_lang->setLang($entityManager->find(Lang::class, $key));
            $trans_to_lang->setTrans($trans);
            $transl->addTranslationToLang($trans_to_lang);
            $entityManager->persist($trans_to_lang);
        }
        $entityManager->flush();

        $trans = $request->get('trans');
         foreach ($domain->getLangs() as $lang) {
           if (!isset($trans[$lang->getCode()])) {
             $trans[$lang->getCode()] = $request->get('code');
           }
         }

        $response = [
        "trans"=> $trans,
        "id"=> $transl->getId(),
        "code"=> $transl->getCode()
        ];

        $data = array(
        "code" => 201,
        "message" => "success",
        "datas" => $response
        );

        $view = $this->view($data, 201);
        return $this->handleView($view);
  }

   public function postDomainAction(Request $request)
   {
   if (!$request->headers->has('Authorization') && function_exists('apache_request_headers')) {
        $all = apache_request_headers();
        if (isset($all['Authorization'])) {
            $request->headers->set('Authorization', $all['Authorization']);
        }
    }
    $token = $request->headers->get('Authorization');
    if (!$this-> getUserApi($token)) throw new \Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
      $form = $this->createForm(DomainType::class, $request->request->all());
      $form->submit($request->request->all());
      if (count($form->getErrors()) || !$form->isValid()) throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
      foreach ($request->get('lang') as $lang) {
          if (!$this->getLangApi($lang)) throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
          }
            if ($request->get('name') == null || $request->get('name') == "") throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

        $entityManager = $this->get('doctrine.orm.entity_manager');
        $dom = new Domain();
        $dom->setName($request->get('name'));
        $slug = $this->get('doctrine.orm.entity_manager')
                  ->getRepository(Domain::class)
                  ->findOneBy([
                    'slug' => $request->get('name')]
                  );
        if ($slug){
           $dom->setSlug($request->get('name') . '1');
          } else {
             $dom->setSlug($request->get('name'));
          }
        $dom->onPrePersist();
        $dom->setDescription($request->get('description'));
        $dom->setUserId($this->getUserApi($token)->getId());
        $dom->setUser($this->getUserApi($token));

        foreach ($request->get('lang') as $lang) {
            $langs[] = $this->getLangApi($lang);
        }
        $dom->setLangs($langs);
        $entityManager->persist($dom);
        $entityManager->flush();

        $formatted_lang = [];
            if(count($dom->getLangs()))
            {foreach ($dom->getLangs() as $lang) {$formatted_lang[] = $lang->getCode(); }}

        $formatted = [];
        $formatted = [
          'langs' => $formatted_lang,
           'id' => $dom->getId(),
           'slug' => $dom->getSlug(),
            'name' => $dom->getName(),
           'description' => $dom->getDescription(),
           'creator' => ['id' => $dom->getUser()->getId(), 'username' => $dom->getUser()->getUsername(), 'email' => $dom->getUser()->getEmail()],
           'created_at'=> $dom->getCreatedAt()
        ];

        $data = array(
           "code" => 201,
           "message" => "success",
           "datas" => $formatted
       );

       $view = $this->view($data, 201);
       return $this->handleView($view);
  }

  /**
    *  @ParamConverter("domain", class="AppBundle:Domain", options={"repository_method" = "findOneBySlug"})
    *  @ParamConverter("translation", class="AppBundle:Translation", options={"repository_method" = "findOneById"})
    */
  public function putDomainTranslationAction($domain, Request $request, $translation)
  {
   if (!$request->headers->has('Authorization') && function_exists('apache_request_headers')) {
        $all = apache_request_headers();
        if (isset($all['Authorization'])) {
            $request->headers->set('Authorization', $all['Authorization']);
        }
    }
    $token = $request->headers->get('Authorization');
    if (!$this-> getUserApi($token)) throw new \Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
      if ($this-> getUserApi($token)->getId() != $domain->getUser()->getId()) throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
        $form = $this->createForm(TranslationType::class, $request->request->all());
        $form->submit($request->request->all());
        if (count($form->getErrors()) || !$form->isValid()) throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
        foreach ($request->get('trans') as $key => $lang) {
            if (!$this->getLangApi($key)) throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
      }

        $entityManager = $this->get('doctrine.orm.entity_manager');
        foreach ($request->get('trans') as $key => $trans) {
          $trans_to_lang = $this->get('doctrine.orm.entity_manager')
                            ->getRepository(TranslationToLang::class)
                            ->findOneBy([
                              'translation' => $translation,
                              'lang' => $entityManager->find(Lang::class, $key)]
                            );
          if ($trans_to_lang){
            $trans_to_lang->setTrans($trans);
          }
          else {
            $trans_to_lang = new TranslationToLang();
            $trans_to_lang->setTranslation($translation);
            $trans_to_lang->setLang($entityManager->find(Lang::class, $key));
            $trans_to_lang->setTrans($trans);
          }
          $entityManager->persist($trans_to_lang);
        }
        $entityManager->flush();

        $response = [
        "trans"=> $translation->getVirtualLangs(),
        "id"=> $translation->getId(),
        "code"=> $translation->getCode()
        ];

        $data = array(
        "code" => 200,
        "message" => "success",
        "datas" => $response
        );

        $view = $this->view($data, 200);
        return $this->handleView($view);
  }

/**
    *  @ParamConverter("domain", class="AppBundle:Domain", options={"repository_method" = "findOneBySlug"})
    *  @ParamConverter("translation", class="AppBundle:Translation", options={"repository_method" = "findOneById"})
    */
  public function deleteDomainTranslationAction($domain, Request $request, $translation)
  {
   if (!$request->headers->has('Authorization') && function_exists('apache_request_headers')) {
        $all = apache_request_headers();
        if (isset($all['Authorization'])) {
            $request->headers->set('Authorization', $all['Authorization']);
        }
    }
    $token = $request->headers->get('Authorization');
    if (!$this-> getUserApi($token)) throw new \Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
      if ($this-> getUserApi($token)->getId() != $domain->getUser()->getId()) throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

        $entityManager = $this->get('doctrine.orm.entity_manager');

        $response = [
          "id"=> $translation->getId(),
        ];

        $entityManager->remove($translation);
        $entityManager->flush();

        $data = array(
        "code" => 200,
        "message" => "success",
        "datas" => $response
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

    public function getUserApi($token){
      return $this->get('doctrine.orm.entity_manager')
          ->getRepository('AppBundle:User')
          ->findOneByPassword($token);
      }

    public function getLangApi($code){
      return $this->get('doctrine.orm.entity_manager')
          ->getRepository('AppBundle:Lang')
          ->findOneByCode($code);
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
