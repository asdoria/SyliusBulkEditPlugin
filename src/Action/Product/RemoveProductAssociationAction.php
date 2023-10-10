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

namespace Asdoria\SyliusBulkEditPlugin\Action\Product;

use Asdoria\SyliusBulkEditPlugin\Action\ResourceActionInterface;
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\AssociationsConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Doctrine\Common\Collections\Criteria;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Product\Model\ProductAssociationInterface;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Product\Repository\ProductAssociationTypeRepositoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

/**
 * Class RemoveProductAssociationAction
 */
final class RemoveProductAssociationAction implements ResourceActionInterface
{
    public const REMOVE_PRODUCT_ASSOCIATION = 'remove_product_association';

    public function __construct(
        private ProductAssociationTypeRepositoryInterface $associationTypeRepository,
        private ProductRepositoryInterface $productRepository,
    ) {
    }

    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        Assert::isInstanceOf($resource, ProductInterface::class);

        $configuration = $message->getConfiguration();

        if (empty($configuration)) {
            return;
        }

        $associationTypeCode = $configuration[AssociationsConfigurationType::_ASSOCIATION_TYPE_FIELD] ?? null;
        $productIds = $configuration[AssociationsConfigurationType::_PRODUCTS_FIELD] ?? null;

        if (empty($associationTypeCode)) {
            return;
        }

        $associationType = $this->associationTypeRepository->findOneByCode($associationTypeCode);

        Assert::isInstanceOf($associationType, ProductAssociationTypeInterface::class);

        $productAssociation = $resource->getAssociations()
            ->matching(Criteria::create()->where(Criteria::expr()->eq('type', $associationType)))->first();

        Assert::isInstanceOf($productAssociation, ProductAssociationInterface::class);

        if (empty($productIds)) {
            $productAssociation->clearAssociatedProducts();

            return;
        }

        foreach (explode(',', $productIds) as $productId) {
            $product = $this->productRepository->find($productId);

            Assert::isInstanceOf($product, ProductInterface::class);

            $productAssociation->removeAssociatedProduct($product);
        }
    }
}
