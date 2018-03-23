<?php
namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
               $exception = $event->getException();
       if (get_class($exception) == 'Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException') {
           $code = 401;
           $response = new JsonResponse([
               "code"=> 401,
               "message"=> 'Unauthorize'
           ]);
           $response->setStatusCode(401);
           $event->setResponse($response);
           return;
       }
       if (get_class($exception) == 'Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException') {
           $code = 403;
           $response = new JsonResponse([
               "code"=> 403,
               "message"=> 'Access denied'
           ]);
           $response->setStatusCode(403);
           $event->setResponse($response);
           return;
       }
       if (get_class($exception) == 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException') {
           $response = new JsonResponse([
               "code"=> 404,
               "message"=> 'Not found'
           ]);
           $response->setStatusCode(404);
           $event->setResponse($response);
           return;
       }

       //$response = $exception;

        $response = new JsonResponse([
            "code"=> 400,
            "message"=> 'Bad request',
            "datas"=>[
                "exception" => get_class($exception)
            ]
        ]);
        $response->setStatusCode( 400);
        $event->setResponse($response);
        return;
    }
}
