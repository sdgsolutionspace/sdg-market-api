<?php
/**
 * Created by IntelliJ IDEA.
 * User: xoeseko
 * Date: 2/10/2019
 * Time: 1:23 PM
 */

namespace App\Tests\Command;

use App\Command\GitParseCommand;
use Doctrine\ORM\EntityManager;
use Github\Client;
use PHPUnit\Framework\TestCase;

class GitParseCommandTest extends TestCase
{

  private $client;
  private $cmd;
  private $em;

  protected function setUp(): void
  {
    parent::setUp();
//    $this->client = new \GuzzleHttp\Client(['base_uri' => "http://127.0.0.1:8000/api/v1/"]);
    $this->cmd = new GitParseCommand(new EntityManager(), new Client());
  }

  public function testGetAll()
  {
    $response = $this->client->get('git-projects');
    $this->assertEquals(200, $response->getStatusCode());
    $data = json_decode($response->getBody(true), true);
    $this->assertGreaterThan(0, count($data));
  }

  public function testRepoWithSymbolInName()
  {
    $response = $this->cmd->parseGithubUrl('http://github.com/xoeseko/sdg.market');
  }

}
