<?php

class Remixjobs_Tests_Public_ApiTest extends PHPUnit_Framework_TestCase
{
    protected $api;
    protected $client;
    protected $adapter;

    public function setUp()
    {
        $this->adapter = new Zend_Http_Client_Adapter_Test;
        $this->client = new Zend_Http_Client(null, array(
            'adapter' => $this->adapter,
        ));
        $this->api = new Remixjobs_Public_Api('https://remixjobs.com', $this->client);
    }

    public function testGet()
    {
        $this->adapter->setResponse(<<<HTTP
HTTP/1.0 200 OK
Content-Type: application/json

{"test": "ok"}
HTTP
        );

        list ($success, $data, $response) = $this->api->get('/test');

        $this->assertTrue($success);
        $this->assertSame(array('test' => 'ok'), $data);

        $lastRequest = $this->client->getLastRequest();

        $this->assertSame(<<<HTTP
GET /test HTTP/1.1
Host: remixjobs.com
Connection: close
Accept-encoding: gzip, deflate
User-Agent: Zend_Http_Client
Accept-Language: fr
Accept: application/json


HTTP
        , preg_replace("#\r?\n#", "\n", $lastRequest));
    }

    public function testGetAppendsQueryParams()
    {
        $this->adapter->setResponse(<<<HTTP
HTTP/1.0 200 OK
Content-Type: application/json

{"test": "ok"}
HTTP
        );

        list ($success, $data, $response) = $this->api->get('/test', array(
            'foo' => 'bar',
        ));

        $this->assertTrue($success);
        $this->assertSame(array('test' => 'ok'), $data);

        $lastRequest = $this->client->getLastRequest();

        $this->assertSame(<<<HTTP
GET /test?foo=bar HTTP/1.1
Host: remixjobs.com
Connection: close
Accept-encoding: gzip, deflate
User-Agent: Zend_Http_Client
Accept-Language: fr
Accept: application/json


HTTP
        , preg_replace("#\r?\n#", "\n", $lastRequest));
    }

    public function testGetAppendsQueryParamsOnExistingQuery()
    {
        $this->adapter->setResponse(<<<HTTP
HTTP/1.0 200 OK
Content-Type: application/json

{"test": "ok"}
HTTP
        );

        list ($success, $data, $response) = $this->api->get('/test?foo=bar&bar=baz', array(
            'foo' => 'qux',
            'qux' => 'foo',
        ));

        $this->assertTrue($success);
        $this->assertSame(array('test' => 'ok'), $data);

        $lastRequest = $this->client->getLastRequest();

        $this->assertSame(<<<HTTP
GET /test?foo=qux&bar=baz&qux=foo HTTP/1.1
Host: remixjobs.com
Connection: close
Accept-encoding: gzip, deflate
User-Agent: Zend_Http_Client
Accept-Language: fr
Accept: application/json


HTTP
        , preg_replace("#\r?\n#", "\n", $lastRequest));
    }
    public function testGetReturnsFailureOnError()
    {
        $this->adapter->setResponse(<<<HTTP
HTTP/1.0 400 Bad Request
Content-Type: application/json

{"test": "nok"}
HTTP
        );

        list ($success, $data, $response) = $this->api->get('/test');

        $this->assertFalse($success);
        $this->assertSame(array('test' => 'nok'), $data);
    }
}

