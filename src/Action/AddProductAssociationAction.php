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

namespace Asdoria\SyliusBulkEditPlugin\Action;

use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\AssociationConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Asdoria\SyliusBulkEditPlugin\Traits\EntityManagerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAssociationInterface;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Product\Repository\ProductAssociationTypeRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Class AddProductAssociationAction
 * @package Asdoria\SyliusBulkEditPlugin\DependencyInjection
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
        protected ProductAssociationTypeRepositoryInterface $associationTypeRepository
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

        $associationCode = $configuration[AssociationConfigurationType::_ASSOCIATION_TYPE_FIELD] ?? null;
        $association     = $this->associationTypeRepository->findOneByCode($associationCode);

        if (!$association instanceof ProductAssociationTypeInterface) return;
        if ($resource->hasAssociation($association)) return;

        /** @var ProductAssociationInterface $productAssociation */
        $productAssociation = $this->productAssociationFactory->createNew();
        $productAssociation->setOwner($resource);
        $productAssociation->setType($association);

//        $productAssociation->addAssociatedProduct();
//        $resource->addProductAssociation($productAssociation);
//        $this->getEntityManager()->persist($productAssociation);
    }
}
