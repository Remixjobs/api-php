<?php

class Remixjobs_Public_Api
{
    protected $endpoint;
    protected $client;
    protected $clientHeaders;

    public function __construct($endpoint = 'https://remixjobs.com', Zend_Http_Client $client = null, $additionalClientHeaders = array())
    {
        $this->endpoint = $endpoint;

        if (null === $client) {
            $client = new Zend_Http_Client(null, array(
                'useragent' => 'remixjobs-api/1.0',
            ));
        }

        $this->clientHeaders = array(
            'Accept-Language' => 'fr',
            'Accept' => 'application/json',
        );

        if (!empty($additionalClientHeaders)) {
            $this->clientHeaders = array_merge($this->clientHeaders, $additionalClientHeaders);
        }

        $this->client = $client;
    }

    /**
     * Do a GET request on the API and returns the result
     *
     * Example:
     *
     * list ($success, $result, $response) = $api->get('/api/jobs', array('q' => 'php'));
     *
     * @param  string $uri    The URI (e.g. /api/jobs)
     * @param  array  $params Additional query parameters
     * @return array  The success status (true/false), the decoded json response, and a Zend_Http_Response
     */
    public function get($uri, array $params = array())
    {
        $client = $this->client;
        $client->resetParameters(true);

        $uri = $this->absolute($uri);

        $uri = Zend_Uri_Http::fromString($uri);
        $uri->addReplaceQueryParameters($params);

        $client->setUri($uri);

        $client->setHeaders($this->clientHeaders);
        
        $response = $client->request('GET');
        $data = null;

        if (preg_match('#^application/json#', $response->getHeader('Content-Type'))) {
            $data = json_decode($response->getBody(), true);
        }

        return array(200 === $response->getStatus(), $data, $response);
    }

    protected function absolute($url)
    {
        if (preg_match('#^https?://#', $url)) {
            return $url;
        }
        return $this->endpoint . $url;
    }
}

