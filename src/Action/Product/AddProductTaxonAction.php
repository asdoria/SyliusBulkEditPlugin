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
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\TaxonsConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Asdoria\SyliusBulkEditPlugin\Traits\EntityManagerTrait;
use Asdoria\SyliusBulkEditPlugin\Traits\TaxonRepositoryTrait;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;

/**
 * Class AddProductTaxonAction
 */
final class AddProductTaxonAction implements ResourceActionInterface
{
    public const ADD_PRODUCT_TAXON = 'add_product_taxon';

    use TaxonRepositoryTrait;
    use EntityManagerTrait;

    public function __construct(
        private FactoryInterface $productTaxonFactory,
    ) {
    }

    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        Assert::isInstanceOf($resource, ProductInterface::class);

        $configuration = $message->getConfiguration();

        if (empty($configuration)) {
            return;
        }

        $taxonCodes = $configuration[TaxonsConfigurationType::_TAXONS_FIELD] ?? null;

        if (empty($taxonCodes) || !is_array($taxonCodes)) {
            return;
        }

        foreach ($taxonCodes as $taxonCode) {
            $taxon = $this->getTaxonRepository()->findOneByCode($taxonCode);

            Assert::isInstanceOf($taxon, TaxonInterface::class);

            if ($resource->hasTaxon($taxon)) {
                continue;
            }

            /** @var ProductTaxonInterface $productTaxon */
            $productTaxon = $this->productTaxonFactory->createNew();
            $productTaxon->setProduct($resource);
            $productTaxon->setTaxon($taxon);
            $resource->addProductTaxon($productTaxon);
            $this->getEntityManager()->persist($productTaxon);
        }
    }
}
