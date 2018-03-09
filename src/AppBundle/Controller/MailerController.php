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
use AppBundle\Entity\DomainLanguage;
use AppBundle\Entity\User;

class MailerController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/api/domains/mailer{extension}")
     */
    public function getMailerAction(Request $request, $extension = '')
    {
        if($extension=='.json'){

            /* @var $domain_lang DomainLanguage[] */
            $domain_lang = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('AppBundle:DomainLanguage')
                    ->findAll();
            $formatted_domain_lang = [];
            foreach ($domain_lang as $_domain_lang) {
                $formatted_domain_lang[] = 
                    $_domain_lang->getLangId()
                ;
            }

             /* @var $domain Domain[] */
            $domain = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('AppBundle:Domain')
                    ->findAll();
            $formatted_domain = [];
            foreach ($domain as $_domain) {
                if($_domain->getId() == '1'){
                    $formatted_domain = [
                       'id' => $_domain->getId(),
                       'slug' => $_domain->getSlug(),
                        'name' => $_domain->getName(),
                       'description' => $_domain->getDescription()
                    ];
                }
            }
            
            /* @var $user User[] */
            $user = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('AppBundle:User')
                    ->findAll();
            $formatted_user = [];
            foreach ($user as $_user) {
                if($_user->getId() == '1'){
                    $formatted_user = [
                       'id' => $_user->getId(),
                       'username' => $_user->getUsername()
                    ];
                }
            }
        }
        else {
            return new JsonResponse(array('code' => 400, 'message' => 'Bad Request', 'datas' => array('.json')), 400);
        }

        return new JsonResponse(['code' => 200, 'message' => 'success', 'datas' => ['langs' => $formatted_domain_lang, $formatted_domain, 'creator' => $formatted_user, 'createdAt' => $_domain->getCreatedAt()->format('Y-m-d H:i:s')]]);
    }
}