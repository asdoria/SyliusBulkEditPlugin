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

use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\TaxonConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Asdoria\SyliusBulkEditPlugin\Traits\EntityManagerTrait;
use Asdoria\SyliusBulkEditPlugin\Traits\TaxonRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * Class AddProductTaxonAction
 * @package Asdoria\SyliusBulkEditPlugin\DependencyInjection
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class AddProductTaxonAction implements ResourceActionInterface
{
    const ADD_PRODUCT_TAXON = 'add_product_taxon';

    use TaxonRepositoryTrait;
    use EntityManagerTrait;

    /**
     * @param EntityManagerInterface $entityManager
     * @param FactoryInterface       $productTaxonFactory
     */
    public function __construct(
        protected FactoryInterface       $productTaxonFactory,
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

        $taxonCode = $configuration[TaxonConfigurationType::_TAXON_FIELD] ?? null;
        $taxon     = $this->getTaxonRepository->findOneByCode($taxonCode);

        if (!$taxon instanceof TaxonInterface) return;
        if ($resource->hasTaxon($taxon)) return;

        /** @var ProductTaxonInterface $productTaxon */
        $productTaxon = $this->productTaxonFactory->createNew();
        $productTaxon->setProduct($resource);
        $productTaxon->setTaxon($taxon);
        $resource->addProductTaxon($productTaxon);
        $this->getEntityManager()->persist($productTaxon);
    }
}
