<?php

// src/EventListener/ApiKeyCheckListener.php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ApiKeyCheckListener
{
    private $validApiKeys = ['commpeak'];

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $apiKey = $request->headers->get('X-API-KEY'); // Assuming the API key is passed in the header

        if (!$this->isValidApiKey($apiKey)) {
            $response = new JsonResponse(['success' => false, 'error' => 'Invalid API key'], 403);
                $event->setResponse($response);
        }
    }

    private function isValidApiKey($apiKey)
    {
        return in_array($apiKey, $this->validApiKeys);
    }
}
