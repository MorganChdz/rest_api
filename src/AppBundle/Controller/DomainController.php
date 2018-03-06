<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Domain;

class DomainController extends Controller
{
    /**
     * @Route("/api/domains.json", name="domain_list")
     * @Method({"GET"})
     */
    public function getDomainAction(Request $request)
    {
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

        return new JsonResponse(array('code' => '200', 'message' => 'success', 'datas' => $formatted));
    }
}