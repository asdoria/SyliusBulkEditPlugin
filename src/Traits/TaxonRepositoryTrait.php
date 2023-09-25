<?php

declare(strict_types=1);

namespace Asdoria\SyliusBulkEditPlugin\Traits;

use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

/**
 * Class TaxonRepositoryTrait.
 * @package Asdoria\SyliusBulkEditPlugin\Traits
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
trait TaxonRepositoryTrait
{
    protected ?TaxonRepositoryInterface $taxonRepository = null;

    /**
     * @return TaxonRepositoryInterface|null
     */
    public function getTaxonRepository(): ?TaxonRepositoryInterface
    {
        return $this->taxonRepository;
    }

    /**
     * @param TaxonRepositoryInterface|null $taxonRepository
     */
    public function setTaxonRepository(?TaxonRepositoryInterface $taxonRepository): void
    {
        $this->taxonRepository = $taxonRepository;
    }
}
