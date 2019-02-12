<?php
namespace App\Tests\Controller;


use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;


class ProjectControllerTest extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client(['base_uri' => "http://127.0.0.1:8000/api/v1/"]);
    }

    public function testPOST()
    {
        $data = array(
            'git_name' => "rohankoid",
            'git_address' => "https://github.com/rohankoid",
            'git_project_address' => 'https://github.com/rohankoid/best-resume-ever'
        );

        $response = $this->client->post('git-projects', ['body' => json_encode($data)]);

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('git_address', $data);
    }

    public function testGetAll()
    {
        $response = $this->client->get('git-projects');
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true), true);
        $this->assertGreaterThan(0, count($data));
    }

    public function testGet()
    {

        $data = array(
            'git_name' => "rohankoid2",
            'git_address' => "https://github.com/rohankoid",
            'git_project_address' => 'https://github.com/rohankoid/best-resume-ever'
        );

        $response = $this->client->post('git-projects', ['body' => json_encode($data)]);

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getBody(true), true);
        $id = $data['id'];

        $get_response = $this->client->get('projects/'.$id);
        $data_response = json_decode($get_response->getBody(true), true);
        $this->assertEquals($data, ($data_response));
    }
}
