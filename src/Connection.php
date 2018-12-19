<?php
/**
 * (c) OOXOO B.V.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ooxoo\GCloud\MLEngine;

interface Connection
{
    /**
     * @param array $args
     * @return array
     */
    public function predict(array $args = []): array;

    /**
     * @param array $args
     * @return array
     */
    public function getConfig(array $args = []): array;
}
