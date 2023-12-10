<?php

// src/EventListener/ApiKeyCheckListener.php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class ApiKeyCheckListener
{
    private $validApiKeys = ['commpeak'];

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // Check if the request is for an API route (you might adjust this condition based on your API routes)
        if (strpos($request->getPathInfo(), '/api') === 0) {
            $apiKey = $request->headers->get('X-API-KEY'); // Assuming the API key is passed in the header

            if (!$this->isValidApiKey($apiKey)) {
                throw new \Exception('Invalid API key', 403); // You can throw an exception or handle this according to your needs
            }
        }
    }

    private function isValidApiKey($apiKey)
    {
        return in_array($apiKey, $this->validApiKeys);
    }
}
