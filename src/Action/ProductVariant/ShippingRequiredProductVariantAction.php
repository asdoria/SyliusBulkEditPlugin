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

namespace Asdoria\SyliusBulkEditPlugin\Action\ProductVariant;

use Asdoria\SyliusBulkEditPlugin\Action\ResourceActionInterface;
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\EnabledConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Class ShippingRequiredProductVariantAction
 * @package Asdoria\SyliusBulkEditPlugin\Action\ProductVariant
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class ShippingRequiredProductVariantAction implements ResourceActionInterface
{
    const SHIPPING_REQUIRED_PRODUCT_VARIANT = 'shipping_required_product_variant';
    /**
     * @param ResourceInterface             $resource
     * @param BulkEditNotificationInterface $message
     */
    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        if (!$resource instanceof ProductVariantInterface) return;

        $configuration = $message->getConfiguration();

        if (empty($configuration)) return;

        $enabled = $configuration[EnabledConfigurationType::_ENABLED_FIELD] ?? null;

        $resource->setShippingRequired(filter_var($enabled, FILTER_VALIDATE_BOOLEAN));
    }
}
