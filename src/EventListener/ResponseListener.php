<?php
// src/EventListener/ResponseListener.php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;


class ResponseListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
           $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
            $response->headers->set('Access-Control-Allow-Methods', 'POST,PUT,DELETE,GET,OPTIONS');
            $response->headers->set('Access-Control-Max-Age', 3600);
			
        // sends the modified response object to the event
        $event->setResponse($response);
    }
}