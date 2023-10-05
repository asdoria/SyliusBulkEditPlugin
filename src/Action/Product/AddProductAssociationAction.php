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
use Asdoria\SyliusConfiguratorPlugin\Entity\ConfiguratorItemProductAttribute;
use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorItemInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Product\Model\ProductAssociationInterface;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Product\Repository\ProductAssociationTypeRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Class AddProductAssociationAction
 * @package Asdoria\SyliusBulkEditPlugin\Action\Product
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class AddProductAssociationAction implements ResourceActionInterface
{
    const ADD_PRODUCT_ASSOCIATION = 'add_product_association';

    use EntityManagerTrait;

    /**
     * @param EntityManagerInterface $entityManager
     * @param FactoryInterface       $productAssociationFactory
     */
    public function __construct(
        protected FactoryInterface $productAssociationFactory,
        protected ProductAssociationTypeRepositoryInterface $associationTypeRepository,
        protected ProductRepositoryInterface $productRepository
    )
    {
    }

    /**
     * @param ResourceInterface             $resource
     * @param BulkEditNotificationInterface $message
     */
    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        if (!$resource instanceof ProductInterface) return;

        $configuration = $message->getConfiguration();

        if (empty($configuration)) return;

        $associationTypeCode = $configuration[AssociationsConfigurationType::_ASSOCIATION_TYPE_FIELD] ?? null;
        $productIds          = $configuration[AssociationsConfigurationType::_PRODUCTS_FIELD] ?? null;

        if(empty($productIds) || empty($associationTypeCode)) return;

        $associationType     = $this->associationTypeRepository->findOneByCode($associationTypeCode);

        if (!$associationType instanceof ProductAssociationTypeInterface) return;

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
            if(!$product instanceof ProductInterface) continue;
            $productAssociation->addAssociatedProduct($product);
        }
    }
}
