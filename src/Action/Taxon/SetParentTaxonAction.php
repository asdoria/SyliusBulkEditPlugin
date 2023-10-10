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

namespace Asdoria\SyliusBulkEditPlugin\Action\Taxon;

use Asdoria\SyliusBulkEditPlugin\Action\ResourceActionInterface;
use Asdoria\SyliusBulkEditPlugin\Form\Type\Configuration\TaxonConfigurationType;
use Asdoria\SyliusBulkEditPlugin\Message\BulkEditNotificationInterface;
use Asdoria\SyliusBulkEditPlugin\Traits\TaxonRepositoryTrait;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;

/**
 * Class SetParentTaxonAction
 */
final class SetParentTaxonAction implements ResourceActionInterface
{
    public const SET_PARENT_TAXON = 'set_parent_taxon';

    use TaxonRepositoryTrait;

    public function handle(ResourceInterface $resource, BulkEditNotificationInterface $message): void
    {
        Assert::isInstanceOf($resource, TaxonInterface::class);

        $configuration = $message->getConfiguration();

        if (empty($configuration)) {
            return;
        }

        $taxonCode = $configuration[TaxonConfigurationType::_TAXON_FIELD] ?? null;

        if (empty($taxonCode)) {
            return;
        }

        $taxon = $this->getTaxonRepository()->findOneByCode($taxonCode);

        Assert::isInstanceOf($taxon, TaxonInterface::class);

        $resource->setParent($taxon);
    }
}
