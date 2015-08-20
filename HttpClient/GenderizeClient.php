<?php

namespace Jhg\GenderizeIoClient\HttpClient;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Header;
use Guzzle\Http\Message\Response;

/**
 * Class GenderizeClient
 * 
 * @package Jhg\GenderizeIoClient\HttpClient
 */
class GenderizeClient extends Client
{
    /**
     * @var null|Response
     */
    protected $lastResponse = null;

    /**
     * @param Response $lastResponse
     */
    public function setLastResponse(Response $lastResponse)
    {
        $this->lastResponse = $lastResponse;
    }

    /**
     * @param array $query
     *
     * @return array
     */
    public function genderize(array $query)
    {
        $queryString = '?'.http_build_query($query);

        $request = $this->get($queryString);
        $this->setLastResponse($request->send());

        return $this->lastResponse->json();
    }

    /**
     * The amount of names in the current time window
     *
     * @return null
     */
    public function getRateLimit()
    {
        if (!$this->lastResponse) {
            return null;
        }

        /** @var Header $limit */
        $limit = $this->lastResponse->getHeader('X-Rate-Limit-Limit');

        return $limit ? $limit->normalize() : null;
    }

    /**
     * The number of names left in the current time window
     *
     * @return null
     */
    public function getRateRemainingLimit()
    {
        if (!$this->lastResponse) {
            return null;
        }

        /** @var Header $limit */
        $limit = $this->lastResponse->getHeader('X-Rate-Limit-Remaining');

        return $limit ? $limit->normalize() : null;
    }

    /**
     * Seconds remaining until a new time window opens
     *
     * @return null
     */
    public function getRateReset()
    {
        if (!$this->lastResponse) {
            return null;
        }

        /** @var Header $limit */
        $limit = $this->lastResponse->getHeader('X-Rate-Reset');

        return $limit ? $limit->normalize() : null;
    }
}