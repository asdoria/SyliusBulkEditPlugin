<?php

declare(strict_types=1);

/*
 * This file is part of the Asdoria Package.
 *
 * (c) Asdoria .
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asdoria\SyliusBulkEditPlugin\Registry;

use Asdoria\SyliusBulkEditPlugin\Action\ResourceActionInterface;

/**
 * Class ResourceActionServiceRegistry
 */
class ResourceActionServiceRegistry implements ResourceActionServiceRegistryInterface
{
    protected iterable $handlers;

    /**
     * SerializerServiceRegistry constructor.
     */
    public function __construct(iterable $handlers)
    {
        $this->handlers = $handlers;
    }

    public function all(): array
    {
        return iterator_to_array($this->handlers);
    }

    public function get(string $type): ?ResourceActionInterface
    {
        return $this->all()[$type] ?? null;
    }
}
