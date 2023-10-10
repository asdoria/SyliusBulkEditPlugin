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
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\ShippingCategoryConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Repository\ShippingCategoryRepositoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;
use Webmozart\Assert\Assert;

/**
 * Class ShippingCategoryProductVariantAction
 */
final class ShippingCategoryProductVariantAction implements ResourceActionInterface
{
    public const SHIPPING_CATEGORY_PRODUCT_VARIANT = 'shipping_category_product_variant';

    public function __construct(private ShippingCategoryRepositoryInterface $shippingCategoryRepository)
    {
    }

    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        Assert::isInstanceOf($resource, ProductVariantInterface::class);

        $configuration = $message->getConfiguration();

        if (empty($configuration)) {
            return;
        }

        $shippingCategoryCode = $configuration[ShippingCategoryConfigurationType::_SHIPPING_CATEGORY_FIELD] ?? null;

        if (empty($shippingCategoryCode)) {
            return;
        }

        $shippingCategory = $this->shippingCategoryRepository->findOneByCode($shippingCategoryCode);

        Assert::isInstanceOf($shippingCategory, ShippingCategoryInterface::class);

        $resource->setShippingCategory($shippingCategory);
    }
}
