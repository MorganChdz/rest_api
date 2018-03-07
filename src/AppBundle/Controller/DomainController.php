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

class DomainController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/api/domains{extension}")
     */
    public function getDomainAction(Request $request, $extension = '')
    {
        if($extension=='.json'){
            $domain = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('AppBundle:Domain')
                    ->findAll();
            /* @var $domain Domain[] */

            $formatted = [];
            foreach ($domain as $_domain) {
                $formatted[] = [
                   'id' => $_domain->getId(),
                   'slug' => $_domain->getSlug(),
                    'name' => $_domain->getName(),
                   'description' => $_domain->getDescription(),
                ];
            }
        }
        else {
            return new JsonResponse(array('code' => '400', 'message' => 'Bad Request', 'datas' => array()), 400);
        }

        // Récupération du view handler
        //$viewHandler = $this->get('fos_rest.view_handler');

        // Création d'une vue FOSRestBundle
        //$view = View::create($formatted);
        //$view->setFormat('json');

        // Gestion de la réponse
        //return $domain;

        return new JsonResponse(array('code' => '200', 'message' => 'success', 'datas' => $formatted));
    }
}