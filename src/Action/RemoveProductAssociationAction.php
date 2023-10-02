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
use Asdoria\SyliusBulkEditPlugin\Traits\TaxonRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Product\Repository\ProductAssociationTypeRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * Class RemoveProductAssociationAction.
 * @package Asdoria\SyliusBulkEditPlugin\Action
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class RemoveProductAssociationAction implements ResourceActionInterface
{
    const REMOVE_PRODUCT_ASSOCIATION = 'remove_product_association';

    /**
     * @param ProductAssociationTypeRepositoryInterface $associationTypeRepository
     */
    public function __construct(
        protected ProductAssociationTypeRepositoryInterface $associationTypeRepository
    )
    {
    }

    /**
     * @param ResourceInterface             $resource
     * @param BulkEditNotificationInterface $message
     *
     * @return void
     */
    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        if (!$resource instanceof ProductInterface) return;

        $configuration = $message->getConfiguration();

        if (empty($configuration)) return;

        $associationCode = $configuration[AssociationConfigurationType::_ASSOCIATION_TYPE_FIELD] ?? null;
        $association     = $this->associationTypeRepository->findOneByCode($associationCode);

        if (!$association instanceof ProductAssociationTypeInterface) return;

//        $productTaxon = $resource->getAssociations()
//            ->filter(fn($current) => $current->get()->getId() === $taxon->getId())->first();
//
//        if (empty($productTaxon)) return;
//
//        $resource->removeProductTaxon($productTaxon);
    }
}
