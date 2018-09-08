<?php
namespace App\Tests\Controller;


use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;


class ProjectControllerTest extends TestCase
{
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = new \GuzzleHttp\Client(['base_uri' => "http://127.0.0.1:8000/api/v1/"]);
    }

    public function testPOST()
    {
        // create http client
        $data = array(
            'git_name' => "rohankoid",
            'git_address' => "https://github.com/rohankoid",
            'git_project_address' => 'https://github.com/rohankoid/best-resume-ever'
        );

        $response = $this->client->post('projects', ['body' => json_encode($data)]);

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('git_address', $data);
    }

    public function testPostFail()
    {
        // create http client
        $data = array();

        $response = $this->client->post('projects', ['body' => json_encode($data)]);

        $this->assertEquals(500, $response->getStatusCode());
    }
}