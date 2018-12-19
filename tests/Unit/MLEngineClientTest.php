<?php
/**
 * (c) OOXOO B.V.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ooxoo\GCloud\MLEngine\Tests\Unit;

use Google\Cloud\Core\Testing\TestHelpers;
use Ooxoo\GCloud\MLEngine\Connection;
use Ooxoo\GCloud\MLEngine\Connection\Rest;
use Ooxoo\GCloud\MLEngine\MLEngineClient;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class MLEngineClientTest extends TestCase
{
    const PROJECT = 'my-project';
    /** @var ObjectProphecy|Connection */
    public $connection;
    private $client;

    public function setUp()
    {
        $this->connection = $this->prophesize(Rest::class);
        $this->client = TestHelpers::stub(MLEngineClient::class, [['projectId' => self::PROJECT]]);
    }

    public function testGetConfig()
    {
        $projectId = self::PROJECT;
        $response = ["serviceAccount" => "my-account@appspot.com"];
        $this->connection->getConfig(["name" => "projects/{$projectId}"])->willReturn($response);
        $this->client->___setProperty('connection', $this->connection->reveal());
        self::assertEquals($response, $this->client->getConfig());
    }

    public function testPredict()
    {
        $projectId = self::PROJECT;
        $model = 'MyModel';
        $instances = [["arg1" => "blabla", "arg2" => 2], ["arg1" => "yesyesyes", "arg2" => 3]];
        $response = ["Awesome" => "response"];


        $this->connection
            ->predict(["name" => "projects/$projectId/models/$model", "instances" => $instances])
            ->willReturn($response);

        $this->client->___setProperty('connection', $this->connection->reveal());

        self::assertEquals(
            $response,
            $this->client->predict($model, $instances)
        );
    }
}
