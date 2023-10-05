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
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\TaxCategoryConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxation\Model\TaxCategoryInterface;
use Sylius\Component\Taxation\Repository\TaxCategoryRepositoryInterface;

/**
 * Class TaxCategoryProductVariantAction
 * @package Asdoria\SyliusBulkEditPlugin\Action\ProductVariant
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class TaxCategoryProductVariantAction implements ResourceActionInterface
{
    const TAX_CATEGORY_PRODUCT_VARIANT = 'tax_category_product_variant';
    public function __construct(protected TaxCategoryRepositoryInterface $taxCategoryRepository) {

    }
    /**
     * @param ResourceInterface             $resource
     * @param BulkEditNotificationInterface $message
     */
    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        if (!$resource instanceof ProductVariantInterface) return;

        $configuration = $message->getConfiguration();

        if (empty($configuration)) return;

        $taxCategoryCode = $configuration[TaxCategoryConfigurationType::_TAX_CATEGORY_FIELD] ?? null;

        if(empty($taxCategoryCode)) return;

        $taxCategory = $this->taxCategoryRepository->findOneByCode($taxCategoryCode);

        if(!$taxCategory instanceof TaxCategoryInterface) return;

        $resource->setTaxCategory($taxCategory);
    }
}
