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
use Asdoria\SyliusBulkEditPlugin\Traits\EntityManagerTrait;
use Doctrine\Common\Collections\Criteria;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Product\Model\ProductAssociationInterface;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Product\Repository\ProductAssociationTypeRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

/**
 * Class AddProductAssociationAction
 */
final class AddProductAssociationAction implements ResourceActionInterface
{
    public const ADD_PRODUCT_ASSOCIATION = 'add_product_association';

    use EntityManagerTrait;

    public function __construct(
        private FactoryInterface $productAssociationFactory,
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

        if (empty($productIds) || empty($associationTypeCode)) {
            return;
        }

        $associationType = $this->associationTypeRepository->findOneByCode($associationTypeCode);

        Assert::isInstanceOf($associationType, ProductAssociationTypeInterface::class);

        $productAssociation = $resource->getAssociations()
            ->matching(Criteria::create()->where(Criteria::expr()->eq('type', $associationType)))->first();

        if (!$productAssociation instanceof ProductAssociationInterface) {
            /** @var ProductAssociationInterface $productAssociation */
            $productAssociation = $this->productAssociationFactory->createNew();
            $productAssociation->setOwner($resource);
            $productAssociation->setType($associationType);
            $this->getEntityManager()->persist($productAssociation);
        }

        foreach (explode(',', $productIds) as $productId) {
            $product = $this->productRepository->find($productId);

            Assert::isInstanceOf($product, ProductInterface::class);

            $productAssociation->addAssociatedProduct($product);
        }
    }
}
