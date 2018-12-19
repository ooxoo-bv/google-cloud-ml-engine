<?php
/**
 * (c) OOXOO B.V.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ooxoo\GCloud\MLEngine\Connection;

use Ooxoo\GCloud\MLEngine\MLEngineClient;
use Ooxoo\GCloud\MLEngine\Connection;
use Google\Cloud\Core\RequestBuilder;
use Google\Cloud\Core\RequestWrapper;
use Google\Cloud\Core\RestTrait;
use Google\Cloud\Core\UriTrait;

class Rest implements Connection
{
    use RestTrait;
    use UriTrait;

    const BASE_URI = 'https://ml.googleapis.com/';

    /**
     * @var string
     */
    private $projectId;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config += [
            'serviceDefinitionPath' => __DIR__ . '/ServiceDefinition/ml-engine-v1.json',
            'componentVersion' => MLEngineClient::VERSION
        ];

        $this->setRequestWrapper(new RequestWrapper($config));
        $this->setRequestBuilder(new RequestBuilder(
            $config['serviceDefinitionPath'],
            self::BASE_URI
        ));

        $this->projectId = $this->pluck('projectId', $config, false);
    }

    public function projectId(): ?string
    {
        return $this->projectId;
    }

    /**
     * @param array $args
     *
     * @return array
     * @throws \Google\Cloud\Core\Exception\NotFoundException
     */
    public function getConfig(array $args = []): array
    {
        return $this->send('projects', 'getConfig', $args);
    }

    /**
     * @param array $args
     *
     * @return array
     * @throws \Google\Cloud\Core\Exception\NotFoundException
     */
    public function predict(array $args = []): array
    {
        return $this->send('projects', 'predict', $args);
    }
}
