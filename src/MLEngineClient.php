<?php
/**
 * (c) OOXOO B.V.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ooxoo\GCloud\MLEngine;

use Ooxoo\GCloud\MLEngine\Connection\Rest;
use Google\Auth\FetchAuthTokenInterface;
use Google\Cloud\Core\ClientTrait;
use Psr\Cache\CacheItemPoolInterface;

class MLEngineClient
{
    use ClientTrait;

    const VERSION = '1.0.0';
    const DEFAULT_CONTROL_SCOPE = 'https://www.googleapis.com/auth/cloud-platform';

    /**
     * @var Connection Represents a connection to ML Engine.
     */
    protected $connection;

    /**
     * Create an EngineML client.
     *
     * @param array $config [optional] {
     *     Configuration options.
     *
     *     @type string $projectId The project ID from the Google Developer's
     *           Console.
     *     @type CacheItemPoolInterface $authCache A cache used storing access
     *           tokens. **Defaults to** a simple in memory implementation.
     *     @type array $authCacheOptions Cache configuration options.
     *     @type callable $authHttpHandler A handler used to deliver Psr7
     *           requests specifically for authentication.
     *     @type FetchAuthTokenInterface $credentialsFetcher A credentials
     *           fetcher instance.
     *     @type callable $httpHandler A handler used to deliver Psr7 requests.
     *           Only valid for requests sent over REST.
     *     @type array $keyFile The contents of the service account credentials
     *           .json file retrieved from the Google Developer's Console.
     *           Ex: `json_decode(file_get_contents($path), true)`.
     *     @type string $keyFilePath The full path to your service account
     *           credentials .json file retrieved from the Google Developers
     *           Console.
     *     @type float $requestTimeout Seconds to wait before timing out the
     *           request. **Defaults to** `0` with REST and `60` with gRPC.
     *     @type int $retries Number of retries for a failed request.
     *           **Defaults to** `3`.
     *     @type array $scopes Scopes to be used for the request.
     * }
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['scopes'])) {
            $config['scopes'] = [self::DEFAULT_CONTROL_SCOPE];
        }

        $this->connection = new Rest($this->configureAuthentication($config) + [
                'projectId' => $this->projectId
            ]);
    }

    /**
     * Get the service account email associated with this client.
     *
     * Example:
     * ```
     * $serviceAccount = $engine->getConfig();
     * ```
     *
     * @return string
     * @throws \Google\Cloud\Core\Exception\NotFoundException
     */
    public function getConfig(): array
    {
        return $this->connection->getConfig(['name' => 'projects/' . $this->projectId]);
    }

    /**
     * @param string $model
     * @param array $instances
     * @param array $options [optional] {
     *      @type string $version The model version to ask for prediction
     * }
     *
     * @return array
     * @throws \Google\Cloud\Core\Exception\NotFoundException
     */
    public function predict(string $model, array $instances, array $options = []): array
    {
        $name = "projects/{$this->projectId}/models/$model";

        if (isset($options['version'])) {
            $name .= "/versions/{$options['version']}";
        }

        $options += ['name' => $name, 'instances' => $instances];
        return $this->connection->predict($options);
    }
}
