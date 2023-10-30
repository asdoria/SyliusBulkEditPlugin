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
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\EnableConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

/**
 * Class TrackedProductVariantAction
 */
final class TrackedProductVariantAction implements ResourceActionInterface
{
    public const TRACKED_PRODUCT_VARIANT = 'tracked_product_variant';

    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        Assert::isInstanceOf($resource, ProductVariantInterface::class);

        $configuration = $message->getConfiguration();

        if (empty($configuration)) {
            return;
        }

        $enabled = $configuration[EnableConfigurationType::_ENABLED_FIELD] ?? null;

        $resource->setTracked(filter_var($enabled, \FILTER_VALIDATE_BOOLEAN));
    }
}
