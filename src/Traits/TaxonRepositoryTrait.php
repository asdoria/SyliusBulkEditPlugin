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

namespace Asdoria\SyliusBulkEditPlugin\Traits;

use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

/**
 * Class TaxonRepositoryTrait.
 */
trait TaxonRepositoryTrait
{
    protected ?TaxonRepositoryInterface $taxonRepository = null;

    public function getTaxonRepository(): ?TaxonRepositoryInterface
    {
        return $this->taxonRepository;
    }

    public function setTaxonRepository(?TaxonRepositoryInterface $taxonRepository): void
    {
        $this->taxonRepository = $taxonRepository;
    }
}
