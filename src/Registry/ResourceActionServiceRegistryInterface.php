<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Registry;

use Asdoria\SyliusBulkEditPlugin\Action\ResourceActionInterface;

/**
 * Class ResourceActionServiceRegistryInterface.
 */
interface ResourceActionServiceRegistryInterface
{
    public function all(): array;

    public function get(string $type): ?ResourceActionInterface;
}
