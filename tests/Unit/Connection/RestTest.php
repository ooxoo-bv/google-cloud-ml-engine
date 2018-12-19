<?php
/**
 * (c) OOXOO B.V.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ooxoo\GCloud\MLEngine\Tests\Unit\Connection;

use Google\Cloud\Core\RequestBuilder;
use Google\Cloud\Core\RequestWrapper;
use Ooxoo\GCloud\MLEngine\Connection\Rest;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use PHPUnit\Framework\TestCase;

class RestTest extends TestCase
{
    private $requestWrapper;
    private $successBody;

    protected function setUp(): void
    {
        $this->requestWrapper = $this->prophesize(RequestWrapper::class);
        $this->successBody = '{"canI":"kickIt"}';
    }

    /**
     * @dataProvider methodProvider
     */
    public function testCallBasicMethods($method)
    {
        $options = [];
        $request = new Request('GET', '/somewhere');
        $response = new Response(200, [], $this->successBody);

        $requestBuilder = $this->prophesize(RequestBuilder::class);
        $requestBuilder->build(
            Argument::type('string'),
            Argument::type('string'),
            Argument::type('array')
        )->willReturn($request);

        $this->requestWrapper->send(
            Argument::type(RequestInterface::class),
            Argument::type('array')
        )->willReturn($response);

        $rest = new Rest();
        $rest->setRequestBuilder($requestBuilder->reveal());
        $rest->setRequestWrapper($this->requestWrapper->reveal());

        if (substr($method, -3) == 'Acl') {
            $options = ['type' => 'bucketAccessControls'];
        }

        $this->assertEquals(json_decode($this->successBody, true), $rest->$method($options));
    }

    public function methodProvider()
    {
        return [
            ['predict'],
            ['getConfig'],
        ];
    }

    public function testProjectId()
    {
        $rest = new Rest(['projectId' => 'foo']);
        $this->assertEquals('foo', $rest->projectId());
    }

    public function testProjectIdNull()
    {
        $rest = new Rest();
        $this->assertNull($rest->projectId());
    }
}
