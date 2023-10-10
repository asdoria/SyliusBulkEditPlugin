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
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\PropertyConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

/**
 * Class PropertyProductVariantAction
 */
final class PropertyProductVariantAction implements ResourceActionInterface
{
    public const PROPERTY_PRODUCT_VARIANT = 'property_product_variant';

    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        Assert::isInstanceOf($resource, ProductVariantInterface::class);

        $configuration = $message->getConfiguration();

        if (empty($configuration)) {
            return;
        }

        $methode = $configuration[PropertyConfigurationType::_PROPERTY_FIELD] ?? null;
        $value = $configuration[PropertyConfigurationType::_VALUE_FIELD] ?? null;

        try {
            $resource->$methode($value);
        } catch (\Throwable $e) {
        }
    }
}
