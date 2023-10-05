<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Action;

use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Interface ResourceActionInterface
 * @package Asdoria\SyliusBulkEditPlugin\Action
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface ResourceActionInterface
{
    const PRODUCT_CONTEXT = 'product';
    const TAXON_CONTEXT = 'taxon';
    const PRODUCT_VARIANT_CONTEXT = 'product_variant';

    /**
     * @param ResourceInterface             $resource
     * @param BulkEditNotificationInterface $message
     */
    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message):void;
}
