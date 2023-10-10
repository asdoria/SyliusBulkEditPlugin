<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Action;

use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Interface ResourceActionInterface
 */
interface ResourceActionInterface
{
    public const PRODUCT_CONTEXT = 'product';

    public const TAXON_CONTEXT = 'taxon';

    public const PRODUCT_VARIANT_CONTEXT = 'product_variant';

    public const CUSTOMER_CONTEXT = 'customer';

    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void;
}
