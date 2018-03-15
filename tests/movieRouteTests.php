<?php
/**
 * User: junade
 * Date: 20/12/2016
 * Time: 16:32
 */

class RouteTests extends \PHPUnit\Framework\TestCase
{
    private $http;
    public function setUp()
    {
        $this->http = new GuzzleHttp\Client(['base_uri' => 'http://localhost/movie-api/public/api/']);
    }

    public function testGet()
    {
        $response = $this->http->request('GET', 'titles');
        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];

    }

        public function tearDown()
    {
        $this->http = null;
    }
}