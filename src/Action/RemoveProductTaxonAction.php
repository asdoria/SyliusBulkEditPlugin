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

use Asdoria\Bundle\BulkEditBundle\Action\ResourceActionInterface;
use Asdoria\Bundle\BulkEditBundle\Form\Type\Configuration\TaxonConfigurationType;
use Asdoria\Bundle\BulkEditBundle\Message\BulkEditNotificationInterface;
use Asdoria\SyliusBulkEditPlugin\Traits\EntityManagerTrait;
use Asdoria\SyliusBulkEditPlugin\Traits\TaxonRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * Class RemoveProductTaxonAction.
 * @package Asdoria\SyliusBulkEditPlugin\DependencyInjection
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class RemoveProductTaxonAction implements ResourceActionInterface
{
    const _REMOVE_PRODUCT_TAXON = 'remove_product_taxon';

    use TaxonRepositoryTrait;

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


        $taxonCode = $configuration[TaxonConfigurationType::_TAXON_FIELD] ?? null;
        $taxon     = $this->getTaxonRepository()->findOneByCode($taxonCode);

        if (!$taxon instanceof TaxonInterface) return;

        $productTaxon = $resource->getProductTaxons()
            ->filter(fn($current) => $current->getTaxon()->getId() === $taxon->getId())->first();

        if (empty($productTaxon)) return;

        $resource->removeProductTaxon($productTaxon);
    }

}
